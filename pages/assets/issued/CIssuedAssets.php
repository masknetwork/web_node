<?
class CIssuedAssets
{
	function CIssuedAssets($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	  function showIssuedAssets()
	  {
		 $query="SELECT *
		          FROM assets
				 WHERE assets.adr IN (SELECT adr 
				                        FROM my_adr 
									   WHERE userID='".$_REQUEST['ud']['ID']."')";
		 $result=$this->kern->execute($query);	
									   
		
		  ?>
            
             <br>
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right"><a href="#" onclick="$('#modal_issue').modal()" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Issue Asset</a></td>
                </tr>
              </tbody>
            </table>
            <br>
            
             <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="57%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="27%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="0%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="14%" align="center"><span class="inset_maro_14">Setup</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                  
                  <?
				     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					 {
				  ?>
                  
                        <tr>
                        <td width="57%" align="left"><a href="#" class="maro_12"><strong><? print base64_decode($row['title'])." (".$row['symbol'].")"; ?></strong></a><br><span class="simple_maro_10"><? print substr(base64_decode($row['description']), 0, 50)."..."; ?></span></td>
                        <td width="26%" align="center" class="simple_green_12"><strong><? print round($row['qty'], 8)." ".strtolower($row['symbol']); ?></strong></td>
                        <td width="17%" align="right" class="simple_maro_12">
                        
                          <div class="dropdown">
                 <button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                 <span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;<span class="caret"></span>
                 </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                  
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#symbol').val('<? print $row['symbol']; ?>'); $('#modal_renew').modal()">Renew</a></li>
                 
                 <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#symbol').val('<? print $row['symbol']; ?>'); $('#modal_increase_bid').modal()">Increase Bid</a></li>
                 
                 <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#symbol').val('<? print $row['symbol']; ?>'); $('#modal_edit').modal()">Edit Asset Data</a></li>
                 
                 <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#symbol').val('<? print $row['symbol']; ?>'); $('#modal_issue_more').modal()">Increase Supply</a></li>
                 
                 </ul>
                  </div>
                        
                        </td>
                        </tr>
                        <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                  
                  <?
	                  }
				  ?>
                  
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
                
            </table>
          
          <?
	  }
	  
	  function showIncreaseAssets()
	{
		$this->template->showModalHeader("modal_issue_more", "Issue More Assets", "act", "issue_more");
		
		?>
        
          <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30%" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/issue_more_assets.png" width="166" height="122" /></td>
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
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="70%" align="center" valign="top">
            
            <table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Qty</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name2" style="width:100px" name="txt_name2" placeholder="Asset Name (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table>
            
            </td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter(true, "Issue");
	}
	
	function showEditModal()
	{
		$this->template->showModalHeader("modal_edit", "Edit Asset Data", "act", "transfer", "edit_symbol", "");
		?>
        
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../template/template/GIF/edit_asset.png" width="182" height="163" /></td>
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
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name2" name="txt_name2" placeholder="Asset Name (5-30 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left"><textarea rows="5" id="txt_desc2" name="txt_desc2" class="form-control" style="width:350px" placeholder="Short Description (10-250 characters)"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Website</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name2" style="width:350px" name="txt_name2" placeholder="Asset Name (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Pic</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name2" style="width:350px" name="txt_name2" placeholder="Asset Name (5-30 characters)"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Transfer the fees to this address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_trans_fee_adr", "360"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
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
	
	function showIssueAssetModal()
	{
		$this->template->showModalHeader("modal_issue", "Issue New Asset", "act", "issue");
		?>
            
            
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/issue_asset.png" width="157" height="179" /></td>
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
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="438" align="right" valign="top"><table width="410" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "360"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_red_14"><strong>Asset Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "360"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name" name="txt_name" placeholder="Asset Name (5-30 characters)" style="width:360px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="5" id="txt_desc" name="txt_desc" class="form-control" style="width:360px" placeholder="Short Description (10-250 characters)"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_red_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="46%" height="30" align="left" valign="top" class="simple_red_14"><strong>Website</strong></td>
                    <td width="54%" align="left" valign="top" class="simple_red_14"><strong>Pic</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_website" name="txt_website" style="width:170px" placeholder="Wesite Link"/></td>
                    <td><input class="form-control" id="txt_pic" name="txt_pic" style="width:170px" placeholder="Link to Image"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="360" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top" class="simple_red_14"><strong>Symbol</strong></td>
                    <td width="33%" align="left" valign="top" class="simple_red_14"><strong>Initial Qty</strong></td>
                    <td width="33%" align="left" valign="top" class="simple_red_14"><strong>Transaction Fee</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_symbol" name="txt_symbol" placeholder="Symbol" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_init_qty" name="txt_init_qty" placeholder="Qty" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_trans_fee" name="txt_trans_fee" placeholder="Fee" style="width:100px"/></td>
                  </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_red_14"><strong>Transfer the fees to this address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_trans_fee_adr", "360"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top" class="simple_red_14"><strong>Market Days</strong></td>
                    <td width="33%" align="left" valign="top" class="simple_red_14"><strong>Market Bid</strong></td>
                    <td width="33%" align="left" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_mkt_days" name="txt_mkt_days" placeholder="Symbol" style="width:100px"/></td>
                    <td><input class="form-control" id="txt_mkt_bid" name="txt_mkt_bid" placeholder="Qty" style="width:100px"/></td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">
                <table width="360" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="10" align="center"><input name="can_issue" type="checkbox" id="can_issue"  style="width:30px" value="Y"/></td>
                    <td width="390" height="30" align="left" class="simple_blue_12"><strong>Issuer can increase asset supply (issue more units)</strong></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#form_modal_issue').submit(
		function() 
		{ 
		   $('#txt_name').val(btoa($('#txt_name').val())); 
		   $('#txt_desc').val(btoa($('#txt_desc').val())); 
		   $('#txt_website').val(btoa($('#txt_website').val())); 
		   $('#txt_pic').val(btoa($('#txt_pic').val())); 
		});
		</script>
        
        <?
		$this->template->showModalFooter();
	}
}
?>