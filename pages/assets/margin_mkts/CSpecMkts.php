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
	
	
	
	function newTrade($net_fee_adr, 
					  $adr, 
					  $mktID, 
					  $tip, 
					  $ex_type,
					  $open, 
					  $sl, 
					  $tp, 
					  $leverage, 
					  $qty,
					  $days)
	{
		// Check addresses
		if (!$this->kern->canSpend($net_fee_adr))
		{
			$this->template->showErr("Network fee address or address can't spend funds");
			return false;
		}
		
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
		 
		 // Open
		 $open=$mkt_row['last_price'];
		 
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
		 $query="SELECT SUM(margin) AS total 
		           FROM feeds_spec_mkts_pos AS fsmp 
				   JOIN feeds_spec_mkts AS fsm ON fsmp.mktID=fsm.mktID 
				  WHERE fsm.adr='".$mkt_row['adr']."' 
				    AND fsmp.status='ID_MARKET'"; 
		 $result=$this->kern->execute($query);	
	     $row = mysql_fetch_array($result, MYSQL_ASSOC);
	     
		 
		 // Address balance
		 $balance=$this->kern->getBalance($mkt_row['adr'], $mkt_row['cur']); 
		 
		 // Maximum margin
		 $max_total_margin=$mkt_row['max_total_margin']*$balance/100; 
		 
		 // Check margin
		 if (($row['total']+$margin)>$max_total_margin)
		 {
			 $this->template->showErr("Invalid margin. Maximum allowed margin is ".($max_total_margin-$row['total']));
			 return false; 
		 }
		 
		 // Minimum margin
		if ($margin<0.00000001) $margin=0.00000001;
		
		if ($mkt_row['cur']=="MSK" && 
		    $margin<0.0001)
		$margin=0.0001;
		
		 // Days
		 if ($days<1)
		 {
			 $this->template->showErr("Minimum days is 1");
			 return false; 
		 }
		 
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
								par_3='".$open."',
								par_4='".$sl."',
								par_5='".$tp."',
								par_6='".$leverage."',
								par_7='".$qty."',
								par_8='".$ex_type."',
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
	
	function newMarket($net_fee_adr, 
	                   $mkt_adr, 
					   $feed, 
					   $branch, 
					   $cur, 
					   $max_leverage,
					   $spread, 
					   $days,
					   $max_total_margin,
					   $title,
					   $desc)
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
		if ($this->feedExist($feed, $branch)==false)
		{
			 $this->template->showErr("Invalid feed 1");
			 return false;
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
		
		// Maximum leverage
		if ($max_leverage>1000)
		{
			 $this->template->showErr("Maximum leverage is 1000");
			 return false;
		}
		
		// Spread
		if ($spread<0.00000001)
		{
			 $this->template->showErr("Invalid spread");
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
		 
		 // Max total margin
		 if ($max_total_margin>25 || $max_total_margin<0.0001)
		 {
			  $this->template->showErr("Invalid total maximum margin (0.0001% - 25%)");
			  return false;
		 }
		 
		 // Days
		 if ($days<100)
		 {
			  $this->template->showErr("Invalid days. Minimum 100 days required.");
			  return false;
		 }
		
		 // Another market exist with other currency ?
		 $query="SELECT * 
		           FROM feeds_spec_mkts 
				  WHERE adr='".$mkt_adr."' 
				    AND cur<>'".$cur."'";
		 $result=$this->kern->execute($query);	
		 
		 if (mysql_num_rows($result)>0)
		 {
			  $this->template->showErr("Another market using another currency is attached to this address.");
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
							    op='ID_NEW_SPEC_MARKET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$mkt_adr."',
								par_1='".$feed."',
								par_2='".$branch."',
								par_3='".$cur."',
								par_4='".$max_leverage."',
								par_5='".$max_total_margin."',
								par_6='".$spread."',
								par_7='".base64_encode($title)."',
								par_8='".base64_encode($desc)."',
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
	
	
	
	
	
	
	function showNewMktBut()
	{
		$this->showNewMktModal();
		
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="#" onClick="javascript:$('#new_mkt_modal').modal()" class="btn btn-primary">
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
	
	
	
	function showMarkets($mine, $type="ID_CRYPTO", $search="", $cur="MSK")
	{
		if ($mine==false)
		$query="SELECT fsm.*, adr.balance AS mkt_adr_balance, fb.type
		          FROM feeds_spec_mkts AS fsm 
				  JOIN feeds_branches AS fb ON (fb.feed_symbol=fsm.feed AND fb.symbol=fsm.branch)
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE fb.type='".$type."'"; 
		else
	    $query="SELECT fsm.*, adr.balance AS mkt_adr_balance
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE fsm.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."') 
				   AND fsm.type='".$type."'";
				 
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
		// QR modal
		$this->template->showQRModal();
		
		$query="SELECT fsm.*, 
		               fb.val AS price
				  FROM feeds_spec_mkts AS fsm
				  LEFT JOIN feeds_branches AS fb ON (fb.feed_symbol=fsm.feed AND 
				                                     fb.symbol=fsm.branch)
				 WHERE mktID='".$mktID."'"; 
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Market not found");
			return false;
		}
		
		// Load data
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
            <td width="33%" class="font_12" align="center">Market ID&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['mktID']; ?></strong></td>
            <td width="40%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			<strong><? print $this->template->formatAdr($row['adr']); ?></strong></a>
            </td>
             <td width="33%" align="center"><span class="font_12">Expires&nbsp;&nbsp;&nbsp;&nbsp; <strong>~ <? print $this->getTime($row['expire']-$_REQUEST['sd']['last_block']); ?></strong></span></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" class="font_12" align="center">Max Leverage&nbsp;&nbsp;&nbsp;&nbsp; <strong><? print "x".$row['max_leverage']; ?></strong>&nbsp;&nbsp;</td>
             <td width="40%" align="center"><span class="font_12">Maximum Total Margin&nbsp;&nbsp;&nbsp;&nbsp; <strong><? print round($row['max_total_margin'], 8)."%"; ?></strong></span></td>
            <td width="33%" class="font_12" align="center">Data Feed&nbsp;&nbsp;&nbsp;&nbsp; <a href="../../assets/feeds/branch.php?feed=<? print $row['feed']; ?>&symbol=<? print $row['branch'] ?>" class="font_12"><strong><? print $row['feed']." / ".$row['branch']; ?></strong></a></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
           
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
		$query="SELECT fsm.*, adr.balance AS mkt_adr_balance, fb.val
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				  JOIN feeds_branches AS fb ON (fb.feed_symbol=fsm.feed AND 
				                                fb.symbol=fsm.branch)
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Market not found");
			return false;
		}
		
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
            
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table>
            <tr>
            <td width="25%" valign="top" align="center"><span class="font_10">Last Price</span><br><span class="font_20">
			<? print round($row['last_price'], 8); ?></span></td>
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
		// Logged in ?
		if (!isset($_REQUEST['ud']['ID'])) return false;
		
		// Load data
		$query="SELECT *
		          FROM feeds_spec_mkts AS fsm
				  JOIN feeds_branches AS feed ON (feed.feed_symbol=fsm.feed
				                                    AND feed.symbol=fsm.branch)
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		?>
        
        <br>
        <table width="90%">
        <tr>
        
        <td width="90%"><a target="_blank" href="../feeds/chart.php?symbol=<? print $row['rl_symbol']; ?>" class="btn btn-default">Pro Chart</a></td>
        <td width="10%">
        
        <?
		   if ($row['mkt_status']=="online")
		   {
		?>
        
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
        
        <?
		   }
		   else print "<span class=\"label label-danger\">Trading Halted</span>";
		?>
        
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
					<span class="font_30" ><? print explode(".", round($row['last_price'], 8))[0]; ?></span>
                    <span class="font_20" ><? print ".".explode(".", round($row['last_price'], 8))[1]." ".$row['cur']; ?></span>
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
                <input class="form-control" placeholder="0.00" id="txt_new_pos_open" name="txt_new_pos_open" style="width:150px" value="<? print round($row['last_price'], 8); ?>"  onchange="javascript:recalculate()" disabled/></td>
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
              <tr>
                <td height="0" align="left" class="font_14"><strong>Expire ( days )</strong></td>
                <td height="0" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="0" align="left" class="simple_gri_14"><input class="form-control" placeholder="10" id="txt_new_pos_days" name="txt_new_pos_days" style="width:150px" onchange="javascript:recalculate()"/></td>
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
	
	
	function showPositions($mktID=0, $status="ID_MARKET", $target="all", $display=true)
	{
		if ($target=="all")
		{
			if ($mktID>0)
		    $query="SELECT fsmp.*, fsm.cur, fsb.rl_symbol, fsm.last_price
		              FROM feeds_spec_mkts_pos AS fsmp 
				      JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
					  JOIN feeds_branches AS fsb ON (fsb.feed_symbol=fsm.feed AND fsb.symbol=fsm.branch)
				     WHERE fsm.mktID='".$mktID."' 
				       AND fsmp.status='".$status."'
			      ORDER BY fsmp.pl DESC 
			         LIMIT 0,25";
			else
			 $query="SELECT fsmp.*, fsm.cur, fsb.rl_symbol, fsm.last_price
		              FROM feeds_spec_mkts_pos AS fsmp 
				      JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
					  JOIN feeds_branches AS fsb ON (fsb.feed_symbol=fsm.feed AND fsb.symbol=fsm.branch)
				     WHERE fsmp.status='".$status."'
			      ORDER BY fsmp.pl DESC 
			         LIMIT 0,25";
		}
		
		if ($target=="mine")
		{
			if ($mktID>0)
		        $query="SELECT fsmp.*, fsm.cur, fsb.rl_symbol, fsm.last_price
		                  FROM feeds_spec_mkts_pos AS fsmp 
				          JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
						  JOIN feeds_branches AS fsb ON (fsb.feed_symbol=fsm.feed AND fsb.symbol=fsm.branch)
				         WHERE fsm.mktID='".$mktID."' 
				           AND fsmp.status='".$status."' 
				           AND fsmp.adr IN (SELECT adr 
				                              FROM my_adr 
							                 WHERE userID='".$_REQUEST['ud']['ID']."')
				      ORDER BY fsmp.ID DESC 
					     LIMIT 0,25";
		   else
		       $query="SELECT fsmp.*, fsm.cur, fsb.rl_symbol, fsm.last_price
		                 FROM feeds_spec_mkts_pos AS fsmp 
				         JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
						 JOIN feeds_branches AS fsb ON (fsb.feed_symbol=fsm.feed AND fsb.symbol=fsm.branch)
				        WHERE fsmp.status='".$status."' 
				          AND fsmp.adr IN (SELECT adr 
				                             FROM my_adr 
							                WHERE userID='".$_REQUEST['ud']['ID']."')
					 ORDER BY fsmp.ID DESC 
					    LIMIT 0,25";
		}
		
		
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
		   print "<br><div class='font_14' style='color:#990000'>No positions found</div>";
		   return false;
		}
		
		?>
           
           <br>
           <div align="left"><span class="font_18">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Open Positions</span></div>
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
                       <a href="../../assets/margin_mkts/story.php?posID=<? print $row['posID']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Story</a>
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
	
	function showChart($feed, $branch, $start_block, $end_block, $open=0)
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
			  if ($a==1 && $open!=0) 
			     print "['', ".($open)."],";
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
		// QR modal
		$this->template->showQRModal();
		
		$query="SELECT fsmp.*, 
		               fsm.cur, 
					   fsm.feed, 
					   fsm.branch, 
					   fb.rl_symbol, 
					   fsm.last_price
		          FROM feeds_spec_mkts_pos AS fsmp 
				  JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				  JOIN feeds_branches AS fb ON (fb.feed_symbol=fsm.feed AND fb.symbol=fsm.branch)
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
		       if ($row['block_end']==0)
		       $this->showChart($row['feed'], 
			                   $row['branch'], 
							   $row['block_start'], 
							   $_REQUEST['sd']['last_block'], 
							   $row['open']);
			   
			   else
			   $this->showChart($row['feed'], 
			                   $row['branch'], 
							   $row['block_start'], 
							   $row['block_end'], 
							   $row['open']);		   
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
            <td width="33%" class="font_12" align="center">&nbsp;</td>
            <td width="33%" class="font_12" align="center">&nbsp;</td>
            </tr>
            
         
            </table>
            <br><br><br><br><br><br>
           
        
        <?
		 
		
	}
	
	function showMktChart($mktID)
	{
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."'"; 
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$this->showChart($row['feed'], 
		                 $row['branch'], 
						 $_REQUEST['sd']['last_block']-50000, 
						 $_REQUEST['sd']['last_block']);
	}
	
	function showNewMktModal()
	{
		$this->template->showModalHeader("new_mkt_modal", "New Speculative Market", "act", "new_mkt");
		  
		?>
              
              
           
              <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../../tweets/GIF/like.png" width="180" name="vote_img" id="vote_img"/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001", "trans"); ?></td>
             </tr>
           </table></td>
           <td width="30" align="right" valign="top">
           
           
           <table width="330" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_new_mkt_net_fee_adr");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showAllMyAdrDD("dd_new_mkt_adr");
			   ?>
               </td>
             </tr>
             <tr>
               <td align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               
               <table width="330" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="33%" height="30" align="left" valign="top" class="font_14"><strong>Feed Symbol</strong></td>
                   <td width="33%" align="left" valign="top" class="font_14"><strong>Feed Branch</strong></td>
                   <td width="33%" align="left" valign="top" class="font_14"><strong>Currency</strong></td>
                 </tr>
                 <tr>
                   <td><input class="form-control" id="txt_new_mkt_feed" name="txt_new_mkt_feed" placeholder="XXXXXX" style="width:100px"/></td>
                   <td align="left"><input class="form-control" id="txt_new_mkt_branch" name="txt_new_mkt_branch" placeholder="XXXXXX" style="width:100px"/></td>
                   <td><input class="form-control" id="txt_new_mkt_cur" name="txt_new_mkt_cur" placeholder="MSK" style="width:100px"/></td>
                 </tr>
               </table>
               
              
                      
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><table width="330" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="33%" height="30" align="left" valign="top" class="font_14"><strong>Max Leverage</strong></td>
                   <td width="33%" align="left" valign="top" class="font_14"><strong>Spread</strong></td>
                   <td width="33%" align="left" valign="top" class="font_14"><strong>Days</strong></td>
                 </tr>
                 <tr>
                   <td><input class="form-control" id="txt_new_mkt_max_leverage" name="txt_new_mkt_max_leverage" placeholder="100" style="width:100px"/></td>
                   <td align="left"><input class="form-control" id="txt_new_mkt_spread" name="txt_new_mkt_spread" placeholder="0.01" style="width:100px"/></td>
                   <td><input class="form-control" id="txt_new_mkt_days" name="txt_new_mkt_days" placeholder="100" style="width:100px"/></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><table width="330" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="33%" height="30" align="left" valign="top" class="font_14"><strong>Max Total Margin (%)</strong></td>
                 </tr>
                 <tr>
                   <td align="left"><input class="form-control" id="txt_new_mkt_max_total_margin" name="txt_new_mkt_max_total_margin" placeholder="10" style="width:100px"/></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><span class="font_14"><strong>Name</strong></span></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <input class="form-control" id="txt_new_mkt_title" name="txt_new_mkt_title" placeholder="Name (5-30 characters)" style="width:100%"/></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><span class="font_14"><strong>Short Description</strong></span></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <textarea rows="5" id="txt_new_mkt_desc" name="txt_new_mkt_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:100%"></textarea></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"></td>
             </tr>
             
           </table>
           
           
           </td>
         </tr>
     </table>
         
     
       
       
        <?
		
		$this->template->showModalFooter("Create");
	}
	}
?>