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
				  AND linked_mktID=0
			  ORDER BY ID ASC
			     LIMIT 0,20"; 
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
                      
                      <?
					     while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="7%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100"; ?>"  class="img-circle img-responsive"/></td>
                            <td width="1%">&nbsp;</td>
                            <td width="92%">
                            <span class="font_16"><a href="asset.php?symbol=<? print $row['symbol']; ?>">
							<? print base64_decode($row['title'])." (".$row['symbol'].")"; ?></a>
                            <p class="font_12"><? print substr(base64_decode($row['description']), 0, 250)."..."; ?></p></td>
                            </tr>
                            <tr>
                            <td colspan="3"><hr></td>
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
					  $days, 
					  $can_issue)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 $how_buy=base64_decode($how_buy);
		 $how_sell=base64_decode($how_sell);
		 $website=base64_decode($website);
		 $pic=base64_decode($pic);
		 
		 // Addresses
		 $net_fee_adr=$this->kern->adrFromDomain($net_fee_adr);
		 $adr=$this->kern->adrFromDomain($adr);
		 $trans_fee_adr=$this->kern->adrFromDomain($trans_fee_adr);
		
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round((($initial_qty*0.0001)+(0.0001*$mkt_days))*$trans_fee, 4);
		 
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
	     if (mysqli_num_rows($result)>0)
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
			if ($this->kern->adrValid($trans_fee_adr)==false)
			{
			    $this->template->showErr("Invalid transaction fee adress");
			    return false;
			}
		 }
		 
		 // Market days
		 if ($days<100)
		 {
			  $this->template->showErr("Minimum market days is 100");
			  return false;
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
	
	 function showIssueAssetModal($symbol="")
	 {
		 ?>
            
            <br><br>
            <form id="form_modal_issue" name="form_modal_issue" method="post" action="<? if ($symbol=="") print "issued.php?act=issue"; else print "issued.php?act=edit&symbol=".$symbol; ?>">
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
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
                   <td height="30" align="left" valign="top"><span class="font_14"><strong>Asset Address</strong></span></td>
                   </tr>
                   <tr>
                   <td align="left"><? $this->template->showMyAdrDD("dd_issue_adr", "100%"); ?></td>
                   </tr>
                   <tr>
                   <td align="left">&nbsp;</td>
                   </tr>
              
             
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_issue_name" name="txt_issue_name" placeholder="Asset Name (5-30 characters)" value=""/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3fd" id="txt_issue_desc" name="txt_issue_desc" class="form-control" placeholder="Short Description (10-250 characters)"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td width="45%" height="30" valign="top"><span class="font_14"><strong>How to buy this asset ?</strong></span></td>
                      <td width="5%" valign="top">&nbsp;</td>
                      <td width="50%" height="30" valign="top"><span class="font_14"><strong>How to sell / redeem this asset ?</strong></span></td>
                    </tr>
                    <tr>
                      <td width="45%"><textarea rows="3fd" id="txt_issue_buy" name="txt_issue_buy" class="form-control" placeholder="Explain how regular users can buy this asset (10-500 characters)"></textarea></td>
                      <td width="5%">&nbsp;</td>
                      <td width="50%"><textarea rows="3fd" id="txt_issue_sell" name="txt_issue_sell" class="form-control"  placeholder="Explain how regular users can sellor redeem this asset (10-500 characters)"></textarea></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="font_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="45%" height="30" align="left" valign="top" class="font_14"><strong>Website</strong></td>
                    <td width="5%">&nbsp;</td>
                    <td width="50%" align="left" valign="top"><strong>Pic</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_issue_website" name="txt_issue_website"  placeholder="Wesite Link" value=""/></td>
                    <td width="5%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_pic" name="txt_issue_pic" placeholder="Link to Image" value=""/></td>
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
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Transaction Fee (%)</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="23%" align="left" valign="top" class="font_14"><strong>Expire (days)</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_issue_symbol" name="txt_issue_symbol" placeholder="XXXXXX" value="" maxlength="6"/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_init_qty" name="txt_issue_init_qty" placeholder="10000" value="" onKeyUp="onClick()" type="number"/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_trans_fee" name="txt_issue_trans_fee" placeholder="1%" value="1" type="number" min="0.01" max="10"  onKeyUp="onClick()"/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_issue_days" name="txt_issue_days" placeholder="1000" style="width:100px" value="" type="number" onKeyUp="onClick()"/></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="77%" height="30" align="left" valign="top" class="font_14"><strong>Transfer the fees to this address</strong></td>
                  </tr>
                  <tr>
                    <td width="77%"><input id="txt_issue_trans_fee_adr" name="txt_issue_trans_fee_adr" type="text" class="form-control" value="" ></td>
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
		
		function onClick()
		{
			var qty=parseFloat($('#txt_issue_init_qty').val()*0.0001);
			var days=parseFloat($('#txt_issue_days').val()*0.0001);
			var trans_fee=parseInt($('#txt_issue_trans_fee').val());
			$('#ss_net_fee_panel_val').text(parseFloat((qty+days)*trans_fee).toFixed(4));
		}
		
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