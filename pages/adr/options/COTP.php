<?
class COTP
{
	function COTP($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function otp($net_fee_adr, $adr, $next_pass, $alt_adr, $days)
	{
		// Addresses valid
		if ($this->kern->adrValid($net_fee_adr)==false || 
		    $this->kern->adrValid($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Alternative address
		if ($alt_adr!="")
		{
			  if ($this->kern->adrValid($alt_adr)==false)
			  {
				   $this->template->showErr("Invalid entry data", 550);
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
							    op='ID_OTP', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".hash("sha256", $next_pass)."',
								par_2='".$alt_adr."',
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
		$this->template->showModalHeader("modal_otp", "Activate One Time Password", "act", "otp_adr", "adr", "");
		
		$pass=substr(hash("sha256", rand(0, 1000000000)), 0, 20);
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_otp.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_otp_net_fee", 340); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Emergency Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_otp_adr" id="txt_otp_adr" style="width:340px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_otp_days" id="txt_otp_days" style="width:80px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="16%" rowspan="3" align="center" valign="top"><img id="qr_img" name="qr_img" src="../../../qr/qr.php?qr=<? print $pass; ?>" /></td>
                    <td width="3%">&nbsp;</td>
                    <td width="81%" align="left" class="simple_gri_12">Next Password</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" class="simple_green_18"><strong>
					<input name="txt_next_pass" id="txt_next_pass" type="hidden" value="<? print $pass; ?>">
					<? print $pass; ?>
                    </strong></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" bgcolor="#fafafa" class="simple_gri_10">You will need to provide this password next time you want to spend coins / assets from this address. </td>
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
		$this->template->showModalFooter("Cancel", "Activate");
	}
}
?>