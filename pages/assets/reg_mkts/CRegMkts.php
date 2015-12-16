<?
class CRegMkts
{
	function CRegMkts($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showRegMkts()
	{
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE tip='ID_REGULAR'"; 
		$result=$this->kern->execute($query);	
		
		?>
           
           <br>
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="324">&nbsp;</td>
                  <td width="99" align="right"><a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-cog" ></span>&nbsp;My Markets</a></td>
                  <td width="127" align="right"><a href="#" class="btn btn-warning"><span class="glyphicon glyphicon-list-alt" ></span>&nbsp;My Orders</a></td>
                </tr>
              </tbody>
            </table>
            <br>
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="50%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Ask</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Bid</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Trade</span></td>
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
                        <td width="51%" align="left"><a href="#" class="maro_12"><strong><? print base64_decode($row['title'])." (".$row['mkt_symbol'].")"; ?></strong></a><br><span class="simple_maro_10"><? print substr(base64_decode($row['description']), 0, 50)." ..."; ?></span></td>
                        <td width="16%" align="center" class="simple_green_12"><strong><? print round($row['ask'], 8)."</strong><br><span class='simple_green_10'>".$row['cur_symbol']."</span>"; ?></strong></td>
                        <td width="19%" align="center" class="simple_green_12"><strong><? print round($row['bid'], 8); ?></strong><br><span class='simple_green_10'><? print $row['cur_symbol']; ?></span></td>
                        <td width="14%" align="center" class="simple_maro_12"><a href="market.php?symbol=<? print $row['mkt_symbol']; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Trade</a></td>
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
              </tbody>
</table>
        
        <?
	}
	
	
	
    function newMarket($net_fee_adr, 
	                   $mkt_adr, 
					   $asset_symbol, 
					   $cur_symbol, 
					   $mkt_symbol,
					   $name, 
					   $desc, 
					   $fee_adr, 
					   $fee, 
					   $decimals,
					   $bid, 
					   $days)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 
		 // Net Fee Address 
		 if ($this->template->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $net_fee=round($bid*$mkt_days, 4);
		 
		 // Funds
		 if ($this->template->getBalance($net_fee_adr)<$net_fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	   
	     // Market address
		 if ($this->template->adrValid($mkt_adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		 // Used in other market ?
		 if ($this->kern->isMarketAdr($mkt_adr)==true)
		 {
			  $this->template->showErr("Market address is already used in another market");
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
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Asset symbol valid
		 $symbol=strtoupper($asset_symbol);
		 if ($this->template->symbolValid($asset_symbol)==false)
		 {
			 $this->template->showErr("Invalid asset symbol");
			 return false;
		 }
		 
		 // Asset symbol exist ?
		 $query="SELECT * 
		           FROM assets 
				  WHERE symbol='".$asset_symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Asset symbol doesn't exist");
			 return false;
		 }
		 
		// Currency symbol valid ?
		if (strlen($cur_symbol)!=3)
		{
		   $cur_symbol=strtoupper($cur_symbol);
		   if ($this->template->symbolValid($cur_symbol)==false)
		   {
			  $this->template->showErr("Invalid currency symbol");
			  return false;
		   }
		   
		   // Currency symbol exist ?
		   $query="SELECT * 
		           FROM assets 
				  WHERE symbol='".$cur_symbol."'";
		   $result=$this->kern->execute($query);	
	       if (mysql_num_rows($result)==0)
		   {
			 $this->template->showErr("Currency doesn't exist");
			 return false;
		   }
		}
		else if ($cur_symbol!="MSK")
		{
			 $this->template->showErr("Invalid currency symbol");
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
		 
		 // Fee address valid ?
		 if ($this->template->adrValid($fee_adr)==false)
		 {
			$this->template->showErr("Invalid fee address");
			return false;
		 }
		 
		// Fee
		if ($fee>25)
		{
			 $this->template->showErr("Maximum market fee is 25%");
			 return false;
		}
		
		// Bid
		if ($bid<0.0001)
		{
			 $this->template->showErr("Minimum bid value is 0.0001");
			 return false;
		}
		
		// Days
		if ($days<100)
		{
			 $this->template->showErr("Minimum period is 100 days");
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
							    op='ID_NEW_REGULAR_ASSET_MARKET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$mkt_adr."',
								par_1='".$asset_symbol."',
								par_2='".$cur_symbol."',
								par_3='".$mkt_symbol."',
								par_4='".base64_encode($name)."',
								par_5='".base64_encode($desc)."',
								par_6='".$fee_adr."',
								par_7='".$fee."',
								par_8='".$decimals."',
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
	
	function showNewRegularMarketModal()
	{
		$this->template->showModalHeader("modal_new_regular_market", "New Regular Assets Market", "act", "new_regular_market", "edit_symbol", "");
		?>
        
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/regular_mkts.png" width="182" height="163" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
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
                <td align="left"><? $this->template->showMyAdrDD("dd_new_net_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Market Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="50%" height="30" align="left" valign="top"><strong>Asset Symbol</strong></td>
                    <td width="50%" height="30" align="left" valign="top"><strong>Currency</strong></td>
                    <td width="50%" align="left" valign="top"><strong>Market Symbol</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_first_symbol" name="txt_new_first_symbol" placeholder="XXXXXX" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_new_cur" name="txt_new_cur" placeholder="XXXXXX" style="width:100px"/></td>
                    <td align="left"><input class="form-control" id="txt_new_mkt_symbol" name="txt_new_mkt_symbol" placeholder="XXXXXX" style="width:100px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
               <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Title</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">
                <input class="form-control" id="txt_new_name" name="txt_new_name" placeholder="Title (5-50 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="5" id="txt_new_desc" name="txt_new_desc" class="form-control" style="width:350px" placeholder="Short Description (optional, 0-250 characters)"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Receive the fees to this address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><span class="simple_blue_14"><strong> Fee (%)</strong></span></td>
                    <td width="33%" align="left" valign="top"><span class="simple_blue_14"><strong>Bid</strong></span></td>
                    <td width="33%" align="left" valign="top"><span class="simple_blue_14"><strong>Days</strong></span></td>
                    <td width="33%" align="left" valign="top"><strong>Decimals</strong></td>
                  </tr>
                  <tr>
                    <td align="left"><input class="form-control" id="txt_new_fee" name="txt_new_fee" placeholder="0" style="width:80px"/></td>
                    <td align="left"><input class="form-control" id="txt_new_bid" name="txt_new_bid" placeholder="0" style="width:80px"/></td>
                    <td align="left"><input class="form-control" id="txt_new_days" name="txt_new_days" placeholder="100" style="width:80px"/></td>
                    <td align="left">
                    <select id="dd_decimals" name="dd_decimals" class="form-control" style="width:70px">
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    </select>
                    </td>
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
		        $('#form_modal_new_regular_market').submit(
		          function() 
		          {   
		            $('#txt_new_name').val(btoa($('#txt_new_name').val())); 
		            $('#txt_new_desc').val(btoa($('#txt_new_desc').val()));  
		          });
		      </script>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showEditRegularMarketModal()
	{
		$this->template->showModalHeader("modal_edit_regular_market", "Edit Regullar Assets Market", "act", "new_regular_market", "edit_symbol", "");
		?>
        
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/edit_asset.png" width="182" height="163" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
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
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left"><textarea rows="5" id="txt_desc2" name="txt_desc2" class="form-control" style="width:350px" placeholder="Short Description (10-250 characters)"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Website</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name2" style="width:350px" name="txt_name2" placeholder="Asset Name (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Receive the fees to this address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Market Fee</strong></span></td>
                  </tr>
                  <tr>
                    <td align="left"><input class="form-control" id="txt_name5" name="txt_name5" placeholder="XXXXXX" style="width:100px"/></td>
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
         
		   
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
	
}
?>