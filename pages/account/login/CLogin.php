<?
class CLogin
{
	function CLogin($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLoginPanel()
	{
		?>
           
           <form method="post" action="index.php?act=login">
           <table width="90%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f5f5f5">
            <tbody>
              <tr>
                <td align="left" class="txt_login_title">Login to Your Account</td>
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
                <td><input class="form-control input-lg" id="txt_pass" name="txt_pass" placeholder="Password" type="password" required></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><button class="btn btn-primary btn-lg" style="width:100%;" id="but_login">Login</button></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </tbody>
            </table>
            </form>
        
        <?
	}
	
	function doLogin($user, $pass)
	{
	    if (strlen($user)<4 || strlen($user)>20)
		{
			$this->template->showerr("Invalid username or password", 460);
			return false;
		}
		
		if (strlen($pass)<4 || strlen($pass)>20)
		{
			$this->template->showerr("Invalid username or password", 460);
			return false;
		}
		
		$query="SELECT * 
		          FROM web_users 
				 WHERE user='".$user."'";
		$result=$this->kern->execute($query);
		if (mysql_num_rows($result)==0)
		{
			$this->template->showerr("Invalid username or password", 460);
			return false;
		}
		else
		{	
			// Load data
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			// set session
			$_SESSION['userID']=$row['ID'];
			
			// New action
			$this->kern->newAct("ID_LOGIN");
			
			// Redirect
			print "<script>window.location='../../transactions/all/index.php'</script>";
		}
		
	}
}
?>