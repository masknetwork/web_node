<?
class CMktPeggedAssets
{
	function CMktPeggedAssets($db, $template, $asset)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->assets=$asset;
	}
	
	function newOrder($net_fee_adr,
                      $adr,
					  $sell_adr,
                      $asset, 
                      $tip, 
                      $qty,
					  $qty_asset)
	{
		// Qty
		if ($tip=="ID_SELL") $adr=$sell_adr;
		
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
		 
		 // Market address
		 if ($this->kern->adrValid($adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		 // Market ID
		 $query="SELECT * 
		           FROM assets
				  WHERE symbol='".$asset."' 
				    AND linked_mktID>0";
		 $result=$this->kern->execute($query);	
		 
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid asset");
			 return false;
		 }
		 
		 // Load data
		 $row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // Load market data
		 $query="SELECT * 
		           FROM feeds_assets_mkts
				  WHERE mktID='".$row['linked_mktID']."'";
		 $result=$this->kern->execute($query);	
		 
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid asset");
			 return false;
		 }
		 
		 // Load market data
		 $mkt_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // Tip
		 if ($tip!="ID_BUY" && $tip!="ID_SELL")
		 {
			 $this->template->showErr("Invalid order type");
			 return false;
		 }
		 
		 // Qty
		 if ($qty<0.00000001)
		 {
			 $this->template->showErr("Invalid qty");
			 return false;
		 }
	     
		 // Address balance currency
		 $adr_balance_cur=$this->kern->getBalance($adr, $mkt_row['cur']); 
		 
		 // Address balance assets
		 $adr_balance_assets=$this->kern->getBalance($adr, $mkt_row['asset_symbol']);
		 
		 // Market balance currency
		 $mkt_balance_cur=$this->kern->getBalance($mkt_row['adr'], $mkt_row['cur']); 
		 
		 // Market balance assets
		 $mkt_balance_assets=$this->kern->getBalance($mkt_row['adr'], $mkt_row['asset_symbol']); 
		 
		 // Price
		 $price=$qty*$mkt_row['last_price'];
		 
		 // Buy order
		 if ($tip=="ID_BUY")
		 {
			 // Funds 
			 if ($adr_balance_cur<$price) 
			 {
				  $this->template->showErr("Innsuficient funds to execute this operation");
			      return false;
			 }
			 
			 // Market funds
			 if ($mkt_balance_assets<$qty)
			 {
				  $this->template->showErr("Innsuficient market funds to execute this operation");
			      return false;
			 }
		 }
		 else
		 {
			 // Funds 
			 if ($adr_balance_assets<$qty) 
			 {
				  $this->template->showErr("Innsuficient funds to execute this operation");
			      return false;
			 }
			 
			 // Market funds
			 if ($mkt_balance_cur<$price)
			 {
				  $this->template->showErr("Innsuficient market funds to execute this operation");
			      return false;
			 }
		 }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new data feed");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_TRADE_PEGGED_ASSET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$mkt_row['mktID']."',
								par_2='".$tip."',
								par_3='".$qty."',
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
	
	function issueAsset($net_fee_adr, 
	                    $adr, 
						$feed_1, $branch_1, 
						$feed_2, $branch_2, 
						$feed_3, $branch_3, 
						$cur,
						$qty,
						$spread, 
						$rl_symbol, 
						$decimals, 
						$days, 
						$interest_interval, 
						$interest, 
						$asset_symbol,
						$trans_fee,
						$trans_fee_adr,
						$name, 
						$description, 
						$img)
	{
		// Addresses
		 $net_fee_adr=$this->kern->adrFromDomain($net_fee_adr);
		 $adr=$this->kern->adrFromDomain($adr);
		 $trans_fee_adr=$this->kern->adrFromDomain($trans_fee_adr);
		 
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
		 
		 // Market address
		 if ($this->kern->adrExist($adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		 // Net fee
		 if ($cur=="MSK")
		 {
		    $net_fee=0.0001*($qty+$days);
		 }
		 else
		 {
			// Load currency data
			$query="SELECT * FROM assets WHERE symbol='".$cur."'";
			$result=$this->kern->execute($query);	
	        
			// Currency does not exist
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Invalid currency");
			    return false;
			}
			
			// Load currency data
			$cur_row = mysql_fetch_array($result, MYSQL_ASSOC);
			 
			// User asset ?
		    if ($cur_row['linked_mktID']>0)
			   $net_fee=0.0001*($qty+$days);
			else
			   $net_fee=0.0001*$qty*$days;
		 }
		 
		 // Net fee funds
		 if ($this->kern->getBalance($adr, "MSK")<$net_fee)
		 {
			 $this->template->showErr("Insufficient funds to issue this asset");
			 return false;
		 }
		
		// Feed 1
		if ($this->kern->feedExist($feed_1, $branch_1)==false)
		{
			 $this->template->showErr("Invalid feed 1");
			 return false;
		}
		
		// Feed 2
		if ($feed_2!="")
		{
		  if ($this->kern->feedExist($feed_2, $branch_2)==false)
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
		
		// Feeds fees
		$feed_fee_1=0;
		$feed_fee_2=0;
		$feed_fee_3=0;
		
		// Load branch data
		$query="SELECT * 
		          FROM feeds_branches
				 WHERE feed_symbol='".$feed_1."'
				   AND symbol='".$branch_1."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Feed fee
		$feed_fee_1=$row['fee']*$days;
		
		// Average price
		$avg_price=$row['val']; 
		
		if ($feed_fee_2!="")
		{
		   // Load branch data
		   $query="SELECT * 
		             FROM feeds_branches 
				    WHERE feed_symbol='".$feed_2."'
				      AND symbol='".$branch_2."'";
		   $result=$this->kern->execute($query);	
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		   // Feed fee
		   $feed_fee_2=$row['fee']*$days;
		   
		   // Average price
		   $avg_price=($avg_price+$row['val'])/2;
		}
		
		if ($feed_fee_3!="")
		{
		   // Load branch data
		   $query="SELECT * 
		             FROM feeds_branches 
				    WHERE feed_symbol='".$feed_3."'
				      AND symbol='".$branch_3."'";
		   $result=$this->kern->execute($query);	
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		   // Feed fee
		   $feed_fee_3=$row['fee']*$days;
		   
		   // Average price
		   $avg_price=($avg_price+$row['val'])/2;
		}
		
		// Total fee
		$total_fee=$feed_fee_1+$feed_fee_2+$feed_fee_3;
		
		// Funds
		if ($this->kern->getBalance($adr, "MSK")<$total_fee)
		{
			$this->template->showErr("Innsuficient funds to pay feeds fees");
			return false;
		}
		
		// Qty
		if ($qty<1)
		{
			$this->template->showErr("Invaid qty");
			return false;
		}
		
		// Average price
		if ($avg_price<0.00000001)
		{
			$this->template->showErr("Invalid average price");
			return false;
		}
		
		// Collateral
		$colateral=$avg_price*$qty;
	    
		// Funds for colateral and feeds fees
		if ($this->kern->getBalance($adr, $cur)<($colateral*2+$total_fee))
		{
			$this->template->showErr("Insufficient funds for collateral");
			return false;
		}
	    
		// Spread
		if ($spread<0)
		{
			$this->template->showErr("Invalid spread");
			return false;
		}
		
		// RL symbol
		if (strlen($rl_symbol)>10)
		{
			$this->template->showErr("Invalid RL symbol");
			return false;
		}
		
		// Decimals
		if ($decimals>8 || $decimals<0)
		{
		   $this->template->showErr("Invalid decimals");
		   return false;	
		}
		
		// Days
		if ($days<365)
		{
			$this->template->showErr("Minimum days is 365");
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
		 
		 // Asset symbol
		 if ($this->kern->symbolValid($asset_symbol)==false)
		 {
			  $this->template->showErr("Invalid asset symbol");
		      return false;
		 }
		 
		 if ($this->kern->assetExist($asset_symbol)==true)
		 {
			 $this->template->showErr("Asset symbol already exist");
		     return false;
		 }
		 
		 // Transaction fee
		 if ($trans_fee<0 || $trans_fee>10)
		 {
			  $this->template->showErr("Invalid transaction fee (0-10%)");
		      return false;
		 }
		 
		 // Trans fee adr
		 if ($trans_fee>0)
		 {
			 if ($this->kern->adrValid($trans_fee_adr)==false)
			 {
				  $this->template->showErr("Invalid fee address");
		          return false;
			 }
		 }
	     
		 // Name
		 $name=base64_decode($name); 
		 if (strlen($name)<5 || strlen($name)>50)
		 {
			 $this->template->showErr("Invalid title length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 $description=base64_decode($description);
		 if (strlen($description)>250)
		 {
			 $this->template->showErr("Invalid description length (50-250 characters)");
			 return false;
		 }
		 
		 // Image
		 if ($img!="")
		 {
			// Decode
		    $img=base64_decode($img); 
		    
			// Valid ?
			if ($this->kern->isImageLink($img)==false)
		    {
			    $this->template->showErr("Invalid image");
			    return false;
		    }
		 }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new data feed");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ISSUE_PEGGED_ASSET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$feed_1."',
								par_2='".$branch_1."',
								par_3='".$feed_2."',
								par_4='".$branch_2."',
								par_5='".$feed_3."',
								par_6='".$branch_3."',
								par_7='".$cur."',
								par_8='".$qty."',
								par_9='".$spread."',
								par_10='".$rl_symbol."',
								par_11='".$decimals."',
								par_12='".$interest_interval."',
								par_13='".$interest."',
								par_14='".$asset_symbol."',
								par_15='".$trans_fee."',
								par_16='".$trans_fee_adr."',
								par_17='".base64_encode($name)."',
								par_18='".base64_encode($description)."',
								par_19='".base64_encode($img)."',
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
	
	function showNewAssetBut()
	{
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="issued.php?act=show_panel" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Issue Asset
         </a>
         </td></tr>
</table>
         <br>
         
         <?
	}
	
	function showNewAssetPanel()
	{
		?>
        
        <form id="form_issued_asset" name="form_issued_asset" action="issued.php?act=issue_asset" method="post">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
             <tr>
               <td width="25%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td align="center"><img src="GIF/issue_asset.png" width="150" class="img img-responsiv img-circle" /></td>
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
                   <td align="left"><? $this->template->showMyAdrDD("dd_issue_asset_net_fee_adr", "100%"); ?></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Market Address</strong></td>
                 </tr>
                 <tr>
                   <td align="left"><? $this->template->showMyAdrDD("dd_issue_asset_adr", "100%"); ?></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="23%" height="30" align="left" valign="top" class="font_14"><strong>Feed Symbol</strong></td>
                       <td width="2%" align="left" valign="top" class="font_14">&nbsp;</td>
                       <td width="23%" align="left" valign="top" class="font_14"><strong>Feed Branch</strong></td>
                        <td width="23%" align="left" valign="top" class="font_14"><strong>Currency</strong></td>
                        <td width="23%" align="left" valign="top" class="font_14"><strong>Initial Qty</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_issue_asset_feed_1" name="txt_issue_asset_feed_1" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_issue_asset_branch_1" name="txt_issue_asset_branch_1" placeholder="XXXXXX" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_cur" name="txt_issue_asset_cur" placeholder="MSK" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_qty" name="txt_issue_asset_qty" placeholder="1000" style="width:90%"/></td>
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
                       <td><input class="form-control" id="txt_new_mkt_feed_2" name="txt_issue_asset_feed2" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_new_mkt_branch_2" name="txt_issue_asset_branch_2" placeholder="XXXXXX" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_feed_3" name="txt_issue_asset_feed_3" placeholder="XXXXXX" style="width:90%"/></td>
                       <td align="center">-&nbsp;&nbsp;</td>
                       <td><input class="form-control" id="txt_issue_asset_branch_3" name="txt_issue_asset_branch_3" placeholder="XXXXXX" style="width:90%"/></td>
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
                       <td width="25%" height="30" align="left" valign="top" class="font_14"><strong>Spread</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Real World Symbol</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Decimals</strong></td>
                       <td width="25%" align="left" valign="top" class="font_14"><strong>Days</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_issue_asset_spread" name="txt_issue_asset_spread" placeholder="0.0001" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_real_symbol" name="txt_issue_asset_real_symbol" placeholder="AAPL" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_decimals" name="txt_issue_asset_decimals" placeholder="5" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_days" name="txt_issue_asset_days" placeholder="1000" style="width:90%"/></td>
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
                       <td width="74%" height="30" align="left" valign="top" class="font_14"><strong>Interest Interval</strong></td>
                       <td width="26%" align="left" valign="top" class="font_14"><strong>Interest (%)</strong></td>
                     </tr>
                     <tr>
                       <td><select id="dd_issue_asset_interest_int" name="dd_issue_asset_interest_int" class="form-control" style="width:95%">
                         <option value="ID_HOURLY">Hourly</option>
                         <option value="ID_DAILY">Daily</option>
                         <option value="ID_WEEKLY">Weekly</option>
                         <option value="ID_MONTHLY">Monthly</option>
                         <option value="ID_MONTH_3">Every 3 Months</option>
                         <option value="ID_MONTH_6">Every 6 Months</option>
                         <option value="ID_YEARLY">Yearly</option>
                       </select></td>
                       <td><input class="form-control" id="txt_issue_asset_interest" name="txt_issue_asset_interest" placeholder="1" style="width:90%"/></td>
                     </tr>
                   </table>
                   </td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="21%" height="30" align="left" valign="top" class="font_14"><strong>Trans Fee (%)</strong></td>
                       <td width="79%" align="left" valign="top" class="font_14"><strong>Fee Address</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_issue_trans_fee" name="txt_issue_trans_fee" placeholder="0" style="width:90%"/></td>
                       <td><input class="form-control" id="txt_issue_trans_fee_adr" name="txt_issue_trans_fee_adr" placeholder="" style="width:95%"/></td>
                     </tr>
                   </table></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td width="74%" height="30" align="left" valign="top" class="font_14"><strong>Asset Name</strong></td>
                       <td width="26%" align="left" valign="top" class="font_14"><strong>Asset Symbol</strong></td>
                     </tr>
                     <tr>
                       <td><input class="form-control" id="txt_issue_asset_title" name="txt_issue_asset_title" placeholder="Name (5-30 characters)" style="width:95%"/></td>
                       <td><input class="form-control" id="txt_issue_asset_symbol" name="txt_issue_asset_symbol" placeholder="XXXXXX" style="width:90%"/></td>
                     </tr>
                   </table></td>
                 </tr>
                 <tr>
                   <td align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Asset Description</strong></td>
                 </tr>
                 <tr>
                   <td align="left">
                   <textarea rows="5" id="txt_issue_asset_desc" name="txt_issue_asset_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:100%"></textarea></td>
                 </tr>
                 <tr>
                   <td align="left">
                   
                 
                   </td>
                 </tr>
                 <tr>
                   <td align="left"></td>
                 </tr>
               
                 <tr>
                   <td align="right" class="font_14"></td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
                 </tr>
                 <tr>
                   <td height="30" align="left" valign="top" class="font_14"><strong>Image</strong></td>
                 </tr>
                 <tr>
                   <td height="30" align="left" class="font_14">
                   <input class="form-control" id="txt_issue_asset_img" name="txt_issue_asset_img" placeholder="Web Link (optional)" style="width:99%"/></td>
                 </tr>
                 <tr>
                   <td align="right" class="font_14"><hr></td>
                 </tr>
                 <tr>
                   <td height="30" align="right" class="font_14"><a hef="javascript:void(0)" onClick="$('#form_issued_asset').submit()" class="btn btn-primary">Submit</a></td>
                 </tr>
               </table></td>
             </tr>
           </tbody>
         </table>
         </form>
         <br><br>
        
         <script>
		   $('#form_issued_asset').submit(
		   function() 
		   { 
		      $('#txt_issue_asset_title').val(btoa($('#txt_issue_asset_title').val())); 
		      $('#txt_issue_asset_desc').val(btoa($('#txt_issue_asset_desc').val())); 
			  $('#txt_issue_asset_img').val(btoa($('#txt_issue_asset_img').val())); 
		   });
		</script>
        
        <?
		
	}
	
	function showAssets($target, $search="", $cur="MSK")
	{
		$query="SELECT ass.*, 
		               adr.balance, 
					   fam.cur, 
					   ao.qty AS balance_asset,
					   fb.type
		          FROM assets AS ass
				  JOIN adr ON adr.adr=ass.adr
				  JOIN feeds_assets_mkts AS fam ON fam.adr=ass.adr
				  JOIN feeds_branches AS fb ON (fb.feed_symbol=fam.feed_1 AND fb.symbol=fam.branch_1)
			 LEFT JOIN assets_owners AS ao ON (ao.owner=ass.adr AND ao.symbol=fam.cur)
				 WHERE linked_mktID>0 
				 AND fb.type='".$target."'
			  ORDER BY ID DESC 
			     LIMIT 0,25"; 
		$result=$this->kern->execute($query);	
	
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Colaterall</th>
           <th class="font_14" height="35px" align="center">Issued</th>
           <th class="font_14" height=\"35px\" align=\"center\">Trade</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%">
                 <img class="img img-responsive img-circle" src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic']); ?>">
                 </td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      if ($row['cur']=="MSK")
				         print "<span class='font_14'>".round($row['balance'], 2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
					  else
					     print "<span class='font_14'>".round($row['balance_asset'], 2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%">
				 <? print "<span class='font_14'>".round($row['qty'], 2)."</span> <span class='font_10'>".$row['symbol']."</span>";  ?></td>
                
                 
                <td class="font_16" width="10%"><a href="asset.php?symbol=<? print $row['symbol']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Trade</a></td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showIssuedAssets()
	{
		$query="SELECT ass.*, adr.balance, fam.cur 
		          FROM assets AS ass
				  JOIN adr ON adr.adr=ass.adr
				  JOIN feeds_assets_mkts AS fam ON fam.adr=ass.adr
				 WHERE linked_mktID>0 
				   AND ass.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."')
			  ORDER BY ID DESC 
			     LIMIT 0,25";
		$result=$this->kern->execute($query);	
	 
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Colaterall</th>
           <th class="font_14" height="35px" align="center">Issued</th>
           <th class="font_14" height=\"35px\" align=\"center\">Trade</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%">
                 <img class="img img-responsive img-circle" src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic']); ?>">
                 </td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print "<span class='font_14'>".round($row['balance'],2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%">
				 <? print "<span class='font_14'>".round($row['qty'],2)."</span> <span class='font_10'>".$row['symbol']."</span>";  ?></td>
                
                 
                <td class="font_16" width="10%"><a href="asset.php?symbol=<? print $row['symbol']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Trade</a></td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showMyAssets()
	{
		$query="SELECT aso.*, 
		               fam.cur,
					   fam.last_price,
					   ass.title,
					   ass.pic
		          FROM assets_owners AS aso
				  JOIN assets AS ass ON ass.symbol=aso.symbol
				  JOIN feeds_assets_mkts AS fam ON fam.asset_symbol=aso.symbol
				 WHERE ass.linked_mktID>0  
				   AND aso.owner IN (SELECT adr 
				                       FROM my_adr 
									  WHERE userID='".$_REQUEST['ud']['ID']."')
				   AND aso.owner<>ass.adr
			  ORDER BY aso.ID DESC 
			     LIMIT 0,25"; 
		$result=$this->kern->execute($query);	
	 
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Asset</th>
           <th class="font_14" height="35px" align="center">Last Price</th>
           <th class="font_14" height="35px" align="center">Gain</th>
           <th class="font_14" height="35px" align="center">Qty</th>
           <th class="font_14" height=\"35px\" align=\"center\">Operations</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%">
                 <img class="img img-responsive img-circle" src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic']); ?>">
                 </td>
                 <td>&nbsp;</td>
                 <td width="30%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print "Owner : ".$this->template->formatAdr($row['owner']); ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print "<span class='font_14'>".round($row['last_price'], 2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
			     ?>
                 </td>
                 
                 <? 
				     // Value
					 $val=$row['last_price']*$row['qty']; 
					 
					 // Gain
					 $gain=100-round($val*100/$row['invested'], 2); 
					
					// Gain ?
					if ($val<$row['invested']) $gain=-$gain; 
				 ?>
                 
                 <td class="font_14" width="10%" style="color:<? if ($gain<0) print "#990000"; else print "#009900"; ?>">
				 <? 
				     if ($gain>0) 
					    print "+".$gain."%";
					 else
					    print $gain."%"; 
				 ?>
                 </td>
                 
                 <td class="font_14" width="15%">
				 <? 
				     print "<span class='font_14'><strong>".round($row['qty'], 4)."</span> <span class='font_10'>".$row['symbol']."</strong></span>"; 
				 ?>
                 </td>
                
                 
                <td class="font_16" width="10%">
                <table>
                <tr>
                <td><a href="asset.php?symbol=<? print $row['symbol']; ?>" class='btn btn-warning btn-sm' style="color:#000000; width:75px">Trade</a></td>
                <td>&nbsp;</td>
                <td><a href="javascript:void(0)" onclick="$('#send_coins_modal').modal(); $('#tab_msk').css('display', 'none'); $('#tab_assets').css('display', 'block'); $('#txt_cur').val('<? print $row['symbol']; ?>'); $('#dd_from').val('<? print $row['owner']; ?>');" class='btn btn-inverse btn-sm' style=" width:75px">Transfer</a></td>
                </tr>
                </table>
                </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showPanel($symbol)
	{
		// Modal
		$this->showTradeModal();
		
		
		$query="SELECT ass.*, fam.*
		          FROM assets AS ass
				  JOIN feeds_assets_mkts AS fam ON fam.adr=ass.adr
				 WHERE symbol='".$symbol."'";
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
            <td width="83%" valign="top"><span class="font_16"><strong><? print base64_decode($row['title']); ?></strong></span>
            <p class="font_14"><? print base64_decode($row['description']); ?></p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table class="table-responsive" width="100%">
             <tr>
            <td width="30%" align="center"><span class="font_12">Symbol&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['symbol']; ?></strong></span></td>
            <td width="40%" class="font_12" align="center">Available&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['qty']; ?> units</strong></td>
            <td width="30%" class="font_12" align="center">Transaction Fee&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['trans_fee']."%"; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><a class="font_12" href="#"><? print $this->template->formatAdr($row['adr']); ?></a></strong></td>
            <td width="40%" class="font_12" align="center">Issued&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></strong></td>
            <td width="30%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center"><span class="font_12">Fee</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><a class="font_12" href="#"><? print $this->template->formatAdr($row['trans_fee_adr']); ?></a></strong></td>
            <td width="40%" class="font_12" align="center">Can Issue More&nbsp;&nbsp;&nbsp;&nbsp;<strong><? if ($row['can_increase']=="Y") print "YES"; else print "NO"; ?></strong></td>
            <td width="30%" class="font_12" align="center">Colaterall&nbsp;&nbsp;&nbsp;&nbsp;<strong>
			
			<?
			     // Finds colateral
				 print round($this->kern->getBalance($row['adr'], $row['cur']), 8)." ".$row['cur']; 
			?>
            
            </strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
              <td width="30%" align="center"><span class="font_12">Interest</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong class="font_12">
			  <? print $row['interest']."%"; ?>
              </strong></td>
            <td width="40%" class="font_12" align="center">Interest Interval&nbsp;&nbsp;&nbsp;&nbsp;<strong>
			<?
			   switch ($row['interest_interval'])
			   {
				   case 60 : print "Every Hour"; break;
				   case 1440 : print "Every Day"; break;
				   case 10080 : print "Every Week"; break;
				   case 43200 : print "Every Month"; break;
				   case 129600 : print "Every 3 Months"; break;
				   case 259200 : print "Every 6 Months"; break;
				   case 518400 : print "Every Year"; break;
			   }
			?>
            </strong></td>
            <td width="30%" class="font_12" align="center">Asset Value&nbsp;&nbsp;<strong>
            <?
			    // Last price
				 $price=round($this->kern->getFeedVal($row['feed_1'], $row['branch_1']), 8);
				 
				 // Address currency balance
				 $balance_cur=round($this->kern->getBalance($row['adr'], $row['cur']), 8);  
				 
				 // Margin
				 $value=$row['qty']*$price; 
				 
				 print round($value, 2)." ".$row['cur'];
			?>
            </strong>&nbsp;</strong></td>
            </tr>
            </table>
            
            <table>
            </table>
            
            </td></tr>
            </table>
            </div>
            </div>
            <br>
            
            <table width="90%">
            <tr>
            
            <td width="50%">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title font_16">You own</div>
            </div>
            <div class="panel-body font_12">
            <table width="100%">
            <tr>
            <td width="70%">
            
            <table>
            <tr>
            <td width="80%">
            
            <? 
			    // Find ownings
				$query="SELECT SUM(qty) AS total 
				          FROM assets_owners 
						 WHERE owner IN (SELECT adr 
						                 FROM my_adr 
										WHERE userID='".$_REQUEST['ud']['ID']."') 
						   AND owner<>'".$row['adr']."' 
						   AND symbol='".$row['symbol']."'"; 
				$result=$this->kern->execute($query); 
				$r = mysql_fetch_array($result, MYSQL_ASSOC);
				
				if ($r['total']=="")
				{
                   $own=0;
				}
				else
				{
				   $own=$r['total'];
				}
				
			    print "<span class='font_20'><strong>".round($own, 8)."</strong></span>"; 
		    ?>
            
            </td>
            
            <td width="10%"><a href="javascript:void(0)" onclick="$('#buy_modal_act').val('ID_BUY'); 
                                                                 $('#modal_trade').modal(); 
                                                                 $('#dd_buy_adr').css('display', 'block');
                                                                 $('#dd_sell_adr').css('display', 'none');" class="btn btn-primary">Buy</a></td>
            <td>&nbsp;&nbsp;</td>
            <td width="10%"><a href="javascript:void(0)" onclick="$('#buy_modal_act').val('ID_SELL'); 
                                                                 $('#modal_trade').modal();
                                                                 $('#dd_buy_adr').css('display', 'none');
                                                                 $('#dd_sell_adr').css('display', 'block');" class="btn btn-danger" <? if ($own==0) print "disabled"; ?>>Sell</a></td>
            </tr>
            </table>
			
            
            </td>
            </tr>
            </table>
            </div>
            </div>
            </td>
            
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            
            <td width="25%">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title font_16">Last Price</div>
            </div>
            <div class="panel-body font_14">
            
            <table width="90%">
            <tr>
            <td width="70%">
			<? 
			    print "<span class='font_20'><strong>".round($row['last_price'], 8)."</strong></span>&nbsp;&nbsp;&nbsp;<span class='font_12'>".$row['cur']."</span>";  
			?>
            </td>
            </tr>
            </table>
            
            </div>
            </div>
            </td>
            
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            
            <td width="25%">
            <div class="panel panel-default">
            <div class="panel-heading">
            <div class="panel-title font_16">Available to Buy</div>
            </div>
            <div class="panel-body">
            
            <table width="90%">
            <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="70%">
			<? 
			    // MArket owns
		        $query="SELECT *
		                  FROM assets_owners 
				         WHERE symbol='".$symbol."' 
				           AND owner='".$row['adr']."'";
       		    $result=$this->kern->execute($query);	
	            $r = mysql_fetch_array($result, MYSQL_ASSOC);
		        
			    print "<span class='font_20'><strong>".round($r['qty'], 8)."</strong></span>&nbsp;&nbsp;&nbsp;<span class='font_12'>".$row['symbol']."</span>";  
			?>
            </td>
            </tr>
            </table>
            
            </div>
            </div>
            </td>
            
            </tr>
            </table>
            <br>
        
		<?
		$this->template->showChart($row['feed_1'], $row['branch_1']);
	}
	
	function showTradeModal()
	{
		$query="SELECT * 
		          FROM feeds_assets_mkts 
				 WHERE asset_symbol='".$_REQUEST['symbol']."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $cur=$row['cur'];
		
		$this->template->showModalHeader("modal_trade", "Buy / Sell", "buy_modal_act", "ID_BUY");
		?>
        
           <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/trade.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(0.0001, "renew"); ?></td>
              </tr>
            </table></td>
            <td width="438" align="center" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_buy_net_fee_adr", 370); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class=font_14><strong> Address</strong></td>
              </tr>
              <tr>
                <td align="left">
				<? 
				    if ($cur=="MSK")
				       $this->template->showMyAdrDD("dd_buy_adr", "370"); 
					else
					   $this->template->showMyAdrAssetDD($row['cur'], "dd_buy_adr", 370);
						
					$this->template->showMyAdrAssetDD($_REQUEST['symbol'], "dd_sell_adr", 370);
			    ?>
                </td>
              </tr>
              <tr>
                <td height="0" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Amount</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_buy_qty" name="txt_buy_qty" placeholder="0" style="width:100px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Trade");
	}
	
}
?>