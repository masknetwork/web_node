<?
class CAutoTrans
{
	function CAutoTrans($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showModal()
	{
		$this->template->showModalHeader("modal_autotrans", "Autotransfers", "act", "autotrans", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="../../GIF/adr_opt_autoresp.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_net_fee", 340); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_field_1_min" id="txt_field_1_min" style="width:340px" placeholder="Min"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Currency</strong></td>
                    <td width="33%" height="30" align="left" valign="top"><strong>Thresold</strong></td>
                    <td width="33%" height="30" align="left" valign="top"><strong>Qty</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" name="txt_field_1_min2" id="txt_field_1_min2" style="width:80px" placeholder="Min"/></td>
                    <td><input class="form-control" name="txt_field_1_min3" id="txt_field_1_min3" style="width:80px" placeholder="Min"/></td>
                    <td><input class="form-control" name="txt_field_1_min4" id="txt_field_1_min4" style="width:80px" placeholder="Min"/></td>
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
		$this->template->showModalFooter("Activate");
	}
}
?>