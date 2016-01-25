<?
 class CIssued
 {
	 function CIssued($db, $template)
	 {
		 $this->kern=$db;
		 $this->template=$template;
	 }
	 
	 function showAssets()
	 {
		$query="SELECT * 
		          FROM assets WHERE adr IN (SELECT adr 
				                              FROM my_adr 
											 WHERE userID='".$_REQUEST['ud']['ID']."')
			  ORDER BY ID ASC
			     LIMIT 0,20"; 
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="12%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=150&h=150"; ?>"  class="img-circle img-responsive"/></td>
                            <td width="2%">&nbsp;</td>
                            <td width="88%">
                            <span class="font_16"><a href="asset.php?symbol=<? print $row['symbol']; ?>">
							<? print base64_decode($row['title'])." (".$row['symbol'].")"; ?></a>
                            <p class="font_12"><? print substr(base64_decode($row['description']), 0, 250)."..."; ?></p></td>
                            <td width="2%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                            
                            <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">
                            <span class="glyphicon glyphicon-cog"></span><span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu">
                            <li><a href="issued.php?act=show_modal&symbol=<? print $row['symbol']; ?>">Edit Asset Details</a></li>
                            <li><a href="#">Renew</a></li>
                            </ul></div></div>
                            
                            </td>
                            </tr>
                            <tr>
                            <td colspan="5"><hr></td>
                            </tr>
                      
                      <?
	                      }
					  ?>
                        
                  </table>
                  
                 
        
        <?
	 }
	
	 function newAsset($net_fee_adr, 
	                  $adr, 
					  $name, 
					  $desc, 
					  $how_buy, 
					  $how_sell, 
					  $website, 
					  $pic, 
					  $symbol, 
					  $initial_qty, 
					  $trans_fee, 
					  $trans_fee_adr, 
					  $mkt_days, 
					  $can_issue,
					  $interest,
					  $interval)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 $how_buy=base64_decode($how_buy);
		 $how_sell=base64_decode($how_sell);
		 $website=base64_decode($website);
		 $pic=base64_decode($pic);
		 
		
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round(($initial_qty*0.0001)+(0.0001*$mkt_days), 4);
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	   
	     // Asset address
		 if ($this->kern->adrValid($adr)==false)
		 {
			$this->template->showErr("Invalid asset address");
			return false;
		 }
		 
		 // Asset fees address
		 if ($this->kern->adrValid($trans_fee_adr)==false)
		 {
			$this->template->showErr("Invalid asset fee address");
			return false;
		 }
		 
		 // Name
		 if (strlen($name)<3 || strlen($name)>50)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 if (strlen($desc)>1000)
		 {
			 $this->template->showErr("Invalid description length (5-1000 characters)");
			 return false;
		 }
		 
		 // How to buy
		 if (strlen($how_buy)>1000)
		 {
			 $this->template->showErr("Invalid how to buy length (5-1000 characters)");
			 return false;
		 }
		 
		 // How to sell
		 if (strlen($how_sell)>1000)
		 {
			 $this->template->showErr("Invalid how to sell length (5-1000 characters)");
			 return false;
		 }
		 
		 // Website
		 if ($website!="")
		 {
			if (strpos($website, "http")===false) $website="http://".$website;
			
		    if (filter_var($website, FILTER_VALIDATE_URL)==false)
		   {
			   $this->template->showErr("Invalid website link");
			   return false;
		   }
		 }
		 
		 // Pic
		 if ($pic!="")
		 {
			 if (strpos($pic, "http")===false) $pic="http://".$pic;
			 
		     if (filter_var($pic, FILTER_VALIDATE_URL) ==false)
		     {
			    $this->template->showErr("Invalid pic");
			    return false;
		     }
		 }
		 
		 // Symbol
		 $symbol=strtoupper($symbol);
		 if ($this->kern->symbolValid($symbol)==false)
		 {
			 $this->template->showErr("Invalid symbol");
			 return false;
		 }
		 
		 // Symbol already exist ?
		 $query="SELECT * 
		           FROM assets 
				  WHERE symbol='".$symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)>0)
		 {
			 $this->template->showErr("Symbol already exist");
			 return false;
		 }
		 
		 // Initial qty
		 if ($initial_qty<1000)
		 {
			 $this->template->showErr("Minimum initial qty is 1000 units");
			 return false;
		 }
		 
		 if ($trans_fee>0)
		 {
		    // Transaction fee
		    if ($trans_fee>10)
			{
				$this->template->showErr("Maximum transaction fee is 10");
			    return false;
			}
		 
		    // Transaction fee address
			if ($this->template->adrValid($trans_fee_adr)==false)
			{
			    $this->template->showErr("Invalid transaction fee adress");
			    return false;
			}
		 }
		 
		 // Market days
		 if ($mkt_days<100)
		 {
			  $this->template->showErr("Minimum market days is 100");
			  return false;
		 }
		 
		 // Can issue
		 if ($can_issue!="Y" && $can_issue!="N")
		 {
			 $this->template->showErr("Invalid entry data");
			 return false;
		 }
		 
		 // Can issue more assets
		 if ($can_issue!="Y") $can_issue="N";
		 
		 // Interest
		 $interest=round($interest, 2);
		 
		 // Interest
		 if ($interest>10000 || 
		    $interest<-10000 || 
			is_numeric($interest)==false)
		 {
			  $this->template->showErr("Interest is a number between -10000 and 10000");
			  return false;
		 }
		 
		 // Interval
		 if ($interval!="ID_HOUR" && 
		    $interval!="ID_DAY" && 
			$interval!="ID_MONTH" && 
			$interval!="ID_MONTH_3" && 
			$interval!="ID_MONTH_6" && 
			$interval!="ID_YEAR")
		 {
			  $this->template->showErr("Invalid interval");
			  return false;
	     }
		 
		 // Find interval blocks
		 switch ($interval)
		 {
			 case "ID_HOUR" : $interval_blocks=60; break;
			 case "ID_DAY" : $interval_blocks=1440; break;
			 case "ID_WEEK" : $interval_blocks=10080; break;
			 case "ID_MONTH" : $interval_blocks=43200; break;
			 case "ID_MONTH_3" : $interval_blocks=129600; break;
			 case "ID_MONTH_6" : $interval_blocks=259200; break;
			 case "ID_YEAR" : $interval_blocks=518400; break;
		 }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Issue a new asset");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ISSUE_ASSET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".base64_encode($name)."',
								par_2='".base64_encode($desc)."',
								par_3='".base64_encode($how_buy)."',
								par_4='".base64_encode($how_sell)."',
								par_5='".base64_encode($website)."',
								par_6='".base64_encode($pic)."',
								par_7='".$symbol."',
								par_8='".$initial_qty."',
								par_9='".$trans_fee."',
								par_10='".$trans_fee_adr."',
								par_11='".$can_issue."',
								par_12='".round($interest, 2)."',
								par_13='".$interval_blocks."',
								days='".$mkt_days."',
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
	
	 function showIssueAssetModal($symbol="")
	 {
		 if ($symbol!="")
		 {
		     $query="SELECT * 
		               FROM assets 
				      WHERE symbol='".$symbol."' 
				        AND adr IN (SELECT adr 
					                  FROM my_adr 
								     WHERE userID='".$_REQUEST['ud']['ID']."')";
		     $result=$this->kern->execute($query);	
	    
		     if (mysql_num_rows($result)==0)
	  	     {
			    $this->template->showErr("Invalid entry data");
			    return false;
	 	     }
		
		     // Load asset data
		     $row = mysql_fetch_array($result, MYSQL_ASSOC);
		 }
		 
		?>
            
            <br><br>
            <form id="form_modal_issue" name="form_modal_issue" method="post" action="<? if ($symbol=="") print "issued.php?act=issue"; else print "issued.php?act=edit&symbol=".$symbol; ?>">
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="10%" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/issue.png" width="180" alt="" class="img-circle"/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showWebsiteCodePanel(); ?></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="90%" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_issue_fee", "100%"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              
              <?
			     if ($symbol=="")
				 {
			  ?>
              
                   <tr>
                   <td height="30" align="left" valign="top"><span class="font_14"><strong>Asset Address</strong></span></td>
                   </tr>
                   <tr>
                   <td align="left"><? $this->template->showMyAdrDD("dd_issue_adr", "100%"); ?></td>
                   </tr>
                   <tr>
                   <td align="left">&nbsp;</td>
                   </tr>
              
              <?
				 }
			  ?>
              
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_issue_name" name="txt_issue_name" placeholder="Asset Name (5-30 characters)" value="<? if ($symbol!="") print base64_decode($row['title']); ?>"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3fd" id="txt_issue_desc" name="txt_issue_desc" class="form-control" placeholder="Short Description (10-250 characters)"><? if ($symbol!="") print base64_decode($row['description']); ?></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>How to buy this asset ?</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3fd" id="txt_issue_buy" name="txt_issue_buy" class="form-control" placeholder="Explain how regular users can buy this asset (10-500 characters)"><? if ($symbol!="") print base64_decode($row['how_buy']); ?></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>How to sell / redeem this asset ?</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3fd" id="txt_issue_sell" name="txt_issue_sell" class="form-control"  placeholder="Explain how regular users can sellor redeem this asset (10-500 characters)"><? if ($symbol!="") print base64_decode($row['how_sell']); ?></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="font_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="40%" height="30" align="left" valign="top" class="font_14"><strong>Website</strong></td>
                    <td width="5%">&nbsp;</td>
                    <td width="55%" align="left" valign="top"><strong>Pic</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_issue_website" name="txt_issue_website"  placeholder="Wesite Link" value="<? if ($symbol!="") print base64_decode($row['website']); ?>"/></td>
                    <td width="5%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_pic" name="txt_issue_pic" placeholder="Link to Image" value="<? if ($symbol!="") print base64_decode($row['pic']); ?>"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="23%" height="30" align="left" valign="top" class="font_14"><strong>Symbol</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Initial Qty</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Transaction Fee</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Expire (days)</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_issue_symbol" name="txt_issue_symbol" placeholder="XXXXXX" value="<? if ($symbol!="") print $row['symbol']; ?>" <? if ($symbol!="") print "disabled"; ?>/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_init_qty" name="txt_issue_init_qty" placeholder="10000" value="<? if ($symbol!="") print $row['qty']; ?>" <? if ($symbol!="") print "disabled"; ?>/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_trans_fee" name="txt_issue_trans_fee" placeholder="1%" value="<? if ($symbol!="") print $row['trans_fee']; ?>" /></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_days" name="txt_issue_days" placeholder="1000" style="width:100px" value="<? if ($symbol!="") print round(($row['expire']-$_REQUEST['sd']['last_block'])/1440); ?>" <? if ($symbol!="") print "disabled"; ?>/></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60%" height="30" align="left" valign="top" class="font_14"><strong>Transfer the fees to this address</strong></td>
                  </tr>
                  <tr>
                    <td>
                    <input id="txt_issue_fee_adr" name="txt_issue_fee_adr" type="text" class="form-control" value="<? if ($symbol!="") print $row['trans_fee_adr']; ?>" ></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="23%" height="30" align="left" valign="top" class="font_14"><strong>Can Issue More</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Yearly Interest (%)</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Interest Interval</strong></td>
                  </tr>
                  <tr>
                    <td>
                    <select class="form-control" id="dd_can_issue" name="dd_can_issue" <? if ($symbol!="") print "disabled"; ?>>
                    <option value="Y" <? if ($symbol!="" && $row['can_issue']=="Y") print "selected"; ?>>Yes</option>
                    <option value="N" <? if ($symbol!="" && $row['can_issue']=="Y") print "selected"; ?>>No</option>
                    </select>
                    </td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_interest" name="txt_interest" placeholder="1" value="<? if ($symbol!="") print $row['interest']; ?>" /></td>
                    <td width="3%">&nbsp;</td>
                    <td>
                    <select class="form-control" id="dd_interval" name="dd_interval">
                    <option value="ID_HOUR" <? if ($symbol!="" && $row['interval']==60) print "selected"; ?>>Every Hour</option>
                    <option value="ID_DAY" <? if ($symbol!="" && $row['interval']==1440) print "selected"; ?>>Every Day</option>
                    <option value="ID_WEEK" <? if ($symbol!="" && $row['interval']==10080) print "selected"; ?>>Every Week</option>
                    <option value="ID_MONTH" <? if ($symbol!="" && $row['interval']==43200) print "selected"; ?>>Every Month</option>
                    <option value="ID_MONTH_3" <? if ($symbol!="" && $row['interval']==129600) print "selected"; ?>>Every 3 Months</option>
                    <option value="ID_MONTH_6" <? if ($symbol!="" && $row['interval']==259200) print "selected"; ?>>Every 6 Months</option>
                    <option value="ID_YEAR" <? if ($symbol!="" && $row['interval']==518400) print "selected"; ?>>Every Year</option>
                    </select>
                    
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
            <tr>
              <td colspan="2" align="center" valign="top"><hr></td>
            </tr>
            <tr>
              <td colspan="2" align="right" valign="top">
              <a href="javascript:void(0)" onClick="$('#form_modal_issue').submit()" class="btn btn-primary">Issue Asset</a></td>
              </tr>
            </table>
            <br><br><br>
        </form>
        
        <script>
		$('#form_modal_issue').submit(
		function() 
		{ 
		   $('#txt_issue_name').val(btoa($('#txt_issue_name').val())); 
		   $('#txt_issue_desc').val(btoa($('#txt_issue_desc').val())); 
		   $('#txt_issue_buy').val(btoa($('#txt_issue_buy').val())); 
		   $('#txt_issue_sell').val(btoa($('#txt_issue_sell').val())); 
		   $('#txt_issue_website').val(btoa($('#txt_issue_website').val())); 
		   $('#txt_issue_pic').val(btoa($('#txt_issue_pic').val())); 
		});
		</script>
        
        <?
	}
	
	
	
	function showIssueBut()
	{
		?>
        
        <table width="90%">
        <tr><td align="right">
        <a href="issued.php?act=show_modal" class="btn btn-primary">
        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Issue Asset
        </a>
        </td></tr>
        </table>
        <br>
        
        <?
	}
 }
?>