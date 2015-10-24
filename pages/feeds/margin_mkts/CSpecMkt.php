<?
class CSpecMkt
{
	function CSpecMkt($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showMarket($symbol)
	{
		$this->showMktPanel($symbol);
		$this->showMarketTabs($symbol);
		$this->template->showMenu("Open Orders", "Closed Orders");
		$this->showTrans($symbol);
		$this->showNewOrderModal($symbol);
	}
	
	function newOrder($net_fee_adr, $adr, $mkt_symbol, $tip, $qty, $leverage)
	{
	    // Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
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
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr, "MSK")<0.0001)
		 {
			 $this->template->showErr("Insufficient funds to execute this operation");
			 return false;
		 }
		 
		 // Order Address 
		 if ($this->template->adrExist($adr)==false)
		 {
			$this->template->showErr("Invalid order address");
			return false;
		 }
		 
		 // Market symbol
		 $mkt_symbol=strtoupper($mkt_symbol);
		 if ($this->kern->symbolValid($mkt_symbol)==false)
		 {
			  $this->template->showErr("Invalid market symbol");
			  return false;
		 }
		 
		 // Market symbol
		 $query="SELECT * 
		           FROM feeds_markets 
				  WHERE mkt_symbol='".$mkt_symbol."' 
				    AND tip='ID_REGULAR'";
		 $result=$this->kern->execute($query);	
		 if (mysql_num_rows($result)==0)
		 {
			  $this->template->showErr("Invalid market symbol");
			  return false;
		 }
		 
		 // Market data
		 $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		  
		 // Buy order
		 if ($tip=="ID_BUY")
		 {
			 // Market has assets
			 if ($this->kern->getBalance($mkt_row['mkt_adr'], $mkt_row['asset'])<$qty)
			 {
				  $this->template->showErr("Insufficient funds to execute transaction");
			      return false;
			 }
			 
			 // User has funds
			 if ($this->kern->getBalance($adr, $mkt_row['cur'])<($qty*$mkt_row['ask']))
			 {
				  $this->template->showErr("Insufficient funds to execute transaction");
			      return false;
			 }
			 
			 // Operation
			 $op="Buys";
		 }
		 else
		 {
			 // Market has currency
			 if ($this->kern->getBalance($mkt_row['mkt_adr'], $mkt_row['cur'])<($qty*$mkt_row['bid']))
			 {
				  $this->template->showErr("Insufficient funds to execute transaction");
			      return false;
			 }
			 
			 // User has funds
			 if ($this->kern->getBalance($adr, $mkt_row['asset'])<$qty)
			 {
				  $this->template->showErr("Insufficient funds to execute transaction");
			      return false;
			 }
			 
			 // Operation
			 $op="Sells";
		 }
		 
		 // Tip
		 if ($tip!="ID_BUY" && $tip!="ID_SELL")
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
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct($op." ".$qty." ".$mkt_row['asset']." on market ".$mkt_symbol);
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_FEED_REG_MKT_ORDER', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='ID_REGULAR',
								par_2='".$mkt_symbol."',
								par_3='".$tip."',
								par_4='".$qty."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->rollback();
		   
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
	
	function showMktPanel($symbol)
	{
		$query="SELECT *  
		          FROM feeds_markets 
				 WHERE mkt_symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
	  
		?>
           
           <br><br>
<table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" align="left" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;&nbsp;<? print base64_decode($row['title']); ?></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">
                  <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="36%" align="left" valign="top"><table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td><img src="../../template/template/GIF/empty_pic_prod.png" width="150" height="150" class="img-circle" /></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="45" align="center">
                              <a href="javascript:null" onClick="javascript:$('#tab_new_buy').css('display', 'block'); $('#tab_new_sell').css('display', 'none'); $('#modal_new_order').modal(); $('#txt_tip').val('ID_BUY');" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Buy</a>
                              </td>
                            </tr>
                            <tr>
                              <td height="45" align="center">
                               <a href="javascript:null" onClick="javascript:$('#tab_new_buy').css('display', 'none'); $('#tab_new_sell').css('display', 'block'); $('#modal_new_order').modal(); $('#txt_tip').val('ID_SELL');" class="btn btn-danger" style="width:100px"><span class="glyphicon glyphicon-minus-sign"></span>&nbsp;&nbsp;Buy</a>
                              </td>
                            </tr>
                            <tr>
                              <td align="center">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><table width="120" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td height="30" align="center" bgcolor="#f4edde" class="simple_maro_12"><? print $row['cur']; ?> Balance</td>
                                  </tr>
                                  <tr>
                                    <td height="50" align="center" bgcolor="#f8f4eb" class="simple_red_20"><? print round($this->kern->getBalance($row['mkt_adr'], $row['cur']), 4); ?></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="center">&nbsp;</td>
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
                                <td width="41%" align="right" class="simple_maro_12">Symbol&nbsp;&nbsp;</td>
                                <td width="59%" height="30"><a href="#" class="red_12"><strong>
								<? 
								   print $row['mkt_symbol']; 
								?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Address&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $this->template->formatAdr($row['mkt_adr']); ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Asset Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['asset']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 1 Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['feed_1']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 1 Branch&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['branch_1']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 2 Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['feed_2']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 2 Branch&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['branch_2']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 3 Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['feed_3']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Feed 3 Branch&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
								<? print $row['branch_3']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Asset Qty&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print $row['asset_qty']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Transaction Fee&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print $row['mkt_fee']; ?>%</strong></a></td>
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
		$query="SELECT * 
		          FROM feeds_markets 
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
                        <td width="105" height="25" align="center" valign="bottom">Ask</td>
                        <td width="39" align="center" valign="bottom">&nbsp;</td>
                        <td width="101" align="center" valign="bottom"> Bid</td>
                        <td width="35" align="center" valign="bottom">&nbsp;</td>
                        <td width="106" align="center" valign="bottom">Max Leverage</td>
                        <td width="30" align="center" valign="bottom">&nbsp;</td>
                        <td width="114" align="center" valign="bottom">Status</td>
                      </tr>
                      <tr class="simple_red_22">
                        <td height="40" align="center" valign="bottom" class="simple_green_18"><span class="simple_red_18"><strong><? print $row['price_ask']; ?></strong></span></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['price_bid']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print "x".$row['max_leverage']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="<? if ($row['closed']=="N") print "simple_red_18"; else print "simple_green_18"; ?>"><strong><? if ($row['closed']=="Y") print "Closed"; else print "Open"; ?></strong></td>
                      </tr>
                      <tr class="simple_blue_10">
                        <td height="0" align="center" valign="bottom"><? print $row['cur']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom"><? print $row['cur']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
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
		$query="SELECT fmt.*, fm.asset 
		          FROM feed_mkts_trans AS fmt 
				  JOIN feeds_markets AS fm ON fm.mkt_symbol=fmt.mkt_symbol
				 WHERE fmt.mkt_symbol='".$symbol."' 
			  ORDER BY fmt.block DESC 
			     LIMIT 0,25"; 
	     $result=$this->kern->execute($query);	
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="44%" align="left" class="inset_maro_14">Trader</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Type</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Time</span></td>
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
                          <td width="9%" align="left" class="simple_maro_12">
                          <img src="../../template/template/GIF/empty_pic_prod.png" width="40" height="40" class="img-circle" /></td>
                          <td width="35%" align="left" class="simple_maro_12">
                          <a href="#" class="maro_12"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a><br>
                          <span class="simple_maro_10">No tag</span></td>
                          <td width="19%" align="center" class="
                          <?
						     switch ($row['type'])
							 {
								case "ID_BUY" : print "simple_green_12"; break; 
								case "ID_SELL" : print "simple_red_12"; break; 
							 }
						  ?>
                          ">
                          <strong>
                          <?
						     switch ($row['type'])
							 {
								case "ID_BUY" : print "Buy"; break; 
								case "ID_SELL" : print "SELL"; break; 
							 }
						  ?>
                          </strong><br><span class="simple_maro_10">at 2.1232 MSK</span>
                          </td>
                          <td width="20%" align="center" class="simple_green_12"><strong><? print $row['qty']; ?></strong><br>
                          <span class="simple_maro_10"><? print $row['asset']; ?></span></td>
                          <td width="17%" align="center" class="simple_maro_12">5 minutes</td>
                          </tr>
                          <tr>
                          <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                          </tr>
                    
                      <?
	                      }
					  ?>
                      
                  
                  
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
            <br><br>
        
        <?
	}
	
	function showNewOrderModal($mkt_symbol)
	{
		$query="SELECT * 
		          FROM feeds_markets 
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
		$asset_balance=$this->kern->getBalance($adr, $row['asset']);
		$cur_balance=$this->kern->getBalance($adr, $row['cur']);
		
		// Header
		$this->template->showModalHeader("modal_new_order", "New Order", "act", "new_order", "mkt_symbol", $mkt_symbol);
		?>
            
            <input type="hidden" id="txt_tip" name="txt_tip" value="ID_BUY"> 
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../reg_mkts/GIF/adr_opt_check_sig.png" width="178" height="162" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                
                <div id="tab_new_buy" name="tab_new_buy" style="display:block">
                <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#dbf9db" class="simple_green_12">Order Type</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#eefdee" class="inset_green_24"><strong>BUY</strong></td>
                  </tr>
                </table>
                </div>
                
                 <div id="tab_new_sell" name="tab_new_sell" style="display:block">
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
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Payment Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="10%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Qty</strong></td>
                  </tr>
                  <tr>
                    <td align="left">
                    <input class="form-control" id="txt_qty" name="txt_qty" placeholder="0" style="width:100px"/>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="40" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="40" align="left"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="190" align="left"><table width="165" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned <? print $row['asset']; ?></td>
                      </tr>
                      <tr>
                        <td height="50" align="center" bgcolor="#fafafa" class="simple_blue_18" id="td_ab" name="td_ab"><? print $asset_balance; ?></td>
                      </tr>
                    </table></td>
                    <td width="185" align="right"><table width="165" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned <? print $row['cur']; ?></td>
                      </tr>
                      <tr>
                        <td height="50" align="center" bgcolor="#fafafa" class="simple_blue_18" id="td_cb" name="td_cb"><? print $cur_balance; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              </table></td>
          </tr>
         </table>
         
		 <script>
		   $('#dd_order_adr').change(
		   function() 
		   {
			   $('#td_ab').load('get_page.php?act=get_balance&adr='+$('#dd_order_adr').val()+'&asset='+'<? print $row['asset_symbol']; ?>');
			   $('#td_cb').load('get_page.php?act=get_balance&adr='+$('#dd_order_adr').val()+'&asset='+'<? print $row['cur_symbol']; ?>');
		   });
		 </script>  
        
        <?
		$this->template->showModalFooter("Close", "Buy");
	}
}
?>