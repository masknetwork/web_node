<?
class CTemplate
{
	function CTemplate($db)
	{
	   $this->kern=$db;	
	}
	
	function showProfile()
	{
		if ($_REQUEST['ud']['ID']>0)
		{
		?>
            
            <br>
            <div class="panel panel-default">
            <div class="panel-body">
  
            <table width="100%">
            <tr><td colspan="2"><img src="../../template/template/GIF/empty_profile.png" class="img img-rounded img-responsive" width="100%"></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td bgcolor="#fafafa" width="75%" class="font_14" align="center"><? print $_REQUEST['ud']['user']; ?></td>
            <td>
            
           <div class="btn-group">
           <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <span class="glyphicon glyphicon-cog"></span>
          </button>
          <ul class="dropdown-menu">
          <li><a href="../../../index.php?act=logout">Logout</a></li>
          <li><a href="../../profile/profile/index.php">Profile</a></li>
          </ul>
          </div></td></tr>
          </table>
             
            </div>
            </div>
           
        
        <?
		}
	}
	
	function showMyAdrDD($name="txt_adr", $width=300, $selected="")
	{
		 $query="SELECT ma.adr, adr.balance, dom.domain 
		           FROM my_adr AS ma 
			  LEFT JOIN adr ON ma.adr=adr.adr
			  LEFT JOIN domains AS dom ON dom.adr=ma.adr
			      WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
				    AND ma.adr NOT IN (SELECT adr 
				                         FROM agents 
										WHERE adr IN (SELECT adr 
										                FROM adr 
													   WHERE userID='".$_REQUEST['ud']['ID']."'))
			   ORDER BY balance DESC"; 
		 $result=$this->kern->execute($query);	
	  
		 print "<select name='".$name."' id='".$name."' class='form-control' style='width:".$width."px'>";
         
		 while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		 {
			// Balance
			if ($row['balance']=="")
			   $balance=0;
			else
			   $balance=$row['balance'];
			   
		    if (strlen($row['domain'])>0)
		    {
			    if ($selected!="" && $selected==$row['adr'])
		           print "<option selected  value='".$row['adr']."'>".$row['domain']." (".$balance." MSK)</option>";
		        else
			       print "<option value='".$row['adr']."'>".$row['domain']." (".$balance." MSK)</option>";
		    }
		    else
		    {
			    if ($selected!="" && $selected==$row['adr'])
		           print "<option selected value='".$row['adr']."'>...".substr($row['adr'], 40, 20)."... (".$balance." MSK)</option>";
		        else
			       print "<option value='".$row['adr']."'>...".substr($row['adr'], 40, 20)."... (".$balance." MSK)</option>";
		    }
		 }
		 
         print "</select>";
        
	}
	
	function showMyAdrAssetDD($cur, $name="txt_adr", $width=300, $selected="")
	{
		 $query="SELECT ma.adr, ao.qty, dom.domain
		           FROM my_adr AS ma 
			       JOIN assets_owners AS ao ON ma.adr=ao.owner
			  LEFT JOIN domains AS dom ON dom.adr=ao.owner
			  WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
			    AND ao.symbol='".$cur."'
			ORDER BY ao.qty DESC";
		 $result=$this->kern->execute($query);	
	  
		 print "<select name='".$name."' id='".$name."' class='form-control' style='width:".$width."px'>";
         
		 while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		 {
			// Balance
			if ($row['qty']=="")
			   $balance=0;
			else
			   $balance=$row['qty'];
			   
		    if (strlen($row['domain'])>0)
		    {
			    if ($selected!="" && $selected==$row['adr'])
		           print "<option selected  value='".$row['adr']."'>".$row['domain']." (".$balance." ".$cur.")</option>";
		        else
			       print "<option value='".$row['adr']."'>".$row['domain']." (".$balance." ".$cur.")</option>";
		    }
		    else
		    {
			    if ($selected!="" && $selected==$row['adr'])
		           print "<option selected value='".$row['adr']."'>...".substr($row['adr'], 40, 20)."... (".$balance." ".$cur.")</option>";
		        else
			       print "<option value='".$row['adr']."'>...".substr($row['adr'], 40, 20)."... (".$balance." ".$cur.")</option>";
		    }
		 }
		 
         print "</select>";
        
	}
	
	function showMSKPricePanel($visible=true)
	{
		?>
        
            <table width="75%" border="0" cellspacing="2" cellpadding="5" tab="tab_net_fee">
                  <tr>
                    <td height="30" align="center" bgcolor="#d6f9e0" class="simple_green_12">MSK Live Price</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#e6ffed">
                    <span class="simple_green_22" id="txt_code" name="txt_code"><? print "$".$_REQUEST['sd']['msk_price']; ?></span></td>
                  </tr>
            </table>
        
<?
	}
	
	function showNetFeePanel($val=0.0001, $header="ss")
	{
		?>
        
            <table width="75%" border="0" cellspacing="2" cellpadding="5" tab="tab_net_fee">
                  <tr>
                    <td height="30" align="center" bgcolor="#fff6d7" class="simple_maro_12">Network Fee</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fffbee">
                    <span class="simple_red_20" id="<? print $header; ?>_net_fee_panel_val" name="<? print $header; ?>_net_fee_panel_val"><strong><? print $val; ?></strong></span>&nbsp;&nbsp;<span class="simple_red_14">MSK</span></td>
                  </tr>
           </table>
        
<?
	}
	
	function showModalHeader($id, $txt, $name_1="", $val_1="", $name_2="", $val_2="", $action="")
	{
		?>
        
           <div class="modal fade" id="<? print $id; ?>">
           <div class="modal-dialog">
           <div class="modal-content">
           <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" align="center"><? print $txt; ?></h4>
           </div>
           <form method="post" action="<? print $action; ?>" name="form_<? print $id; ?>" id="form_<? print $id; ?>">
           <div class="modal-body">
        
        <?
		
		  if ($name_1!="") print "<input type='hidden' name='".$name_1."' id='".$name_1."' value='".$val_1."'/>";
		  if ($name_2!="") print "<input type='hidden' name='".$name_2."' id='".$name_2."' value='".$val_2."'/>";
	}
	
	function showModalFooter($send_but_txt="Send")
	{
		?>
        
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Close</button>
             <? if ($send_but_txt!="") print "<button id=\"but_activate\" name=\"but_activate\" type=\"submit\" class=\"btn btn-primary btn-primary\"><span class=\"glyphicon glyphicon-ok\"></span>&nbsp;&nbsp;".$send_but_txt."</button>"; ?>
             </div>
             </form>
             </div></div></div>
        
        <?
	}
	
	function showSendModal()
	{
		$this->showModalHeader("send_coins_modal", "Send Coins", "act", "send_coins", "", "", "../../transactions/all/index.php?act=send_coins");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../../template/template/GIF/wallet.png" width="200" /></td>
             </tr>
             <tr>
               <td align="center"><? $this->showNetFeePanel("0.0001", "trans"); ?></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center"><? $this->showMSKPricePanel(); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>From Address</strong></td>
             </tr>
             <tr>
               <td width="391" align="left">
               
			   <?
			      $this->showMyAdrDD("dd_from");
			   ?>
               
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>To Address</strong></td>
             </tr>
             <tr>
               <td align="left">
               <input type="text" class="form-control" style="width:300px" id="txt_to" name="txt_to" placeholder="Address" onfocus="this.placeholder=''"  />
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="50" align="left">
               
               
               <table width="300px" border="0" cellspacing="0" cellpadding="0" style="display:block" id="tab_msk" name="tab_msk">
                 <tr>
                   <td class="font_16"><strong>Amount</strong></td>
                   <td>&nbsp;</td>
                   <td align="right" class="font_12"><a hef="javascript:void(0)" onclick="$('#tab_assets').css('display', 'block'); $('#tab_msk').css('display', 'none');" style="color:#ff2a00">send assets</a>&nbsp;&nbsp;</td>
                 </tr>
                 <tr>
                   <td ><div class="input-group">
                     <div class="input-group-addon">MSK</div>
                     <input type="number" step="0.00001" class="form-control" id="txt_msk" name="txt_msk"  style="width:80px" placeholder="0" onKeyUp="var  usd=$(this).val()*<? print $_REQUEST['sd']['msk_price']; ?>; var fee=$(this).val()/10000; if (fee<0.0001) fee=0.0001; fee=Math.round(fee*10000)/10000; usd=Math.round(usd*100)/100; $('#trans_net_fee_panel_val').text(fee); $('#txt_usd').val(usd)"/>
                     </div>
                   </td>
                   <td width="10px">&nbsp;</td>
                   <td><div class="input-group">
                     <div class="input-group-addon">USD</div>
                     <input type="number" step="0.01" class="form-control" id="txt_usd" name="txt_usd"  style="width:80px" placeholder="0" onKeyUp="var  msk=$('#txt_usd').val()/<? print $_REQUEST['sd']['msk_price']; ?>; var fee=msk/10000; if (fee<0.0001) fee=0.0001; fee=Math.round(fee*10000)/10000; $('#trans_net_fee_panel_val').text(fee); $('#txt_msk').val(msk);"/>
                   </div></td>
                  
                 </tr>
               </table>
               
                 <table width="300px" border="0" cellspacing="0" cellpadding="0" style="display:none" id="tab_assets" name="tab_assets">
                   <tr>
                     <td class="font_16"><strong>Amount</strong></td>
                     <td>&nbsp;</td>
                     <td align="left" class="font_16"><strong>Asset Symbol</strong></td>
                   </tr>
                   <tr>
                     <td >
                     <input type="number" step="0.00001" class="form-control" id="txt_asset_amount" name="txt_asset_amount"  style="width:150px" placeholder="0"/>
                     </div></td>
                     <td width="10px">&nbsp;</td>
                     <td><input type="text" class="form-control" id="txt_cur" name="txt_cur"  style="width:120px" placeholder="MSK" maxlength="6" value="MSK"/>
                     </td>
                   </tr>
               </table>
               
               
               </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Message</strong></td>
             </tr>
             <tr>
               <td>
               <textarea name="txt_mes" rows="3"  style="width:300px" class="form-control" placeholder="Comments (optional)" onfocus="this.placeholder=''"></textarea>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Escrower</strong> (optional) </td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:300px" id="exampleInputAmount4" placeholder="Escrower Address (optional)" onfocus="this.placeholder=''" name="txt_escrower" /></td>
             </tr>
           </table></td>
         </tr>
     </table>
     
    
       
        <?
		$this->showModalFooter("Send");
		
	}
	
	function showTestnetModal()
	{
		$this->showModalHeader("testnet_modal", "Node running over testnet", "act", "");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../../template/template/GIF/testnet.png" width="180" height="181" alt=""/></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
           </table></td>
           <td width="90%" align="left" valign="top"><table width="350" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="10%" align="left">&nbsp;</td>
               <td width="90%" height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left" valign="top" style="font-size:16px">&nbsp;</td>
               <td height="25" align="left" valign="top" style="font-size:16px">This node is running over MaskNetwork testnet. The testnet is an alternative Maskcoin blockchain to be used for testing. Testnet MaskCoins are separate and distinct from actual Maskcoins, and are never supposed to have any value. This allows application developers or bitcoin testers to experiment, without having to use real Maskcoins or worrying about breaking the main network chain.</td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
               <td height="0" align="left">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
    
       
        <?
		$this->showModalFooter("Close");
		
	}
	
	function showBalancePanel()
	{
		if (!isset($_SESSION['userID'])) return false;
		
		// Send modal
		$this->showSendModal();
		
		$v=explode(".", $_REQUEST['ud']['balance']);
		if (sizeof($v)==1) $v[1]="0000";
		?>
           
           <table width="175" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td height="90" align="center" valign="top" background="../../template/template/GIF/coin.png">
                        
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="37%">&nbsp;</td>
                              <td width="63%" height="59">
                              <span class="inset_red_24"><? print $v[0]; ?></span><span class="inset_red_18"><? print ".".$v[1]; ?></span></td>
                            </tr>
                            <tr>
                              <td colspan="2" align="center" valign="top" class="inset_maro_11">
							  <?
							     print "$".round($_REQUEST['ud']['balance']*$_REQUEST['sd']['msk_price'], 2);  
							  ?>
                              </td>
                              </tr>
                          </tbody>
                        </table>
                        
                         </td>
                      </tr>
                      <tr>
                        <td align="center">
                        <a href="#" onclick="$('#send_coins_modal').modal()" class="btn btn-danger" style="width:175px">
                        <span class="glyphicon glyphicon-send">&nbsp;</span>Send Coins</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
        
        <?
	}
	
	
                      
                       
	
	function showHelp($txt)
	{
		?>
        
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="113">&nbsp;</td>
                  <td width="457">&nbsp;</td>
                </tr>
                <tr>
                  <td width="20%" style="padding-right:20px" valign="top"><img src="../../template/template/GIF/help.png" class="img-responsive" /></td>
                  <td valign="top" class="txt_help"><? print $txt; ?></td>
                </tr>
                <tr>
                  <td colspan="2"><hr></td>
                  </tr>
              </tbody>
            </table>
            <br>
        
        <?
	}
	
	
	function showAds()
	{
		// Profile panel
		$this->showProfile();
		
		$this->showAdsModal();
	
		?>
        
              <br>
              <table><tr>
                <td><a href="http://www.cryptotalk.net"><img src="../../template/template/GIF/forums.png" class="img img-responsive img-rounded" width="100%"></a></td></tr></table>
              <br>
              <table width="180" border="0" cellspacing="0" cellpadding="0">
               
                  <tr>
                    <td align="left"><strong>Advertising</strong></td>
                  </tr>
                  <tr>
                    <td align="left"><hr></td>
                  </tr>
                    
                    <?
					  $query="SELECT * 
					            FROM ads 
						    ORDER BY mkt_bid DESC 
							   LIMIT 0,10";
					  $result=$this->kern->execute($query);	
	                  
					  while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					  {
                    ?>
                    
                         <tr>
                         <td align="left">
                         <a href="<? print base64_decode($row['link']); ?>" style="font-size:14px"><strong><? print base64_decode($row['title']); ?></strong></a>
                         <br><span style="font-size:12px"><? print base64_decode($row['message']); ?></span> 
                         </td></tr><tr>
                         <td align="left"><hr></td>
                         </tr>
                    
                    <?
					  }
					?>
                    
              </table>
              
              <table width="170" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <?
				     if (isset($_SESSION['userID']))
					 {
				  ?>
                  
                    <tr>
                    <td><a href="javascript:void(0)" class="btn btn-primary" onClick="$('#modal_ads').modal()">Advertise Here</a></td>
                    <td>&nbsp;</td>
                    <td><a href="../../ads/ads/index.php" class="btn btn-danger"><span class="glyphicon glyphicon-cog"></span></a></td>
                    </tr>
                  
                  <?
	                  }
				  ?>
                  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>
              
              <table><tr><td height="800">&nbsp;</td></tr></table>
        
        <?
	}
	
	function showBottomMenu()
	{
		?>
        
           <div class="row" style="background-color:#4d5d6d; padding-top:10px; padding-bottom:10px;" align="center">
           <div class="col-md-2"></div>
           <div class="col-md-1"><a href="../../../index.php" class="font_14" style="color:#e1e6eb">Home</a></div>
           <div class="col-md-1"><a href="../../transactions/all/index.php" class="font_14" style="color:#e1e6eb">Transactions&nbsp;&nbsp;</a></div>
           <div class="col-md-1"><a href="../../addresses/adr/index.php" class="font_14" style="color:#e1e6eb">Addresses</a></div>
           <div class="col-md-1"><a href="../../mes/inbox/index.php" class="font_14" style="color:#e1e6eb">Messages</a></div>
           <div class="col-md-1"><a href="../../assets/regular/index.php" class="font_14" style="color:#e1e6eb">Assets</a></div>
           <div class="col-md-1"><a href="../../markets/goods/index.php" class="font_14" style="color:#e1e6eb">Markets</a></div>
           <div class="col-md-1"><a href="../../explorer/packets/index.php" class="font_14" style="color:#e1e6eb">Explorer</a></div>
           <div class="col-md-1"><a href="../../help/help/index.php" class="font_14" style="color:#e1e6eb">Help</a></div>
           <div class="col-md-2"></div>
           </div>     
           
 
           <div class="row" style="background-color:#334456" align="center">
           <div class="col-md-2"></div>
           <div class="col-md-8"><a href="https://github.com/masknetwork" class="font_10" style="color:#e1e6eb">Code distributed under MIT licence. Contributions welcome. Click for source code.</a>&nbsp;<? if ($_REQUEST['ud']['user']=="vchris") print "<a href=\"../../transactions/all/index.php?act=send_block\" class=\"font_10\">send block</a></div>"; ?></div>
           <div class="col-md-2"></div>
           </div>
        <?
	}
	
	function showOk($mes, $width=600)
	{
		?>
            
           
           <br>
           <table width="90%" id="tab_alert">
           <tr><td>
           <div class="alert alert-success alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <? print $mes; ?>
           </div>
           </td></tr>
           </table>
           <br>
           
           <script>
		   window.setTimeout(function() {
           $("#tab_alert").fadeTo(500, 0).slideUp(10, function(){
           $(this).remove(); 
           });
           }, 1000);
		   </script>
        
        <?
	}
	
	function showErr($mes, $width=600)
	{
		?>
            
           <table width="90%" id="tab_alert">
           <tr><td>
           <div class="alert alert-danger alert-dismissible font_14" role="alert">
           <? print $mes; ?>
           </div>
           </td></tr>
           </table>
           <br>
           
           <script>
		   window.setTimeout(function() {
           $("#tab_alert").fadeTo(2000, 0).slideUp(10, function(){
           $(this).remove(); 
           });
           }, 2000);
		   </script>
        
        <?
	}
	
	function showConfirmModal($question="Are you sure you want to delete this item ?", 
	                          $details="This item will be deleted immediately. You can't undo this action.")
	{
		?>
            
             <div class="modal fade" id="confirm_modal">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">Confirm Action</h4>
             </div>
             <form method="post" action="">
             <input type="hidden" name="act" id="act" value="del"/>
             <input type="hidden" name="par_1" id="par_1" value=""/>
             <input type="hidden" name="par_2" id="par_2" value=""/>
             <div class="modal-body">
           
            <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center"><img src="../../template/template/GIF/confirm.png" width="150" height="150" alt=""/></td>
            <td width="443" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" class="simple_blue_18"><strong><? print $question; ?></strong></td>
              </tr>
             
              <tr>
                <td align="left" class="simple_gri_12"><? print $details; ?></td>
              </tr>
            </table></td>
            </tr>
            </table>
        
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;No</button>
             <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
             </div>
             </form>
             </div></div></div>
        
        <?
	}
	
	function showConfirmBuyModal($question="Are you sure you want to buy this item ?", 
	                             $details="This item will be bought immediately. You can't undo this action.")
	{
		?>
            
             <div class="modal fade" id="confirm_buy_modal">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">Confirm Action</h4>
             </div>
             <form method="post" action="">
             <input type="hidden" name="act" id="act" value="buy"/>
             <input type="hidden" name="par_1" id="par_1" value=""/>
             <input type="hidden" name="par_2" id="par_2" value=""/>
             <div class="modal-body">
           
            <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center"><img src="../../GIF/cart.png" width="116" height="181" /></td>
            <td width="443" align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" class="simple_blue_18"><strong><? print $question; ?></strong></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" class="simple_gri_12"><? print $details; ?></td>
              </tr>
            </table></td>
            </tr>
            </table>
        
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;No</button>
             <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
             </div>
             </form>
             </div></div></div>
        
        <?
	}
	
	function showDelModal()
	{
		?>
            
             <div class="modal fade" id="del_modal">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">Delete Item Confirmation</h4>
             </div>
             <form method="post" action="">
             <input type="hidden" name="act" id="act" value="del"/>
             <input type="hidden" name="par_1" id="par_1" value=""/>
             <input type="hidden" name="par_2" id="par_2" value=""/>
             <div class="modal-body">
           
            <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center"><img src="../../template/template/GIF/trash.png" width="116" height="181" /></td>
            <td width="443" align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="25" align="left" valign="top" class="simple_blue_18"><span class="simple_blue_14"><strong>Network Fee Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><span class="simple_blue_14">
                  <?
			      $this->showMyAdrDD("dd_net_fee", 400);
			   ?>
                  </span></td>
              </tr>
              </table></td>
            </tr>
            </table>
        
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;No</button>
             <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
             </div>
             </form>
             </div></div></div>
        
        <?
	}
	
	
	
	
	
	
	
	function showWebsiteCodePanel($visible=false)
	{
		?>
        
            <table width="75%" border="0" cellspacing="2" cellpadding="5" tab="tab_net_fee" style="<? if ($visible==true) print "display:block"; else print "display:none"; ?>">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Network Fee</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fafafa">
                    <span class="simple_blue_18" id="txt_code" name="txt_code"><? print $val; ?></span></td>
                  </tr>
</table>
        
<?
	}
	
	function domainValid($domain)
	{
		return true;
	}
	
	function adrValid($adr)
	{
		if (strlen($adr)<31)
		{
			if ($this->domainExist($adr)==false)
			   return false;
		}
		else
		{
		   if (strlen($adr)!=108 && 
		    strlen($adr)!=124 && 
		    strlen($adr)!=160 && 
		    strlen($adr)!=212) 
	       return false;
					
		   for ($a=0; $a<=strlen($adr)-1; $a++)
	  	   {
			   if (ord($adr[$a])!=47 && 
			    ord($adr[$a])!=43 && 
			    ord($adr[$a])!=61 && 
				$this->isLetter(ord($adr[$a]))==false && 
				$this->isFigure(ord($adr[$a]))==false) 
			 return false;
		   }
		}
		
		return true;
	}
	
	
	function adrFromDomain($domain)
	{
		$query="SELECT * FROM domains WHERE domain='".$domain."'";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $row['adr'];
	}
	
	function feeAdrValid($adr)
	{
		return true;
	}
	
	function domainExist($domain)
	{
		if ($this->domainValid($domain)==false) return false;
		
		$query="SELECT * FROM domains WHERE domain='".$domain."'";
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;
	}
	
	function hasAttr($adr, $attr)
	{
		$query="SELECT * 
		          FROM adr_options 
				 WHERE adr='".$adr."' 
				   AND op_type='".$attr."'";
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;
	}
	
	function isSealed($adr)
	{
		if ($this->hasAttr($adr, "ID_SEALED")==true)
		  return true;
		else
		  return false;
	}
	
	
	
	function getBalance($adr)
	{
		$query="SELECT * FROM adr WHERE adr='$adr'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
        return $row['balance'];
	}
	
	function isLetter($char)
	{
		if ($char>=65 && $char<=90) return true;
		if ($char>=97 && $char<=122) return true;
		return false;
	}
	
	function isFigure($char)
	{
		if ($char>=48 && $char<=57) return true;
		return false;
	}
	

	
	function showIncreaseBidModal($init_panel_val="0.0365", $init_val=365)
	{
		$this->showModalHeader("modal_increase_bid", "Increase Bid", "act", "renew", "rowhash", "", $link);
		?>
        
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../template/template/GIF/increase_bid.png" width="179" height="144" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
             
            </table></td>
            <td width="438" align="center" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->showMyAdrDD("dd_fee", $init_val); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>New Bid</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_new_bid" name="txt_new_bid" placeholder="Bid" style="width:100px" value="0.0001"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->showModalFooter("Update");
	}
	
	function showRenewModal($link="")
	{
		$this->showModalHeader("modal_renew", "Renew", "act", "renew", "", "", $link);
		?>
        
           <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../template/template/GIF/renew.png" width="150" height="150" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->showNetFeePanel(0.0365, "renew"); ?></td>
              </tr>
            </table></td>
            <td width="438" align="center" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->showMyAdrDD("dd_fee", "370"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_ren_days" name="txt_ren_days" placeholder="Days" style="width:100px" value="365"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->showModalFooter(true, "Renew");
	}
	
	function showAdsModal()
	{
		$this->showModalHeader("modal_ads", "New Ad Message", "act", "new_ad", "", "", "../../ads/ads/index.php");
		?>
        
            <table width="560" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="214" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../ads/ads/GIF/ads.png" width="180" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->showNetFeePanel(0.0365, "ads"); ?></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="396" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" height="30px" class="simple_blue_14" valign="top"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->showMyAdrDD("dd_ads_fee_adr", 340); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" height="30px"><strong class="simple_blue_14">Title</strong>&nbsp;&nbsp;<span class="simple_gri_10">(5-35 characters)</span></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_ads_title" name="txt_ads_title" class="form-control" placeholder="Title (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" class="simple_blue_14" valign="top" height="30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="73%" align="left"><strong>Message</strong>&nbsp;&nbsp;<span class="simple_gri_10">(50-70 characters)</span></td>
                    <td width="27%" align="right"><span class="simple_gri_10" id="td_chars" name="td_chars">0 characters</span></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">
                <textarea class="form-control" id="txt_ads_mes" name="txt_ads_mes" placeholder="Message (50-70 charcaters)" rows="5"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" class="simple_blue_14" valign="top" height="30px"><strong>Link</strong></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_ads_link" name="txt_ads_link" class="form-control" placeholder="Link"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left"><span class="simple_blue_14"><strong>Country</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->showCountriesDD(); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" align="left" class="simple_blue_14" valign="top" height="30px"><strong>Hours</strong></td>
                    <td width="33%" align="left" class="simple_blue_14" valign="top" height="30px"><strong>Bid</strong></td>
                    <td width="33%" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>
                    <input id="txt_ads_hours" name="txt_ads_hours" class="form-control" style="width:100px" value="24" type="number" min="1" step="1" /></td>
                    <td>
                    <input id="txt_ads_bid" name="txt_ads_bid" class="form-control" style="width:100px" value="0.0001" type="number" min="0.0001" step="0.0001" /></td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#form_modal_ads').submit(
		function() 
		{ 
		   $('#txt_ads_title').val(btoa($('#txt_ads_title').val())); 
		   $('#txt_ads_mes').val(btoa($('#txt_ads_mes').val())); 
		   $('#txt_ads_link').val(btoa($('#txt_ads_link').val())); 
		});
		
		
		    $('#txt_ads_mes').keyup(
			function() 
			{ 
			   var str=String($('#txt_ads_mes').val());
			   var length=str.length;
			   $('#td_chars').text(length+" characters");
			});
		
		  linkToNetFeeBid("txt_ads_hours", "ads_net_fee_panel_val", "txt_ads_bid", 0.0024);
		  </script>
		
        
        <?
		$this->showModalFooter("Send");
	}
	
	
	
	function showSearchBox($txt)
	{
		?>
           
           <br />
           <table width="93%" border="0" cellspacing="0" cellpadding="5">
           <tr>
            <td width="81%">
            <div class="input-group">
            <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
            <input type="text" class="form-control" id="txt_search" name="txt_search" placeholder="<? print $txt; ?>">
            </div>
            </td>
            </tr>
            </table>
            <br />
        
        <?
	}
	
	function formatAdr($adr)
	{
		// No content
		if ($adr=="") return "";
		
		// Load name data
		$query="SELECT * FROM domains WHERE adr='".$adr."'";
		$result=$this->kern->execute($query);	
		
		// Has a name ?
		if (mysql_num_rows($result)>0)
		{
	       $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   return $row['domain'];
		}
		else return "...".substr($adr, 40, 20)."...";
	}
	
	function showSwitch($id, $pos="off")
	{
		?>
        
           <table width="68" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <input id="<? print $id; ?>" name="<? print $id; ?>" type="hidden" value="off"/>
            <img src="../../GIF/sw_off.png" width="68" height="34" style="cursor:pointer; display:<? if ($pos=="off") print "block"; else print "none"; ?>" id="img_off_<? print $id; ?>"  name="img_off_<? print $id; ?>" />
            <img src="../../GIF/sw_on.png" width="68" height="34" style="cursor:pointer; display:<? if ($pos=="off") print "none"; else print "block"; ?>" id="img_on_<? print $id; ?>" name="img_on_<? print $id; ?>"/>
            </td>
          </tr>
        </table>
        
        <script>
		   $('#img_off_<? print $id; ?>').click(function() 
		   {  
		       $('#img_off_<? print $id; ?>').css('display', 'none'); 
			   $('#img_on_<? print $id; ?>').css('display', 'block'); 
			   $('#<? print $id; ?>').val('on'); 
		   });
		   
		   $('#img_on_<? print $id; ?>').click(function() 
		   {  
		       $('#img_off_<? print $id; ?>').css('display', 'block'); 
			   $('#img_on_<? print $id; ?>').css('display', 'none'); 
			   $('#<? print $id; ?>').val('off'); 
			});
		</script>
        
        <?
	}
	
	function showQRModal()
	{
		$this->showModalHeader("modal_qr", "Address QR Code");
		?>
        
           <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td align="center">
            <img id="qr_img" name="qr_img"/>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><textarea class="form-control" name="txt_plain" id="txt_plain" rows="4" style="width:550px"></textarea></td>
          </tr>
        </table>
        
        <?
		$this->showModalFooter(false);
	}
	
	
	
	function showTopBar($sel=1)
	{
		if ($_SESSION['userID']>0)
		{
			// Load unmoderated commnets
		    $query="SELECT COUNT(*) AS total 
		          FROM tweets_comments 
		         WHERE tweetID IN (SELECT tweetID 
				                     FROM tweets 
								    WHERE adr IN (SELECT adr 
									                FROM my_adr 
												   WHERE userID='".$_REQUEST['ud']['ID']."')) 
			       AND status='ID_PENDING'";
		    $result=$this->kern->execute($query);	
		    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		    $comments=$row['total'];
		
		    ?>
        
            <nav class="navbar navbar-inverse navbar-static-top" style="margin-bottom:0px">
            <div class="container-fluid">
            <div class="navbar-header">
            <div class="navbar-brand"><a href="../../../index.php">Wallet</a></div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
            </button>
            </div>
           
            <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
            <li <? if ($sel==1) print "class='active'"; ?>><a href="../../transactions/all/index.php?act=clear">Transactions
			<? 
			    $unread=$_REQUEST['ud']['unread_trans']+$_REQUEST['ud']['unread_esc']+$_REQUEST['ud']['unread_multisig'];
			    if ($unread>0) $this->showBadge($unread); 
		    ?>
            </a></li>
            <li <? if ($sel==2) print "class='active'"; ?>><a href="../../adr/adr/index.php">Addresses <? if ($_REQUEST['ud']['pending_adr']>0) $this->showBadge($_REQUEST['ud']['pending_adr']); ?></a></li>       
            <li <? if ($sel==3) print "class='active'"; ?>><a href="../../mes/inbox/index.php">Messages <? if ($_REQUEST['ud']['unread_mes']>0) $this->showBadge($_REQUEST['ud']['unread_mes']); ?></a></li>
            <li class='dropdown open; <? if ($sel==4) print "active"; ?>'>
            <a href="../../assets/assets/index.php" class="dropdown-toggle" data-toggle="dropdown">Trade<b class="caret"></b></a>
            <ul class="dropdown-menu">
             <li><a href="../../assets/options/index.php">Binary Options</a></li>
            <li><a href="../../assets/margin_mkts/index.php">Margin Markets</a></li>
            <li><a href="../../assets/pegged_assets/index.php">Market Pegged Assets</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../../assets/user/index.php">User Issued Assets</a></li>
            <li><a href="../../assets/assets_mkts/index.php">Assets Markets</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../../assets/exchangers/index.php">Assets Exchangers</a></li>
            
            <li><a href="../../assets/feeds/index.php">Data Feeds</a></li>
            </ul></li>     
            
             <li class='dropdown open; <? if ($sel==5) print "active"; ?>'><a href="../../shop/goods/index.php" class="dropdown-toggle" data-toggle="dropdown">Shop<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="../../shop/goods/index.php">Goods and Services</a></li>
            <li><a href="../../shop/escrowers/index.php">Escrowers</a></li>
            </ul></li>
            
            <li class='dropdown open; <? if ($sel==6) print "active"; ?>'><a href="../../app/directory/index.php" class="dropdown-toggle" data-toggle="dropdown">Applications<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="../../app/directory/index.php">Applications Directory</a></li>
            <li><a href="../../app/market/index.php">Applications Market</a></li>
            <li><a href="../../app/mine/index.php">My Applications</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../../app/write/index.php">Write Applications</a></li>
            <li><a href="../../app/reference/index.php">Language Reference</a></li>
            </ul></li>
            
             <li class='dropdown open; <? if ($sel==7) print "active"; ?>'><a href="../../shop/goods/index.php" class="dropdown-toggle" data-toggle="dropdown">Explorer<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="../../explorer/packets/index.php">Block Explorer</a></li>
            <li><a href="../../help/help/index.php">Help</a></li>
            </ul></li>
          
            <li <? if ($sel==8) print "class='active'"; ?>><a href="../../tweets/home/index.php">Tweets <? if ($comments>0) $this->showBadge($comments); ?></a></li>
            
            
            </ul>
            
          
            
            </div>   
            </div>
            </nav> 
        
           <?
		}
		else
		{
			?>
            
             <nav class="navbar navbar-inverse navbar-static-top" style="margin-bottom:0px">
             <div class="container-fluid">
             <div class="navbar-header">
             <div class="navbar-brand"><a href="../../../index.php">Wallet</a></div>
             <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span> 
             </button>
             </div>
             <div class="collapse navbar-collapse" id="myNavbar">
             <ul class="nav navbar-nav">
             <li <? if ($sel==1) print "class='active'"; ?>><a href="../../tweets/tweets/index.php">Tweets</a></li>
            <li class='dropdown open; <? if ($sel==4) print "active"; ?>'>
            <a href="../../assets/assets/index.php" class="dropdown-toggle" data-toggle="dropdown">Trade<b class="caret"></b></a>
            <ul class="dropdown-menu">
             <li><a href="../../assets/options/index.php">Binary Options</a></li>
            <li><a href="../../assets/margin_mkts/index.php">Margin Markets</a></li>
            <li><a href="../../assets/pegged_assets/index.php">Market Pegged Assets</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../../assets/user/index.php">User Issued Assets</a></li>
            <li><a href="../../assets/assets_mkts/index.php">Assets Markets</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../../assets/exchangers/index.php">Assets Exchangers</a></li>
            
            <li><a href="../../assets/feeds/index.php">Data Feeds</a></li>
            </ul></li>     
            
             <li class='dropdown open; <? if ($sel==5) print "active"; ?>'><a href="../../shop/goods/index.php" class="dropdown-toggle" data-toggle="dropdown">Shop<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="../../shop/goods/index.php">Goods and Services</a></li>
            <li><a href="../../shop/escrowers/index.php">Escrowers</a></li>
            </ul></li>
            
             <li class='dropdown open; <? if ($sel==6) print "active"; ?>'><a href="../../app/directory/index.php" class="dropdown-toggle" data-toggle="dropdown">Applications<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="../../app/directory/index.php">Applications Directory</a></li>
            <li><a href="../../app/market/index.php">Applications Market</a></li>
            </ul></li>
            
            <li <? if ($sel==2) print "class='active'"; ?>><a href="../../explorer/packets/index.php">Explorer</a></li>
            <li <? if ($sel==3) print "class='active'"; ?>><a href="../../help/help/index.php">Help</a></li>
            </ul>
             
              
             </ul>
             </div>   
             </div>
             </nav>
            
            
            <?
		}
	}
	
	function showBalanceBar()
	{
		// Testnet
		$this->showTestnetModal();
		
		if ($_SESSION['userID']>0)
		{
				$this->showSendModal();
		?>
        
            <div class="row">
            <table class="table-responsive" width="100%">
            <tr bgcolor="#cad1d7">
            <td height="65px" width="8%" align="center"><a href="javascript:void(0)" onClick="$('#testnet_modal').modal()"><span class="label label-danger">Testnet</span></a></td>
            <td height="50px" width="84%" align="right" id="td_balance">
            <table>
            <tr>
            <td>
            <span class="font_20" id="balance_msk"><strong><? print explode(".", $_REQUEST['ud']['balance'])[0]; ?> </strong></span>
            <span class="font_16"><? print ".".explode(".", $_REQUEST['ud']['balance'])[1]; ?> MSK&nbsp;&nbsp;</span>
           
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="btn btn-primary" id="but_send" onClick="$('#send_coins_modal').modal()">
            <span class="glyphicon glyphicon-send">&nbsp;</span>Send Coins</a>
            </td>
            </tr>
            </table>
            </td>
            <td height="60px" width="8%">&nbsp;</td>
            </tr>
            </table>
            </div>
        
        <?
		}
	}
	
	function showLocation($link_1="", $txt_1="", $link_2="", $txt_2="")
	{
		?>
        
          <br>
          <ol class="breadcrumb" style="width:90%; font-size:14px;">
          <li><a href="../../../index.php">Home</a></li>
          <li><a href="<? print $link_1; ?>"><? print $txt_1; ?></a></li>
          <li class="active"><? print $txt_2; ?></li>
          </ol>
          
        
        <?
	}
	
	function showBadge($no)
	{
		print "&nbsp;&nbsp;&nbsp;<span class='badge'>".$no."</span>";
	}
	
	function showNav($active=1,
	                 $link_1="", $txt_1="", $no_1="",
	                 $link_2="", $txt_2="", $no_2="", 
					 $link_3="", $txt_3="", $no_3="", 
					 $link_4="", $txt_4="", $no_4="", 
					 $link_5="", $txt_5="", $no_5="")
	{
		   // Zero ?
		   if ($no_1==0) $no_1="";
		   if ($no_2==0) $no_2="";
		   if ($no_3==0) $no_3="";
		   if ($no_4==0) $no_4="";
		   if ($no_5==0) $no_5="";
		   
		   print "<br><ul class=\"nav nav-tabs\" style=\"width:90%\">";
           
		   // Tab 1
		   if ($link_1!="") 
		   {  
		       if ($active==1) 
			      print "<li role='presentation' class='active'><a href='".$link_1."'>".$txt_1."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_1."</span></a></li>";
			   else
			      print "<li role='presentation'><a href='".$link_1."'>".$txt_1."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_1."</span></a></li>";
				
		   }
		   
		   // Tab 2
		   if ($link_2!="") 
		   {  
		       if ($active==2) 
			      print "<li role='presentation' class='active'><a href='".$link_2."'>".$txt_2."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_2."</span></a></li>";
			   else
			      print "<li role='presentation'><a href='".$link_2."'>".$txt_2."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_2."</span></a></li>";
				
		   }
		   
		   // Tab 3
		   if ($link_3!="") 
		   {  
		       if ($active==3) 
			      print "<li role='presentation' class='active'><a href='".$link_3."'>".$txt_3."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_3."</span></a></li>";
			   else
			      print "<li role='presentation'><a href='".$link_3."'>".$txt_3."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_3."</span></a></li>";
				
		   }
		   
		   // Tab 4
		   if ($link_4!="") 
		   {  
		       if ($active==4) 
			      print "<li role='presentation' class='active'><a href='".$link_4."'>".$txt_4."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_4."</span></a></li>";
			   else
			      print "<li role='presentation'><a href='".$link_4."'>".$txt_4."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_4."</span></a></li>";
				
		   }
		   
		   // Tab 5
		   if ($link_5!="") 
		   {  
		       if ($active==5) 
			      print "<li role='presentation' class='active'><a href='".$link_5."'>".$txt_5."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_5."</span></a></li>";
			   else
			      print "<li role='presentation'><a href='".$link_5."'>".$txt_5."&nbsp;&nbsp;&nbsp;<span class='badge'>".$no_5."</span></a></li>";
				
		   }
		  
           print "</ul>";
	}
	
	function showStreaming($type, $data)
	{
		?>
        
        <script>
            var ws=new WebSocket("ws://localhost:8181");
            ws.onopen=function(e) 
            {
	          console.log('Connection started...');
	
	          var packet={'type' : '<? print $type; ?>', 'data' : [<? print $data; ?>]};
	          ws.send(JSON.stringify(packet));
            }

            ws.onmessage=function(e)
            {
               console.log(e.data);
               var data=JSON.parse(e.data);
   
              for (a=0; a<=data['positions'].length-1; a++)
              {
                var posID=data['positions'][a]['posID'];
                var pl=data['positions'][a]['pl'];
                var pl_proc=data['positions'][a]['pl_proc']; 
				var cur=data['positions'][a]['cur']; 
      
	            // Old pl
	            var old_pl=parseFloat($('#td_pos_'+posID).text());
				
				if (pl<0) 
				   $('#td_pos_'+posID).css('color', '#990000');
	            else
				   $('#td_pos_'+posID).css('color', '#009900');
				   
				if (pl_proc<0) 
				   $('#td_pos_proc_'+posID).css('color', '#990000');
	            else
				   $('#td_pos_proc_'+posID).css('color', '#009900');
				   
	            if (old_pl==parseFloat(pl)) 
				{
				   $('#td_pos_'+posID).css('backgroundColor', '#f0f0f0');
	               $('#td_pos_'+posID).animate({backgroundColor:"#ffffff"});
				}
				else if (old_pl<parseFloat(pl)) 
				{
	               $('#td_pos_'+posID).css('backgroundColor', '#bfe3c6');
	               $('#td_pos_'+posID).animate({backgroundColor:"#ffffff"}, 3000);
				}
				else if (old_pl>parseFloat(pl)) 
				{
	               $('#td_pos_'+posID).css('backgroundColor', '#e3c3bf');
	               $('#td_pos_'+posID).animate({backgroundColor:"#ffffff"}, 3000);
				}
	  
	            // Old proc
	            var old_pl_proc=parseFloat($('#td_pos_proc_'+posID).text());
	  
	            if (old_pl_proc==parseFloat(pl_proc)) 
				{
				   $('#td_pos_proc_'+posID).css('backgroundColor', '#f0f0f0');
	               $('#td_pos_proc_'+posID).animate({backgroundColor:"#ffffff"});
				}
				else if (old_pl_proc<parseFloat(pl_proc)) 
				{
	               $('#td_pos_proc_'+posID).css('backgroundColor', '#bfe3c6');
	               $('#td_pos_proc_'+posID).animate({backgroundColor:"#ffffff"}, 3000);
				}
				else if (old_pl_proc>parseFloat(pl_proc)) 
				{
	               $('#td_pos_proc_'+posID).css('backgroundColor', '#e3c3bf');
	               $('#td_pos_proc_'+posID).animate({backgroundColor:"#ffffff"}, 3000);
				}
	  
	            $('#td_pos_'+posID).text(pl+" "+cur)
	            $('#td_pos_proc_'+posID).text(pl_proc+"%")
   }
}

ws.onError=function(e)
{
	console.log(e);
}

</script>
        
        <?
	}
	
	function showChart($feed, $branch)
	{
		// Feed is mine ?
		$query="SELECT * 
		          FROM feeds 
				 WHERE symbol='".$feed."' 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
							    WHERE userID='".$_REQUEST['ud']['ID']."')";
	   $result=$this->kern->execute($query);	
	   if (mysql_num_rows($result)>0) 
	      $mine=true;
	   else
	     $mine=false;
		 
		 // Load branch
		 $query="SELECT * 
		           FROM feeds_branches 
				  WHERE feed_symbol='".$feed."' 
				    AND symbol='".$branch."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$rl_symbol=$row['rl_symbol'];
		
		// Last 100 records
		$query="SELECT MAX(ID) AS ma
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$min=$row['ma']-250;
		
		$query="SELECT * 
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."' 
			       AND ID>".$min; 
		$result=$this->kern->execute($query);	
	   
		?>
           
           <script type="text/javascript">
	       google.load('visualization', '1', {packages: ['corechart', 'line']});
           google.setOnLoadCallback(drawChart);

      function drawChart() 
	  {
         
		 var data = new google.visualization.DataTable();
         data.addColumn('string', 'Date');
		 data.addColumn('number', 'Price');
		 
         data.addRows([
		 <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  print "['', ".$row['val']."],";
		 ?>
		 ]);

        var options = {
          title: '<? print $symbol; ?> Chart',
          curveType: 'function',
		  legend:'none',
	      tooltip: { isHtml: true },
	      chartArea: {'width': '80%', 'height': '85%'},
	      backgroundColor : '#ffffff'
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
    
           <br>
           <table width="100%">
           <tr>
           <td width="100%"><div id="curve_chart" style="width: 100%; height: 400px"></div></td>
           </tr>
           </table>
           
           <br><br>
        
        <?
	}
	
	function showCurDD($name)
	{
		?>
        
            <select id="<? print $name; ?>" name="<? print $name; ?>" class="form-control">
            <option value="USD">America (United States) Dollars – USD</option>
            <option value="Euro - EUR">Euro – EUR</option>
            <option value="GBP">United Kingdom Pounds – GBP</option>
            <option value="AUD">Australia Dollars – AUD</option>
           
            <option value="AFN">Afghanistan Afghanis – AFN</option>
            <option value="ALL">Albania Leke – ALL</option>
            <option value="DZD">Algeria Dinars – DZD</option>
            <option value="ARS">Argentina Pesos – ARS</option>
            <option value="BSD">Bahamas Dollars – BSD</option>
            <option value="BHD">Bahrain Dinars – BHD</option>
            <option value="BDT">Bangladesh Taka – BDT</option>
            <option value="BBD">Barbados Dollars – BBD</option>
            <option value="BMD">Bermuda Dollars – BMD</option>   
            <option value="BRL">Brazil Reais – BRL</option>
            <option value="BGN">Bulgaria Leva – BGN</option>
            <option value="CAD">Canada Dollars – CAD</option>
            <option value="CLP">Chile Pesos – CLP</option>
            <option value="CNY">China Yuan Renminbi – CNY</option>
            <option value="COP">Colombia Pesos – COP</option>
            <option value="CRC">Costa Rica Colones – CRC</option>
<option value="HRK">Croatia Kuna – HRK</option>
<option value="CZK">Czech Republic Koruny – CZK</option>
<option value="DKK">Denmark Kroner – DKK</option>
<option value="DOP">Dominican Republic Pesos – DOP</option>
<option value="NLG">Dutch (Netherlands) Guilders – NLG</OPTION>
<option value="XCD">Eastern Caribbean Dollars – XCD</option>
<option value="EGP">Egypt Pounds – EGP</option>
<option value="EEK">Estonia Krooni – EEK</option>
<option value="FJD">Fiji Dollars – FJD</option>
<option value="FIM">Finland Markkaa – FIM</OPTION>
<option value="GTQ">Guatemalan Quetzal – GTQ</OPTION>
<option value="HKD">Hong Kong Dollars – HKD</option>
<option value="HUF">Hungary Forint – HUF</option>
<option value="ISK">Iceland Kronur – ISK</option>
<option value="INR">India Rupees – INR</option>
<option value="IDR">Indonesia Rupiahs – IDR</option>
<option value="IRR">Iran Rials – IRR</option>
<option value="IQD">Iraq Dinars – IQD</option>
<option value="IEP*">Ireland Pounds – IEP*</OPTION>
<option value="ILS">Israel New Shekels – ILS</option>
<option value="JMD">Jamaica Dollars – JMD</option>
<option value="JPY">Japan Yen – JPY</option>
<option value="JOD">Jordan Dinars – JOD</option>
<option value="KES">Kenya Shillings – KES</option>
<option value="KRW">Korea (South) Won – KRW</option>
<option value="KWD">Kuwait Dinars – KWD</option>
<option value="LBP">Lebanon Pounds – LBP</option>
<option value="MYR">Malaysia Ringgits – MYR</option>
<option value="MUR">Mauritius Rupees – MUR</option>
<option value="MXN">Mexico Pesos – MXN</option>
<option value="MAD">Morocco Dirhams – MAD</option>
<option value="NZD">New Zealand Dollars – NZD</option>
<option value="NOK">Norway Kroner – NOK</option>
<option value="OMR">Oman Rials – OMR</option>
<option value="PKR">Pakistan Rupees – PKR</option>
<option value="PEN">Peru Nuevos Soles – PEN</option>
<option value="PHP">Philippines Pesos – PHP</option>
<option value="PLN">Poland Zlotych – PLN</option>
<option value="PTE">Portugal Escudos – PTE</OPTION>
<option value="QAR">Qatar Riyals – QAR</option>
<option value="RON">Romania Lei – RON</option>
<option value="RUB">Russia Rubles – RUB</option>
<option value="SAR">Saudi Arabia Riyals – SAR</option>
<option value="SGD">Singapore Dollars – SGD</option>
<option value="SKK">Slovakia Koruny – SKK</option>
<option value="SIT">Slovenia Tolars – SIT</option>
<option value="ZAR">South Africa Rand – ZAR</option>
<option value="KRW">South Korea Won – KRW</option>
<option value="ESP">Spain Pesetas – ESP</OPTION>
<option value="XDR">Special Drawing Rights (IMF) – XDR</option>
<option value="LKR">Sri Lanka Rupees – LKR</option>
<option value="SDD">Sudan Dinars – SDD</option>
<option value="SEK">Sweden Kronor – SEK</option>
<option value="CHF">Switzerland Francs – CHF</option>
<option value="TWD">Taiwan New Dollars – TWD</option>
<option value="THB">Thailand Baht – THB</option>
<option value="TTD">Trinidad and Tobago Dollars – TTD</option>
<option value="TND">Tunisia Dinars – TND</option>
<option value="TRY">Turkey New Lira – TRY</option>
<option value="AED">United Arab Emirates Dirhams – AED</option>
<option value="VEB">Venezuela Bolivares – VEB</option>
<option value="VND">Vietnam Dong – VND</option>
<option value="ZMK">Zambia Kwacha – ZMK</option>
</select>

        
        <?
	}
	
	function showCountriesDD($name="dd_country")
	{
		?>
        
        <select name="<? print $name; ?>" id="<? print $name; ?>" class="form-control" style="width:90%">
	<option value="XX">All countries</option>
    <option value="AF">Afghanistan</option>
	<option value="AX">Åland Islands</option>
	<option value="AL">Albania</option>
	<option value="DZ">Algeria</option>
	<option value="AS">American Samoa</option>
	<option value="AD">Andorra</option>
	<option value="AO">Angola</option>
	<option value="AI">Anguilla</option>
	<option value="AQ">Antarctica</option>
	<option value="AG">Antigua and Barbuda</option>
	<option value="AR">Argentina</option>
	<option value="AM">Armenia</option>
	<option value="AW">Aruba</option>
	<option value="AU">Australia</option>
	<option value="AT">Austria</option>
	<option value="AZ">Azerbaijan</option>
	<option value="BS">Bahamas</option>
	<option value="BH">Bahrain</option>
	<option value="BD">Bangladesh</option>
	<option value="BB">Barbados</option>
	<option value="BY">Belarus</option>
	<option value="BE">Belgium</option>
	<option value="BZ">Belize</option>
	<option value="BJ">Benin</option>
	<option value="BM">Bermuda</option>
	<option value="BT">Bhutan</option>
	<option value="BO">Bolivia, Plurinational State of</option>
	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
	<option value="BA">Bosnia and Herzegovina</option>
	<option value="BW">Botswana</option>
	<option value="BV">Bouvet Island</option>
	<option value="BR">Brazil</option>
	<option value="IO">British Indian Ocean Territory</option>
	<option value="BN">Brunei Darussalam</option>
	<option value="BG">Bulgaria</option>
	<option value="BF">Burkina Faso</option>
	<option value="BI">Burundi</option>
	<option value="KH">Cambodia</option>
	<option value="CM">Cameroon</option>
	<option value="CA">Canada</option>
	<option value="CV">Cape Verde</option>
	<option value="KY">Cayman Islands</option>
	<option value="CF">Central African Republic</option>
	<option value="TD">Chad</option>
	<option value="CL">Chile</option>
	<option value="CN">China</option>
	<option value="CX">Christmas Island</option>
	<option value="CC">Cocos (Keeling) Islands</option>
	<option value="CO">Colombia</option>
	<option value="KM">Comoros</option>
	<option value="CG">Congo</option>
	<option value="CD">Congo, the Democratic Republic of the</option>
	<option value="CK">Cook Islands</option>
	<option value="CR">Costa Rica</option>
	<option value="CI">Côte d'Ivoire</option>
	<option value="HR">Croatia</option>
	<option value="CU">Cuba</option>
	<option value="CW">Curaçao</option>
	<option value="CY">Cyprus</option>
	<option value="CZ">Czech Republic</option>
	<option value="DK">Denmark</option>
	<option value="DJ">Djibouti</option>
	<option value="DM">Dominica</option>
	<option value="DO">Dominican Republic</option>
	<option value="EC">Ecuador</option>
	<option value="EG">Egypt</option>
	<option value="SV">El Salvador</option>
	<option value="GQ">Equatorial Guinea</option>
	<option value="ER">Eritrea</option>
	<option value="EE">Estonia</option>
	<option value="ET">Ethiopia</option>
	<option value="FK">Falkland Islands (Malvinas)</option>
	<option value="FO">Faroe Islands</option>
	<option value="FJ">Fiji</option>
	<option value="FI">Finland</option>
	<option value="FR">France</option>
	<option value="GF">French Guiana</option>
	<option value="PF">French Polynesia</option>
	<option value="TF">French Southern Territories</option>
	<option value="GA">Gabon</option>
	<option value="GM">Gambia</option>
	<option value="GE">Georgia</option>
	<option value="DE">Germany</option>
	<option value="GH">Ghana</option>
	<option value="GI">Gibraltar</option>
	<option value="GR">Greece</option>
	<option value="GL">Greenland</option>
	<option value="GD">Grenada</option>
	<option value="GP">Guadeloupe</option>
	<option value="GU">Guam</option>
	<option value="GT">Guatemala</option>
	<option value="GG">Guernsey</option>
	<option value="GN">Guinea</option>
	<option value="GW">Guinea-Bissau</option>
	<option value="GY">Guyana</option>
	<option value="HT">Haiti</option>
	<option value="HM">Heard Island and McDonald Islands</option>
	<option value="VA">Holy See (Vatican City State)</option>
	<option value="HN">Honduras</option>
	<option value="HK">Hong Kong</option>
	<option value="HU">Hungary</option>
	<option value="IS">Iceland</option>
	<option value="IN">India</option>
	<option value="ID">Indonesia</option>
	<option value="IR">Iran, Islamic Republic of</option>
	<option value="IQ">Iraq</option>
	<option value="IE">Ireland</option>
	<option value="IM">Isle of Man</option>
	<option value="IL">Israel</option>
	<option value="IT">Italy</option>
	<option value="JM">Jamaica</option>
	<option value="JP">Japan</option>
	<option value="JE">Jersey</option>
	<option value="JO">Jordan</option>
	<option value="KZ">Kazakhstan</option>
	<option value="KE">Kenya</option>
	<option value="KI">Kiribati</option>
	<option value="KP">Korea, Democratic People's Republic of</option>
	<option value="KR">Korea, Republic of</option>
	<option value="KW">Kuwait</option>
	<option value="KG">Kyrgyzstan</option>
	<option value="LA">Lao People's Democratic Republic</option>
	<option value="LV">Latvia</option>
	<option value="LB">Lebanon</option>
	<option value="LS">Lesotho</option>
	<option value="LR">Liberia</option>
	<option value="LY">Libya</option>
	<option value="LI">Liechtenstein</option>
	<option value="LT">Lithuania</option>
	<option value="LU">Luxembourg</option>
	<option value="MO">Macao</option>
	<option value="MK">Macedonia, the former Yugoslav Republic of</option>
	<option value="MG">Madagascar</option>
	<option value="MW">Malawi</option>
	<option value="MY">Malaysia</option>
	<option value="MV">Maldives</option>
	<option value="ML">Mali</option>
	<option value="MT">Malta</option>
	<option value="MH">Marshall Islands</option>
	<option value="MQ">Martinique</option>
	<option value="MR">Mauritania</option>
	<option value="MU">Mauritius</option>
	<option value="YT">Mayotte</option>
	<option value="MX">Mexico</option>
	<option value="FM">Micronesia, Federated States of</option>
	<option value="MD">Moldova, Republic of</option>
	<option value="MC">Monaco</option>
	<option value="MN">Mongolia</option>
	<option value="ME">Montenegro</option>
	<option value="MS">Montserrat</option>
	<option value="MA">Morocco</option>
	<option value="MZ">Mozambique</option>
	<option value="MM">Myanmar</option>
	<option value="NA">Namibia</option>
	<option value="NR">Nauru</option>
	<option value="NP">Nepal</option>
	<option value="NL">Netherlands</option>
	<option value="NC">New Caledonia</option>
	<option value="NZ">New Zealand</option>
	<option value="NI">Nicaragua</option>
	<option value="NE">Niger</option>
	<option value="NG">Nigeria</option>
	<option value="NU">Niue</option>
	<option value="NF">Norfolk Island</option>
	<option value="MP">Northern Mariana Islands</option>
	<option value="NO">Norway</option>
	<option value="OM">Oman</option>
	<option value="PK">Pakistan</option>
	<option value="PW">Palau</option>
	<option value="PS">Palestinian Territory, Occupied</option>
	<option value="PA">Panama</option>
	<option value="PG">Papua New Guinea</option>
	<option value="PY">Paraguay</option>
	<option value="PE">Peru</option>
	<option value="PH">Philippines</option>
	<option value="PN">Pitcairn</option>
	<option value="PL">Poland</option>
	<option value="PT">Portugal</option>
	<option value="PR">Puerto Rico</option>
	<option value="QA">Qatar</option>
	<option value="RE">Réunion</option>
	<option value="RO">Romania</option>
	<option value="RU">Russian Federation</option>
	<option value="RW">Rwanda</option>
	<option value="BL">Saint Barthélemy</option>
	<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
	<option value="KN">Saint Kitts and Nevis</option>
	<option value="LC">Saint Lucia</option>
	<option value="MF">Saint Martin (French part)</option>
	<option value="PM">Saint Pierre and Miquelon</option>
	<option value="VC">Saint Vincent and the Grenadines</option>
	<option value="WS">Samoa</option>
	<option value="SM">San Marino</option>
	<option value="ST">Sao Tome and Principe</option>
	<option value="SA">Saudi Arabia</option>
	<option value="SN">Senegal</option>
	<option value="RS">Serbia</option>
	<option value="SC">Seychelles</option>
	<option value="SL">Sierra Leone</option>
	<option value="SG">Singapore</option>
	<option value="SX">Sint Maarten (Dutch part)</option>
	<option value="SK">Slovakia</option>
	<option value="SI">Slovenia</option>
	<option value="SB">Solomon Islands</option>
	<option value="SO">Somalia</option>
	<option value="ZA">South Africa</option>
	<option value="GS">South Georgia and the South Sandwich Islands</option>
	<option value="SS">South Sudan</option>
	<option value="ES">Spain</option>
	<option value="LK">Sri Lanka</option>
	<option value="SD">Sudan</option>
	<option value="SR">Suriname</option>
	<option value="SJ">Svalbard and Jan Mayen</option>
	<option value="SZ">Swaziland</option>
	<option value="SE">Sweden</option>
	<option value="CH">Switzerland</option>
	<option value="SY">Syrian Arab Republic</option>
	<option value="TW">Taiwan, Province of China</option>
	<option value="TJ">Tajikistan</option>
	<option value="TZ">Tanzania, United Republic of</option>
	<option value="TH">Thailand</option>
	<option value="TL">Timor-Leste</option>
	<option value="TG">Togo</option>
	<option value="TK">Tokelau</option>
	<option value="TO">Tonga</option>
	<option value="TT">Trinidad and Tobago</option>
	<option value="TN">Tunisia</option>
	<option value="TR">Turkey</option>
	<option value="TM">Turkmenistan</option>
	<option value="TC">Turks and Caicos Islands</option>
	<option value="TV">Tuvalu</option>
	<option value="UG">Uganda</option>
	<option value="UA">Ukraine</option>
	<option value="AE">United Arab Emirates</option>
	<option value="GB">United Kingdom</option>
	<option value="US">United States</option>
	<option value="UM">United States Minor Outlying Islands</option>
	<option value="UY">Uruguay</option>
	<option value="UZ">Uzbekistan</option>
	<option value="VU">Vanuatu</option>
	<option value="VE">Venezuela, Bolivarian Republic of</option>
	<option value="VN">Viet Nam</option>
	<option value="VG">Virgin Islands, British</option>
	<option value="VI">Virgin Islands, U.S.</option>
	<option value="WF">Wallis and Futuna</option>
	<option value="EH">Western Sahara</option>
	<option value="YE">Yemen</option>
	<option value="ZM">Zambia</option>
	<option value="ZW">Zimbabwe</option>
</select>
        
        <?
	}
}
?>