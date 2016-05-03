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
			$this->template->showErr("Invalid username length (5-15 characters)", 510);
			return false;
		}
		
		// User exist ?
		$query="SELECT * 
		          FROM web_users 
				 WHERE user='".$user."'";
		$result=$this->kern->execute($query);
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("User already exist", 510);
			return false;
		}
		
		// Password
		if (strlen($pass)<5 || strlen($pass)>25)
		{
			$this->template->showErr("Invalid password length", 510);
			return false;
		}
		
		// Passwwords match
		if ($pass!=$re_pass)
		{
			$this->template->showErr("Passwords don't match", 510);
			return false;
		}
		
		// Email used ?
		$query="SELECT * 
		          FROM web_users 
				 WHERE email='".$email."'";
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("Email is already used", 510);
			return false;
		}
		
		// Email valid
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email";
        }
		
		// IP
		if ($_SERVER['HTTP_CF_CONNECTING_IP']=="")
		  $IP=$_SERVER['REMOTE_ADDR'];
		else
		  $IP=$_SERVER['HTTP_CF_CONNECTING_IP'];
		  
		if ($IP!="89.38.169.57")
		{
		   $query="SELECT * 
		             FROM web_users
				    WHERE IP='".$IP."'";
	       $result=$this->kern->execute($query);
		
		   if (mysql_num_rows($result)>0)
	   	   {
			  $this->template->showErr("One account per IP allowed.", 510);
			  return false;
		   }
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();
		   
		   // Creates account
		   $query="INSERT INTO web_users 
		                   SET user='".$user."', 
						       pass='".hash("sha256", $pass)."', 
							   IP='".$IP."', 
							   tstamp='".time()."',
							   email='".$email."'";
		   $this->kern->execute($query);
		   
		   // UserID
		   $userID=mysql_insert_id();
		   
		   // Creates adress
		   $query="INSERT INTO web_ops 
			                SET user='".$user."', 
							    op='ID_NEW_ACCOUNT', 
								par_1='secp224r1', 
								par_2='".base64_encode("Initial address")."', 
								par_3='".$userID."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	        $this->kern->execute($query); 
			
			// set session
			$_SESSION['userID']=$userID;
		    
			// Commit
	 	    $this->kern->commit();

	
			// Redirect
			print "<script>window.location='../../transactions/all/index.php'</script>";
		   
		   return true;
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 510);

		  return false;
	   }
	}
	
	function showSignupPanel()
	{
		?>
           
           <form method="post" action="index.php?act=signup">
           <table width="90%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f5f5f5">
            <tbody>
              <tr>
                <td align="left" class="txt_login_title">New Account</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><input class="form-control input-lg" id="txt_user" name="txt_user" placeholder="Username" required></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><input class="form-control input-lg" id="txt_email" name="txt_email" placeholder="Email" type="email"  required></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
              <td><input class="form-control input-lg" id="txt_pass_1" name="txt_pass_1" placeholder="Password" type="password" required></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><input class="form-control input-lg" id="txt_pass_2" name="txt_pass_2" type="password" placeholder="Retype Password" required></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><button class="btn btn-primary btn-hg" style="width:100%;" id="but_login">Signup</button></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </tbody>
            </table>
            </form>
      
        <?
	}
}
?>