<?
class CExchangers
{
	function CExchangers($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showNewTradePanel()
	{
		?>
        
           <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="520" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="155" align="center" valign="top"><table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td align="center"><img src="GIF/exchangers.png" width="150" height="162" alt=""/></td>
                            </tr>
                            <tr>
                              <td align="center">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" class="simple_maro_16"><strong>New Exchanger Offer</strong></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="365" align="right" valign="top"><table width="350" border="0" cellspacing="0" cellpadding="0">
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
                            <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Order Address</strong></span></td>
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
                            <td height="30" align="left" valign="top" class="simple_blue_14">
                            <input class="form-control" id="txt_title" name="txt_title" placeholder="Title (max 50 characters)" style="width:330px"/></td>
                          </tr>
                          <tr>
                            <td height="0" align="left">&nbsp;</td>
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
                              <input class="form-control" id="txt_webpage" name="txt_webpage" placeholder="Webpage Link" />
                            </span></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><strong>Type</strong></td>
                          </tr>
                          <tr>
                            <td align="left">
                            <select class="form-control" style="width:100px" id="pos_type" name="pos_type">
                              <option value="ID_BUY">Buy</option>
                              <option value="ID_SELL">Sell</option>
                            </select>
                            </td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                  <td width="50%" height="30" valign="top"><strong>Asset Symbol</strong></td>
                                  <td width="50%"><strong>Currency</strong></td>
                                </tr>
                                <tr>
                                  <td><span class="simple_blue_14">
                                    <input class="form-control" id="txt_asset" name="txt_asset" placeholder="XXXXXX" style="width:150px" />
                                  </span></td>
                                  <td>
                                  <select class="form-control" style="width:150px" id="pos_type" name="pos_type">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                  </select>
                                  </td>
                                </tr>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30" align="left" valign="top"><strong>Price Type</strong></td>
                          </tr>
                          <tr>
                            <td align="left">
                            <select class="form-control" style="width:100px" id="dd_price_type" name="dd_price_type">
                              <option value="ID_FIXED">Fixed</option>
                              <option value="ID_MOBILE">Based on Feed</option>
                            </select></td>
                          </tr>
                          <tr>
                            <td align="left">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody>
                                <tr>
                                  <td width="50%" height="30" valign="top"><strong>Feed Symbol</strong></td>
                                  <td width="50%"><strong>Feed Branch</strong></td>
                                </tr>
                                <tr>
                                  <td><span class="simple_blue_14">
                                    <input class="form-control" id="txt_feed_symbol" name="txt_feed_symbol" placeholder="XXXXXX" style="width:150px" />
                                  </span></td>
                                  <td>
                                   <input class="form-control" id="txt_feed_branch" name="txt_feed_branch" placeholder="XXXXXX" style="width:150px" />
                                  </td>
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
                      <tr>
                        <td colspan="2" align="center" valign="top" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" valign="top">&nbsp;</td>
                        <td align="right" valign="top"><a href="" onclick="" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Submit</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
            <br><br><br>
        
        <?
	}
}
?>