<?
class CRegMkt
{
	function CRegMkt($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function delPos($net_fee_adr, $uid)
	{
		// Position ID
		if (strlen($uid)!=10)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Position exist ?
		$query="SELECT * 
		          FROM assets_markets_pos AS amp 
				  JOIN assets_markets AS am ON am.mkt_symbol=amp.mkt_symbol 
				 WHERE amp.uid='".$uid."'
				   AND am.tip='ID_REGULAR'"; 
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Order data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Rights
		if ($this->kern->isMine($row['adr'])==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Delete a market position ".$uid);
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_CLOSE_REGULAR_MKT_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$row['adr']."',
								par_1='".$row['uid']."',
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
	
	function newMarketOrder($net_fee_adr, $order_adr, $uid, $qty)
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
		
		// Order ID
		if (strlen($uid)!=10)
		{
			$this->template->showErr("Invalid order address");
			return false;
		}
		
		// Position exist ?
		$query="SELECT asp.* 
		          FROM assets_markets_pos AS asp 
				  JOIN assets_markets AS am ON am.mkt_symbol=asp.mkt_symbol 
				 WHERE asp.uid='".$uid."' 
				   AND am.tip='ID_REGULAR'";
		$result=$this->kern->execute($query);
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Position data
		$pos_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$pos_row['mkt_symbol']."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Asset
		$asset=$mkt_row['asset_symbol']; 
		
		// Currency
		$cur=$mkt_row['cur_symbol'];
		
		// Qty
		if ($pos_row['tip']=="ID_BUY")
		{
			// Check asset balance
			$asset_qty=$this->kern->getBalance($order_adr, $asset);
			
			// Enough balance
			if ($asset_qty<$qty)
			{
				$this->template->showErr("Insufficient assets to execute this transaction");
			    return false;
			}
			
			// On sale
			if ($pos_row['qty']<$qty)
			{
				$this->template->showErr("You can sell maximum ".$pos_row['qty']." assets");
			    return false;
			}
			
			// Operation
			$op="Buys ";
		}
		else
		{
			// Check asset balance
			$cur_qty=$this->kern->getBalance($order_adr, $cur);
			
			// Enough balance
			if ($cur_qty<($qty*$pos_row['price']))
			{
				$this->template->showErr("Insufficient assets to execute this transaction");
			    return false;
			}
			
			// On sale
			if ($pos_row['qty']<$qty)
			{
				$this->template->showErr("You can buy maximum ".$pos_row['qty']." assets");
			    return false;
			}
			
			// Operation
			$op="Sells ";
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct($op." ".$qty." ".$asset." on market ".$pos_row['mkt_symbol']);
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_REGULAR_MKT_ORDER', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$order_adr."',
								par_1='".$pos_row['uid']."',
								par_2='".$qty."',
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
	
	function newMarketPos($mkt_symbol,
	                      $tip,
	                      $net_fee_adr, 
	                      $order_adr, 
					      $price, 
					      $qty, 
					      $days)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($order_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Market symbol
		$symbol=strtoupper($mkt_symbol);
		if ($this->kern->symbolValid($mkt_symbol)==false)
		{
			 $this->template->showErr("Invalid market symbol");
			 return false;
		}
		 
		// Market exist ?
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$symbol."'";
		$result=$this->kern->execute($query);	
	    if (mysql_num_rows($result)==0)
		{
		   $this->template->showErr("Market doesn't exist");
		   return false;
		}
		
		// Market data
		$mkt_row=mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Currency
		$cur=$mkt_row['cur_symbol'];
		
		// Asset
		$asset=$mkt_row['asset_symbol'];
		
		// Tip
		if ($tip!="ID_BUY" && $tip!="ID_SELL")
		{
		    $this->template->showErr("Market doesn't exist");
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
		
		// Price
		if ($price<0.00000001)
		{
			$this->template->showErr("Invalid price");
			return false;
		}
		
		// Qty
		if ($qty<0.00000001)
		{
			$this->template->showErr("Invalid qty");
			return false;
		}
		
		// Buy order
		if ($tip=="ID_BUY")
		{
			// Amount
			$amount=$price*$qty;
			
			// Enough currency ?
			if ($this->kern->getBalance($order_adr, $cur)<$amount)
			{
				$this->template->showErr("Insufficient funds or assets to execute this transaction");
			    return false;
			}
			
			// Lower than the lower sell ?
			$query="SELECT * 
			          FROM assets_markets_pos 
					 WHERE mkt_symbol='".$mkt_symbol."' 
					   AND tip='ID_SELL' 
				  ORDER BY price ASC";
			$result=$this->kern->execute($query);	
			
			if (mysql_num_rows($result)>0) 
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				if ($price>$row['price'])
				{
					$this->template->showErr("Maximum price allowed is ".$row['price']);
			        return false;
				}
			}
		}
		else
		{
			// Enough assets ?
			if ($this->kern->getBalance($order_adr, $asset)<$qty)
			{
				$this->template->showErr("Insufficient funds or assets to execute this transaction");
			    return false;
			}
			
			// Higher than the higher buy ?
			$query="SELECT * 
			          FROM assets_markets_pos 
					 WHERE mkt_symbol='".$mkt_symbol."' 
					   AND tip='ID_BUY' 
				  ORDER BY price DESC";
			$result=$this->kern->execute($query);	
			
			if (mysql_num_rows($result)>0) 
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				if ($price<$row['price'])
				{
					$this->template->showErr("Maximum price allowed is ".$row['price']);
			        return false;
				}
			}
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("You can post an order for minimum 1 day");
			return false;
		}
		
		try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Launch a new regular asset market");
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_REGULAR_MKT_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$order_adr."',
								par_1='".$price."',
								par_2='".$qty."',
								par_3='".$tip."',
								par_4='".$mkt_symbol."',
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
	
	function showMarketPage($symbol)
	{
		// Market panel
		$this->showMarketPanel($symbol);
		
		// Market tabs
		$this->showMarketTabs($symbol);
		
		// Menu
		$this->template->showMenu("Sellers", "Buyers", "My Orders", "Transactions", "");
		
		// Sellers tab
		$this->showTraders($symbol, "ID_SELL");
		
		// Buyers tab
		$this->showTraders($symbol, "ID_BUY", false);
		
		// My Orders
		$this->showMyOrders($symbol);
		
		// Transactions
		$this->showTrans($symbol);
		
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
                                                   $('#tab_sell').css('display', 'none');" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Buy Order</a>
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
                                <td align="right" class="simple_maro_12">Transaction Fee&nbsp;&nbsp;</td>
                                <td height="30" class="simple_red_12"><a href="#" class="red_12"><strong><? print $row['mkt_fee']; ?>%</strong></a></td>
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
                        <td width="101" align="center" valign="bottom">Ask</td>
                        <td width="35" align="center" valign="bottom">&nbsp;</td>
                        <td width="106" align="center" valign="bottom">Bid</td>
                        <td width="30" align="center" valign="bottom">&nbsp;</td>
                        <td width="114" align="center" valign="bottom">Last Price</td>
                      </tr>
                      <tr class="simple_red_22">
                        <td height="40" align="center" valign="bottom" class="simple_green_18"><strong>
						<? print $this->kern->getMyBalance($row['asset_symbol']); ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['ask']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['bid']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_green_18"><span class="simple_red_18"><strong>
						<? print $row['price']; ?></strong></span></td>
                      </tr>
                      <tr class="simple_blue_10">
                        <td height="0" align="center" valign="bottom"><? print $row['asset_symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">units</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">months</td>
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
	
	function showTraders($mkt_symbol, $tip, $visible=true)
	{
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		// Load data
		if ($tip=="ID_BUY")
		    $query="SELECT * 
		              FROM assets_markets_pos 
				     WHERE mkt_symbol='".$mkt_symbol."' 
				       AND tip='ID_BUY' 
			      ORDER BY price DESC";
		else
		    $query="SELECT * 
		              FROM assets_markets_pos 
				     WHERE mkt_symbol='".$mkt_symbol."' 
				       AND tip='ID_SELL' 
			      ORDER BY price ASC";
				  
		$result=$this->kern->execute($query);	
	  
	    $this->template->showQRModal();
		?>
           
           <div id="div_traders_<? print $tip; ?>" name="div_sellers" style="display:<? if ($visible==true) print "block"; else print "none"; ?>">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="44%" align="left" class="inset_maro_14">Seller</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center" class="inset_maro_14">Amount</td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Price</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="18%" align="center"><span class="inset_maro_14">Buy</span></td>
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
                             <td width="10%" align="left"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle" /></td>
                           <td width="34%" align="left">
                           <a href="#" class="maro_12"><strong>
						   <? print $this->template->formatAdr($row['adr']); ?></strong>
                           </a><br><span class="simple_maro_10"><? print  "OrderID : ".$row['uid']; ?></span></td>
                           <td width="21%" align="center" class="simple_maro_12"><strong><? print $row['qty']; ?></strong><br><span class="simple_maro_10"><? print $mkt_row['asset_symbol']; ?></span></td>
                           <td width="18%" align="center" class="simple_green_12"><strong><? print $row['price']; ?></strong><br><span class="simple_green_10"><? print $mkt_row['cur_symbol']; ?></span></td>
                           <td width="17%" align="right" class="simple_maro_12">
                        
                        
                           <a style="width:80px" class="btn btn-<? if ($tip=="ID_BUY") print "danger"; else print "success"; ?>" href="#" onClick="javascript:$('#uid').val('<? print $row['uid']; ?>'); 
                                <? 
								   if ($tip=="ID_BUY") 
								   {
								      print "$('#tab_new_buy').css('display', 'none');";
									  print "$('#tab_new_sell').css('display', 'block');";
								   }
								   else
								   {
								      print "$('#tab_new_buy').css('display', 'block');";
									  print "$('#tab_new_sell').css('display', 'none');";
								   }
							    ?>
                                $('#modal_new_order').modal();" ><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;
                                
								<?
			                        if ($tip=="ID_BUY")
			                           print "Sell";
				                    else
				                       print "Buy"; 
			                     ?>
                             </a>
            
                             </td>
                      </tr>
                             <tr>
                             <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                             </tr>
                        
                          <?
	                         }
				          ?>
                    <tr>
                      <td>                      
                    
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
            
               
                
            </table>
            
            <br><br><br>
            </div>
        
        <?
	}
	
	function showBuyers()
	{
		?>
           
           <div id="div_buyers" name="div_buyers" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="30%" align="left" class="inset_maro_14">Seller</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center" class="inset_maro_14">Amount</td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Price</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Qty</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Sell</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png"><table width="92%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="30%" align="left"><a href="#" class="maro_12"><strong>fdvfdvdfvfd</strong></a><br><span class="simple_maro_10">Owns 3233.90 RONRON</span></td>
                        <td width="20%" align="center" class="simple_maro_12"><strong>43.0090</strong><br><span class="simple_maro_10">RONRON</span></td>
                        <td width="17%" align="center" class="simple_green_12"><strong>0.00000000</strong><br><span class="simple_green_10">MSK</span></td>
                        <td width="17%" align="center" class="simple_maro_12"><input href="" class="form-control" placeholder="0.0000" style="width:80px"></td>
                        <td width="16%" align="right" class="simple_maro_12"><a href="#" class="btn btn-danger"><span class="glyphicon glyphicon-minus-sign"></span>&nbsp;&nbsp;Sell</a></td>
                      </tr>
                      <tr>
                        <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                    </tbody>
                  </table></td>
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
	
	function showMyOrders($mkt_symbol)
	{
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// My addresses
		$query="SELECT adr 
		          FROM my_adr 
				 WHERE userID='".$_REQUEST['ud']['ID']."'";
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		   $adr=$adr."'".$row['adr']."', ";
		
		// Eliminate last comma
		$adr=substr($adr, 0, strlen($adr)-2);
		
		// Load data
		$query="SELECT * 
		          FROM assets_markets_pos 
				 WHERE mkt_symbol='".$mkt_symbol."' 
				   AND adr IN (".$adr.")"; 
		$result=$this->kern->execute($query);	
	    
		// Confirm modal
		$this->template->showDelModal();
		
		// QR code
		$this->template->showQRModal();
		
		?>
           
           <div id="div_my_orders" name="div_my_orders" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="38%" align="left" class="inset_maro_14">Order</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="21%" align="center" class="inset_maro_14">Amount</td>
                        <td width="0%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Price</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Delete</span></td>
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
                        <td width="39%" align="left" class="
                        <? 
			               if ($row['tip']=="ID_BUY") 
				              print "simple_green_14"; 
				           else 
				               print "simple_red_14"; 
			            ?>">
			            
            
                        <strong>
						<? 
			               if ($row['tip']=="ID_BUY") 
				             print "<strong>Buy</strong> ".$mkt_symbol; 
		             	   else 
				             print "<strong>Sell</strong> ".$mkt_symbol; 
			            ?>
                        
                        </strong><br>
                          <span class="simple_maro_10"><? print "Order ID : ".$row['uid']; ?></span></td>
                        <td width="22%" align="center" class="simple_maro_12"><strong><? print $row['qty']; ?></strong><br><span class="simple_maro_10"><? print $mkt_row['asset_symbol']; ?></span></td>
                        <td width="21%" align="center" class="simple_green_12"><strong><? print $row['price']; ?></strong><br><span class="simple_green_10"><? print $row['cur_symbol']; ?></span></td>
                        <td width="18%" align="right" class="simple_maro_12">
                        <a class="btn btn-danger" href="#" 
            onClick="javascript:$('#act').val('del_order');
                                $('#par_1').val('<? print $row['uid']; ?>'); 
                                $('#del_modal').modal();" 
            style="width:90px">
            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete
            </a>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
                
                
            </table>
            <br><br><br>
            </div>
        
        <?
	}
	
	
	function showTrans($symbol)
	{
		?>
           
           <div id="div_trans" name="div_trans" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="28%" align="left" class="inset_maro_14">Seller</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center" class="inset_maro_14">Buyer</td>
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
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png"><table width="92%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="28%" align="left" class="simple_green_12"><a href="#" class="maro_12"><strong>fdvfdvdfvfd</strong></a><br></td>
                        <td width="23%" align="left" class="simple_maro_12"><a href="#" class="maro_12"><strong>fdvfdvdfvfd</strong></a><br></td>
                        <td width="17%" align="center" class="simple_green_12"><span class="simple_maro_12"><strong>43.0090</strong><br>
                        <span class="simple_maro_10">RONRON</span></span></td>
                        <td width="17%" align="center" class="simple_maro_12"><span class="simple_green_12"><strong>0.00000000</strong><br>
                        <span class="simple_green_10">MSK</span></span></td>
                        <td width="15%" align="center" class="simple_maro_12">5 days</td>
                      </tr>
                      <tr>
                        <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                    </tbody>
                  </table></td>
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
                    <td height="25" align="center" bgcolor="#dbf9db" class="inset_green_12">Order Type</td>
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
                    <td width="33%" height="30" align="left" valign="top"><strong> Price</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Qty</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Days</strong></td>
                  </tr>
                  <tr>
                    <td align="left"><input class="form-control" id="txt_price" name="txt_price" placeholder="0" style="width:100px"/></td>
                    <td align="left"><input class="form-control" id="txt_qty" name="txt_qty" placeholder="0" style="width:100px"/></td>
                    <td align="left"><input class="form-control" id="txt_days" name="txt_days" placeholder="100" style="width:100px"/></td>
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
	
	function showNewOrderMarketModal($mkt_symbol)
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
		$this->template->showModalHeader("modal_new_order", "New Order", "act", "new_order", "uid", "");
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
                
                <div id="tab_new_buy" name="tab_new_buy" style="display:block">
                <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#dbf9db" class="inset_green_12">Order Type</td>
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
                <td align="left"><? $this->template->showMyAdrDD("dd_order_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Payment Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_order_adr", "330"); ?></td>
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
                    <td align="left"><input class="form-control" id="txt_order_qty" name="txt_order_qty" placeholder="0" style="width:100px"/></td>
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
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned RONRON</td>
                      </tr>
                      <tr>
                        <td height="50" align="center" bgcolor="#fafafa" class="simple_blue_18" id="td_ab" name="td_ab"><? print $asset_balance; ?></td>
                      </tr>
                    </table></td>
                    <td width="185" align="right"><table width="165" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" bgcolor="#f0f0f0" class="simple_gri_12">Owned MSK</td>
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
		$this->template->showModalFooter();
	}
	
	function showOrders($mkt_symbol, $tip)
	{
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		// Load data
		if ($tip=="ID_BUY")
		    $query="SELECT * 
		              FROM assets_markets_pos 
				     WHERE mkt_symbol='".$mkt_symbol."' 
				       AND tip='ID_BUY' 
			      ORDER BY price DESC";
		else
		    $query="SELECT * 
		              FROM assets_markets_pos 
				     WHERE mkt_symbol='".$mkt_symbol."' 
				       AND tip='ID_SELL' 
			      ORDER BY price ASC";
				  
		$result=$this->kern->execute($query);	
	  
	    $this->template->showQRModal();
		?>
             
<div  id="div_markets_<? print $tip; ?>">
             <br />
  <table width="610" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="10"><img src="../../GIF/top_bar_left.png" width="10" height="45" /></td>
      <td width="287" background="../../GIF/top_bar_middle.png" class="inset_gri_14">&nbsp;&nbsp;Market</td>
      <td width="10" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="67" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Qty</td>
      <td width="10" align="center" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="122" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Price</td>
      <td width="10" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="80" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Trade</td>
      <td width="14"><img src="../../GIF/top_bar_right.png" width="10" height="45" /></td>
    </tr>
  </table>
          
  <table width="600" border="0" cellspacing="0" cellpadding="0">
          
    <?
		     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			 {
		  ?>
          
        <tr>
          <td width="47" align="center">
          
         <a data-toggle="tooltip" data-placement="top" title="Show Address QR" href="#" class="qr"><img src="../../GIF/ico/qr.png" width="20" onclick="$('#qr_img').attr('src', '../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()" />
            </a>
          
          </td>
          <td width="244">
            <span class="simple_blue_14"><? print substr($row['adr'], 20, 20)."..."; ?></span><br />
          <span class="simple_gri_10"><? print "Order ID : ".$row['uid']; ?></span></td>
          <td width="80" align="center" class="simple_gri_14"><strong><? print $row['qty']; ?></strong></td>
          <td width="166" align="center" class="simple_porto_14"><strong><? print round($row['price'], 8)." ".$mkt_row['cur_symbol']; ?></strong></td>
          <td width="63" align="center">
            <a class="btn btn-<? if ($tip=="ID_BUY") print "danger"; else print "success"; ?>" href="#" 
            onClick="javascript:$('#uid').val('<? print $row['uid']; ?>'); 
                                <? 
								   if ($tip=="ID_BUY") 
								   {
								      print "$('#tab_new_buy').css('display', 'none');";
									  print "$('#tab_new_sell').css('display', 'block');";
								   }
								   else
								   {
								      print "$('#tab_new_buy').css('display', 'block');";
									  print "$('#tab_new_sell').css('display', 'none');";
								   }
							    ?>
                                $('#modal_new_order').modal();" 
            style="width:90px">
            <?
			   if ($tip=="ID_BUY")
			      print "Sell";
				else
				  print "Buy"; 
			?>
            </a>
          </td>
    </tr>
        <tr>
          <td colspan="5" background="../../GIF/lp.png">&nbsp;</td>
        </tr>
            
    <?
			 }
			?>
        
  </table>
</div>
        
        <?
	}
	
	function showMyOrd($mkt_symbol)
	{
		// Market data
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE mkt_symbol='".$mkt_symbol."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// My addresses
		$query="SELECT adr 
		          FROM my_adr 
				 WHERE user='".$_REQUEST['ud']['user']."'";
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		   $adr=$adr."'".$row['adr']."', ";
		
		// Eliminate last comma
		$adr=substr($adr, 0, strlen($adr)-2);
		
		// Load data
		$query="SELECT * 
		          FROM assets_markets_pos 
				 WHERE mkt_symbol='".$mkt_symbol."' 
				   AND adr IN (".$adr.")"; 
		$result=$this->kern->execute($query);	
	    
		// Confirm modal
		$this->template->showDelModal();
		
		// QR code
		$this->template->showQRModal();
		
	 	?>
             
<div  id="div_markets_<? print $tip; ?>">
             <br />
  <table width="610" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="10"><img src="../../GIF/top_bar_left.png" width="10" height="45" /></td>
      <td width="287" background="../../GIF/top_bar_middle.png" class="inset_gri_14">&nbsp;&nbsp;Order</td>
      <td width="10" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="67" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Qty</td>
      <td width="10" align="center" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="122" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Price</td>
      <td width="10" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14"><img src="../../GIF/top_bar_sep.png" width="10" height="45" /></td>
      <td width="80" align="center" background="../../GIF/top_bar_middle.png" class="inset_gri_14">Delete</td>
      <td width="14"><img src="../../GIF/top_bar_right.png" width="10" height="45" /></td>
    </tr>
  </table>
          
  <table width="600" border="0" cellspacing="0" cellpadding="0">
          
    <?
		     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			 {
		  ?>
          
        <tr>
          <td width="47" align="center">
          
         <a data-toggle="tooltip" data-placement="top" title="Show Address QR" href="#" class="qr"><img src="../../GIF/ico/qr.png" width="20" onclick="$('#qr_img').attr('src', '../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()" />
            </a>
          
          </td>
          <td width="249">
            <span class="
			<? 
			    if ($row['tip']=="ID_BUY") 
				   print "simple_green_14"; 
				else 
				   print "simple_red_14"; 
			?>">
			<? 
			     if ($row['tip']=="ID_BUY") 
				   print "<strong>Buy</strong> ".$mkt_symbol; 
				else 
				   print "<strong>Sell</strong> ".$mkt_symbol; 
			?>
            </span><br />
          <span class="simple_gri_10"><? print "Address : ".substr($row['adr'], 40, 20)."..."; ?></span></td>
          <td width="77" align="center" class="simple_gri_14"><strong><? print $row['qty']; ?></strong></td>
          <td width="133" align="center" class="simple_porto_14"><strong><? print round($row['price'], 8)." ".$mkt_row['cur_symbol']; ?></strong></td>
          <td width="94" align="center">
            <a class="btn btn-danger" href="#" 
            onClick="javascript:$('#act').val('del_order');
                                $('#par_1').val('<? print $row['uid']; ?>'); 
                                $('#del_modal').modal();" 
            style="width:90px">
            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete
            </a>
          </td>
    </tr>
        <tr>
          <td colspan="5" background="../../GIF/lp.png">&nbsp;</td>
        </tr>
            
    <?
			 }
			?>
        
  </table>
</div>
        
        <?
	}	
} 
?>