<?
class CSignup
{
	function CSignup($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function doSignup($user, $pass, $re_pass, $email)
	{
		// User
		if (strlen($user)<5 || strlen($user)>15)
		{
			$this->template->showErr("Invalid username length (5-15 characters)");
			return false;
		}
		
		// User exist ?
		$query="SELECT * 
		          FROM users 
				 WHERE user='".$user."'";
		$result=$this->kern->execute($query);
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("User already exist");
			return false;
		}
		
		// Password
		if (strlen($pass)<5 || strlen($pass)>25)
		{
			$this->template->showErr("Invalid password length");
			return false;
		}
		
		// Passwwords match
		if ($pass!=$re_pass)
		{
			$this->template->showErr("Passwords don't match");
			return false;
		}
		
		// Email used ?
		$query="SELECT * 
		          FROM users 
				 WHERE email='".$email."'";
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("Email is already used");
			return false;
		}
		
		// Email valid
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email";
        }
		
		try
	    {
		   // Begin
		   $this->kern->begin();
		   
		   // Creates account
		   $query="INSERT INTO web_users 
		                   SET user='".$user."', 
						       pass='".hash("sha256", $pass)."', 
							   email='".$email."'";
		   $this->kern->execute($query);
		   
		   // UserID
		   $userID=mysql_insert_id();
		   
		   // Creates adress
		   $query="INSERT INTO web_ops 
			                SET user='".$user."', 
							    op='ID_NEW_ADR', 
								par_1='secp224r1', 
								par_2='".base64_encode("Initial address")."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	        $this->kern->execute($query);
			
			// set session
			$_SESSION['userID']=$userID;
			
			// Redirect
			print "<script>window.location='../../transactions/all/index.php'</script>";
		
		   // Commit
		   $this->kern->commit();

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
	
	function showSignupPanel()
	{
		?>
           
            <form id="form_login" name="form_login" method="post" action="index.php?act=signup">
           <table width="465" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="350" height="45" align="center" background="../GIF/panel_top.png" class="bold_shadow_white_14">Signup</td>
    </tr>
    <tr>
      <td height="250" align="center" valign="top" background="../GIF/panel_middle.png"><table width="430" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top" class="simple_red_16"><strong>Username</strong></td>
          </tr>
          <tr>
            <td align="left"><input class="form-control" id="txt_user" name="txt_user"></td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top" class="simple_red_16"><strong>Password</strong></td>
          </tr>
          <tr>
            <td align="left"><input class="form-control" type="password" id="txt_pass" name="txt_pass"></td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top"><span class="simple_red_16"><strong>Confirm Password</strong></span></td>
          </tr>
          <tr>
            <td align="left"><input class="form-control" type="password" id="txt_pass_retype" name="txt_pass_retype"></td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top"  class="simple_red_16"><strong>Email</strong></td>
          </tr>
          <tr>
            <td align="left"><input class="form-control" id="txt_email" name="txt_email"></td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="67" align="center" background="../GIF/panel_bottom.png"><table width="430" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="right"><a class="btn btn-success" href="#" onClick="$('#form_login').submit()"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Signup</a></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
</form>
        
        <?
	}
}
?>