<?
class CRestrict
{
	function CRestrict($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function restrict($net_fee_adr, 
	                  $adr, 
					  $target_1, 
					  $target_2, 
					  $target_3, 
					  $target_4, 
					  $target_5, 
					  $days)
	{
		// Addresses valid
		if ($this->kern->adrValid($net_fee_adr)==false || 
		    $this->kern->adrValid($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Target 1
		if ($target_1!="")
		{
			// Domain ?
			if (strlen($target_1)<30) $target_1=$this->kern->adrFromDomain($target_1);
			
			if ($this->kern->adrValid($target_1)==false)
			{
				$this->template->showErr("Invalid recipient 1", 550);
			    return false;
			}
		}
		
		// Target 2
		if ($target_2!="")
		{
			// Domain ?
			if (strlen($target_2)<30) $target_2=$this->kern->adrFromDomain($target_2);
			
			if ($this->kern->adrValid($target_2)==false)
			{
				$this->template->showErr("Invalid recipient 2", 550);
			    return false;
			}
		}
		
		// Target 3
		if ($target_3!="")
		{
			// Domain ?
			if (strlen($target_3)<30) $target_3=$this->kern->adrFromDomain($target_3);
			
			if ($this->kern->adrValid($target_3)==false)
			{
				$this->template->showErr("Invalid recipient 3", 550);
			    return false;
			}
		}
		
		// Target 4
		if ($target_4!="")
		{
			// Domain ?
			if (strlen($target_4)<30) $target_4=$this->kern->adrFromDomain($target_4);
			
			if ($this->kern->adrValid($target_4)==false)
			{
				$this->template->showErr("Invalid recipient 4", 550);
			    return false;
			}
		}
		
		// Target 5
		if ($target_5!="")
		{
			// Domain ?
			if (strlen($target_5)<30) $target_5=$this->kern->adrFromDomain($target_5);
			
			if ($this->kern->adrValid($target_5)==false)
			{
				$this->template->showErr("Invalid recipient 3", 550);
			    return false;
			}
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
		
		// Target address sealed
		if ($this->kern->isSealed($adr)==true)
		{
			$this->template->showErr("Address is sealed.", 550);
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
							    op='ID_RESTRICT', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$target_1."',
								par_2='".$target_2."',
								par_3='".$target_3."',
								par_4='".$target_4."',
								par_5='".$target_5."',
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
	
	function showModal()
	{
		$this->template->showModalHeader("modal_restrict", "Restrict Recipients", "act", "restrict", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_restrict.png" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0365", "restrict"); ?></td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_restrict_net_fee", 350); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient 1</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_target_1" id="txt_target_1" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient 2</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><input class="form-control" name="txt_target_2" id="txt_target_2" style="width:350px"/></td>
              </tr>
              <tr>
                <td height="0" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient 3</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><input class="form-control" name="txt_target_3" id="txt_target_3" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><strong>Recipient 4</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><input class="form-control" name="txt_target_4" id="txt_target_4" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><strong>Recipient 5</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><input class="form-control" name="txt_target_5" id="txt_target_5" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" name="txt_restrict_days" id="txt_restrict_days" style="width:100px" type="number" min="1" step="1" value="365"/>
                </td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFee("txt_restrict_days", "restrict_net_fee_panel_val", 0.0365);
		</script>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>