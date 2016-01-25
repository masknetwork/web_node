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
						   $website, 
						   $pic_back,
						   $pic,
						   $days)
	{
		// Decode
		$name=base64_decode($name);
		$desc=base64_decode($desc);
		$email=base64_decode($email);
		$website=base64_decode($website);
		$pic_back=base64_decode($pic_back);
		$pic=base64_decode($pic);
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// My address
	    if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
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
		if (strlen($desc)>500)
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
           $this->kern->newAct("Updates a profile");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_UPDATE_PROFILE', 
							   fee_adr='".$net_fee_adr."', 
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
		$this->template->showModalHeader("modal_profile", "Receive Interest", "act", "update_profile", "adr", "");
		?>
           
           <input id="fileupload" type="file" name="files[]" data-url="../../tweets/home/server/php/" multiple style="display:none">
           <input type="hidden" id="h_pic_back" name="h_pic_back" value="">
           <input type="hidden" id="h_pic" name="h_pic" value="">
           
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
                <td align="center"><? $this->template->showNetFeePanel("0.0365", "profile"); ?></td>
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
                      <td width="49%" valign="top"><strong>Website</strong></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" name="txt_email" id="txt_email" style="width:150px" placeholder="Email"/></td>
                      <td><input class="form-control" name="txt_web" id="txt_web" style="width:150px" placeholder="Website"/></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
             
               <tr id="row_progress">
               <td height="60" align="left" valign="bottom">
               <div id="progress" class="progress" style="width:350px">
               <div class="progress-bar progress-bar-success">&nbsp;</div>
               </div>
               </td>
               </tr>
             
             <tr>
                <td height="170" align="left" valign="top">
                <img src="../../../crop.php?src=./pages/adr/options/GIF/drop.png&w=350"  id="pic_back" class="img-responsive img-rounded">
                <img src="../../../crop.php?src=./pages/adr/options/GIF/drop_pic.png&w=100" id="pic" style="position:absolute; top:70%; left:40%; border:solid; border-width:4px; border-color:#ffffff" class="img-responsive img-rounded">
                <a href="javascript:void(0)" onclick="$('#tab_links').css('display', 'block'); $('#pic_back').css('display', 'none'); $('#pic').css('display', 'none'); $('#row_progress').css('display', 'none'); $(this).css('display', 'none');" class="font_12" style="position:absolute; top:78%; left:60%;">Use Links</a>
                
                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:none;" id="tab_links">
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
               <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="23%" height="30" align="left" valign="top"><strong>Days</strong></td>
                   <td width="77%" align="left">&nbsp;</td>
                 </tr>
                 <tr>
                   <td>
                   <input class="form-control" type="number" min="10" step="1" name="txt_prof_days" id="txt_prof_days" style="width:100px" placeholder="365" value="365"/></td>
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
		   $('#txt_web').val(btoa($('#txt_web').val())); 
		   $('#txt_pic_back').val(btoa($('#txt_pic_back').val())); 
		   $('#txt_pic').val(btoa($('#txt_pic').val())); 
		});
		
		linkToNetFee("txt_prof_days", "profile_net_fee_panel_val", 0.0365);
		</script>
        
        <?
		$this->template->showModalFooter("Activate");
	}
}
?>