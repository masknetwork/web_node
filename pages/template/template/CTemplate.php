<?
class CTemplate
{
	function CTemplate($db)
	{
	   $this->kern=$db;	
	}
	
	function showBubble($no, $col="ID_ROSU")
	{
		switch ($col)
		{
			case "ID_ROSU" : $img="bula_rosie.png"; break;
			case "ID_ALBASTRU" : $img="bula_albastru.png"; break;
			case "ID_MOV_DESCHIS" : $img="bula_mov.png"; break;
			case "ID_MOV_INCHIS" : $img="bula_mov_inchis.png"; break;
			case "ID_PORTO" : $img="bula_porto.png"; break;
			case "ID_VERDE" : $img="bula_verde.png"; break;
		}
		
		?>
        
            <table width="42" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="32" align="center" background="../../template/template/GIF/<? print $img; ?>" class="bold_shadow_white_12">
                  <? print $no; ?>
                  </td>
                </tr>
              </tbody>
            </table>
        
        <?
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
	
	function showModalFooter($send_but=true, $send_but_txt="Send")
	{
		?>
        
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <? if ($send_but==true) print "<button id=\"but_activate\" name=\"but_activate\" type=\"submit\" class=\"btn btn-primary btn-success\">".$send_but_txt."</button>"; ?>
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
               <td height="25" align="left" valign="top" class="simple_red_14"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" class="simple_red_14">
               <?
			      $this->showMyAdrDD("dd_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" class="simple_red_14">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" class="simple_red_14"><strong>From Address</strong></td>
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
               <td height="25" align="left" valign="top" class="simple_red_14"><strong>To Address</strong></td>
             </tr>
             <tr>
               <td align="left">
               <input type="text" class="form-control" style="width:300px" id="txt_to" name="txt_to" placeholder="Address" onfocus="this.placeholder=''" />
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" class="simple_red_14"><strong>Amount</strong></td>
             </tr>
             <tr>
               <td height="50" align="left">
               
               <table width="300px" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td ><div class="input-group">
                     <div class="input-group-addon">MSK</div>
                     <input type="number" class="form-control" id="txt_msk" name="txt_msk"  style="width:80px" placeholder="0" onKeyUp="var  usd=$(this).val()*<? print $_REQUEST['sd']['msk_price']; ?>; var fee=$(this).val()/10000; if (fee<0.0001) fee=0.0001; fee=Math.round(fee*10000)/10000; usd=Math.round(usd*100)/100; $('#trans_net_fee_panel_val').text(fee); $('#txt_usd').val(usd)"/>
                     </div>
                   </td>
                   <td width="10px">&nbsp;</td>
                   <td><div class="input-group">
                     <div class="input-group-addon">USD</div>
                     <input type="number" class="form-control" id="txt_usd" name="txt_usd"  style="width:80px" placeholder="0" onKeyUp="var  msk=$('#txt_usd').val()/<? print $_REQUEST['sd']['msk_price']; ?>; var fee=msk/10000; if (fee<0.0001) fee=0.0001; fee=Math.round(fee*10000)/10000; $('#trans_net_fee_panel_val').text(fee); $('#txt_msk').val(msk);"/>
                   </div></td>
                  
                 </tr>
               </table>
               
               </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" class="simple_red_14"><strong>Message</strong></td>
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
               <td height="25" align="left" valign="top" class="simple_red_14"><strong>Escrower</strong> (optional) </td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:300px" id="exampleInputAmount4" placeholder="Escrower Address (optional)" onfocus="this.placeholder=''" name="txt_escrower" /></td>
             </tr>
           </table></td>
         </tr>
     </table>
     
    
       
        <?
		$this->showModalFooter();
		
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
        
            <table width="570" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="113">&nbsp;</td>
                  <td width="457">&nbsp;</td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/help.png" width="100" height="71" alt=""/></td>
                  <td valign="top" class="simple_maro_12"><? print $txt; ?></td>
                </tr>
                <tr>
                  <td colspan="2" background="../../template/template/GIF/lp.png">&nbsp;</td>
                  </tr>
              </tbody>
            </table>
            <br><br>
        
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
                  <a href="../../account/login/index.php" class="btn btn-success" style="width:100px">Login</a>
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
        
<br><br>
              <table width="180" border="0" cellspacing="0" cellpadding="0">
               
                  <tr>
                    <td align="left" class="inset_maro_16"><strong>Advertising</strong></td>
                  </tr>
                  <tr>
                    <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
                  </tr>
                    
                    <?
					  $query="SELECT * FROM ads ORDER BY mkt_bid DESC LIMIT 0,10";
					  $result=$this->kern->execute($query);	
	                  
					  while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					  {
                    ?>
                    
                         <tr>
                         <td align="left">
                         <a href="#" class="maro_14"><strong><? print base64_decode($row['title']); ?></strong></a>
                         <br><span class="inset_maro_12"><? print base64_decode($row['message']); ?></span> 
                         </td></tr><tr>
                         <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
                    <td><a href="javascript:void(0)" class="btn btn-warning" onClick="$('#modal_ads').modal()">Advertise Here</a></td>
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
        
        <?
	}
	
	function showBottomMenu()
	{
		?>
        
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td><a href="../../transactions/all/index.php?act=send_block" class="maro_12">Send Block</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
        
        <?
	}
	
	function showOk($mes, $width=600)
	{
		?>
            
            <br />
           <table width="<? print $width."px"; ?>"  id="tab_alert">
           <tr><td>
           <div class="alert alert-success alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <? print $mes; ?>
           </div>
           </td></tr>
           </table>
           
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
            
            <br />
           <table width="<? print $width."px"; ?>"  id="tab_alert">
           <tr><td>
           <div class="alert alert-danger alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <? print $mes; ?>
           </div>
           </td></tr>
           </table>
           
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
             <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
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
             <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
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
             <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Yes</button>
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
              <tr>
                <td align="center"><? $this->showNetFeePanel($init_panel_val); ?></td>
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
                <td align="left"><input class="form-control" id="txt_new_bid" name="txt_new_bid" placeholder="Bid" style="width:100px" value="<? print $init_val; ?>"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->showModalFooter();
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
                    <input id="txt_ads_bid" name="txt_ads_bid" class="form-control" style="width:100px" value="0.0001" type="number" min="1" step="1" /></td>
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
		   $('#txt_title').val(btoa($('#txt_title').val())); 
		   $('#txt_ads_mes').val(btoa($('#txt_ads_mes').val())); 
		   $('#txt_link').val(btoa($('#txt_link').val())); 
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
		$this->showModalFooter();
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
		$query="SELECT * FROM domains WHERE adr='".$adr."'";
		$result=$this->kern->execute($query);	
		
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
	
	function showMenu($panel_1, $panel_2="", $panel_3="", $panel_4="", $panel_5="")
	{
		?>
           
          
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="10" align="center" >&nbsp;</td>
                 
                  <td width="103" height="40" align="center" style="cursor:pointer" background="../../template/template/GIF/menu_tab_on.png" class="bold_shadow_white_14" id="tab_1" name="tab_1" onclick="menu_clear(1); $(this).attr('background', '../../template/template/GIF/menu_tab_on.png'); $(this).attr('class', 'bold_shadow_white_14');"><? print $panel_1; ?></td>
                 
                  
                  <td width="103" align="center" style="cursor:pointer" id="tab_2" name="tab_2" background="../../template/template/GIF/menu_tab_off.png" class="inset_maro_12" onclick="menu_clear(2); $(this).attr('background', '../../template/template/GIF/menu_tab_on.png'); $(this).attr('class', 'bold_shadow_white_14');"><? print $panel_2; ?></td>
                  
                
                  
                  <td width="103" align="center" style="cursor:pointer" id="tab_3" name="tab_3" onclick="menu_clear(3); $(this).attr('background', '../../template/template/GIF/menu_tab_on.png'); $(this).attr('class', 'bold_shadow_white_14'); " background="<? if ($panel_3!="") print "../../template/template/GIF/menu_tab_off.png"; ?>" class="inset_maro_12"><? if ($panel_3!="") print $panel_3; ?></td>
                  
             
                  
                 <td width="103" align="center" style="cursor:pointer" id="tab_4" name="tab_4" onclick="menu_clear(4); $(this).attr('background', '../../template/template/GIF/menu_tab_on.png'); $(this).attr('class', 'bold_shadow_white_14'); " background="<? if ($panel_4!="") print "../../template/template/GIF/menu_tab_off.png"; ?>" class="inset_maro_12"><? if ($panel_4!="") print $panel_4; ?></td>
                  
                
                  
                <td width="103" align="center" style="cursor:pointer" id="tab_5" name="tab_5" onclick="menu_clear(5); $(this).attr('background', '../../template/template/GIF/menu_tab_on.png'); $(this).attr('class', 'bold_shadow_white_14'); " background="<? if ($panel_5!="") print "../../template/template/GIF/menu_tab_off.png"; ?>" class="inset_maro_12"><? if ($panel_5!="") print $panel_5; ?></td>
                  
                  <td width="10" align="center">&nbsp;</td>
                
                </tr>
                <tr>
                  <td colspan="10" background="../../template/template/GIF/menu_tab_bar.png" >&nbsp;</td>
                  </tr>
              </tbody>
            </table>
            
            <script>
			  function menu_clear(tab)
			  {
				  $('#tab_1').attr('class', 'inset_maro_12');
				  $('#tab_2').attr('class', 'inset_maro_12');
				  $('#tab_3').attr('class', 'inset_maro_12');
				  $('#tab_4').attr('class', 'inset_maro_12');
				  $('#tab_5').attr('class', 'inset_maro_12');
				  
				  $('#tab_1').attr('background', '../../template/template/GIF/menu_tab_off.png');
				  $('#tab_2').attr('background', '../../template/template/GIF/menu_tab_off.png');
				  <? if ($panel_3!="") print "$('#tab_3').attr('background', '../../template/template/GIF/menu_tab_off.png');"; ?>
				  <? if ($panel_4!="") print "$('#tab_4').attr('background', '../../template/template/GIF/menu_tab_off.png');"; ?>
				  <? if ($panel_5!="") print "$('#tab_5').attr('background', '../../template/template/GIF/menu_tab_off.png');"; ?>
				  
				  menuClicked(tab);
			  }
			</script>
        
        <?
	}
	
	function showArrow()
	{
		?>
           <br>
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="center"><img src="../../template/template/GIF/arrow.png" width="17" height="9" alt=""/></td>
                </tr>
              </tbody>
</table>
            <br>
        
        <?
	}
	
}
?>