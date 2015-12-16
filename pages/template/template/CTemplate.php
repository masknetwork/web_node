<?
class CTemplate
{
	function CTemplate($db)
	{
	   $this->kern=$db;	
	}
	
	
	function showMyAdrDD($name="txt_adr", $width=300, $selected="")
	{
		 $query="SELECT ma.adr, adr.balance, dom.domain 
		           FROM my_adr AS ma 
			  LEFT JOIN adr ON ma.adr=adr.adr
			  LEFT JOIN domains AS dom ON dom.adr=ma.adr
			  WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
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
           <h4 class="modal-title"><? print $txt; ?></h4>
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
               <td align="center"><img src="../../template/template/GIF/wallet.jpg" /></td>
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
               <td height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->showMyAdrDD("dd_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
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
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Amount</strong></td>
             </tr>
             <tr>
               <td height="50" align="left">
               
               <table width="300px" border="0" cellspacing="0" cellpadding="0">
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
	
	function showTopMenu($sel=1)
	{
		if ($_REQUEST['ud']['ID']>0)
		  $this->showLoggedTopMenu($sel);
		else
		  $this->showGuestTopMenu($sel);
	}
	
	function showLoggedTopMenu($sel=1)
	{
		?>
        
            <table width="1000" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="112" height="40" align="center" valign="middle" class="<? if ($sel==1) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../transactions/all/index.php'">Transactions</td>
                  <td width="11" align="center" valign="bottom">
                  <img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/>
                  </td>
                  <td width="122" align="center" valign="middle" class="<? if ($sel==2) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../adr/adr/index.php'">Addresses <? if ($_REQUEST['ud']['pending_adr']>0) print "(".$_REQUEST['ud']['pending_adr'].")"; ?></td>
                  <td width="13" align="center" valign="middle" class="<? if ($sel==3) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../mes/inbox/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="123" align="center" valign="middle" class="<? if ($sel==3) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../mes/inbox/index.php'">Messages <span class="<? if ($sel==2) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer">
                    <? if ($_REQUEST['ud']['unread_mes']>0) print "(".$_REQUEST['ud']['unread_mes'].")"; ?>
                  </span></td>
                  <td width="13" align="center" valign="middle" class="<? if ($sel==4) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../assets/assets/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="102" align="center" valign="middle" class="<? if ($sel==4) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../assets/assets/index.php'">Assets</td>
                  <td width="22" align="center" valign="middle" class="<? if ($sel==5) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../feeds/feeds/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="95" align="center" valign="middle" class="<? if ($sel==5) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../feeds/feeds/index.php'">Feeds</td>
                  <td width="14" align="center" valign="middle" class="<? if ($sel==6) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../markets/goods/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="114" align="center" valign="middle" class="<? if ($sel==6) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../markets/goods/index.php'">Markets</td>
                  <td width="16" align="center" valign="middle" class="<? if ($sel==7) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../explorer/packets/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="114" align="center" valign="middle" class="<? if ($sel==7) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../explorer/packets/index.php'">Explorer</td>
                  <td width="10" align="center" valign="middle" class="<? if ($sel==8) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../help/help/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="119" align="center" valign="middle" class="<? if ($sel==8) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../help/help/index.php'">Help</td>
                  </tr>
                </tbody>
            </table>
        
        <?
	}
	
	function showGuestTopMenu($sel=1)
	{
		?>
        
            <table width="1000" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="112" height="40" align="center" valign="middle">
                  <a href="../../account/login/index.php" class="btn btn-primary" style="width:100px">Login</a>
                  </td>
                  
                  <td width="110" align="center" valign="middle">
                  <a href="../../account/signup/index.php" class="btn btn-warning" style="width:100px">Signup</a></span></td>
                  <td width="574" align="center" valign="middle" class="<? if ($sel==7) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../explorer/packets/index.php'">&nbsp;</td>
                  <td width="98" align="center" valign="middle" class="<? if ($sel==7) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../explorer/packets/index.php'">Explorer</td>
                  <td width="10" align="center" valign="middle" class="<? if ($sel==8) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../help/help/index.php'"><img src="../../template/template/GIF/top_menu_sep.png" width="10" height="60"/></td>
                  <td width="96" align="center" valign="middle" class="<? if ($sel==8) print "bold_shadow_white_14"; else print "shadow_red_14"; ?>" style="cursor:pointer" onClick="window.location='../../help/help/index.php'">Help</td>
                  </tr>
                </tbody>
            </table>
        
        <?
	}
	
	function showRightPanel()
	{
		if (!isset($_SESSION['userID'])) return false;
		?>
        
           <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><img src="../../template/template/GIF/empty_pic.png" width="170" height="170" alt="" class="img-circle" style="padding:1px; border:3px solid #ffffff; background-color:#ffffff; box-shadow:0px 0px 5px #cccccc"/></td>
                </tr>
                <tr>
                  <td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="76%" height="30" bgcolor="#f7f5e9" class="inset_maro_14" align="center"><? print $_REQUEST['ud']['user']; ?></td>
                        <td width="6%">&nbsp;</td>
                        <td width="18%" align="center"><a href="../../../index.php?act=logout" class="btn btn-danger"><span class="glyphicon glyphicon-off"></span></a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
	
	function showAds()
	{
		$this->showAdsModal();
	
		?>
        
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
           <div class="alert alert-danger alert-dismissible" role="alert">
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
            <td width="147" align="center"><img src="../../template/template/GIF/trash.png" width="116" height="181" /></td>
            <td width="443" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
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
	
	
	
	
	function showRightPic()
	{
		?>
        
        <table width="140" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="79%" height="40" class="inset_gri_14">Welcome <? print $_REQUEST['ud']['user']; ?></td>
            <td width="21%" height="40" align="left"><a href="../../index.php?act=logout" class="btn btn-default btn-sm" style="height:30px"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></td>
          </tr>
        </table>
        
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
                <td align="center"><img src="../../ads/ads/GIF/ads.png" width="200" height="274" /></td>
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
	
	function showCountriesDD()
	{
		$query="SELECT * FROM countries ORDER BY country ASC";
		$result=$this->kern->execute($query);	
		
		print "<select id='dd_country' name='dd_country' class='form-control'>";
		print "<option value=\"XX\">All Countries</option>";
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  print "<option value=\"".$row['code']."\">".$row['country']."</option>";
		print "<select>";
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
            <li <? if ($sel==4) print "class='active'"; ?>><a href="../../assets/assets/index.php">Assets</a></li>     
            <li <? if ($sel==5) print "class='active'"; ?>><a href="../../markets/goods/index.php">Markets</a></li>
            <li <? if ($sel==6) print "class='active'"; ?>><a href="../../explorer/packets/index.php">Explorer</a></li>
            <li <? if ($sel==7) print "class='active'"; ?>><a href="../../help/help/index.php">Help</a></li>
            </ul>
            
            <ul class="nav navbar-nav navbar-right">
            <li><a href="../../../index.php?act=logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
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
             <li class="active"><a href="#">Explorer</a></li>
             <li><a href="./pages/explorer/packets/index.php">Help</a></li>       
             </ul>
             </div>   
             </div>
             </nav>
            
            
            <?
		}
	}
	
	function showBalanceBar()
	{
		if ($_SESSION['userID']>0)
		{
				$this->showSendModal();
		?>
        
            <div class="row">
            <table class="table-responsive" width="100%">
            <tr bgcolor="#cad1d7">
            <td height="65px" width="8%">&nbsp;</td>
            <td height="50px" width="84%" align="right" id="td_balance">
            <table>
            <tr>
            <td>
            <span class="balance_msk" id="balance_msk"><? print $_REQUEST['ud']['balance']; ?> MSK&nbsp;&nbsp;~&nbsp;&nbsp;</span>
            <span class="balance_usd" id="balance_usd"><? print "$".round($_REQUEST['ud']['balance']*$_REQUEST['sd']['msk_price'], 2); ?></span>
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
	
}
?>