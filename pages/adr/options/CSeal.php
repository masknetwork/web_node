<?
class CSeal
{
	function CSeal($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function sealAdr($net_fee_adr, $adr, $days)
	{
		// Addresses valid
		if ($this->kern->adrValid($net_fee_adr)==false || 
		    $this->kern->adrValid($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Net fee adr
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
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
			$this->template->showErr("Minimum days is 1", 550);
			return false;
		}
		
		// Fee
		if ($this->kern->getBalance($net_fee_adr)<0.0001*$days)
		{
			 $this->template->showErr("Insufficient funds", 550);
			 return false;
		}
		
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Shares an address");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SEAL_ADR', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								days='".$days."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
			
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been successfully executed", 550);

		   return true;
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
	
	function showModal($adr)
	{
		$this->template->showModalHeader("modal_seal", "Seal Address", "act", "seal_adr", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_seal.png" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0365", "seal"); ?></td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_seal_net_fee"); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" type="number" min="1" step="1" name="txt_seal_days" id="txt_seal_days" style="width:100px" value="365"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFee("txt_seal_days", "seal_net_fee_panel_val", 0.0365);
		</script>
        
        <?
		if ($this->kern->hasAttr($adr, "ID_SEALED")==true)
		   $this->template->showModalFooter("Renew");
		else
		   $this->template->showModalFooter("Activate");
	}
}
?>