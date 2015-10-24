<?
class CShare
{
	function CShare($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function share($net_fee_adr, $adr, $share_adr)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Net fee adr
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Fee
		if ($this->kern->getBalance($net_fee_adr)<0.0001)
		{
			 $this->template->showErr("Insufficient funds");
			 return false;
		}
		
		// Address
		if ($this->kern->adrValid($adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Share address
		if ($this->kern->adrValid($share_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Domain ?
		if (strlen($share_adr)<31) 
		   $share_adr=$this->kern->adrFromDomain($share_adr); 
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Shares an address");
		
		  // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SHARE_ADR', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$share_adr."',
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
	
	function showModal()
	{
		$this->template->showModalHeader("modal_share", "Receive Interest", "act", "share_adr", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_share.png" width="180" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001", "share"); ?></td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_net_fee"); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Share the Private key with this address</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" name="txt_adr" id="txt_adr" style="width:300px"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>