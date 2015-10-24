<?
class CBranch
{
	function CBranch($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showInjectModal()
	{
		$this->template->showModalHeader("modal_inject", "Inject Data", "act", "inject", "feed_symbol", "");
		?>
        
        <input type="hidden" id="branch_symbol" name="branch_symbol" value="0" />
        
        <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/inject.png"  /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" height="300" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Value</strong></td>
              </tr>
              <tr>
                <td align="left"><input name="txt_value" class="form-control" id="txt_value" placeholder="0.0000" style="width:90px" maxlength="6"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter(true, "Send");
	}
	
}
?>