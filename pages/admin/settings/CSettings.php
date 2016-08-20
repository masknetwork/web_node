<?
class CSettings
{
	function CSettings($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function changePass($old_pass, $new_pass, $new_pass_retype)
	{
		// Old pass ok ?
		$query="SELECT * 
		          FROM web_users 
				 WHERE user='root' 
				   AND pass='".hash("sha256", $old_pass)."'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid old password");
			return false;
		}
		
		// New pass valid
		if (strlen($new_pass)>25 || strlen($new_pass)<5)
		{
			$this->template->showErr("Password is a 5-25 characters string");
			return false;
		}
		
		// Password match ?
		if ($new_pass!=$new_pass_retype)
		{
			$this->template->showErr("Invalid passwords");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Change root password");
		   
		   // Change pass
		   $query="UPDATE web_users 
		              SET pass='".hash("sha256", $new_pass)."' 
				    WHERE user='root'"; 
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
	
	function restrictIP($list)
	{
		// No space
		$list=str_replace(" ", "", $list);
		
	    // Split
		$v=explode(",", $list);
		
		// Check IP
		for ($a=0; $a<=sizeof($v); $a++)
		{
		   if ($this->kern->isIP($v[$a])==false)
		   {
			   $this->template->showErr("Invalid IP ".$v[$a]);
			   return false;
		   }
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Update IPs");
		   
		   // Change pass
		   $query="UPDATE web_sys_data 
		              SET root_whitelist_ip='".$list."'"; 
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
	
	function setReward($adr, $reward)
	{
	    // Address valid
		if ($this->kern->isAdr($adr)==false || 
	        $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid address");
			 return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Set reward");
		   
		   // Change pass
		   $query="UPDATE web_sys_data 
		              SET new_acc_reward_adr='".$adr."', 
					      new_acc_reward='".$reward."'"; 
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
	
	function showChangePassModal()
	{
		$this->template->showModalHeader("modal_change_pass", "Change Password", "act", "change_pass", "", "");
		?>
            
            <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center" valign="top"><img src="GIF/pass.png" width="150" height="150" alt=""/></td>
            <td width="443" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"  class="font_14"><strong>Old password</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input id="txt_old_pass" name="txt_old_pass" class="form-control" placeholder="Old Password"  type="password">
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>New password</strong></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_new_pass" name="txt_new_pass" class="form-control" placeholder="New password" type="password"></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Retype new password</strong></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_new_pass_retype" name="txt_new_pass_retype" class="form-control" placeholder="Retype new password"  type="password"></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table></td>
            </tr>
            </table>
        
        <?
		$this->template->showModalFooter("Change");
	}
	
	function showRestrictIPModal()
	{
		$this->template->showModalHeader("modal_restrict", "Restrict IPs", "act", "restrict", "", "");
		?>
            
           <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center" valign="top"><img src="GIF/ip.png" width="150" height="150" alt=""/></td>
            <td width="443" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"  class="font_14"><strong>Whitelist IP (comma separated)</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea id="txt_ip" name="txt_ip" class="form-control" rows="3"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table></td>
            </tr>
            </table>
        
        <?
		$this->template->showModalFooter("Update");
	}
	
	function showRewardModal()
	{
		$this->template->showModalHeader("modal_reward", "New Accounts Reward", "act", "reward", "", "");
		?>
            
           <table width="580" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="147" align="center" valign="top"><img src="GIF/reward.png" width="150" height="150" alt=""/></td>
            <td width="443" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>New accounts reward</strong></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_reward_adr" name="txt_reward_adr" class="form-control" placeholder="Reward Address"></td>
              </tr>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="font_14"><strong>Reward</strong></span></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_reward_amount" name="txt_reward_amount" class="form-control" type="number" style="width:100px" step="0.0001"></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table></td>
            </tr>
            </table>
        
        <?
		$this->template->showModalFooter("Update");
	}
}
?>