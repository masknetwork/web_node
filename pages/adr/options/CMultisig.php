<?
class CMultisig
{
	function CMultisig($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function multisig($net_fee_adr, 
	                  $adr, 
					  $target_1, 
					  $target_2, 
					  $target_3, 
					  $target_4, 
					  $target_5, 
					  $min,
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
			
			// Check address
			if ($this->kern->adrValid($target_1)==false)
			{
				$this->template->showErr("Invalid recipient 1", 550);
			    return false;
			}
			
			$no++;
		}
		
		// Target 2
		if ($target_2!="")
		{
			// Domain ?
			if (strlen($target_2)<30) $target_2=$this->kern->adrFromDomain($target_2);
			
			// Check address
			if ($this->kern->adrValid($target_2)==false)
			{
				$this->template->showErr("Invalid recipient 2", 550);
			    return false;
			}
			
			$no++;
		}
		
		// Target 3
		if ($target_3!="")
		{
			// Domain ?
			if (strlen($target_3)<30) $target_3=$this->kern->adrFromDomain($target_3);
		    
			// Check address	
			if ($this->kern->adrValid($target_3)==false)
			{
				$this->template->showErr("Invalid recipient 3", 550);
			    return false;
			}
			
			$no++;
		}
		
		// Target 4
		if ($target_4!="")
		{
			// Domain ?
			if (strlen($target_4)<30) $target_4=$this->kern->adrFromDomain($target_4);
			
			// Check address
			if ($this->kern->adrValid($target_4)==false)
			{
				$this->template->showErr("Invalid recipient 4", 550);
			    return false;
			}
			
			$no++;
		}
		
		// Target 5
		if ($target_5!="")
		{
			// Domain ?
			if (strlen($target_5)<30) $target_5=$this->kern->adrFromDomain($target_3);
			
			// Check address
			if ($this->kern->adrValid($target_5)==false)
			{
				$this->template->showErr("Invalid recipient 3", 550);
			    return false;
			}
			
			$no++;
		}
		
		// Min signersd
		if ($min<1)
		{
			$this->template->showErr("Minimum cosigners is 1", 550);
			return false;
		}
		
		// Max
		if ($min>$no)
		{
			$this->template->showErr("Maximum ".$no." accepted", 550);
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
							    op='ID_MULTISIG', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$target_1."',
								par_2='".$target_2."',
								par_3='".$target_3."',
								par_4='".$target_4."',
								par_5='".$target_5."',
								par_6='".$min."',
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
		$this->template->showModalHeader("modal_multisig", "Multisignatures", "act", "multisig", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_multisig.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_multi_net_fee"); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Signer 1</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_signer_1" id="txt_signer_1" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Signer 2</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_signer_2" id="txt_signer_2" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Signer 3</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_signer_3" id="txt_signer_3" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Signer 4</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_signer_4" id="txt_signer_4" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Signer 5</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_signer_5" id="txt_signer_5" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="31%" height="30" valign="top"><strong>Minimum </strong></td>
                      <td width="69%" valign="top"><strong>Days</strong></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" name="txt_sig_min" id="txt_sig_min" style="width:80px"/></td>
                      <td><input class="form-control" name="txt_sig_days" id="txt_sig_days" style="width:80px"/></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>