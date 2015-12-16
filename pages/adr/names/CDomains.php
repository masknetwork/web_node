<?
  class CDomains
  {
	  function CDomains($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	function newDomain($net_fee_adr, $adr, $domain, $days)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
			return false;
		}
		
		// Address
	    if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid address", 550);
			return false;
		}
		
		// Target address sealed
		if ($this->kern->isSealed($adr)==true)
		{
			$this->template->showErr("Address is sealed.", 550);
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days (minimum 1 day)", 550);
			return false;
		}
	   
	   // Funds
	   if ($this->kern->getBalance($net_fee_adr)<$days*0.0001)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction", 550);
		   return false;
	   }
	   
	   // Domain valid
	   if ($this->kern->domainValid($domain)==false)
	   {
		    $this->template->showErr("Invalid domain name", 550);
		    return false;
	   }
	   
	   // Domain exist
	   if ($this->kern->domainExist($domain)==true)
	   {
		    $this->template->showErr("Domain already exist", 550);
		    return false;
	   }
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Register a new domain ($domain)");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_DOMAIN', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$domain."',
								days='".$days."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function renewDomain($net_fee_adr, $domain, $days)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days (minimum 1 day)", 550);
			return false;
		}
	   
	   // Funds
	   if ($this->kern->getBalance($net_fee_adr)<$days*0.0001)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction", 550);
		   return false;
	   }
	   
	   // Domain valid
	   if ($this->kern->domainValid($domain)==false)
	   {
		    $this->template->showErr("Invalid domain name", 550);
		    return false;
	   }
	   
	   // Domain exist
	   if ($this->kern->domainExist($domain)==false)
	   {
		    $this->template->showErr("Domain doesn't exist", 550);
		    return false;
	   }
	   
	   // My domain
	   if ($this->kern->myDomain($domain)==false)
	   {
		    $this->template->showErr("You don't own this domain", 550);
		    return false;
	   }
	   	
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Renew a domain ($domain)");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_RENEW_DOMAIN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$domain."',
								days='".$days."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function transferDomain($net_fee_adr, $domain, $to_adr)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
			return false;
		}
		
		// Funds
	   if ($this->kern->getBalance($net_fee_adr)<0.0001)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction", 550);
		   return false;
	   }
	   
	   // Domain valid
	   if ($this->kern->domainValid($domain)==false)
	   {
		    $this->template->showErr("Invalid domain name", 550);
		    return false;
	   }
	   
	   // Domain exist
	   if ($this->kern->domainExist($domain)==false)
	   {
		    $this->template->showErr("Domain doesn't exist", 550);
		    return false;
	   }
	   
	   // Target address valid
	   if ($this->kern->adrValid($to_adr)==false)
	   {
		   $this->template->showErr("Invalid target address", 550);
		   return false;
	   }
	   
	   // My domain
	   if ($this->kern->myDomain($domain)==false)
	   {
		    $this->template->showErr("You don't own this domain", 550);
		    return false;
	   }
	   
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Transfer a domain ($domain)");
		   
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_TRANSFER_DOMAIN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$domain."',
								par_2='".$to_adr."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function setSalePrice($net_fee_adr, $domain, $price, $days, $mkt_bid)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
			return false;
		}
		
		// Funds
	   if ($this->kern->getBalance($net_fee_adr)<0.0001)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction", 550);
		   return false;
	   }
	   
	   // Domain valid
	   if ($this->kern->domainValid($domain)==false)
	   {
		    $this->template->showErr("Invalid domain name", 550);
		    return false;
	   }
	   
	   // Domain exist
	   if ($this->kern->domainExist($domain)==false)
	   {
		    $this->template->showErr("Domain doesn't exist", 550);
		    return false;
	   }
	   
	   // Target address valid
	   if ($price<0.0001)
	   {
		   $this->template->showErr("Invalid price");
		   return false;
	   }
	   
	   // My domain
	   if ($this->kern->myDomain($domain)==false)
	   {
		    $this->template->showErr("You don't own this domain", 550);
		    return false;
	   }
	   
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Set the sale price for a domain ($domain)");
		   
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SALE_DOMAIN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$domain."',
								par_2='".$price."',
								days='".$days."',
								bid='".$mkt_bid."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function showMyDomains()
	{
		$query="SELECT my_adr.*, adr.balance, dom.domain, dom.expires, dom.sale_price
		          FROM my_adr 
				  LEFT JOIN adr ON adr.adr=my_adr.adr
				  JOIN domains AS dom ON dom.adr=my_adr.adr
				 WHERE my_adr.userID='".$_REQUEST['ud']['ID']."' 
			  ORDER BY balance DESC"; 
	    $result=$this->kern->execute($query);
		
			
		?>
            
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <thead>
                  <tr bgcolor="#fafafa" height="40px" style="padding-bottom:10px">
                  <td width="60%" class="font_14">&nbsp;&nbsp;&nbsp;Address Name</td>
                  <td width="20%" class="font_14" align="center">Sale Price</td>
                  <td width="20%" class="font_14" align="center">Expires</td>
                  <td width="5%" class="font_14">Operations&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                  </thead>
                  <?
				       while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				       {
				  ?>
                  
                        <tr>
                        <td width="52%" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="15%"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle"/></td>
                              <td width="85%" class="font_14"><strong><? print $row['domain']; ?></strong>
                              <p class="font_10"><? print $this->template->formatAdr($row['adr']); ?></p>
                              </td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="17%" align="center" class="font_14" style="color:#009900">
                        <span class="font_15"><strong><? print $row['sale_price']; ?></strong></span><span class="font_12">&nbsp;MSK</span>
                        </td>
                        <td width="16%" align="center" class="simple_green_12">
                        <span class="font_14"><? print $this->kern->daysFromBlock($row['expires']); ?></span>
                        <p class="font_10">days</p>
                        </td>
                        <td width="15%" align="center" class="font_14">
                        
                        
                         <div class="dropdown">
                         <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                         <span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;<span class="caret"></span>
                         </button>
                         <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#renew_domain').val('<? print $row['domain']; ?>'); $('#modal_renew').modal()">Renew</a></li>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#transfer_domain').val('<? print $row['domain']; ?>'); $('#modal_transfer').modal()">Transfer</a></li>
                         <li role="presentation" class="divider"></li>
                 
                         <?
				           if ($row['sale_price']==0)
				           {
				         ?>
                 
                 
                             <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#set_price_domain').val('<? print $row['domain']; ?>'); $('#modal_set_price').modal()">Set Sale Price</a></li>
                 
                         <?
				          }
				          else
				         {
				         ?>
                 
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#set_price_domain').val('<? print $row['domain']; ?>'); $('#modal_update_price').modal(); ">Update Sale Price</a></li>
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#set_price_domain').val('<? print $row['domain']; ?>'); $('#modal_update_price').modal(); $('#txt_upd_price').val('0'); ">Suspend from Market</a></li>
                          
                 
                 <?
					}
				 ?>
                 
                 </ul>
                 </div>
                        
                        </td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                  
                  <?
					   }
				  ?>
                  
                  </table>
               
            
        <?
		
	}
	
	
	function showMarket()
	{
		$query="SELECT my_adr.*, 
		               adr.balance, 
					   dom.domain, 
					   dom.expires, 
					   dom.sale_price, 
					   dom.market_bid, 
					   dom.market_expires 
		          FROM my_adr 
				  LEFT JOIN adr ON adr.adr=my_adr.adr
				  JOIN domains AS dom ON dom.adr=adr.adr
				 WHERE dom.sale_price>0
			  ORDER BY dom.market_bid DESC"; 
	    $result=$this->kern->execute($query);
		
			
		?>
            <div id="div_market" style="display:none">
            <? $this->template->showSearchBox("Search Address Name"); ?>
            <table width="550" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
             <thead align="center">
                <th width="6%">&nbsp;</th>
                <th width="53%" align="center">Address Name</th>
                <th width="25%" align="center">Sale Price</th>
                <th width="18%" align="center">Expires</th>
                <th width="7%" align="center">&nbsp;</th>
                <td width="0%">
              <tbody>
                
                <?
				   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				   {
				?>
                
                  <tr>
                  <td>
                  <a data-toggle="tooltip" data-placement="top" title="Show Address QR" href="#" class="qr"><img src="../../GIF/ico/qr.png" width="20" onclick="$('#qr_img').attr('src', '../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()" />
                  </a>
                 
                  </td>
                  <td><? print $row['domain']; ?><br />
                    <span class="simple_gri_10"><? print "Seller : ...".substr($row['adr'], 40, 30)."..."; ?></span></td>
                  <td align="left" class="simple_blue_14"><? print $row['sale_price']." MSK"; ?></td>
                  <td align="left" class="simple_blue_14"><? print $this->kern->getAbsTime($row['expires']*100, false); ?></td>
                  <td align="left"> 
                  <a class="btn btn-success" href="javascript:$('#buy_adr').val('<? print $row['domain']; ?>'); $('#modal_buy_domain').modal()" style="width:80px"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;&nbsp;Buy</a>
                  </td>
                  </tr>
                
                <?
				   }
				?>
                
                 </tbody>
            </table>
            </div>
            
        <?
		
	}
	
	function showRenewModal()
	{
		$this->template->showModalHeader("modal_renew", "Renew Address Name", "act", "renew", "renew_domain", "");
		
		?>
        
<table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/renew.png" width="200" height="200" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(0.0365, "renew"); ?></td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_my_adr_renew"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td><input name="txt_days_re" id="txt_days_re" class="form-control" value="365" style="width:100px" type="number" min="10" step="1"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFee("txt_days_re", "renew_net_fee_panel_val", 0.0365);
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showTransferModal()
	{
		$this->template->showModalHeader("modal_transfer", "Transfer Address Name", "act", "transfer", "transfer_domain", "");
		?>
        
         <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/transfer.png" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_my_adr_transfer"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Recipient Address</strong></td>
              </tr>
              <tr>
                <td><input name="txt_rec" id="txt_rec" class="form-control" value="" style="width:300px"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showSetPriceModal()
	{
		$this->template->showModalHeader("modal_set_price", "Address Name Price", "act", "set_price", "set_price_domain", "");
		?>
        
         <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/domain_price.png" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->shownetFeePanel(0.0365, "sp"); ?></td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" colspan="3" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td colspan="3"><? $this->template->showMyAdrDD("dd_my_adr_set_sale_price"); ?></td>
              </tr>
              <tr>
                <td width="34%">&nbsp;</td>
                <td width="36%">&nbsp;</td>
                <td width="30%">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Price</strong></td>
                <td valign="top" class="simple_blue_14"><strong> Days</strong></td>
                <td valign="top" class="simple_blue_14"><strong> Bid</strong></td>
              </tr>
              <tr>
                <td>
                <input name="txt_price" id="txt_price" class="form-control" value="1" style="width:80px" type="number" min="0.0001" step="0.0001"/>
                </td>
                <td>
                <input name="txt_days_sp" id="txt_days_sp" class="form-control" value="365" style="width:80px" type="number" min="0.0001" step="0.0001"/>
                </td>
                <td>
                <input name="txt_bid_sp" id="txt_bid_sp" class="form-control" value="0.0001" style="width:80px" type="number" min="0.0001" step="0.0001"/>
                </td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFeeBid("txt_days_sp", "sp_net_fee_panel_val", "txt_bid_sp", 0.0365);
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
	function showNewDomainModal($attach_to="")
	{
		if ($attach_to!="")
		{
			$query="SELECT * 
			          FROM my_adr 
					 WHERE SHA2(adr, 256)='".$attach_to."' 
					   AND user='".$_REQUEST['ud']['user']."'";
					   
			$result=$this->kern->execute($query);	
			if (mysql_num_rows($result)==0) die ("Invalid address");
			
	        $row = mysql_fetch_array($result, MYSQL_ASSOC);
			$attach_to=$row['adr'];
		}
		
		$this->template->showModalHeader("modal_new_domain", "New Address Name", "act", "new_domain", "adr", "");
		?>
        
         <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/new_domain.png" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                <? $this->template->shownetFeePanel(0.0365, "nd"); ?>
                </td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_net_fee_adr"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="simple_blue_14"><strong>Attach Name to Address</strong></span></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_adr", 300, $attach_to); ?></td>
              </tr>
              <tr>
                <td valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td><input name="txt_name" class="form-control" id="txt_name" style="width:300px" value="" maxlength="30"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="simple_blue_14"><strong>Days</strong></span></td>
              </tr>
              <tr>
                <td><input name="txt_days_nd" class="form-control" id="txt_days_nd" style="width:80px" value="365" maxlength="6"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFee("txt_days_nd", "nd_net_fee_panel_val", 365);
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showBuyDomainModal()
	{
		$this->template->showModalHeader("modal_buy_domain", "Buy Address Name", "act", "buy_domain", "buy_adr", "");
		?>
        
         <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/cart.png" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                <? $this->template->shownetFeePanel(0.0001, "nd"); ?>
                </td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_net_fee_adr"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="simple_blue_14"><strong>Pay domain using this address</strong></span></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_pay_adr"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="simple_blue_14"><strong>Attach Name to Address</strong></span></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_adr"); ?></td>
              </tr>
              <tr>
                <td valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
       
        
        <?
		$this->template->showModalFooter();
	}
  }
?>