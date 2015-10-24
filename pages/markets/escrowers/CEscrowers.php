<?
class CEscrowers
{
	function CEscrowers($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showButtons()
	{
		?>
        
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="20%" align="right">
                  <a href="#" onclick="$('#modal_new_offer').modal();" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Offert </a></td>
                  <td width="21%" align="right">
                  <a href="my_offerts.php" class="btn btn-warning"><span class="glyphicon glyphicon-th-list"></span>&nbsp;My Offerts </a></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
	
	function showNewPosModal()
	{
		// Header
		$this->template->showModalHeader("modal_new_offer", "New Offer", "act", "new_offer", "tip", "");
		?>
            
            <input type="hidden" id="mkt_symbol" name="mkt_symbol" value="<? print $mkt_symbol; ?>" />
            <table width="610" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/escrowers.png" width="180" height="159" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showWebsiteCodePanel(); ?></td>
              </tr>
            </table></td>
            <td width="438" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Escrower Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Title</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_title" name="txt_title" placeholder="Title (max 50 characters)" style="width:330px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Description</strong></td>
              </tr>
              <tr>
                <td align="left"><textarea id="txt_desc" name="txt_desc" rows="5" class="form-control" style="width:330px"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Webpage</strong></td>
              </tr>
              <tr>
                <td align="left"><span class="simple_blue_14">
                  <input class="form-control" id="txt_webpage" name="txt_webpage" placeholder="Webpage Link" style="width:330px"/>
                </span></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="33%" align="left" valign="top"><strong>Market Bid</strong></td>
                      <td width="33%" align="left" valign="top"><strong>Market Days</strong></td>
                      <td width="33%" height="30" align="left" valign="top"><strong>Escrower Fee</strong></td>
                    </tr>
                    <tr>
                      <td width="33%">
                      <input class="form-control" id="txt_mkt_bid" name="txt_title" placeholder="0.0001" style="width:100px"/></td>
                      <td width="33%">
                      <input class="form-control" id="txt_mkt_days" name="txt_mkt_days" placeholder="100" style="width:100px"/></td>
                      <td width="33%">
                      <input class="form-control" id="txt_fee" name="txt_fee" placeholder="1%" style="width:100px"/></td>
                    </tr>
                  </tbody>
                </table></td>
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
		$this->template->showModalFooter();
	}
}
?>