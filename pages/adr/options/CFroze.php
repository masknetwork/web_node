<?
class CFroze
{
	function CFroze($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function frozeAdr($net_fee_adr, $adr, $days)
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
							    op='ID_FROZE_ADR', 
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
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function showModal()
	{
		$this->template->showModalHeader("modal_froze", "Froze Address", "act", "froze_adr", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_froze.png" width="150" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001", "froze"); ?></td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_froze_net_fee"); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" name="txt_froze_days" id="txt_froze_days" style="width:80px"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>