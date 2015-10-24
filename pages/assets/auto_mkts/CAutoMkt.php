<?
class CAutoMkt
{
	function CAutoMkt($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showMarketPage($symbol)
	{
		// Market panel
		$this->showMarketPanel($symbol);
		
		// Market tabs
		$this->showMarketTabs($symbol);
		
		// Transactions
		$this->showTrans($symbol);
		
	}
	
	function newOrder($net_fee_adr, $order_adr, $mkt_symbol, $tip, $qty)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($order_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Net Fee Address 
		 if ($this->template->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $net_fee=0.0001;
		 
		 // Funds
		 if ($this->template->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		// Order address
		if ($this->template->adrExist($order_adr)==false)
		{
			$this->template->showErr("Invalid order address");
			return false;
		}
		
		// Tip
		if ($tip!="ID_BUY" && 
		    $tip!="ID_SELL")
		{
			$this->template->showErr("Invalid order type");
			return false;
		}
		
		// Qty
		if ($qty<0.0001)
		{
			$this->template->showErr("Minimum qty is 0.0001");
			return false;
		}
		
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."' 
				   AND tip='ID_AUTO'"; 
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Asset
		$asset=$mkt_row['asset_symbol']; 
		
		// Currency
		$cur=$mkt_row['cur_symbol'];
		
		// Qty
		if ($tip=="ID_BUY")
		{
			  // Calculate amount to pay
              $price=$mkt_row['tmp_price'];
              
              // Price difference
              $dif=$qty*$mkt_row['volatility'];
              
               // New price
              $end_price=$price+$dif;
              
              // To pay
              $to_pay=((($dif*$qty)+$dif)*$qty)/2+($price*$qty);
			  
			  // Balance
			  if ($this->template->getBalance($order_adr, $cur)<$to_pay)
	          {
		         $this->template->showErr("Insufficient funds to execute the transaction");
		         return false;
	          }
		}
		else
		{
			 // Balance
			  if ($this->kern->getBalance($order_adr, $asset)<$qty)
	          {
		         $this->template->showErr("Insufficient funds to execute the transaction");
		         return false;
	          }
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();
		   
		   // Track ID
		   $tID=$this->kern->getTrackID();

           // Action
           $this->kern->newAct("Post a new order on market ".$mkt_symbol, $tID);
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_AUTO_ASSET_MKT_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$order_adr."',
								par_1='".$mkt_symbol."',
								par_2='".$tip."',
								par_3='".$qty."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
           
		   // Ok
		   $this->template->showOk("Your request has been successfully executed");
		   
		   return true;
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
	
	function showMarketPanel($symbol)
	{
		// New market position
		$this->showNewPosMarketModal($symbol);
	    
		// Load market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$symbol."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
           
           <br><br>
           <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" align="left" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;&nbsp;<? print base64_decode($row['title'])." (".$row['mkt_symbol'].")"; ?></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="36%" align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td><img src="../../template/template/GIF/empty_pic_prod.png" width="150" height="150" class="img-circle" /></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="45" align="center">
                              <a href="#" onclick="$('#modal_new_pos').modal(); 
                                                   $('#tab_buy').css('display', 'block'); 
                                                   $('#tip').val('ID_BUY');
                                                   $('#mkt_symbol').val('<? print $_REQUEST['symbol']; ?>'); 
                                                   $('#tab_sell').css('display', 'none');" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Buy Order</a>
                              </td>
                            </tr>
                            <tr>
                              <td height="45" align="center">
                              <a href="#" onclick="$('#modal_new_pos').modal();
                                                   $('#tab_buy').css('display', 'none'); 
                                                   $('#tip').val('ID_SELL');
                                                    $('#mkt_symbol').val('<? print $_REQUEST['symbol']; ?>'); 
                                                   $('#tab_sell').css('display', 'block');" class="btn btn-danger"><span class="glyphicon glyphicon-minus-sign"></span>&nbsp;&nbsp;Sell Order</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="64%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td class="simple_maro_12"><? print base64_decode($row['description']); ?></td>
                            </tr>
                            <tr>
                              <td background="../../template/template/GIF/lc.png">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td align="right" class="simple_maro_12">Symbol&nbsp;&nbsp;</td>
                                <td height="30"><strong><span class="simple_red_12"><? print $row['mkt_symbol']; ?></span></strong></td>
                              </tr>
                              <tr>
                                <td width="37%" align="right" class="simple_maro_12">Owner&nbsp;&nbsp;</td>
                                <td width="63%" height="30"><a href="#" class="red_12"><strong><? print $this->template->formatAdr($row['mkt_adr']); ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Fee Address&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print $this->template->formatAdr($row['mkt_fee_adr']); ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Initial Price&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><strong><? print $row['initial_price']; ?></strong></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Asset&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><a href="#" class="red_12"><strong><? print $row['asset_symbol']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Currency&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><a href="#" class="red_12"><strong><? print $row['cur_symbol']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Expire&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><strong>3 months</strong></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12"><? print $row['asset_symbol']; ?> Balance&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><strong><? print $this->kern->getBalance($row['mkt_adr'], $row['asset_symbol'])." ".$row['asset_symbol']; ?></strong></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12"><? print $row['cur_symbol']; ?> Balance&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><strong><? print $this->kern->getBalance($row['mkt_adr'], $row['cur_symbol'])." ".$row['cur_symbol']; ?></strong></td>
                              </tr>
                              </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	
	function showMarketTabs($symbol)
	{
		// Load market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$symbol."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
        
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="95" align="center" valign="top" background="../../template/template/GIF/4_panels.png"><table width="530" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr class="bold_shadow_white_12">
                        <td width="105" height="25" align="center" valign="bottom">You Own</td>
                        <td width="39" align="center" valign="bottom">&nbsp;</td>
                        <td width="101" align="center" valign="bottom">Price</td>
                        <td width="35" align="center" valign="bottom">&nbsp;</td>
                        <td width="106" align="center" valign="bottom">Volatility</td>
                        <td width="30" align="center" valign="bottom">&nbsp;</td>
                        <td width="114" align="center" valign="bottom">Transaction Fee</td>
                      </tr>
                      <tr class="simple_red_22">
                        <td height="40" align="center" valign="bottom" class="simple_green_18"><strong>
						<? print $this->kern->getMyBalance($row['asset_symbol']); ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['tmp_price']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['volatility']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_green_18"><span class="simple_red_18"><strong><? print $row['mkt_fee']; ?>%</strong></span></td>
                      </tr>
                      <tr class="simple_blue_10">
                        <td height="0" align="center" valign="bottom" class="simple_gri_10"><? print $row['asset_symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_gri_10"><? print $row['cur_symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_gri_10"><? print $row['cur_symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_gri_10">per transaction</td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	
	function showTrans($symbol)
	{
		$query="SELECT * 
		          FROM assets_auto_mkts_trans 
				 WHERE mkt_symbol='".$symbol."' 
			  ORDER BY block DESC 
			     LIMIT 0,10";
		$result=$this->kern->execute($query);	
	    
	  
		?>
           
           <div id="div_trans" name="div_trans" style="display:block">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="28%" align="left" class="inset_maro_14">Trader</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center" class="inset_maro_14">Order</td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Qty</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Price</span></td>
                        <td width="0%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Date</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                          <tr>
                          <td width="28%" align="left" class="simple_green_12"><a href="#" class="maro_12"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a><br>
                          <span class="<? if ($row['status']=="ID_CLEARED") print "simple_green_10"; else print "simple_red_10"; ?>"><? if ($row['status']=="ID_CLEARED") print "cleared"; else print "pending"; ?></span></td>
                          <td width="23%" align="center" class="<? if ($row['order_type']=="ID_BUY") print "simple_green_12"; else print "simple_red_12"; ?>"><strong><? if ($row['order_type']=="ID_BUY") print "Buy"; else print "Sell"; ?></strong></td>
                          <td width="17%" align="center" class="simple_green_12"><span class="simple_maro_12"><strong><? print $row['qty']; ?></strong><br>
                          <span class="simple_maro_10">RONRON</span></span></td>
                          <td width="17%" align="center" class="simple_maro_12"><span class="simple_green_12"><strong><? print $row['new_price']; ?></strong><br>
                          <span class="simple_green_10">MSK</span></span></td>
                          <td width="15%" align="center" class="simple_maro_12">5 days</td>
                          </tr>
                          <tr>
                          <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                          </tr>
                      
                      <?
	                     } 
					  ?>
                  
                  </table>
                  
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
            <br><br><br>
            </div>
        
        <?
	}
    
	function showNewPosMarketModal($mkt_symbol)
	{
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			 $this->template->showErr("Invalid market symbol");
			 return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC); 
		
		// Finds address balances
		$adr=$this->kern->getMyFirstAdr();
		$asset_balance=$this->kern->getBalance($adr, $row['asset_symbol']);
		$cur_balance=$this->kern->getBalance($adr, $row['cur_symbol']);
	  
		// Header
		$this->template->showModalHeader("modal_new_pos", "New Trade Position", "act", "new_pos", "tip", "");
		?>
            
            <input type="hidden" id="mkt_symbol" name="mkt_symbol" value="<? print $mkt_symbol; ?>" />
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_check_sig.png" width="178" height="162" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                
                <div id="tab_buy" name="tab_buy" style="display:block">
                <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#dbf9db" class="simple_green_12">Order Type</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#eefdee" class="inset_green_24"><strong>BUY</strong></td>
                  </tr>
                </table>
                </div>
                
                 <div id="tab_sell" name="tab_sell" style="display:block">
                 <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#f9dbdb" class="inset_red_12">Order Type</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#faecec" class="inset_red_24"><strong>SELL</strong></td>
                  </tr>
                </table>
                </div>
                
                </td>
              </tr>
              <tr>
                <td height="30" align="center">&nbsp;</td>
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
            </table></td>
            <td width="438" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Order Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                
                <table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Qty</strong></td>
                  </tr>
                  <tr>
                    <td align="left"><input class="form-control" id="txt_qty" name="txt_qty" placeholder="0" style="width:100px"/></td>
                  </tr>
                </table>
                
                </td>
              </tr>
              <tr>
                <td height="40" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">
                
                <table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="190" align="left"><table width="165" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned <? print $row['asset_symbol']; ?></td>
                      </tr>
                      <tr>
                        <td height="50" align="center" bgcolor="#fafafa" class="simple_blue_18" id="td_pos_ab" name="td_pos_ab">
						<? 
						   print $asset_balance;
						?>
                        </td>
                      </tr>
                    </table></td>
                    <td width="185" align="right"><table width="165" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned <? print $row['cur_symbol']; ?></td>
                      </tr>
                      <tr>
                        <td height="50" align="center" bgcolor="#fafafa" class="simple_blue_18" id="td_pos_cb" name="td_pos_cb">
                        <? 
						   print $cur_balance;
						?>
                        </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table></td>
          </tr>
         </table>
         
          <script>
		   $('#dd_adr').change(
		   function() 
		   {
			  //alert('get_page.php?act=get_balance&adr='+encodeURIComponent($('#dd_adr')).val()+'&asset='+'<? print $row['cur_symbol']; ?>');
			  $('#td_pos_ab').load('get_page.php?act=get_balance&adr='+encodeURIComponent($('#dd_adr').val())+'&asset='+'<? print $row['asset_symbol']; ?>');
			  $('#td_pos_cb').load('get_page.php?act=get_balance&adr='+encodeURIComponent($('#dd_adr').val())+'&asset='+'<? print $row['cur_symbol']; ?>');
		   });
		 </script>  
         
		   
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
		
} 
?>