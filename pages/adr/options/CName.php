<?
class CName
{
   function CName($db, $template)
   {
	   $this->kern=$db;
	   $this->template=$template;
   }
   
   function newDomain($net_fee_adr, $adr, $domain, $days)
	{
		// Standard checks
		if ($this->kern->standardCheck($this->template, $net_fee_adr, $adr, $days*0.0001)==false)
		   return false;
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days (minimum 1 day)", "90%");
			return false;
		}
	   
	   // Domain valid
	   if ($this->kern->isDomain($domain)==false)
	   {
		    $this->template->showErr("Invalid domain name", "90%");
		    return false;
	   }
	   
	   // Domain exist
	   if ($this->kern->domainExist($domain)==true)
	   {
		    $this->template->showErr("Domain already exist", "90%");
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
		   $this->template->showOk("Your request has been succesfully recorded", "90%");
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
	
   function showModal($attach_to="")
	{
		$this->template->showModalHeader("modal_new_domain", "New Address Name", "act", "rent_domain", "adr", "");
		?>
        
         <table width=""90%"" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_names.png" width="180" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                <? $this->template->shownetFeePanel(0.036, "nd"); ?>
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
                <td height="30" valign="top" class="simple_blue_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td><input name="txt_domain" class="form-control" id="txt_domain" style="width:300px" value="" maxlength="30"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="simple_blue_14"><strong>Days</strong></span></td>
              </tr>
              <tr>
                <td>
                <input type="number" min="10" step="1" name="txt_days_domain" class="form-control" id="txt_days_domain" style="width:100px" value="365" maxlength="6"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
       
        
        <?
		$this->template->showModalFooter();
	}
}
?>