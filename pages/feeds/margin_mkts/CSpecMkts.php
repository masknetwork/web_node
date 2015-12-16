<?
class CSpecMkts
{
	function CSpecMkts($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function newMarket($net_fee_adr, 
	                   $mkt_adr, 
					   $mkt_symbol, 
					   $feed, 
					   $branch, 
					   $asset, 
					   $asset_qty, 
					   $cur, 
					   $name, 
					   $desc, 
					   $fee_adr, 
					   $mkt_fee,
					   $decimals,
					   $bid, 
					   $days)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($mkt_adr)==false ||
			$this->kern->isMine($fee_adr)==false)
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
		 $net_fee=0.0001*$days;
		 
		 // Market address
		 if ($this->template->adrExist($mkt_adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		 // Market symbol
		$mkt_symbol=strtoupper($mkt_symbol);
		if ($this->template->symbolValid($mkt_symbol)==false)
		{
			  $this->template->showErr("Invalid market symbol");
			  return false;
		}
		
		// Another market using this symbol
		if ($this->kern->isMarketSymbol($mkt_symbol)==true)
		{
			 $this->template->showErr("This symbol is used by another market");
			 return false;
		}
		
		// Feed symbol
		$mkt_symbol=strtoupper($mkt_symbol);
		if ($this->template->symbolValid($mkt_symbol)==false)
		{
			  $this->template->showErr("Invalid market symbol");
			  return false;
		}
		
		// Feed exist ?
		if ($this->kern->feedExist($feed)==false)
		{
			$this->template->showErr("Invalid feed symbol");
			return false;
		}
		
		// Feed branch exist ?
		if ($this->kern->branchExist($feed, $branch)==false)
		{
			$this->template->showErr("Invalid feed branch symbol");
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
		
		// Asset
		$asset=strtoupper($asset);
		if ($this->template->symbolValid($asset)==false)
		{
			  $this->template->showErr("Invalid asset symbol");
			  return false;
		}
		
		// Asset exist already ?
		if ($this->kern->assetExist($asset)==true)
		{
			 $this->template->showErr("Asset already exist");
			 return false;
		}
		
		// Currency 
		if ($cur!="MSK")
		{
		   $cur=strtoupper($cur);
		   if ($this->template->symbolValid($cur)==false)
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
		
		// Asset qty
		if ($asset_qty<1)
		{
			$this->template->showErr("Minimum asset qty is 1");
			return false;
		}
		
		// Asset qty cost
		$cost=$asset_qty*$row['val']*2;
		
		// Enough currency ?
		if ($this->template->getBalance($mkt_adr)<$cost)
	    {
		    $this->template->showErr("Insufficient funds. You need at least ".$cost." ".$cur." to fund the market.");
		    return false;
	    }
		
		// Name
		 if (strlen($name)<5 || strlen($name)>50)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 if (strlen($desc)>250)
		 {
			 $this->template->showErr("Invalid description length (50-250 characters)");
			 return false;
		 }
		 
		 // Fee address
		 if ($this->template->adrValid($fee_adr)==false)
		 {
			$this->template->showErr("Invalid market address");
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
		 
		 // Market bid
		 if ($bid<0.0001)
		 {
			 $this->template->showErr("Invalid bid");
			 return false;
		 }
		 
		 // Days
		 if ($days<100)
		 {
			  $this->template->showErr("Invalid days. Minimum 100 days required.");
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
							    op='ID_NEW_REGULAR_FEED_MARKET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$mkt_adr."',
								par_1='ID_REGULAR',
								par_2='".$mkt_symbol."',
								par_3='".$feed."',
								par_4='".$branch."',
								par_5='".$asset."',
								par_6='".$asset_qty."',
								par_7='".$cur."',
								par_8='".base64_encode($name)."',
								par_9='".base64_encode($desc)."',
								par_10='".$fee_adr."',
								par_11='".$mkt_fee."',
								par_12='".$decimals."',
								par_13='0',
								par_14='0',
								days='".$days."',
								bid='".$bid."', 
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
	
	
	function showMarkets()
	{
		$query="SELECT * 
		          FROM feeds_markets 
				 WHERE tip='ID_SPECULATIVE'";
	    $result=$this->kern->execute($query);	
	   
	   
		?>
        
          <table width="565" border="0" cellspacing="0" cellpadding="0">
              
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png">
                  <table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="75%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="23%" align="center"><span class="inset_maro_14">Details</span></td>
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
                          <td width="11%" align="left" class="simple_maro_12">
                          <img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle" /></td>
                        <td width="73%" align="left" class="simple_maro_12"><strong><? print base64_decode($row['name']); ?></strong>
                        <br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span></td>
                        <td width="16%" align="center" class="simple_maro_12">
                        
                        <?
						   if ($this->kern->isMine($row['mkt_adr'])==false)
						   {
						?>
                        
                             <a href="market.php?symbol=<? print $row['mkt_symbol']; ?>" class="btn btn-warning btn-sm">
                             <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Details
                             </a>
                        <?
						   }
						   else
						   {
						?>
                        
                         <div class="dropdown" align="right">
                                  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp; Settings&nbsp; &nbsp; <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="../reg_mkts/market.php?symbol=<? print $row['mkt_symbol']; ?>">Details</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_new_feed_branch').modal(); $('#feed_symbol').val('<? print $row['symbol']; ?>');">Edit Market</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_increase_bid').modal()">Increase Bid</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_renew').modal()">Renew</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#confirm_modal').modal()">Close Market</a></li>
                    </ul>
                  </div>  
                  
                  <?
						   }
				  ?>
                        
                        </td>
                        </tr>
                        <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>                          
                        
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
        
        <?
	}
	
	
	function showMarketModal()
	{
		$this->template->showModalHeader("modal_new_regular_mkt", "New Regular Market", "act", "new_reg_mkt", "opt", "");
		?>
        
          <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/balanta.png" width="177" height="148" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_net_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Market Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Market Symbol</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Feed Symbol</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Feed Branch</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_mkt_symbol" name="txt_new_mkt_symbol" placeholder="XXXXXX" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_feed_symbol" name="txt_new_mkt_feed_symbol" placeholder="XXXXXX" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_feed_branch" name="txt_new_mkt_feed_branch" placeholder="XXXXXX" style="width:100px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Asset</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Asset Qty</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Currency</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_mkt_asset" name="txt_new_mkt_asset" placeholder="XXXXXX" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_asset_qty" name="txt_new_mkt_asset_qty" placeholder="XXXXXX" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_cur" name="txt_new_mkt_cur" placeholder="XXXXXX" style="width:100px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Market Name</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_new_mkt_name" name="txt_new_mkt_name" placeholder="Name (5-30 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
              <textarea rows="5" id="txt_new_mkt_desc" name="txt_new_mkt_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px">
              </textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong> Fee (%)</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Decimals</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Bid</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Days</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_mkt_fee" name="txt_new_mkt_fee" placeholder="0" style="width:80px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_decimals" name="txt_new_mkt_decimals" placeholder="0" style="width:80px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_bid" name="txt_new_mkt_bid" placeholder="0.0001" style="width:80px"/></td>
                    <td><input class="form-control" id="txt_new_mkt_days" name="txt_new_mkt_days" placeholder="1000" style="width:80px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter(true, "Start");
	}
}
?>