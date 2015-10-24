<?
class CEmailIPN
{
	function CEmailIPN($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showModal()
	{
		$this->template->showModalHeader("modal_email_ipn", "Email Instant Payment Notification", "act", "web_ipn", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="../../GIF/adr_opt_email_ipn.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Send me an email to this address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_tag2" id="txt_tag2" style="width:340px"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Notify me when</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="85%" height="40" align="left">When a new transaction is received</td>
                    <td width="15%" height="30" align="center"><? $this->template->showSwitch("sw_email_new_trans", "off"); ?></td>
                  </tr>
                  <tr>
                    <td height="40" align="left">When the transaction is cleared</td>
                    <td height="30" align="center"><? $this->template->showSwitch("sw_email_cleared_trans", "off"); ?></td>
                  </tr>
                  <tr>
                    <td height="40" align="left">When i receive a new message</td>
                    <td height="30" align="center"><? $this->template->showSwitch("sw_email_new_mes", "off"); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>