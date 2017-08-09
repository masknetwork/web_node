<?
class CExchangers
{
	function CExchangers($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function newOffer($net_fee_adr,
                      $adr,
                      $title,
                      $desc,
                      $webpage,
                      $pos_type,
                      $asset,
                      $cur,
                      $pay_method,
                      $pay_details,
                      $price_type,
                      $price,
                      $price_feed,
                      $price_branch,
                      $price_margin,
                      $country,
                      $town_type,
                      $town,
                      $escrowers,
					  $days)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $net_fee=0.0001*$days;
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		// Order address
		if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid order address");
			return false;
		}
		
		// Title
		$title=base64_decode($title);
		if ($this->kern->titleValid($title)==false)
		{
		    $this->template->showErr("Invalid title");
		    return false;
	    }
		
		// Description
		$desc=base64_decode($desc);
		if ($this->kern->descValid($desc)==false)
		{
		    $this->template->showErr("Invalid description");
		    return false;
	    }
		
		// Web page link
		if ($webpage!="")
		{
		    // Decode web page 
		   $webpage=base64_decode($webpage);
		   
		   if ($this->kern->isLink($webpage)==false)
		   {
		       $this->template->showErr("Invalid web page");
		       return false;
	       }
		}
		
		// Position type
		if ($pos_type!="ID_BUY" && $pos_type!="ID_SELL")
		{
			 $this->template->showErr("Invalid position type");
		     return false;
		}
		
		// Asset
		if ($asset!="MSK" && strlen($asset)!=6)
		{
			 $this->template->showErr("Invalid asset");
		     return false;
		}
		
		// Asset exist ?
		if ($asset!="MSK")
		{
		   $query="SELECT * 
		          FROM assets 
				 WHERE symbol='".$asset."'"; 
		   $result=$this->kern->execute($query);	
	       if (mysqli_num_rows($result)==0)
		   {
			    $this->template->showErr("Invalid asset");
		        return false;
		   }
		}
		
		// Currency
		if (strlen($cur)!=3)
		{
			 $this->template->showErr("Invalid currency");
		     return false;
		}
		
		// Payment method
		if ($pay_method!="ID_LOCAL_BANK" && 
		    $pay_method!="ID_SEPA" && 
			$pay_method!="ID_WIRE" && 
			$pay_method!="ID_WU" && 
			$pay_method!="ID_PAYPAL" && 
			$pay_method!="ID_SKRILL" && 
			$pay_method!="ID_BITCOIN" && 
			$pay_method!="ID_LITECOIN" && 
			$pay_method!="ID_OTHER")
		{
			 $this->template->showErr("Invalid payment method");
		     return false;
		}
		
		// Payment details
		$pay_details=base64_decode($pay_details);
		if ($this->kern->descValid($pay_details)==false)
		{
			 $this->template->showErr("Invalid payment details");
		     return false;
		}
		
		// Price type
		if ($price_type!="ID_FIXED" && 
		    $price_type!="ID_MOBILE")
		{
			 $this->template->showErr("Invalid price type");
		     return false;
		}
		
        // Price
		if ($price_type=="ID_FIXED")
		{
			if ($price<0.00000001)
			{
				$this->template->showErr("Invalid price type");
		        return false;
			}
		}
		else
		{
			// Branch valid
			if ($this->kern->branchExist($price_feed, $price_branch)==false) 
			{
				$this->template->showErr("Invalid price type");
		        return false;
			}
			
			// Margin
			if ($price_margin<0 || $price_margin>25)
			{
				$this->template->showErr("Invalid price margin");
		        return false;
			}
		}
		
        // Country
		if (strlen($country)!=2)
		{
			$this->template->showErr("Invalid country");
		    return false;
		}
		
        // Town type
		if ($town_type!="ID_ALL" && 
		    $town_type!="ID_SPECIFY")
		{
			$this->template->showErr("Invalid town type");
		    return false;
		}
			
		if ($town_type=="ID_SPECIFY")
		{
			// Town
			$town=base64_decode($town);
			
			// Town length
			if (strlen($town)<3 || strlen($town)>50)
			{
				$this->template->showErr("Invalid town");
		        return false;
			}
		}
		
		// Escrowers
		$escrowers=base64_decode($escrowers);
		if ($this->kern->descValid($escrowers)==false)
		{
			$this->template->showErr("Invalid escrowers");
		    return false;
		}
		
        // Days
		if ($days<10)
		{
			$this->template->showErr("Invalid days");
		    return false;
		}
		
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Post an exchange offer");
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_EXCHANGE', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".base64_encode($title)."',
								par_2='".base64_encode($desc)."',
								par_3='".base64_encode($webpage)."',
								par_4='".$pos_type."',
								par_5='".$asset."',
								par_6='".$cur."',
								par_7='".$pay_method."',
								par_8='".base64_encode($pay_details)."',
								par_9='".$price_type."',
								par_10='".$price."',
								par_11='".$price_feed."',
								par_12='".$price_branch."',
								par_13='".$price_margin."',
								par_14='".$country."',
								par_15='".$town_type."',
								par_16='".base64_encode($town)."',
								par_17='".base64_encode($escrowers)."',
								days='".$days."', 
								status='ID_PENDING', 
								tstamp='".time()."'";
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function showNewOfferBut()
	{
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="my_offers.php?act=show_panel" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Offer
         </a>
         </td></tr>
</table>
         <br>
         
         <?
	}
	
	
	function showNewOfferPanel()
	{
		?>
        
            <form id="form_new_ex" name="form_new_ex" action="my_offers.php?act=new" method="post">
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="155" align="center" valign="top">
                        <table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td align="center"><img src="GIF/exchangers.png" width="150" height="162" alt=""/></td>
                            </tr>
                            <tr>
                              <td align="center">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" class="simple_maro_16">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="1078" align="right" valign="top">
                        
                        <table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
                          </tr>
                          <tr>
                            <td align="left"><? $this->template->showMyAdrDD("dd_new_ex_net_fee_adr", "90%"); ?></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><span class="font_14"><strong>Order Address</strong></span></td>
                          </tr>
                          <tr>
                            <td align="left"><? $this->template->showMyAdrDD("dd_new_ex_adr", "90%"); ?></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td width="50%"><strong>Title</strong></td>
                                </tr>
                                <tr>
                                  <td><input class="form-control" id="txt_new_ex_title" name="txt_new_ex_title" placeholder="Title (5-250 characters)" style="width:100%" /></td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Description</strong></td>
                          </tr>
                          <tr>
                            <td align="left"><textarea id="txt_new_ex_desc" name="txt_new_ex_desc" rows="3" class="form-control" style="width:100%" placeholder="Description (10-2500 characters)"></textarea></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Web Page Link</strong></td>
                          </tr>
                          <tr>
                            <td align="left"><input class="form-control" id="txt_new_ex_webpage" name="txt_new_ex_webpage" placeholder="Webpage Link" /></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                  <td width="33%" valign="top" class="font_14"><strong>Type</strong></td>
                                  <td width="33%" height="30" valign="top" class="font_14"><strong>Asset Symbol</strong></td>
                                  <td width="33%" valign="top" class="font_14"><strong>Real World Currency</strong></td>
                                </tr>
                                <tr>
                                  <td><select class="form-control" style="width:90%" id="pos_new_ex_type" name="pos_new_ex_type">
                                    <option value="ID_BUY">Buy</option>
                                    <option value="ID_SELL">Sell</option>
                                  </select></td>
                                  <td><span class="font_14">
                                    <input class="form-control" id="txt_new_ex_asset" name="txt_new_ex_asset" placeholder="XXXXXX" style="width:90%" />
                                  </span></td>
                                  <td>
                                  <? $this->template->showCurDD("dd_new_ex_cur"); ?>
                                  </td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td width="75%"><span class="font_14"><strong>Payment Method</strong></span></td>
                                </tr>
                                <tr>
                                  <td>
                                  <select class="form-control" style="width:100%" id="txt_new_ex_pay_method" name="txt_new_ex_pay_method">
                                    <option value="ID_LOCAL_BANK">Local Bank Transfer</option>
                                    <option value="ID_SEPA">SEPA (EU) Bank Transfer</option>
                                    <option value="ID_WIRE">International Bank Transfer</option>
                                    <option value="ID_CASH_MAIL">Cash by Mail</option>
                                    <option value="ID_CASH_PERSON">Cash in person</option>
                                    <option value="ID_WU">Western Union</option>
                                    <option value="ID_PAYPAL">PayPal</option>
                                    <option value="ID_SKRILL">Skrill</option>
                                    <option value="ID_BITCOIN">Bitcoin</option>
                                    <option value="ID_LITECOIN">Litecoin</option>
                                    <option value="ID_OTHER">Other Payment Method</option>
                                  </select>
                                  </td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td height="0" align="left" valign="top">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Payment Details</strong></td>
                          </tr>
                          <tr>
                            <td height="0" align="left" valign="top">
                            <textarea id="txt_new_ex_pay_details" name="txt_new_ex_pay_details" rows="3" class="form-control" style="width:100%"></textarea></td>
                          </tr>
                          <tr>
                            <td height="0" align="left" valign="top">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Price </strong></td>
                          </tr>
                          <tr>
                            <td height="0" align="left" valign="top">
                            <select class="form-control" style="width:100%" id="dd_new_ex_price_type" name="dd_new_ex_price_type">
                              <option value="ID_FIXED">Fixed</option>
                              <option value="ID_MOBILE">Based on Feed</option>
                            </select>
                            
                            <script>
							$('#dd_new_ex_price_type').change(
							function() 
							{ 
							    if ($('#dd_new_ex_price_type').val()=="ID_FIXED") 
								{
									$('#txt_new_ex_price').prop('disabled', false);
									$('#txt_new_ex_feed').prop('disabled', true);
									$('#txt_new_ex_branch').prop('disabled', true);
									$('#txt_new_ex_margin').prop('disable', true);
								}
								else
								{
									$('#txt_new_ex_price').prop('disabled', true);
									$('#txt_new_ex_feed').prop('disabled', false);
									$('#txt_new_ex_branch').prop('disabled', false);
									$('#txt_new_ex_margin').prop('disabled', false);
								}
							});
							</script>
                            
                            </td>
                          </tr>
                          <tr>
                            <td height="0" align="left" valign="top">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                  <td width="25%" valign="top" class="font_14"><strong>Price </strong></td>
                                  <td width="25%" height="30" valign="top" class="font_14"><strong>Feed Symbol</strong></td>
                                  <td width="25%" class="font_14"><strong>Feed Branch</strong></td>
                                  <td width="25%" class="font_14"><strong>Margin (%)</strong></td>
                                </tr>
                                <tr>
                                  <td><span class="font_14">
                                    <input class="form-control" id="txt_new_ex_price" name="txt_new_ex_price" placeholder="0" style="width:90%" type="number" />
                                  </span></td>
                                  <td><span class="font_14">
                                    <input class="form-control" id="txt_new_ex_feed" name="txt_new_ex_feed" placeholder="XXXXXX" style="width:90%" disabled/>
                                  </span></td>
                                  <td>
                                   <input class="form-control" id="txt_new_ex_branch" name="txt_new_ex_branch" placeholder="XXXXXX" style="width:90%" disabled />
                                  </td>
                                  <td><input class="form-control" id="txt_new_ex_margin" name="txt_new_ex_margin" placeholder="5" style="width:90%" disabled type="number" /></td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                  <td width="33%" valign="top" class="font_14"><strong>Country</strong></td>
                                  <td width="33%" height="30" valign="top" class="font_14"><strong>Town</strong></td>
                                  <td width="33%" valign="top" class="font_14"><strong>Town</strong></td>
                                </tr>
                                <tr>
                                  <td>
                                  <?
								     $this->template->showCountriesDD("dd_new_ex_country");
								  ?>
                                  </td>
                                  <td>
                                  <select class="form-control" style="width:90%" id="dd_new_ex_town" name="dd_new_ex_town">
                                    <option value="ID_ALL">All Towns</option>
                                    <option value="ID_SPECIFY">Specify Town</option>
                                  </select>
                                  
                                   <script>
							         $('#dd_new_ex_town').change(
						       	     function() 
							         { 
							            if ($('#txt_new_ex_town').val()=="ID_ALL") 
								          $('#txt_new_ex_town').prop('disabled', true);
								        else
								          $('#txt_new_ex_town').prop('disabled', false);
								     });
							       </script>
                            
                                  </td>
                                  <td>
                                  <input class="form-control" id="txt_new_ex_town" name="txt_new_ex_town" placeholder="Town" style="width:90%" disabled />
                                  </td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><span class="font_14"><strong>Escrow Policy</strong></span></td>
                          </tr>
                          <tr>
                            <td align="left">
                            <textarea id="txt_new_ex_escrowers" name="txt_new_ex_escrowers" rows="3" class="form-control" style="width:100%" placeholder="We accept all escrowers"></textarea></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top" class="font_14"><strong>Days </strong></td>
                          </tr>
                          <tr>
                            <td align="left"><input class="form-control" id="txt_new_ex_days" name="txt_new_ex_days" placeholder="100" style="width:100px" /></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left"><hr></td>
                          </tr>
                          <tr>
                            <td align="right"><a href="javascript:void(0)" onClick="$('#form_new_ex').submit()" class="btn btn-primary">Post Offer</a></td>
                          </tr>
                        </table>
                        </td>
                      </tr>
                      
                   
                    </tbody>
                  </table>
                  </form>
                  <br><br><br>
                  
                   <script>
		           $('#form_new_ex').submit(
		           function() 
		           { 
				      $('#txt_new_ex_title').val(btoa($('#txt_new_ex_title').val())); 
		              $('#txt_new_ex_desc').val(btoa($('#txt_new_ex_desc').val())); 
					  $('#txt_new_ex_webpage').val(btoa($('#txt_new_ex_webpage').val())); 
					  $('#txt_new_ex_pay_details').val(btoa($('#txt_new_ex_pay_details').val())); 
					  $('#txt_new_ex_escrowers').val(btoa($('#txt_new_ex_escrowers').val())); 
					  $('#txt_new_ex_town').val(btoa($('#txt_new_ex_town').val())); 
		           });
		</script>
        
        <?
		
	}
	
	function showExchangers($mine=false, $type="ID_BUY", $asset="ID_ALL", $cur="ID_ALL", $method="ID_ALL")
	{
		$query="SELECT * 
	   	          FROM exchangers 
				 WHERE type='".$type."' ";
		
		// Type
		if ($asset!="ID_ALL")
		   $query=$query."AND asset='".$asset."' ";
		   
		// Currency
		if ($cur!="ID_ALL")
		   $query=$query."AND cur='".$asset."' ";
		   
		// Method
		if ($method!="ID_ALL")
		   $query=$query."AND method='".$asset."' ";
		
		// Mine ?
		if ($mine==true)
		  $query=$query." AND adr IN (SELECT adr 
		                               FROM my_adr 
									  WHERE userID='".$_REQUEST['ud']['ID']."')";
				
		 $result=$this->kern->execute($query);	
		 
		?>
       
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Asset</th>
           <th class="font_14" height="35px" align="center">Currency</th>
           <th class="font_14" height="35px" align="center">Method</th>
           <th class="font_14" height=\"35px\" align=\"center\">Price</th>
           <th class="font_14" height=\"35px\" align=\"center\">Details</th>
           </thead>
           
           <?
		      while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="35%">
                 <a href="exchanger.php?ID=<? print $row['ID']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 
                 <td class="font_14" width="10%">
				 <a href="#" class="font_14"><? print $row['asset']; ?></a>
                 </td>
                 
                 <td class="font_14" width="10%"><? print $row['cur']; ?></td>
                 
                 <td class="font_14" width="20%">
                 
                               
				 <? 
				     switch ($row['pay_method'])
					 {
						 case "ID_LOCAL_BANK" : print "Local Transfer"; break;
						 case "ID_SEPA" : print "SEPA Transfer"; break;
						 case "ID_WIRE" : print "Wire Transfer"; break;
						 case "ID_WU" : print "Western Union"; break;
						 case "ID_PAYPAL" : print "PayPal"; break;
						 case "ID_SKRILL" : print "Skrill"; break;
						 case "ID_BITCOIN" : print "Bitcoin"; break;
						 case "ID_LITECOIN" : print "Litecoin"; break;
						 case "ID_OTHER" : print "Other"; break;
					 }
				 ?>
                 </td>
                
                <td width="20%" class="font_14">
				<? 
				    if ($row['price_type']=="ID_FIXED")
					{
					   print $row['price'];
					}
					else
					{
						// Feed price
						$fprice=$this->kern->getFeedVal($row['price_feed'], $row['price_branch']);
						
						// Price
						if ($row['price_margin']>0)
						   $margin=$row['price_margin']*$fprice/100;
						   
						// Paice
						if ($row['type']=="ID_BUY")
						   $price=$fprice+$margin;
						else
						   $price=$fprice-$margin;
						
						print round($price, 4)." ".$row['cur'];
					}
					
				?>
                </td> 
                <td class="font_16" width="10%">
                <a href="exchanger.php?ID=<? print $row['ID']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Details</a>
                </td>
                
                 
                 </tr>
                 <tr><td colspan="8"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
      <?
	}
	
	function showExchanger($ID)
	{
		$query="SELECT * 
		          FROM exchangers 
				 WHERE ID='".$ID."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="15%"><img src="../../template/template/GIF/empty_pic.png" class="img-responsive img-circle"></td>
            <td width="3%">&nbsp;</td>
            <td width="83%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="77%"><span class="font_16"><strong><? print base64_decode($row['title']); ?></strong></span>
                  <p class="font_14"><? print base64_decode($row['description']); ?></p>
                 
                  </td>
                </tr>
              </tbody>
            </table>              <p class="font_14">&nbsp;</p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table class="table-responsive" width="95%">
            
            <tr>
            
            <td width="30%" class="font_12" align="center">Type&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? if ($row['type']=="ID_BUY") print "Buy"; else print "Sell"; ?></strong></td>
           
            <td width="40%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			<strong><? print $this->template->formatAdr($row['adr']); ?></strong></a></td>
            
            <td width="30%" align="center"><span class="font_12">Asset</span>&nbsp;&nbsp;&nbsp;&nbsp;<font class="font_12">
             <strong>
			 <? 
			    print $row['asset']
			?>
            </strong></font></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" class="font_12" align="center">Currency&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['cur']; ?></strong>
            </td>
            
            <td width="33%" align="center"><span class="font_12">Payment Method&nbsp;&nbsp;&nbsp;&nbsp;
			<strong>
			<? 
			 	switch ($row['pay_method'])
				{
					case "ID_LOCAL_BANK" :  print "Local Bank Transfer"; break;
					case "ID_SEPA" :  print "SEPA (EU) Transfer"; break;
					case "ID_WIRE" :  print "International Bank Transfer"; break;
					case "ID_CASH_MAIL" :  print "Cash bt Mail"; break;
					case "ID_CASH_PERSON" :  print "Cash in person"; break;
					case "ID_WU" :  print "Western Union"; break;
					case "ID_PAYPAL" :  print "Paypal"; break;
					case "ID_SKRILL" :  print "Skrill"; break;
					case "ID_LITECOIN" :  print "Litecoin"; break;
					case "ID_BITCOIN" :  print "Bitcoin"; break;
					case "ID_OTHER" :  print "Other Method"; break;
				}
			?>
            </strong></span>&nbsp;&nbsp;</td>
            
            <td width="33%" class="font_12" align="center">Price Type&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? if ($row['price_type']=="ID_FIXED") print "Fixed Price"; else print "Feed Based Price"; ?></strong>&nbsp;&nbsp;</td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" align="center"><span class="font_12">Price Feed&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>
            <a href="../../assets/feeds/branch.php?feed=<? print $row['price_feed']; ?>&symbol=<? print $row['price_branch']; ?>" font="font_14">
            <?
			   print $row['price_feed']." / ".$row['price_branch'];
			?>
            </a>
            </strong>
            </span></td>
            <td width="33%" class="font_12" align="center">Margin&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['price_margin'], 2)."%"; ?></strong></td>
            <td width="33%" class="font_12" align="center">Country&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong>
			<? 
			   if ($row['country']=="XX") 
			      print "All Countries";
			   else
			      print $row['country']; 
			?>
            </strong></td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            <tr>
            <td width="33%" class="font_12" align="center">Town&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong>
			<?
			    if ($row['town']!="") 
			       print base64_decode($row['town']);
				else
				   print "All Towns";    
		    ?>
            </strong></td>
            
            <td width="33%" class="font_12" align="center">Posted&nbsp;&nbsp;&nbsp;&nbsp; 
			<span class="font_12"><strong><? print "~".$this->kern->timeFromBlock($row['block']); ?></strong></span></td>
            
            <td width="33%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp; 
			<strong><? print "~".$this->kern->timeFromBlock($row['expire']); ?></strong>
            </td>
            </tr>
            
            
            </table>
            <br></td></tr>
            </table>
            </div>
            </div>
            <br>
            
            <table width="90%">
            <tr>
            <td width="30%">
            <div class="panel panel-default">
            <div class="panel-heading font_14">Payment Details</div>
            <div class="panel-body font_12" style="height:100px">
            <? print base64_decode($row['pay_details']); ?>
            </div></div>
            </td>
            
            <td>&nbsp;</td>
            
            <td width="30%">
            <div class="panel panel-default">
            <div class="panel-heading font_14">Escrow Policy</div>
            <div class="panel-body font_12" style="height:100px">
            <? print base64_decode($row['escrowers']); ?>
            </div></div>
            </td>
            
             <td>&nbsp;</td>
             
            <td width="30%">
            <div class="panel panel-default">
            <div class="panel-heading font_14">Price</div>
            <div class="panel-body font_22" style="height:100px">
            
			<? 
				    if ($row['price_type']=="ID_FIXED")
					{
					   print $row['price'];
					}
					else
					{
						// Feed price
						$fprice=$this->kern->getFeedVal($row['price_feed'], $row['price_branch']);
						
						// Price
						if ($row['price_margin']>0)
						   $margin=$row['price_margin']*$fprice/100;
						   
						// Paice
						if ($row['type']=="ID_BUY")
						   $price=$fprice-$margin;
						else
						   $price=$fprice+$margin;
						
						print round($price, 4)." ".$row['cur'];
					}
					
				?>
            </div></div>
            </td>
            
            </tr>
            </table>
        
        <?
	}
}
?>