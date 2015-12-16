<?
class CWebIPN
{
	function CWebIPN($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function update($adr, $email, $web_adr, $pass)
	{
		// Adr
		if ($this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Web adr
		if ($web_adr!="")
		{
		  if (filter_var($web_adr, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid web link", 550);
			return false;
		  }
		}
		
		
		// Pass
		if (strlen($pass)<5 || strlen($pass)>25)
		{
			$this->template->showErr("Password is a string 5-25 characters long", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates Web IPN data");
		
		   // IPN already setup
		   $query="SELECT * 
		             FROM ipn 
					WHERE adr='".$adr."'";
		   $result=$this->kern->execute($query);	
	       
		   if (mysql_num_rows($result)>0)
		      $query="UPDATE ipn 
			             SET web_link='".$web_adr."', 
						     web_pass='".$pass."',
							 email='".$email."' 
					   WHERE adr='".$adr."'";
		   else
		   $query="INSERT INTO ipn 
			               SET adr='".$adr."',
						       web_link='".$web_adr."', 
						       web_pass='".$pass."',
							   email='".$email."'";
		   
		   // Execute
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
		$this->template->showModalHeader("modal_web_ipn", "Web Instant Payment Notification", "act", "web_ipn", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_web_ipn.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Notify this web address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_ipn_web_adr" id="txt_ipn_web_adr" style="width:340px" placeholder="Web Link"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Web Password</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input name="txt_ipn_pass" class="form-control" id="txt_ipn_pass" style="width:340px"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Activate");
	}
}
?>