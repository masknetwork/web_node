<?
class CProfile
{
	function CProfile($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function updateProfile($net_fee_adr, 
	                       $adr, 
						   $name, 
						   $desc, 
						   $email, 
						   $tel, 
						   $website, 
						   $fb, 
						   $pic,
						   $days)
	{
		// Decode
		$name=base64_decode($name);
		$desc=base64_decode($desc);
		$email=base64_decode($email);
		$tel=base64_decode($tel);
		$website=base64_decode($website);
		$fb=base64_decode($fb);
		$pic=base64_decode($pic);
		$days=base64_decode($days);
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days", 550);
			return false;
		}
		
		// Balance
		$balance=$this->kern->getBalance($net_fee_adr);
		
		// Funds
		if ($balance<0.0001*$days)
		{
			$this->template->showErr("Insufficient funds to execute this operation", 550);
			return false;
		}
		
		// Name
		if (strlen($name)>50)
		{
			$this->template->showErr("Invalid name length", 550);
			return false;
		}
		
		// Description
		if (strlen($desc)>50)
		{
			$this->template->showErr("Invalid description length", 550);
			return false;
		}
		
		// Email
		if ($email!="")
		{
		   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		   {
    		  $this->template->showErr("Invalid email", 550);
			  return false;
		   }
		}
		
		// Telephone
		if ($tel!="")
		{
		  if (strlen($tel)>25) 
		  {
    		$this->template->showErr("Invalid telephone", 550);
			return false;
		  }
		}
		
		// Website
		if ($website!="")
		{
		  if (filter_var($website, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid website", 550);
			return false;
		  }
		}
			
		// Facebook
		if ($fb!="")
		{
		  if (filter_var($fb, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid facebook profile", 550);
			return false;
		  }
		}
		
		// Pic
		if ($pic!="")
		{
		  if (filter_var($pic, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid avatar link", 550);
			return false;
		  }
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates a profile");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_UPDATE_PROFILE', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".$name."',
							   par_2='".$desc."',
							   par_3='".$email."',
							   par_4='".$tel."',
							   par_5='".$website."',
							   par_6='".$fb."',
							   par_7='".$pic."',
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
	
	function showModal()
	{
		$this->template->showModalHeader("modal_profile", "Receive Interest", "act", "update_profile", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="152" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_profile.png" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001", "profile"); ?></td>
              </tr>
              </table></td>
            <td width="398" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_net_fee", 350); ?></td>
              </tr>
              <tr>
                <td height="0" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" name="txt_prof_name" id="txt_prof_name" style="width:350px" placeholder="Name (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea class="form-control" id="txt_desc" name="txt_desc" style="width:350px" rows="4" placeholder="Short Descripion (0-250 characters)"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="51%" height="30" valign="top"><strong>Email</strong></td>
                      <td width="49%" valign="top"><strong>Telephone</strong></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" name="txt_email" id="txt_email" style="width:150px" placeholder="Email"/></td>
                      <td><input class="form-control" name="txt_tel" id="txt_tel" style="width:150px" placeholder="Telephone"/></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="51%" height="30" valign="top"><strong>Website</strong></td>
                      <td width="49%" valign="top"><strong>Facebook Profile</strong></td>
                    </tr>
                    <tr>
                      <td>
                      <input class="form-control" name="txt_web" id="txt_web" style="width:150px" placeholder="Website"/></td>
                      <td>
                      <input class="form-control" name="txt_fb" id="txt_fb" style="width:150px" placeholder="Profile Link"/></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Profile Pic Link</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" name="txt_pic" id="txt_pic" style="width:350px" placeholder="Profile Pic Link (5-100 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="23%" height="30" align="left" valign="top"><strong>Days</strong></td>
                    <td width="77%" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><input class="form-control" name="txt_days" id="txt_days" style="width:70px" placeholder="365" value="365"/></td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
         <script>
		 $('#form_modal_profile').submit(
		 function() 
		 {
		   $('#txt_prof_name').val(btoa($('#txt_prof_name').val())); 
		   $('#txt_desc').val(btoa($('#txt_desc').val())); 
		   $('#txt_email').val(btoa($('#txt_email').val())); 
		   $('#txt_tel').val(btoa($('#txt_tel').val())); 
		   $('#txt_web').val(btoa($('#txt_web').val())); 
		   $('#txt_fb').val(btoa($('#txt_fb').val())); 
		   $('#txt_pic').val(btoa($('#txt_pic').val())); 
		   $('#txt_days').val(btoa($('#txt_days').val())); 
		});
		</script>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>