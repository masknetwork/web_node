<?
class CProfile
{
	function CProfile($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function updateProfile($adr, 
						   $name, 
						   $desc, 
						   $email, 
						   $website, 
						   $pic_back,
						   $pic,
						   $days,
						   $sign="")
	{
		// Decode
		$name=base64_decode($name);
		$desc=base64_decode($desc);
		
		// Fee Address
		if ($this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->canSpend($adr)==false)
		{
			$this->template->showErr("This address is associated with a speculative market, contract or market pegged asset.", 550);
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days", 550);
			return false;
		}
		
		// Balance
		$balance=$this->kern->getBalance($adr);
		
		// Funds
		if ($balance<0.0001*$days)
		{
			$this->template->showErr("Insufficient funds to execute this operation", 550);
			return false;
		}
		
		// Name
		if ($this->kern->isTitle($name)==false)
		{
			$this->template->showErr("Invalid name", 550);
			return false;
		}
		
		// Description
		if ($this->kern->isDesc($desc)==false)
		{
			$this->template->showErr("Invalid name", 550);
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
		
		// Website
		if ($website!="")
		{
		   if (strpos($website, "http:")===false) $website="http://".$website;
		
		
		  if (filter_var($website, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid website", 550);
			return false;
		  }
		}
			
		
		// Pic back
		if ($pic_back!="")
		{
		  if (filter_var($pic_back, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic link", 550);
			return false;
		  }
		}
		
		// Pic
		if ($pic!="")
		{
		  if (filter_var($pic, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic link", 550);
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
							   fee_adr='".$adr."', 
							   target_adr='".$adr."',
							   par_1='".base64_encode($name)."',
							   par_2='".base64_encode($desc)."',
							   par_3='".base64_encode($email)."',
							   par_4='".base64_encode($website)."',
							   par_5='".base64_encode($pic_back)."',
							   par_6='".base64_encode($pic)."',
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
		$this->template->showModalHeader("modal_profile", "Profile Setup", "act", "update_profile", "adr", "");
		?>
           
           <input id="fileupload" type="file" name="files[]" data-url="../../tweets/home/server/php/" multiple style="display:none">
           <input type="hidden" id="h_pic_back" name="h_pic_back" value="">
           <input type="hidden" id="h_pic" name="h_pic" value="">
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="152" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_profile.png" class="img-circle" width="150px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0365", "profile"); ?></td>
              </tr>
              </table></td>
            <td width="380" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" name="txt_prof_name" id="txt_prof_name" style="width:350px" placeholder="Name (2-100 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea class="form-control" id="txt_desc" name="txt_desc" style="width:350px" rows="3" placeholder="Short Descripion (5-1000 characters)"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="51%" height="30" valign="top"><strong class="font_14">Email</strong>&nbsp;<span class="font_12">(optional)</span></td>
                      <td width="49%" valign="top" class="font_14"><strong>Website</strong>&nbsp;<span class="font_12">(optional)</span></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" name="txt_email" id="txt_email" style="width:150px" placeholder="Email"/></td>
                      <td><input class="form-control" name="txt_web" id="txt_web" style="width:150px" placeholder="Website"/></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
             
              <tr>
                <td height="130" align="left" valign="top">
               
                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tab_links">
                  <tbody>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="60px"><input class="form-control" name="txt_pic_back" id="txt_pic_back" style="width:350px" placeholder="Back pic link"/></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" name="txt_pic" id="txt_pic" style="width:350px" placeholder="Main pic link"/></td>
                    </tr>
                  </tbody>
                </table>
                
                
                </td>
              </tr>
             
             <tr>
               <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="23%" height="30" align="left" valign="top" class="font_14"><strong>Days</strong></td>
                    <td width="77%" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><input class="form-control" type="number" min="10" step="1" name="txt_prof_days" id="txt_prof_days" style="width:100px" placeholder="365" value="365"/></td>
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
		});
		
	
		</script>
        
        <?
		$this->template->showModalFooter("Activate");
	}
}
?>