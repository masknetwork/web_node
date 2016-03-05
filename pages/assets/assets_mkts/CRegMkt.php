<?
class CRegMkt
{
	function CRegMkt($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function closeOrder($net_fee_adr, $orderID)
	{
		// Position exist ?
		$query="SELECT * 
		          FROM assets_mkts_pos AS amp 
				  JOIN assets_mkts AS am ON am.mktID=amp.mktID 
				 WHERE amp.orderID='".$orderID."'"; 
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
								par_1='".$row['orderID']."',
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
	
	function newTrade($net_fee_adr, $order_adr, $orderID, $qty)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($order_adr)==false)
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
		 $net_fee=0.0001;
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		// Order address
		if ($this->kern->adrExist($order_adr)==false)
		{
			$this->template->showErr("Invalid order address");
			return false;
		}
		
		// Position exist ?
		$query="SELECT asp.* 
		          FROM assets_mkts_pos AS asp 
				  JOIN assets_mkts AS am ON am.mktID=asp.mktID
				 WHERE asp.orderID='".$orderID."'"; 
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
		          FROM assets_mkts 
				 WHERE mktID='".$pos_row['mktID']."'";
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Asset
		$asset=$mkt_row['asset']; 
		
		// Currency
		$cur=$mkt_row['cur']; 
		
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
							    op='ID_NEW_REGULAR_MKT_TRADE', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$order_adr."',
								par_1='".$pos_row['orderID']."',
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
	
	function newMarketPos($mktID,
	                      $tip,
	                      $net_fee_adr, 
	                      $order_adr, 
						  $order_adr_asset, 
					      $price, 
					      $qty, 
					      $days)
	{
		// Asset address
		if ($tip=="ID_SELL") $order_adr=$order_adr_asset;
		
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($order_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Market exist ?
		$query="SELECT * 
		          FROM assets_mkts 
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    if (mysql_num_rows($result)==0)
		{
		   $this->template->showErr("Market doesn't exist");
		   return false;
		}
		
		// Market data
		$mkt_row=mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Currency
		$cur=$mkt_row['cur'];
		
		// Asset
		$asset=$mkt_row['asset'];
		
		// Tip
		if ($tip!="ID_BUY" && $tip!="ID_SELL")
		{
		    $this->template->showErr("Market doesn't exist");
			return false;
		}
		
		// Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $net_fee=0.0001;
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		// Order address
		if ($this->kern->adrExist($order_adr)==false)
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
				$this->template->showErr("Insufficient funds to execute this transaction");
			    return false;
			}
			
			// Lower than the lower sell ?
			$query="SELECT * 
			          FROM assets_mkts_pos 
					 WHERE mktID='".$mktID."' 
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
				$this->template->showErr("Insufficient assets to execute this transaction");
			    return false;
			}
			
			// Higher than the higher buy ?
			$query="SELECT * 
			          FROM assets_mkts_pos 
					 WHERE mktID='".$mktID."' 
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
								par_1='".$mktID."',
								par_2='".$tip."',
								par_3='".$qty."',
								par_4='".$price."',
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

	
	function showPanel($mktID)
	{
		$query="SELECT *
		          FROM assets_mkts 
				 WHERE mktID='".$mktID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
        
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="15%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=150&h=150"; ?>"  class="img-circle img-responsive"/></td>
            <td width="3%">&nbsp;</td>
            <td width="83%" valign="top"><span class="font_16"><strong><? print base64_decode($row['name']); ?></strong></span>
            <p class="font_14"><? print base64_decode($row['description']); ?></p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table class="table-responsive" width="100%">
             <tr>
            <td width="30%" align="center"><span class="font_12">Address&nbsp;&nbsp;&nbsp;&nbsp;<strong><a class="font_12" href="#"><? print $this->template->formatAdr($row['adr']); ?></a></strong></span></td>
            <td width="40%" class="font_12" align="center">Asset&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['asset']; ?></strong></td>
            <td width="30%" class="font_12" align="center">Currency Fee&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['cur']; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center" class="font_12"><span class="font_12">Decimals</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['decimals']; ?></strong></td>
            <td width="40%" class="font_12" align="center">Issued&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></strong></td>
            <td width="30%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center" class="font_12"><span >Market ID</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['mktID']; ?></strong></td>
            <td width="40%" class="font_12" align="center">Ask&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print round($row['ask'], 8)." ".$row['cur']; ?></strong></td>
            <td width="30%" class="font_12" align="center">Bid&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print round($row['bid'], 8)." ".$row['cur']; ?></strong></td>
            </tr>
            
           
            </table>
            
            <table>
            </table>
            
            </td></tr>
            </table>
            </div>
            </div>
            <br>
            
        <?
	}
	
	function showReport($mktID)
	{
		// Last value
		$query="SELECT * 
		         FROM assets_mkts 
				WHERE mktID='".$mktID."'"; 
	    $result=$this->kern->execute($query);	
	    $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// Owned assets
		$query="SELECT sum(qty) AS total
		          FROM assets_owners 
		         WHERE symbol='".$mkt_row['asset']."' 
				   AND owner IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$owned_assets=$row['total'];
		
		
		// Owned Currency
		if ($mkt_row['cur']=="MSK")
		{
		   $owned_cur=$_REQUEST['ud']['balance'];
		}
		else
		{
		  $query="SELECT sum(qty) AS total
		          FROM assets_owners 
		         WHERE symbol='".$mkt_row['cur']."' 
				   AND owner IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')";
								
		  $result=$this->kern->execute($query);	
		  $row = mysql_fetch_array($result, MYSQL_ASSOC);
		  $owned_cur=$row['total'];
		}
		
		// Trades 24 H
		$query="SELECT COUNT(*) AS total 
		          FROM assets_mkts_trades 
				 WHERE mktID='".$mktID."' 
				   AND block>".($_REQUEST['sd']['last_block']-$_REQUEST['sd']['blocks_per_day']);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$trades=$row['total'];
		  
		?>
            
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table>
            <tr>
            <td width="25%" valign="top" align="center"><span class="font_10">Owned Asset</span><br><span class="font_20">
			<? print round($owned_assets, 8)." <span class='font_12'>".$mkt_row['asset']."</span>"; ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Owned Currency</span><br><span class="font_20">
			<? print round($owned_cur, 8)." <span class='font_12'>".$mkt_row['cur']."</span>"; ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Last Price</span><br><span class="font_20">
			<? print round($mkt_row['last_price'], 8)." <span class='font_12'>".$mkt_row['cur']."</span>"; ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Trades 24H</span><br><span class="font_20">
			<? print $trades; ?></span></td>
            </tr>
            </table>
            </div>
            </div>
        
        <?
	} 
	function showTraders($mktID, $tip, $visible=true)
	{
		// Order modal
		$this->showNewPosMarketModal($mktID);
		
		// Close modal
		$this->showCloseOrderModal();
		
		// New trade modal
		$this->showNewTradeModal($mktID);
		
		if ($tip=="ID_BUY")
		    $query="SELECT amp.*, am.asset, am.cur, ma.userID
			          FROM assets_mkts_pos AS amp
					  JOIN assets_mkts AS am ON am.mktID=amp.mktID
				 LEFT JOIN my_adr AS ma ON ma.adr=amp.adr
					 WHERE tip='ID_BUY' 
				  ORDER BY price DESC 
				     LIMIT 0,25";
		else
		    $query="SELECT amp.*, am.asset, am.cur , ma.userID
			          FROM assets_mkts_pos AS amp
					  JOIN assets_mkts AS am ON am.mktID=amp.mktID
				 LEFT JOIN my_adr AS ma ON ma.adr=amp.adr
					 WHERE tip='ID_SELL' 
				  ORDER BY price ASC 
				     LIMIT 0,25";
		
		$result=$this->kern->execute($query);	
		
		?>
           
           
           <div id="div_traders_<? print $tip; ?>" name="div_sellers" style="display:<? if ($visible==true) print "block"; else print "none"; ?>">
           <br>
           
           <?
		      if ($tip=="ID_SELL")
			  {
		   ?>
           
                 <table width="90%">
                 <tr>
                 <td align="right">
                 <a href="javascript:void(0)" onclick="$('#modal_new_pos').modal(); 
                                                      $('#tab_buy').css('display', 'none'); 
                                                      $('#tab_sell').css('display', 'block');
                                                      $('#img_buy').css('display', 'none'); 
                                                      $('#img_sell').css('display', 'block');
                                                      $('#tip').val('ID_SELL');
                                                      $('#dd_new_pos_adr').css('display', 'none');
                                                      $('#dd_new_pos_adr_asset').css('display', 'block');" class="btn btn-danger">
                 <span class="glyphicon glyphicon-minus"></span>&nbsp;&nbsp;New Sell Order</a>
                 </td>
                 </tr>
                 </table>
           
           <?
			  }
			  else
			  {
		   ?>
           
                 <table width="90%">
                 <tr>
                 <td align="right">
                 <a href="javascript:void(0)" onclick="$('#modal_new_pos').modal(); 
                                                      $('#tab_buy').css('display', 'block'); 
                                                      $('#tab_sell').css('display', 'none');
                                                      $('#img_buy').css('display', 'block'); 
                                                      $('#img_sell').css('display', 'none');
                                                      $('#tip').val('ID_BUY');
                                                      $('#dd_new_pos_adr').css('display', 'block');
                                                      $('#dd_new_pos_adr_asset').css('display', 'none');" class="btn btn-success">
                 <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Buy Order</a>
                 </td>
                 </tr>
                 </table>
           
           <?
			  }
		   ?>
          
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Address</th>
           <th class="font_14" height="35px" align="center">Qty</th>
           <th class="font_14" height="35px" align="center">Price</th>
           <th class="font_14" height=\"35px\" align=\"center\"><? if ($tip=="ID_BUY") print "Sell"; else print "Buy"; ?></th>
           </thead>
           
           <br>
           
           <?
		      $a=0;
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
				  $a++;
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="#" class="font_14"><? print $this->template->formatAdr($row['adr'])."<br>"; ?></a>
                 <p class="font_10"><? print $this->template->formatAdr($row['adr']); ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print round($row['qty'], 8)." ".$row['asset']; 
			     ?>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print round($row['price'], 8)." ".$row['cur']; 
			     ?>
                 </td>
                 
                 
                 <td class="font_16" width="12%">
                 
                 <?
				     if ($a==1)
					 {
                     if ($tip=="ID_SELL")
					 {
				 ?>
                 
                            <a href="javascript:void(0)" onclick="$('#modal_new_trade').modal(); 
                                                $('#new_trade_orderID').val('<? print $row['orderID']; ?>'); 
                                                $('#dd_new_trade_adr').css('display', 'block');
                                                $('#dd_new_trade_adr_assets').css('display', 'none');" class='btn btn-primary btn-sm'>
				            Buy</a>
                 
                 <?
					 }
					 else
					 {
						 ?>
                         
                            <a href="javascript:void(0)" onclick="$('#modal_new_trade').modal(); 
                                                 $('#new_trade_orderID').val('<? print $row['orderID']; ?>'); 
                                                 $('#dd_new_trade_adr').css('display', 'none'); 
                                                 $('#dd_new_trade_adr_assets').css('display', 'block');" class='btn btn-danger btn-sm'>
				            Sell</a>
                         
                         <?
					 }
					 }
					 
					 
				    if ($row['userID']==$_REQUEST['ud']['ID'])
					   print "<a class='btn btn-danger btn-sm' href='javascript:void(0)' onclick=\"$('#modal_close_order').modal(); $('#orderID').val('".$row['orderID']."'); \"><span class='glyphicon glyphicon-remove'></span></a>";
				 ?>
                 </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
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
	
	
	function showLastTrades($mktID)
	{
		$query="SELECT * 
		          FROM assets_mkts_trades 
				 WHERE mktID='".$mktID."' 
			  ORDER BY ID DESC 
			     LIMIT 0,25"; 
		$result=$this->kern->execute($query);	
	  
		
		?>
           
          <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th class="font_14" height="35px">&nbsp;&nbsp;Buyer</th>
           <th class="font_14" height="35px" align="center">Seller</th>
           <th class="font_14" height="35px" align="center">Qty</th>
           <th class="font_14" height=\"35px\" align=\"center\">Price</th>
           <th class="font_14" height=\"35px\" align=\"center\">Time</th>
           </thead>
           
           <br>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
				  
		   ?>
           
                 <tr>
                 <td width="30%">
                 <a href="#" class="font_14"><? print $this->template->formatAdr($row['buyer'])."<br>"; ?></a>
                
                 </td>
                 <td class="font_14" width="30%">
				 <a href="#" class="font_14"><? print $this->template->formatAdr($row['seller'])."<br>"; ?></a>
                 </td>
                 <td class="font_14" width="10%">
				 <? 
				      print round($row['qty'], 8)." ".$row['asset']; 
			     ?>
                 </td>
                 <td class="font_14" width="10%">
				 <? 
				      print round($row['price'], 8)." ".$row['cur']; 
			     ?>
                 </td>
                 
                 
                 <td width="10%" class="font_14">
                 <?
				    print "~".$this->kern->timeFromBlock($row['block']);
				 ?>
                 </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           <br><br><br>
        
        <?
	}
    
	function showNewPosMarketModal($mktID)
	{
		$query="SELECT * 
		          FROM assets_mkts
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			 $this->template->showErr("Invalid market symbol");
			 return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Header
		$this->template->showModalHeader("modal_new_pos", "New Trade Position", "act", "new_position", "tip", "");
		?>
            
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/buy.png" width="180" height="181" id="img_buy" /><img src="GIF/sell.png" width="180" height="181" id="img_sell" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                
                <div id="tab_buy" name="tab_buy" style="display:block">
                <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#dbf9db" class="font_12" style="color:#009900">Order Type</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#eefdee" class="font_24" style="color:#009900"><strong>BUY</strong></td>
                  </tr>
                </table>
                </div>
                
                 <div id="tab_sell" name="tab_sell" style="display:block">
                 <table width="130" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="25" align="center" bgcolor="#f9dbdb" style="color:#990000" class="font_12">Order Type</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#faecec" style="color:#990000" class="font_24"><strong>SELL</strong></td>
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
                <td align="left"><? $this->template->showMyAdrDD("dd_new_trade_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Order Address</strong></span></td>
              </tr>
              <tr>
                <td align="left">
				<? 
				   $this->template->showMyAdrDD("dd_new_pos_adr", "330");
				   $this->template->showMyAdrAssetDD($row['asset'], "dd_new_pos_adr_asset", 330);
				?>
                </td>
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
                    <td align="left"><input class="form-control" id="txt_new_trade_price" name="txt_new_trade_price" placeholder="0" style="width:100px"/></td>
                    <td align="left"><input class="form-control" id="txt_new_trade_qty" name="txt_new_trade_qty" placeholder="0" style="width:100px"/></td>
                    <td align="left"><input class="form-control" id="txt_new_trade_days" name="txt_new_trade_days" placeholder="100" style="width:100px"/></td>
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
			  $('#td_pos_ab').load('get_page.php?act=get_balance&adr='+encodeURIComponent($('#dd_adr').val())+'&asset='+'<? print $row['asset_symbol']; ?>');
			  $('#td_pos_cb').load('get_page.php?act=get_balance&adr='+encodeURIComponent($('#dd_adr').val())+'&asset='+'<? print $row['cur_symbol']; ?>');
		   });
		 </script>  
         
		   
        
        <?
		$this->template->showModalFooter();
	}
	
	function showNewTradeModal($mktID)
	{
		$query="SELECT * 
		          FROM assets_mkts
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			 $this->template->showErr("Invalid market symbol");
			 return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Header
		$this->template->showModalHeader("modal_new_trade", "New Trade", "act", "new_trade", "new_trade_orderID", "");
		?>
            
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/basket.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
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
                <td align="left">
				<? 
				    if ($row['cur']=="MSK")
				       $this->template->showMyAdrDD("dd_new_trade_adr", 330); 
					else
					    $this->template->showMyAdrDD($row['cur'], "dd_new_trade_adr", 330); 
						
					 $this->template->showMyAdrAssetDD($row['asset'], "dd_new_trade_adr_assets", 330);
			    ?>
                </td>
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
                    <input class="form-control" id="txt_new_trade_order_qty" name="txt_new_trade_order_qty" placeholder="0" style="width:100px"/></td>
                  </tr>
                </table></td>
              </tr>
              </table></td>
          </tr>
         </table>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showCloseOrderModal()
	{
		// Header
		$this->template->showModalHeader("modal_close_order", "Close Order", "act", "close_order", "orderID", "0");
		?>
            
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/close.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="438" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_close_order_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              </table></td>
          </tr>
         </table>
         
		 
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
	
} 
?>