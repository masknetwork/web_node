<?
class CSpecMkts
{
	function CSpecMkts($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function updateTrade($net_fee_adr, $tradeID, $sl, $tp)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false)
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
		 
		 // Balance
		 if ($this->kern->getBalance($net_fee_adr)<0.0001)
		 {
			 $this->template->showErr("Insufficient funds");
			 return false;
		 }
		 
		 // Trade ID exist ?
		 $query="SELECT * 
		           FROM feeds_spec_mkts_pos 
				  WHERE posID='".$tradeID."' 
				    AND status<>'ID_CLOSED'";
		 $result=$this->kern->execute($query);	
	     
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Load data
		 $pos_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // Load market data
		 $query="SELECT * 
		           FROM feeds_spec_mkts 
				  WHERE mktID='".$pos_row['mktID']."'";
	     $result=$this->kern->execute($query);	
		 $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // My position
		 if ($this->kern->isMine($pos_row['adr'])==false)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Additional margin
		 $margin=0;
		 
		 // Check open sl and tp
		 if ($pos_row['status']=="ID_PENDING")
		    $open=$pos_row['open'];
	     else
		    $open=$mkt_row['last_price'];
			
		
		 if ($pos_row['tip']=="ID_BUY")
		 {
			    if ($sl>$open-($mkt_row['spread']*2))
			    {
				  $this->template->showErr("Invalid stop loss value. Maximum value is ".($open-($mkt_row['spread']*2)));
			      return false;
			    }
			 
			    if ($tp<$open+$mkt_row['spread'])
			    {
				    $this->template->showErr("Invalid take profit value. Minimum value is ".($open+$mkt_row['spread']));
			        return false; 
			    }
			 
			    // SL lower ?
			    if ($sl<$pos_row['sl']) 
			        $margin=($pos_row['sl']-$sl)*$mkt_row['last_price'];
		 }
		 else
		 {
			   if ($sl<$open+($mkt_row['spread']*2))
			   {
				    $this->template->showErr("Invalid stop loss value. Maximum value is ".($open+($mkt_row['spread']*2)));
			        return false;
			   }
			 
			   if ($tp>$open-$mkt_row['spread'])
			   {
				   $this->template->showErr("Invalid take profit value. Minimum value is ".($open-$mkt_row['spread']));
			       return false; 
			   }
			 
			   // SL higher ?
			   if ($sl>$pos_row['sl']) 
			      $margin=($sl-$pos_row['sl'])*$mkt_row['last_price'];
		}
		
		
		// Enough funds ?
		if ($margin>0 && $margin>$_pos_row['margin'])
		{
			if ($this->kern->getBalance($pos_row['adr'], $mkt_row['cur'])<($margin-$pos_row['margin']))
			{
				$this->template->showErr("Insufficient funds to cover aditional margin");
			    return false; 
			}
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Modify a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_CHANGE_SPEC_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$pos_row['adr']."',
								par_1='".$tradeID."',
								par_2='".$sl."',
								par_3='".$tp."',
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
	
	function closeTrade($net_fee_adr, $tradeID, $percent)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false)
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
		 
		 // Balance
		 if ($this->kern->getBalance($net_fee_adr)<0.0001)
		 {
			 $this->template->showErr("Insufficient funds");
			 return false;
		 }
		 
		 // Trade ID exist ?
		 $query="SELECT * 
		           FROM feeds_spec_mkts_pos 
				  WHERE posID='".$tradeID."' 
				    AND status<>'ID_CLOSED'";
		 $result=$this->kern->execute($query);	
	     
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Load data
		 $pos_row = mysql_fetch_array($result, MYSQL_ASSOC);
	     
		 // My position
		 if ($this->kern->isMine($pos_row['adr'])==false)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Percent
		 if ($percent<1 || $percent>100)
		 {
			 $this->template->showErr("Invalid percent");
			 return false; 
		 }
		 
		  try
	      {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Closes a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_CLOSE_SPEC_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$pos_row['adr']."',
								par_1='".$tradeID."',
								par_2='".$percent."',
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
	
	function newTrade($net_fee_adr, 
					  $adr, 
					  $mktID, 
					  $tip, 
					  $ex_type,
					  $open, 
					  $sl, 
					  $tp, 
					  $leverage, 
					  $qty)
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
		 
		 // Address 
		 if ($this->kern->adrExist($adr)==false)
		 {
			$this->template->showErr("Invalid address");
			return false;
		 }
		 
		 // Balance
		 if ($this->kern->getBalance($net_fee_adr)<0.0001)
		 {
			 $this->template->showErr("Insufficient funds");
			 return false;
		 }
		 
		 // Load market data
		 $query="SELECT fsm.*, adr.balance AS mkt_adr_balance 
		           FROM feeds_spec_mkts AS fsm 
				   JOIN adr ON adr.adr=fsm.adr
				  WHERE mktID='".$mktID."'";
		 $result=$this->kern->execute($query);	
	     
		 // Market exist ?
		 if (mysql_num_rows($result)==0)
		 {
			  $this->template->showErr("Invalid market ID");
			  return false;
		 }
		 
		 // Market data
		 $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // Tip
		 if ($tip!="ID_BUY" && $tip!="ID_SELL")
		 {
			  $this->template->showErr("Invalid position type");
			  return false;
		 }
		 
		 // Execution type
		 if ($ex_type!="ID_MARKET" && $ex_type!="ID_PENDING")
		 {
			  $this->template->showErr("Invalid execution type");
			  return false;
		 }
		 
		 // Open line
		 if ($ex_type=="ID_PENDING")
		 {
			 if ($open<$mkt_row['last_price'])
			    $open_line="ID_ABOVE";
		     else
			    $open_line="ID_BELOW";
		 }
		 
		 // Check open sl and tp
	     if ($tip=="ID_BUY")
		 {
			 if ($sl>$open-($mkt_row['spread']*2))
			 {
				  $this->template->showErr("Invalid stop loss value. Maximum value is ".($open-($mkt_row['spread']*2)));
			      return false;
			 }
			 
			 if ($tp<$open+$mkt_row['spread'])
			 {
				 $this->template->showErr("Invalid take profit value. Minimum value is ".($open+$mkt_row['spread']));
			     return false; 
			 }
		 }
		 else
		 {
			 if ($sl<$open+($mkt_row['spread']*2))
			 {
				  $this->template->showErr("Invalid stop loss value. Maximum value is ".($open+($mkt_row['spread']*2)));
			      return false;
			 }
			 
			 if ($tp>$open-$mkt_row['spread'])
			 {
				 $this->template->showErr("Invalid take profit value. Minimum value is ".($open-$mkt_row['spread']));
			     return false; 
			 }
		}
		 
		 // Leverage
		 if ($leverage>$mkt_row['max_leverage'])
		 {
			 $this->template->showErr("Invalid leverage");
			 return false;
		 }
		 
		 // Margin
		 $margin=$qty*$mkt_row['last_price']/$leverage;
		 
		 // Max loss
		 $max_loss=abs($open*$qty-$sl*$qty);
		 
		 // Max losss bigger than margin ?
		 if ($max_loss>$margin) $margin=$max_loss;
			 
		 // Address have margin ?
		 if ($this->kern->getBalance($adr, $mkt_row['cur'])<$margin)
		 {
			  $this->template->showErr("Insufficient funds to cover the margin");
			  return false; 
		 }
		 
		 // Calculate free colaterall
		 $query="SELECT SUM(pl) AS total 
		           FROM feeds_spec_mkts_pos 
				  WHERE mktID='".$mktID."' 
				    AND (status='ID_MARKET' OR 
					     status='ID_PENDING')";
		 $result=$this->kern->execute($query);	
	     $row = mysql_fetch_array($result, MYSQL_ASSOC);
	     $free_colateral=$mkt_row['mkt_adr_balance']-$row['total'];
		 
		 // Maximum margin
		 $max_margin=$free_colateral*$mkt_row['max_margin']/100;
		 
		 // Check margin
		 if ($margin>$max_margin)
		 {
			 $this->template->showErr("Invalid margin. Maximum allowed margin is ".$max_margin);
			 return false; 
		 }
		 
		 // Minimum margin
		if ($margin<0.00000001) $margin=0.00000001;
		
		if ($mkt_row['cur']=="MSK" && 
		    $margin<0.0001)
		$margin=0.0001;
		
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Open a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_SPEC_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$mktID."',
								par_2='".$tip."',
								par_3='".$ex_type."',
								par_4='".$open."',
								par_5='".$sl."',
								par_6='".$tp."',
								par_7='".$leverage."',
								par_8='".$qty."',
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
	
	function newMarket($net_fee_adr, 
	                   $mkt_adr, 
					   $feed_1, 
					   $branch_1, 
					   $feed_2, 
					   $branch_2, 
					   $feed_3, 
					   $branch_3, 
					   $cur, 
					   $min_hold,
					   $max_hold, 
					   $min_leverage,
					   $max_leverage,
					   $spread,
					   $real_symbol,
					   $decimals,
					   $pos_type,
					   $long_int,
					   $short_int,
					   $interest_interval,
					   $title,
					   $desc,
					   $max_margin,
					   $days)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($mkt_adr)==false)
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
		 
		 // Market address
		 if ($this->kern->adrExist($mkt_adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		
		// Feed 1
		if ($this->feedExist($feed_1, $branch_1)==false)
		{
			 $this->template->showErr("Invalid feed 1");
			 return false;
		}
		
		// Feed 2
		if ($feed_2!="")
		{
		  if ($this->feedExist($feed_2, $branch_2)==false)
		  {
			 $this->template->showErr("Invalid feed 2");
			 return false;
		  }
		}
		
		// Feed 3
		if ($feed_3!="")
		{
		  if ($this->feedExist($feed_3, $branch_3)==false)
		  {
			 $this->template->showErr("Invalid feed 3");
			 return false;
		  }
		}
		
		// Load branch data
		$query="SELECT * 
		          FROM feeds_components 
				 WHERE feed_symbol='".$feed."'
				   AND symbol='".$branch."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Feed fee
		$feed_fee=$row['fee']*$days;
		
		// Total fee
		$total_fee=$feed_fee+$net_fee;
		
		// Funds
		if ($this->template->getBalance($net_fee_adr)<$total_fee)
	    {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	    }
		
		// Currency 
		if ($cur!="MSK")
		{
		   $cur=strtoupper($cur);
		   if ($this->kern->symbolValid($cur)==false)
		   {
			  $this->template->showErr("Invalid currency symbol");
			  return false;
		   }
		
		   // Currency exist ?
		   if ($this->kern->assetExist($cur)==false)
		   {
			 $this->template->showErr("Currency doesn't exist");
			 return false;
		   }
		}
		
		// Mininum hold
		if ($min_hold<1)
		{
			 $this->template->showErr("Minimum hold period is 1 block");
			 return false;
		}
		
		// Maximum hold
		if ($max_hold<10)
		{
			 $this->template->showErr("Maximum hold period is 10 blocks");
			 return false;
		}
		
	    // Minimum leverage
		if ($min_leverage<1)
		{
			 $this->template->showErr("Minimum leverage is 1");
			 return false;
		}
		
		// Maximum leverage
		if ($max_leverage>1000)
		{
			 $this->template->showErr("Maximum leverage is 1000");
			 return false;
		}
		
		// Spread
		$tick=1;
		for ($a=1; $a<=$decimals; $a++) $tick=$tick/10;
		
		if ($spread<0 || $spread<$tick)
		{
			 $this->template->showErr("Invalid spread");
			 return false;
		}
		
		// Real symbol
		if (strlen($real_symbol)>10)
		{
			 $this->template->showErr("Invalid real symbol");
			 return false;
		}
		
		// Decimals
		if ($decimals>8 || $decimals<0)
		{
			 $this->template->showErr("Invalid decimals");
			 return false;
		}
		
		// Position type
		if ($pos_type!="ID_LONG_SHORT" && 
		   $pos_type!="ID_LONG_ONLY" && 
		   $pos_type!="ID_SHORT_ONLY")
		{
			$this->template->showErr("Invalid position type");
			return false;
		}
		
		// Long positions interest
		if ($long_int<-10000 || $long_int>10000)
		{
			$this->template->showErr("Invalid long positions interest (-10000 - 10000)");
			return false;
		}
		
		// Short positions interest
		if ($short_int<-10000 || $short_int>10000)
		{
			$this->template->showErr("Invalid short positions interest (-10000 - 10000)");
			return false;
		}
		
		// Interest interval
		if ($interest_interval)
					 
	    // Collateral
		if ($this->kern->getBalance($mkt_adr, $cur)<$collateral)
		{
			$this->template->showErr("Insufficient funds for collateral");
			return false;
		}
		
		 // Name
		 if (strlen($title)<5 || strlen($title)>50)
		 {
			 $this->template->showErr("Invalid title length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 if (strlen($desc)>250)
		 {
			 $this->template->showErr("Invalid description length (50-250 characters)");
			 return false;
		 }
		 
		 // Fee address not a market address
		 if ($this->kern->isMarketAdr($fee_adr)==true)
		 {
			 $this->template->showErr("Market fee address is used in another market.");
			 return false;
		 }
		
		 // Market fee
		 if ($mkt_fee<0 || $mkt_fee>10)
		 {
			 $this->template->showErr("Invalid market fee");
			 return false;
		 }
		 
		 // Decimals
		 if ($decimals<0 || $decimals>8)
		 {
			 $this->template->showErr("Invalid decimals");
			 return false;
		 }
		 
         // Max position size
		 if ($max_margin>10 || $max_margin<0.1)
		 {
			  $this->template->showErr("Invalid maximum position size (0.1 - 10)");
			  return false;
		 }
		 
		 // Days
		 if ($days<100)
		 {
			  $this->template->showErr("Invalid days. Minimum 100 days required.");
			  return false;
		 }
		
		 // Interest interval
		 if ($interest_interval!="ID_HOURLY" && 
		     $interest_interval!="ID_DAILY" && 
			 $interest_interval!="ID_WEEKLY" && 
			 $interest_interval!="ID_MONTHLY" && 
			 $interest_interval!="ID_MONTH_3" && 
			 $interest_interval!="ID_MONTH_6" && 
			 $interest_interval!="ID_YEARLY")
		 {
			 $this->template->showErr("Invalid interest interval");
			 return false;
		 }
		 
		 // Calculates interest interval
		 switch ($interest_interval)
		 {
			 case "ID_HOURLY" : $interest_interval=60; break;
			 case "ID_DAILY" : $interest_interval=1440; break;
			 case "ID_WEEKLY" : $interest_interval=10080; break;
			 case "ID_MONTHLY" : $interest_interval=302400; break;
			 case "ID_MONTH_3" : $interest_interval=907200; break;
			 case "ID_MONTH_6" : $interest_interval=1814400; break;
			 case "ID_YEARLY" : $interest_interval=3628800; break;
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
							    op='ID_NEW_SPEC_MARKET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$mkt_adr."',
								par_1='".$feed_1."',
								par_2='".$branch_1."',
								par_3='".$feed_2."',
								par_4='".$branch_2."',
								par_5='".$feed_3."',
								par_6='".$branch_3."',
								par_7='".$cur."',
								par_8='".$min_hold."',
								par_9='".$max_hold."',
								par_10='".$min_leverage."',
								par_11='".$max_leverage."',
								par_12='".$spread."',
								par_13='".$real_symbol."',
								par_14='".$decimals."',
								par_15='".$pos_type."',
								par_16='".$long_int."',
								par_17='".$short_int."',
								par_18='".$interest_interval."',
								par_19='".$title."',
								par_20='".$desc."',
								par_21='".$max_margin."',
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
	
	
	
	
	function showNewMarketPanel()
	{
		?>
        
        <form id="form_new_mkt" name="form_new_mkt" action="my_mkts.php?act=new_mkt" method="post">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
             <tr>
               <td width="25%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td align="center"><img src="GIF/new.png" width="150" class="img img-responsiv img-circle" /></td>
                   </tr>
                   <tr>
                     <td>&nbsp;</td>
                   </tr>
                   <tr>
                     <td>&nbsp;</td>
                   </tr>
                 </tbody>
               </table></td>
               <td><table width="90%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
                 </tr>
                 <tr>
                   <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_net_fee_adr", "100%"); ?></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Market Address</strong></td>
                 </tr>
                 <tr>
                   <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_adr", "100%"); ?></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="33%" height="30" align="left" valign="top" class="font_14"><strong>Feed Symbol</strong></td>
                       <td width="2%" align="left" valign="top" class="font_14">&nbsp;</td>
                       <td width="33%" align="left" valign="top" class="font_14"><strong>Feed Branch</strong></td>
                        <td width="33%" align="left" valign="top" class="font_14"><strong>Currency</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_new_mkt_feed_1" name="txt_new_mkt_feed_1" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_new_mkt_branch_1" name="txt_new_mkt_branch_1" placeholder="XXXXXX" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_cur" name="txt_new_mkt_cur" placeholder="MSK" style="width:90%"/></td>
                     </tr>
                   </table></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="22%" height="30" align="left" valign="top" class="font_14"><strong>Feed Symbol 2</strong></td>
                       <td width="1%" align="left" valign="top" class="font_14">&nbsp;</td>
                       <td width="27%" align="left" valign="top" class="font_14"><strong>Feed Branch 2</strong></td>
                       <td width="28%" align="left" valign="top" class="font_14"><strong>Feed Symbol 3</strong>                       </td>
                       <td width="1%" align="left" valign="top" class="font_14">&nbsp;</td>
                       <td width="21%" align="left" valign="top" class="font_14"><strong>Feed Branch 3</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_new_mkt_feed_2" name="txt_new_mkt_feed2" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_new_mkt_branch_2" name="txt_new_mkt_branch2" placeholder="XXXXXX" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_feed_3" name="txt_new_mkt_feed_3" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_new_mkt_branch_3" name="txt_new_mkt_branch_3" placeholder="XXXXXX" style="width:90%"/></td>
                     </tr>
                   </table></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="25%" height="30" align="left" valign="top" class="font_14"><strong>Min Hold Time</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Max Hold Time</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Min Leverage</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Max Leverage</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_new_mkt_min_hold" name="txt_new_mkt_min_hold" placeholder="1" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_max_hold" name="txt_new_mkt_max_hold" placeholder="10000" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_min_leverage" name="txt_new_mkt_min_leverage" placeholder="1" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_max_leverage" name="txt_new_mkt_max_leverage" placeholder="100" style="width:90%"/></td>
                     </tr>
                   </table>
                   </td>
                 </tr>
                  <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="25%" height="30" align="left" valign="top" class="font_14"><strong>Spread</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Real World Symbol</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Decimals</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Days</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_new_mkt_spread" name="txt_new_mkt_spread" placeholder="0.0001" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_real_symbol" name="txt_new_mkt_real_symbol" placeholder="AAPL" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_decimals" name="txt_new_mkt_decimals" placeholder="5" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_days" name="txt_new_mkt_days" placeholder="1000" style="width:90%"/></td>
                     </tr>
                   </table>
                   </td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="50%" height="30" align="left" valign="top" class="font_14"><strong>Accepted Positions</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Long Interest (%)</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Short Interest (%)</strong></td>
                     </tr>
                     <tr>
                       <td>
                       <select id="dd_new_mkt_pos_types" name="dd_new_mkt_pos_types" class="form-control" style="width:90%">
                       <option value="ID_LONG_SHORT">Long and short positions</option>
                       <option value="ID_LONG_ONLY">Long positions only</option>
                       <option value="ID_SHORT_ONLY">Short positions only</option>
                       </select>
                       </td>
                       <td><input class="form-control" id="txt_new_mkt_long_int" name="txt_new_mkt_long_int" placeholder="1" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_new_mkt_short_int" name="txt_new_mkt_short_int" placeholder="1" style="width:90%"/></td>
                     </tr>
                   </table>
                   </td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="75%" height="30" align="left" valign="top" class="font_14"><strong>Interest Interval</strong></td>
                       <td width="25%" align="left" valign="top"><strong class="font_14">Max Margin (%)</strong><span class="font_12"></span></td>
                     </tr>
                     <tr>
                       <td>
                       <select id="dd_new_mkt_int_interval" name="dd_new_mkt_int_interval" class="form-control" style="width:90%">
                       <option value="ID_HOURLY">Hourly</option>
                       <option value="ID_DAILY">Daily</option>
                       <option value="ID_WEEKLY">Weekly</option>
                       <option value="ID_MONTHLY">Monthly</option>
                       <option value="ID_MONTH_3">Every 3 Months</option>
                       <option value="ID_MONTH_6">Every 6 Months</option>
                       <option value="ID_YEARLY">Yearly</option>
                       </select>
                       </td>
                       <td><input class="form-control" id="txt_new_mkt_max_margin" name="txt_new_mkt_max_margin" placeholder="1" style="width:90%"/></td>
                     </tr>
                   </table>
                   </td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Market Name</strong></td>
                 </tr>
                 <tr>
                   <td align="left">
                   <input class="form-control" id="txt_new_mkt_title" name="txt_new_mkt_title" placeholder="Name (5-30 characters)" style="width:100%"/></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
                 </tr>
                 <tr>
                   <td align="left">
                   <textarea rows="5" id="txt_new_mkt_desc" name="txt_new_mkt_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:100%"></textarea></td>
                 </tr>
                 <tr>
                   <td align="left">
                   
                 
                   </td>
                 </tr>
                 <tr>
                   <td align="left"></td>
                 </tr>
                 <tr>
                   <td align="left"><hr></td>
                 </tr>
                 <tr>
                   <td height="30" align="right" class="font_14"><a hef="javascript:void(0)" onClick="$('#form_new_mkt').submit()" class="btn btn-primary">Submit</a></td>
                 </tr>
               </table></td>
             </tr>
           </tbody>
         </table>
         </form>
         <br><br>
        
         <script>
		   $('#form_new_mkt').submit(
		   function() 
		   { 
		      $('#txt_new_mkt_title').val(btoa($('#txt_new_mkt_title').val())); 
		      $('#txt_new_mkt_desc').val(btoa($('#txt_new_mkt_desc').val())); 
		   });
		</script>
        
        <?
		
	}
	
	function showNewMktBut()
	{
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="my_mkts.php?act=show_new_mkt_panel" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Market
         </a>
         </td></tr>
</table>
         <br>
         
         <?
	}
	
	function feedExist($feed, $branch)
	{
		// Feed exist ?
		if ($this->kern->feedExist($feed)==false) 
		    return false;
		
		// Feed branch exist ?
		if ($this->kern->branchExist($feed, $branch)==false) 
		    return false;
			
	    return true;
	}
	
	function showMarkets($mine, $search="", $cur="MSK")
	{
		if ($mine==false)
		$query="SELECT fsm.*, adr.balance AS mkt_adr_balance
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE cur='".$cur."'";
		else
	    $query="SELECT fsm.*, adr.balance AS mkt_adr_balance
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE cur='".$cur."' 
				   AND fsm.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."')";
				 
		$result=$this->kern->execute($query);	
	 
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Colaterall</th>
           <th class="font_14" height="35px" align="center">Leverage</th>
           <th class="font_14" height="35px" align="center">Currency</th>
           <th class="font_14" height=\"35px\" align=\"center\">Trade</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print "<span class='font_14'>".round($row['mkt_adr_balance'],2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%"><? print "x ".$row['max_leverage']; ?></td>
                 <td class="font_14" width="10%"><? print $row['cur']; ?></td>
                
                 
                <td class="font_16" width="10%"><a href="market.php?ID=<? print $row['mktID']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Trade</a></td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showPanel($mktID)
	{
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
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
            <td width="30%" class="font_12" align="center">Market ID&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['mktID']; ?></strong></td>
            <td width="40%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			<strong><? print $this->template->formatAdr($row['adr']); ?></strong></a></td>
             <td width="30%" align="center"><span class="font_12">Hold Time</span>&nbsp;&nbsp;&nbsp;&nbsp;<font class="font_12">
             <strong>
			 <? 
			    print $this->getTime($row['min_hold'])." - ".$this->getTime($row['max_hold']); 
			?>
            </strong></font></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" class="font_12" align="center">Expires&nbsp;&nbsp;&nbsp;&nbsp;
			<strong>~ <? print $this->getTime($row['expire']-$_REQUEST['sd']['last_block']); ?></strong>
            </td>
             <td width="33%" align="center"><span class="font_12">Min Leverage&nbsp;&nbsp;&nbsp;&nbsp;
			 <strong><? print "x".$row['min_leverage']; ?></strong></span>&nbsp;&nbsp;</td>
            <td width="33%" class="font_12" align="center">Max Leverage&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print "x".$row['max_leverage']; ?></strong>&nbsp;&nbsp;</td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" align="center"><span class="font_12">Position Types&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>
            <?
			   switch ($row['pos_type'])
			   {
				   case "ID_LONG_SHORT" : print "Long and Short"; break;
				   case "ID_LONG_ONLY" : print "Only Long Positions"; break;
				   case "ID_SHORT_ONLY" : print "Only Short Positions"; break;
				}
			?>
            </strong>
            </span></td>
            <td width="33%" class="font_12" align="center">Maximum Margin&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['max_margin'], 8)."%"; ?></strong></td>
            <td width="33%" class="font_12" align="center">Decimals&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print $row['decimals']; ?></strong></td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            <tr>
            <td width="33%" class="font_12" align="center">Feed  1&nbsp;&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_1']; ?>&symbol=<? print $row['branch_1'] ?>" class="font_12"><strong><? print $row['feed_1']." / ".$row['branch_1']; ?></strong></a></td>
            <td width="33%" class="font_12" align="center">Feed 1 Price &nbsp;&nbsp;&nbsp;&nbsp; 
			<span class="font_12"><strong><? print round($row['price_1'], $row['decimals']); ?></strong></span></td>
            <td width="33%" class="font_12" align="center">Feed 2&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_2']; ?>&symbol=<? print $row['branch_2'] ?>" class="font_12"><strong><? if ($row['feed_2']!="") print $row['feed_2']." / ".$row['branch_2']; else print "none"; ?></strong></a></td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            <tr>
            <td width="33%" class="font_12" align="center">Feed Price 2&nbsp;&nbsp;&nbsp;&nbsp; 
			<span href="" class="font_12"><strong>
			<? if ($row['feed_symbol_2']=="") print "0"; else print round($row['price_2'], $row['decimals']); ?></strong></span></td>
            <td width="33%" class="font_12" align="center">Feed 3&nbsp;&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_3']; ?>&symbol=<? print $row['branch_3'] ?>" class="font_12"><strong><? if ($row['feed_3']!="") print $row['feed_3']." / ".$row['branch_3']; else print "none"; ?></strong></a></td>
            <td width="33%" class="font_12" align="center">Feed 3 Price&nbsp;&nbsp;&nbsp; 
			<span class="font_12"><strong><? if ($row['feed_3']=="") print "0"; else print round($row['price_3'], $row['decimals']); ?></strong></span></td>
            </tr>
            
           
            </table>
            <br></td></tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
	function getTime($time)
	{
		if ($time<360) return round(($time/6))." minutes";
		else if ($time>=360 && $time<8640) return round($time/360)." hours";
		else if ($time>=8640) return round($time/8640)." days";
	}
	
	function showReport($mktID)
	{
		// Last value
		$query="SELECT fsm.*, adr.balance AS mkt_adr_balance 
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
            
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table>
            <tr>
            <td width="25%" valign="top" align="center"><span class="font_10">Last Price</span><br><span class="font_20">
			<? print round($row['last_price'], $row['decimals']); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Currency</span><br><span class="font_20">
			<? print $row['cur']; ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Collateral</span><br><span class="font_20">
			<? print round($row['mkt_adr_balance'],8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Spread</span><br><span class="font_20">
			<? print round($row['spread'], 4); ?></span></td>
            </tr>
            </table>
            </div>
            </div>
        
        <?
	} 
	
	
	
	
	function showTradeButs($mktID)
	{
		// Load data
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
        
        <br>
        <table width="90%">
        <tr>
        
        <td width="90%">&nbsp;</td>
        <td width="10%">
        <a href="javascript:void(0)" onClick="$('#trade_modal').modal(); 
                                              $('#h_type').val('ID_BUY'); 
                                              $('#td_price').css('color', '#009900');
                                              $('#td_price_header').text('Buy at price');
                                              $('#h_spread').val('<? print $row['spread']; ?>');
                                              init();" class="btn btn-small btn-primary">
        <span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Buy</a>
        </td>
        
        <td>&nbsp;&nbsp;</td>
        
        <td width="10%">
        <a href="javascript:void(0)" onClick="$('#trade_modal').modal(); 
                                              $('#h_type').val('ID_SELL'); 
                                              $('#td_price').css('color', '#990000'); 
                                              $('#td_price_header').text('Sell at price');
                                              $('#h_spread').val('<? print $row['spread']; ?>');
                                              init();" class="btn btn-small btn-danger">
        <span class="glyphicon glyphicon-minus-sign"></span>&nbsp;&nbsp;Sell
        </a>
        </td>
        
        </tr>
        </table>
        
        <?
	}
	
	
	function showNewTransModal($mktID)
	{
		// Load data
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Modal
		$this->template->showModalHeader("trade_modal", "New Trade", "act", "new_trade", "mktID", $mktID);
		?>
          
          
           <input type="hidden" id="h_price" value="<? print round($row['last_price'], $row['decimals']); ?>" />
           <input type="hidden" id="h_type" name="h_type" value="ID_BUY" />
           <input type="hidden" id="h_spread" name="h_spread" value="0" />
           
           <script>
		     $('#trade_forex_modal').submit(function() { $('#txt_price').attr('disabled', false); });
		   </script>
             
          <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center">
                <img src="../../assets/margin_mkts/GIF/new_trade.png" class="img-circle img-responsive"></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >
                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14">Required Margin</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fafafa">
                    <strong><span class="font_28" id="s_margin_1">0</span><span class="font_14" id="s_margin_2">.00</span></strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="center" class="bold_gri_18">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" class="bold_gri_18"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14">Maximum Loss</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fafafa" style="color:#990000">
                    <strong><span class="font_28" id="s_loss_1">0</span><span class="font_14" id="s_loss_2">.0000 </span></strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="center" class="bold_gri_18">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" class="bold_gri_18"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14">Potential Profit</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" class="bold_green_28" bgcolor="#fafafa" style="color:#009900">
                    <strong><span class="font_28" id="s_profit_1">0</span><span class="font_14" id="s_profit_2">.0000</span></strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" colspan="2" align="center" valign="top" class="font_14"><table width="340" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Buy at price</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900" id="td_price">
                    <strong>
					<span class="font_30" ><? print explode(".", round($row['last_price'], $row['decimals']))[0]; ?></span>
                    <span class="font_20" ><? print ".".explode(".", round($row['last_price'], $row['decimals']))[1]." ".$row['cur']; ?></span>
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_new_pos_net_fee_adr", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14"><strong>Address</strong></td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_new_pos_adr", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Leverage</strong></td>
                <td height="30" align="left" valign="top" class="font_14"><strong>Execute At</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_gri_14">
                
                <input name="txt_new_pos_leverage" class="form-control" id="txt_new_pos_leverage" placeholder="100" style="width:150px" value="<? print $row['max_leverage']; ?>" onchange="javascript:recalculate()"/>
                
                </span></td>
                <td height="30" align="left" valign="top">
                <select id="dd_new_pos_exec" name="dd_new_pos_exec" class="form-control" style="width:150px" onchange="javascript:execution_change()">
                  <option selected value="ID_MARKET">Market Price</option>
                  <option value="ID_PENDING">Specified price</option>
                </select>
                
                <script>
				  function execution_change()
				  {
					  if ($('#dd_new_pos_exec').val()=="ID_PENDING") 
					  {
						 $('#txt_new_pos_open').attr('disabled', false);
					  }
					  else
					  {
						 $('#txt_new_pos_open').attr('disabled', true);
						 $('#txt_price').val($('#h_price').val());
					  }
				  }
				</script>
                
                </td>
              </tr>
              <tr>
                <td align="left" valign="top" class="font_14">&nbsp;</td>
                <td height="0" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td width="49%" height="30" align="left" valign="top" class="font_14"><strong>Execute at Price&nbsp;&nbsp;</strong></td>
                <td width="51%" height="30" align="left" valign="top" class="font_14"><strong>Buy Qty</strong></td>
              </tr>
              <tr>
                <td height="35" align="left" class="simple_gri_14">
                <input class="form-control" placeholder="0.00" id="txt_new_pos_open" name="txt_new_pos_open" style="width:150px" value="<? print round($row['last_price'], 6); ?>"  onchange="javascript:recalculate()" disabled/></td>
                <td align="left" class="simple_gri_14">
                <input name="txt_new_pos_qty" class="form-control" id="txt_new_pos_qty" placeholder="0.00" style="width:150px" value="1" onchange="javascript:recalculate()"/>
                </td>
              </tr>
              <tr>
                <td height="0" align="left" class="simple_gri_14">&nbsp;&nbsp;</td>
                <td align="left" class="simple_green_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="font_14"><strong>Stop Loss&nbsp;</strong></span></td>
                <td height="30" align="left" valign="top"><span class="font_14"><strong>Take Profit</strong></span></td>
              </tr>
              <tr>
                <td height="0" align="left" class="simple_gri_14">
                <input class="form-control" placeholder="0.00" id="txt_new_pos_sl" name="txt_new_pos_sl" style="width:150px" onchange="javascript:recalculate()"/></td>
                <td height="0" align="left"><span class="simple_gri_14">
                  <input class="form-control" placeholder="0.00" id="txt_new_pos_tp" name="txt_new_pos_tp" style="width:150px" onchange="javascript:recalculate()"/>
                </span></td>
              </tr>
              <tr>
                <td height="0" align="left" class="simple_gri_14">&nbsp;</td>
                <td height="0" align="left">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
          <script>
		  // ------------------------------------------ Margin Calculation -----------------------------------------------
		  function rec_margin()
		  {
			 // Margin
			 price=$('#txt_new_pos_open').val(); 
			
			 if ($('#h_type').val()=="ID_BUY")
			    margin=price*$('#txt_new_pos_qty').val();
			 else
				margin=price*$('#txt_new_pos_qty').val(); 
			
			 // Apply leverage
			 if ($('#txt_new_pos_leverage').val()>1) 
			   margin=margin/$('#txt_new_pos_leverage').val(); 
			
			 margin=Math.round(margin*10000)/(10000); 
			 s=margin.toString().split('.');
			
			 $('#s_margin_1').html(s[0]); 
			
			 if (parseFloat(s[1])>0) 
			    $('#s_margin_2').html("."+s[1]+" <? print $row['cur']; ?>");
			 else
			    $('#s_margin_2').html(".00"+" <? print $row['cur']; ?>");
				
		  }
		  
		  // ------------------------------------------------ Max Loss ---------------------------------------------------
		  function rec_loss()
		  {
			  // Margin
			 price=$('#txt_new_pos_open').val();
			 
			 if ($('#h_type').val()=="ID_BUY")
			    sl=price*$('#txt_new_pos_qty').val()-$('#txt_new_pos_sl').val()*$('#txt_new_pos_qty').val();
			 else
				sl=$('#txt_new_pos_sl').val()*$('#txt_new_pos_qty').val()-price*$('#txt_new_pos_qty').val();
			 
			 // Format 
			 sl=Math.round(sl*10000)/(10000); 
			 ssl=sl.toString().split('.');
			 $('#s_loss_1').html("-"+ssl[0]);
			
			 if (parseFloat(ssl[1])>0) 
			    $('#s_loss_2').html("."+ssl[1]+" <? print $row['cur']; ?>");
			 else
			    $('#s_loss_2').html(".00"+" <? print $row['cur']; ?>");
			 
			 // Loss higher than margin
			 m=$('#s_margin_1').text()+$('#s_margin_2').text();
			 m=parseFloat(m.substring(0, m.length-1));
		     
			 if (Math.abs(sl)>m) 
			 {
				 $('#s_margin_1').text($('#s_loss_1').text());
				 $('#s_margin_1').text($('#s_margin_1').text().substring(1, $('#s_margin_1').text().length));
				 $('#s_margin_2').text($('#s_loss_2').text());
			 }
		  }
		  
		  // ------------------------------------------------ Max Profit ---------------------------------------------------
		  function rec_profit()
		  {
			  // Margin
			 price=$('#txt_new_pos_open').val();
			 
			 if ($('#h_type').val()=="ID_SELL")
			        tp=price*$('#txt_new_pos_qty').val()-$('#txt_new_pos_tp').val()*$('#txt_new_pos_qty').val();
				 else
				    tp=$('#txt_new_pos_tp').val()*$('#txt_new_pos_qty').val()-price*$('#txt_new_pos_qty').val();
			 
			 // Format price
			 tp=Math.round(tp*10000)/(10000); 
			 stp=tp.toString().split('.');
			 $('#s_profit_1').html("+"+stp[0]);
			
			 if (parseFloat(stp[1])>0) 
			    $('#s_profit_2').html("."+stp[1]+" <? print $row['cur']; ?>");
			 else
			    $('#s_profit_2').html(".00"+" <? print $row['cur']; ?>");
		  }
		  
		  function init()
		  {
			  // Calculate sl and tp
			 if ($('#h_type').val()=="ID_BUY")
			 {
				 $('#txt_new_pos_sl').val(parseFloat($('#h_price').val())-(3*parseFloat($('#h_spread').val())));
				 $('#txt_new_pos_tp').val(parseFloat($('#h_price').val())+(3*parseFloat($('#h_spread').val())));
			 }
			 else
			 {
				 $('#txt_new_pos_sl').val(parseFloat($('#h_price').val())+(3*parseFloat($('#h_spread').val())));
				 $('#txt_new_pos_tp').val(parseFloat($('#h_price').val())-(3*parseFloat($('#h_spread').val())));
			 }
			 
			 recalculate();
		  }
		  
		 
		  
		  function recalculate()
		  {
			  // Calculate the margin
			  rec_margin();
			  
			  // Calculate losss
			  rec_loss();
			  
			  // Calculate profit
			  rec_profit();
			  
		  }
		  
		  $('#form_trade_modal').submit(
		  function() 
		  {   
		      $('#txt_new_pos_open').attr('disabled', false); 
		  });
		  
		  init();
		  
		</script>
           
        <?
		$this->template->showModalFooter();
	}
	
	function showCloseModal()
	{
		$this->template->showModalHeader("close_modal", "Close Trade", "act", "close_trade", "close_posID", 0);
		?>
            
            <input name="h_close_margin" id="h_close_margin" value="0" type="hidden">
            <input name="h_close_cur" id="h_close_cur" value="0" type="hidden">
            
            <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/close.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >
                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14">You will receive</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fafafa" style="color:#b83c30">
                    <strong>
                    <span class="font_28" id="s_close_receive_1">0</span>
                    <span class="font_14" id="s_close_receive_2">.00</span>
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100%" height="30" align="center" valign="top" class="font_14"><table width="340" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Last Price</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900">
                    <strong>
					<span id="td_close_last_price" class="font_30">0.0000</span>
                  
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_close_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Percent</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
                <select id="dd_close_percent" name="dd_close_percent" class="form-control" style="width:150px" onchange="javascript:percent_change()">
                  <option selected value="5">5%</option>
                  <option value="10">10%</option>
                  <option value="25">25%</option>
                  <option value="50">50%</option>
                  <option value="75">75%</option>
                  <option value="100">100%</option>
                </select></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
        <script>
	 	  function percent_change()
		  {
			  var receive=(parseFloat($('#dd_close_percent').val())*parseFloat($('#h_close_margin').val()))/100; 
			  receive=Math.round(receive*10000)/10000;
			  $('#s_close_receive_1').text(receive.toString().split(".")[0]);
			  $('#s_close_receive_2').text("."+receive.toString().split(".")[1]+" "+$('#h_close_cur').val());
		  }
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
	
	function showChangeModal()
	{
		$this->template->showModalHeader("change_modal", "Change Trade", "act", "change_trade", "change_posID", 0);
		?>
          
          <input id="h_change_posID" name="h_change_posID" value="" type="hidden">
          <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/change.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >&nbsp;</td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100%" height="30" align="center" valign="top" class="font_14"><table width="340" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Last Price</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900">
                    <strong>
					<span id="td_change_last_price" class="font_30">0.0000</span>
                  
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_change_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="left"><strong>Stop Loss</strong></td>
                      <td align="left"><strong>Take Profit</strong></td>
                    </tr>
                    <tr>
                      <td><span class="simple_gri_14">
                        <input name="txt_change_sl" class="form-control" id="txt_change_sl" placeholder="0" style="width:150px" onchange="javascript:recalculate()"/>
                      </span></td>
                      <td><span class="simple_gri_14">
                      <input name="txt_change_tp" class="form-control" id="txt_change_tp" placeholder="100" style="width:150px"/>
                      </span></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
       
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
	
	function showPositions($mktID=0, $status="ID_MARKET", $target="all", $display=true)
	{
		// Close Modal
		$this->showCloseModal();
		
		// Change modal
		$this->showChangeModal();
		
		if ($target=="all")
		$query="SELECT fsmp.*, fsm.cur, fsm.real_symbol, fsm.last_price
		          FROM feeds_spec_mkts_pos AS fsmp 
				  JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				 WHERE fsm.mktID='".$mktID."' 
				   AND fsmp.status='".$status."'
			 ORDER BY fsmp.ID DESC LIMIT 0,25";
		
		if ($target=="mine")
		{
			if ($mktID>0)
		        $query="SELECT fsmp.*, fsm.cur, fsm.real_symbol, fsm.last_price
		                  FROM feeds_spec_mkts_pos AS fsmp 
				          JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				         WHERE fsm.mktID='".$mktID."' 
				           AND fsmp.status='".$status."' 
				           AND fsmp.adr IN (SELECT adr 
				                              FROM my_adr 
							                 WHERE userID='".$_REQUEST['ud']['ID']."'
				      ORDER BY fsmp.ID DESC LIMIT 0,25";
		   else
		       $query="SELECT fsmp.*, fsm.cur, fsm.real_symbol, fsm.last_price
		                 FROM feeds_spec_mkts_pos AS fsmp 
				         JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				        WHERE fsmp.status='".$status."' 
				          AND fsmp.adr IN (SELECT adr 
				                             FROM my_adr 
							                WHERE userID='".$_REQUEST['ud']['ID']."')
					 ORDER BY fsmp.ID DESC LIMIT 0,25";
		}
		
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
		   print "<div class='font_14' style='color:$990000'>No positions found</div>";
		   return false;
		}
		
		?>
           
           <br>
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Address</th>
           <th class="font_14" height="35px" align="center">Type</th>
           <th class="font_14" height="35px" align="center">Invested</th>
           <th class="font_14" height="35px" align="center">P/L</th>
           <th class="font_14" height="35px" align="center"><? if ($status=="ID_PENDING") print "Open"; ?></th>
           <th class="font_14" height="35px" align="center"></th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
				  $data=$data.",'".$row['posID']."'";
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="30%">
                 <a href="#" class="font_14">
				 <? 
				    if ($mktID==0)
					   print $row['real_symbol'];
					else
				       print $this->template->formatAdr($row['adr']); 
			     ?>
                 </a>
                
                 <p class="font_10"><? print "Open : ".round($row['open'], 4).",  SL : ".round($row['sl'], 4).", TP : ".round($row['tp'], 4); ?></p>
                 </td>
                 
                 <td class="font_14" width="10%" style="color:<? if ($row['tip']=="ID_BUY") print "#009900"; else print "#990000"; ?>">
				 <? if ($row['tip']=="ID_BUY") print "Buy"; else print "Sell"; ?>
                 </td>
                 
                 <td class="font_14" width="15%">
				 <?
				     if ($row['status']=="ID_CLOSED")
					    $margin=$row['closed_margin'];
					 else 
					    $margin=$row['margin'];
						 
				     print round($margin, 4)." ".$row['cur']; 
				 ?>
                 </td>
                 
                 <td class="font_14" width="20%" id="<? 
				     if ($row['status']=="ID_MARKET") 
					    print "td_pos_".$row['posID']; 
					 else 
					    print "td_pos_closed_".$row['posID']; 
				 ?>" style="color:
				 <?
				     if ($row['status']=="ID_CLOSED")
					    $pl=$row['closed_pl'];
					 else 
					    $pl=$row['pl'];
											 
						
				     if ($pl>0) 
						print "#009900"; 
					 else 
						print "#990000";
				 ?>" align="center">
                 <strong>
				 <?
				    if ($pl>0) 
				       print "+".round($pl, 8)." ".$row['cur'];
				  	else
					   print round($pl, 8)." ".$row['cur']; 
					 
				 ?>
                 </strong>
                 </td>
                 
                  <td class="font_14" width="15%" id="
				  <?
				    if ($row['status']=="ID_MARKET") 
					       print "td_pos_proc_".$row['posID']; 
					    else 
					       print "td_pos_proc_closed_".$row['posID']; 
					 
					
				 ?>" style="color:
				 <? 
				     if ($status!="ID_PENDING")
					 {
				        if ($pl>0) 
					      print "#009900"; 
					    else 
					      print "#990000";
					 }
				 ?>" 
                 align="center">
                 <strong>
				 <?
				    if ($status!="ID_PENDING")
					{
				       if ($pl>0) 
				          print "+".round($pl*100/$margin, 2)." %";
					   else
					     print round($pl*100/$margin, 2)." %";
					}
					else print round($row['open'], 8)." ".$row['cur'];
				 ?>
                 </strong>
                 </td>
                
                 <?
				    if ($target=="all")
					{
				 ?>
                 
                       <td class="font_16" width="10%">
                       <a href="market.php?ID=<? print $row['mktID']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Story</a>
                       </td>
                
                <?
					}
					else
					{
						?>
                        
                        <td class="font_16" width="5%">
                        <div style="height:10px">&nbsp;</div>
                       <div class="btn-group">
                       <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle btn-sm" type="button">
                       <span class="glyphicon glyphicon-cog"></span>
                       <span class="caret"></span></button>
                       <ul role="menu" class="dropdown-menu">
                       <li><a href="javascript:void(0)" onClick="$('#h_close_margin').val('<? print $row['margin']+$row['pl']; ?>'); 
                                                                $('#h_close_cur').val('<? print $row['cur']; ?>'); 
                                                                $('#td_close_last_price').text('<? print $row['last_price']; ?>');
                                                                $('#close_posID').val('<? print $row['posID']; ?>');
                                                                $('#close_modal').modal(); percent_change();">Close Position</a></li>
                       
                       <li><a href="javascript:void(0)" onclick="$('#change_modal').modal(); 
                                                                 $('#td_change_last_price').text('<? print $row['last_price']; ?>');
                                                                 $('#h_change_posID').val('<? print $row['posID']; ?>');
                                                                 $('#txt_change_sl').val('<? print $row['sl']; ?>');
                                                                 $('#txt_change_tp').val('<? print $row['tp']; ?>');">Change Position</a></li>
                       <li><a href="story.php?posID=<? print $row['posID']; ?>">Trade Story</a></li>
                       </ul>
                       </div>
                        </td>
                        
                        <?
					}
				?>
                 
                 </tr>
                 <tr><td colspan="8"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
		$this->template->showStreaming("get_pos", substr($data, 1, strlen($data)));
	}
	
	function showPosSel($status="ID_MARKET")
	{
		?>
          
          <table width="90%">
          <tr><td align="right">
          <div class="btn-group" role="group" align="right">
          
          <a href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_MARKET" type="button" class="btn <? if ($status=="ID_MARKET") print "btn-primary"; else print "btn-default"; ?>">Open</a>
          
          <a href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_PENDING" type="button" class="btn <? if ($status=="ID_PENDING") print "btn-primary"; else print "btn-default"; ?>">Pending</a>
          
          <a href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_CLOSED" type="button" class="btn <? if ($status=="ID_CLOSED") print "btn-primary"; else print "btn-default"; ?>">Closed</a> 
          
          </div>
          </td></tr>
          </table>
          <br>
        
        <?
	}
	
	function showChart($feed, $branch, $start_block, $end_block, $spread=0)
	{
		$query="SELECT COUNT(*) AS total
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."' 
				   AND block>".$start_block." 
				   AND block<".$end_block; 
				   
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$total=$row['total'];
		
		$query="SELECT AVG(val) AS val
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."' 
				   AND block>=".$start_block." 
				   AND block<=".$end_block." GROUP BY ROUND(block/".($total/25).")"; 
				   
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
		    $a=0;
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
			  $a++;
			  if ($a==1) 
			     print "['', ".($row['val']+$spread)."],";
			  else
			     print "['', ".$row['val']."],";
			}
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
           <table width="90%">
           <tr>
           <td width="90%">
           <div id="curve_chart" style="width: 100%; height: 400px"></div>
           </td>
           </tr>
           </table>
           
           <br><br>
        
        <?
	}
	
	function showTradeStory($tradeID)
	{
		$query="SELECT fsmp.*, 
		               fsm.cur, 
					   fsm.feed_1, 
					   fsm.branch_1, 
					   fsm.real_symbol, 
					   fsm.last_price
		          FROM feeds_spec_mkts_pos AS fsmp 
				  JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				 WHERE fsmp.posID='".$tradeID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
           <table width="90%" class="table-responsive">
           <tr>
           <td width="25%">
 
           <div class="panel panel-default">
           <div class="panel-heading" style="font-size:14px">Type</div>
           <div class="panel-body">
           <?
		      if ($row['tip']=="ID_BUY")
			    print "<span style='color:#009900'>BUY</span>";
			  else
			    print "<span style='color:#990000'>SELL</span>"; 
		   ?>
           </div>   
           </div>
 
           </td>
           <td>&nbsp;&nbsp;&nbsp;</td>
           <td width="25%">
 
           <div class="panel panel-default">
           <div class="panel-heading" style="font-size:14px">Invested (<? print $row['cur']; ?>)</div>
           <div class="panel-body">
           <? print round($row['margin'], 4); ?>
           </div>
           </div>
 
           </td>
           
           <td>&nbsp;&nbsp;&nbsp;</td>
           <td width="25%">
 
           <div class="panel panel-default">
           <div class="panel-heading" style="font-size:14px">Profit (<? print $row['cur']; ?>)</div>
           <div class="panel-body" id="td_pos_<? print $row['posID']; ?>">
           <?
		      if ($row['pl']>0)
			    print "<span style='color:#009900'>+".$row['pl']."</span>";
			  else
			    print "<span style='color:#990000'>".$row['pl']."</span>"; 
		   ?>
           </div>
           </div>
 
           </td>
           
           <td>&nbsp;&nbsp;&nbsp;</td>
           <td width="25%">
 
           <div class="panel panel-default">
           <div class="panel-heading" style="font-size:14px">Profit (%)</div>
           <div class="panel-body" id="td_pos_proc_<? print $row['posID']; ?>">
           <?
		      if ($row['pl']>0)
			    print "<span style='color:#009900'>+".round($row['pl']*100/$row['margin'], 2)."%</span>";
			  else
			    print "<span style='color:#990000'>".round($row['pl']*100/$row['margin'], 2)."%</span>"; 
		   ?>
           </div>
           </div>
 
           </td>
           </tr>
           </table>
           <br>
           
           
           <?
		       if ($row['tip']=="ID_BUY")
			      $spread=$row['spread'];
			   else
			      $spread=-$row['spread'];
				  
		       $this->showChart($row['feed_1'], 
			                   $row['branch_1'], 
							   $row['block'], 
							   $row['last_block'], 
							   $spread);
							   
		   ?>
           
           <table class="table-responsive" width="95%">
           <tr>
            <td width="30%" class="font_12" align="center">Market ID&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['mktID']; ?></strong></td>
            <td width="40%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			<strong><? print $this->template->formatAdr($row['adr']); ?></strong></a></td>
             <td width="30%" align="center"><span class="font_12">Started</span>&nbsp;&nbsp;&nbsp;&nbsp;<font class="font_12">
             <strong>
			 <? 
			    print "~".$this->getTime($_REQUEST['sd']['last_block']-$row['block'])." ago"; 
			?>
            </strong></font></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" class="font_12" align="center">Position ID&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['posID']; ?></strong>
            </td>
             <td width="33%" align="center"><span class="font_12">Leverage&nbsp;&nbsp;&nbsp;&nbsp;
			 <strong><? print "x".$row['leverage']; ?></strong></span>&nbsp;&nbsp;</td>
            <td width="33%" class="font_12" align="center">Position Size&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['qty'], 4)." ".$row['real_symbol']; ?></strong>&nbsp;&nbsp;</td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" align="center"><span class="font_12">Open&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>
            <?
			  print round($row['open'],  8);
			?>
            </strong>
            </span></td>
            <td width="33%" class="font_12" align="center">Stop Loss&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['sl'], 8); ?></strong></td>
            <td width="33%" class="font_12" align="center">Take Profit&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['tp'], 8); ?></strong></td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            <tr>
            <td width="33%" class="font_12" align="center">Status&nbsp;&nbsp;&nbsp;&nbsp; 
            <strong>
			<?
			   switch ($row['status'])
			   {
				   case "ID_MARKET" : print "Open"; break;
				   case "ID_PENDING" : print "Pending"; break;
				   case "ID_CLOSED" : print "Closed"; break;
			   }
			?>
            </strong>
            </td>
            <td width="33%" class="font_12" align="center">Start Block &nbsp;&nbsp;&nbsp;&nbsp; 
			<span class="font_12"><strong><? print $row['block']; ?></strong></span></td>
            <td width="33%" class="font_12" align="center">Last Price&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['last_price'], 8); ?></strong></td>
            </tr>
            
         
            </table>
            <br><br><br><br><br><br>
           
        
        <?
		 
		$this->template->showStreaming("get_pos", $row['posID']);
	}
	
}
?>