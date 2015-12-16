<?
  class CMarket
  {
	  function CMarket($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	 function buyDomain($net_fee_adr, $pay_adr, $attach_adr, $domain)
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
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.");
			return false;
		}
		
		// Address
	    if ($this->kern->adrExist($pay_adr)==false)
		{
			$this->template->showErr("Invalid address");
			return false;
		}
		
		// Target address sealed
		if ($this->kern->isSealed($pay_adr)==true)
		{
			$this->template->showErr("Address is sealed.");
			return false;
		}
		
		// Attach to address
	    if ($this->kern->adrValid($attach_adr)==false)
		{
			$this->template->showErr("Invalid attachment address");
			return false;
		}
	   
	   // Load domain data
	   $query="SELECT * 
	             FROM domains 
				WHERE domain='".$domain."' 
				  AND sale_price>0 
				  AND market_bid>0 
				  AND market_bid>0";
	   $result=$this->kern->execute($query);	
	   
	   if (mysql_num_rows($result)==0)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction");
		   return false;
	   }
	   
	   // Load data
	   $row = mysql_fetch_array($result, MYSQL_ASSOC);
	   
	   // Funds
	   if ($this->kern->getBalance($pay_adr)<$row['sale_price'])
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction");
		   return false;
	   }
	   
	   // Funds
	   if ($this->kern->getBalance($net_fee_adr)<($row['sale_price']*0.001))
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction");
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
							    op='ID_BUY_DOMAIN', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$pay_adr."',
								par_1='".$attach_adr."',
								par_2='".$domain."',
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
	
	
	function showMarket()
	{
		$query="SELECT * 
		          FROM domains 
				 WHERE sale_price>0
			  ORDER BY market_bid DESC"; 
	    $result=$this->kern->execute($query);
		
			
		?>
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                        <td width="47%" align="left"><span class="font_14"><strong>
						<? print $row['domain']; ?></strong></span><br><p class="font_10">
						<? print "Seller : ...".substr($row['adr'], 30, 20)."..."; ?></p></td>
                        <td width="16%" align="center"><span class="font_14" style="color:#009900"><strong><? print $row['sale_price']; ?></strong></span>
                        <p class="font_10"><? print "MSK"; ?></p></td>
                        <td width="18%" align="center" class="font_14"><? print $this->kern->daysFromBlock($row['expires']); ?><p class="font_10">days</p></td>
                        <td width="19%" align="center" class="simple_maro_12">
                         <a class="btn btn-primary" href="javascript:$('#buy_adr').val('<? print $row['domain']; ?>'); $('#modal_buy_domain').modal()" style="width:80px"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;&nbsp;Buy</a>
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