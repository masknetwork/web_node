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
           
            <form id="form_login" name="form_login" method="post" action="index.php?act=login">
           <table width="465" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td width="350" height="45" align="center" background="../GIF/panel_top.png" class="bold_shadow_white_14">Login</td>
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
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="67" align="center" background="../GIF/panel_bottom.png"><table width="430" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="right"><a class="btn btn-success" href="#" onClick="$('#form_login').submit()"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Login</a></td>
          </tr>
        </tbody>
      </table></td>
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
			$this->template->showerr("Invalid username", 450);
			return false;
		}
		
		if (strlen($pass)<4 || strlen($pass)>20)
		{
			$this->template->showerr("Invalid password", 450);
			return false;
		}
		
		$query="SELECT * 
		          FROM web_users 
				 WHERE user='".$user."' 
				   AND pass='".hash("sha256", $pass)."'";
		$result=$this->kern->execute($query);
		if (mysql_num_rows($result)==0)
		{
			$this->template->showerr("Invalid username or password", 450);
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