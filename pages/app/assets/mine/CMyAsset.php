<?
  class CMyAssets
  {
	  function CMyAssets($db, $template)
	  {
		$this->kern=$db;
		$this->template=$template;
	  }  
	  
	  function showTransferModal()
	  {
		$this->template->showModalHeader("modal_transfer", "Transfer Assets", "act", "transfer", "asset_symbol", "");
		?>
        
           <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/transfer_asset.png" width="173" height="190" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_as_net_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Send From Address</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_as_from" name="txt_as_from" placeholder="" style="width:350px" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient Address</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_as_to" name="txt_as_to" placeholder="Address or address name" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Message</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="5" id="txt_as_mes" name="txt_as_mes" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px">
                </textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Amount</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_as_amount" name="txt_as_amount" placeholder="0.0000" style="width:100px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Escrower</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_as_escrower" name="txt_as_escrower" placeholder="Asset Name (5-30 characters)" style="width:350px"/>
                </td>
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
		$this->template->showModalFooter(true, "Transfer");
	}
	
	  function showMyAssets()
	  {
		  $this->showTransferModal();
		  
		  $query="SELECT ao.*, assets.*, ao.qty AS own_qty
		            FROM assets_owners AS ao 
				    JOIN assets ON assets.symbol=ao.symbol 
				   WHERE ao.owner IN (SELECT adr 
				                        FROM my_adr 
									   WHERE userID='".$_REQUEST['ud']['ID']."')"; 
									   
		 $result=$this->kern->execute($query);	
		 
		  ?>
             
             <br><br>
             <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="57%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="21%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="18%" align="center"><span class="inset_maro_14">Send</span></td>
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
                          <td width="10%" align="left">
                          <img src="../../template/template/GIF/empty_pic_prod.png" width="45" height="45" class="img-circle"></td>
                        <td width="48%" align="left"><a href="#" class="maro_12"><strong><? print base64_decode($row['title'])." (".$row['symbol'].")"; ?></strong></a><br><span class="simple_maro_10"><? print substr(base64_decode($row['description']), 0, 50)."..."; ?></span></td>
                        <td width="24%" align="center" class="simple_green_12"><strong><? print round($row['own_qty'], 8)." ".strtolower($row['symbol']); ?></strong></td>
                        <td width="18%" align="right" class="simple_maro_12"><a href="#" onclick="$('#modal_transfer').modal(); $('#txt_as_from').val('<? print $row['owner']; ?>'); $('#asset_symbol').val('<? print $row['symbol']; ?>');" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-share-alt">&nbsp;</span>Send</a></td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
  }
?>