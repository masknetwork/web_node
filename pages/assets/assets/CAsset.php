<?
class CAsset
{
	function CAsset($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showAssetPage($symbol)
	{
		$this->showAssetPanel($symbol);
		$this->showAssetTabs($symbol);
		$this->template->showMenu("Owners", "Transactions", "Markets", "Exchangers", "Spend It");
		
		$this->showOwners($symbol);
		$this->showTrans($symbol);
		$this->showMarkets($symbol);
	}
	
	function showAssetPanel($symbol)
	{
		$query="SELECT *  
		          FROM assets 
				 WHERE symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
	  
		?>
           
           <br><br>
<table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" align="left" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;&nbsp;<? print base64_decode($row['title']); ?></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="36%" align="left" valign="top"><img src="../../template/template/GIF/empty_pic_prod.png" width="150" height="150" class="img-circle" /></td>
                        <td width="64%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td class="simple_maro_12"><? print base64_decode($row['description']); ?></td>
                            </tr>
                            <tr>
                              <td background="../../template/template/GIF/lc.png">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td width="37%" align="right" class="simple_maro_12">Issuer&nbsp;&nbsp;</td>
                                <td width="63%" height="30"><a href="#" class="red_12"><strong>
								<? 
								   print $this->template->formatAdr($row['adr']); 
								?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Fee Address&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print $this->template->formatAdr($row['trans_fee_adr']); ?></strong></a></td>
                              </tr>
                              </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	
	function showAssetTabs($symbol)
	{
		$query="SELECT * 
		          FROM assets 
				 WHERE symbol='".$symbol."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="95" align="center" valign="top" background="../../template/template/GIF/4_panels.png"><table width="530" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr class="bold_shadow_white_12">
                        <td width="105" height="25" align="center" valign="bottom">Transaction Fee</td>
                        <td width="39" align="center" valign="bottom">&nbsp;</td>
                        <td width="101" align="center" valign="bottom"> Qty</td>
                        <td width="35" align="center" valign="bottom">&nbsp;</td>
                        <td width="106" align="center" valign="bottom">Expire</td>
                        <td width="30" align="center" valign="bottom">&nbsp;</td>
                        <td width="114" align="center" valign="bottom">Can issue more</td>
                      </tr>
                      <tr class="simple_red_22">
                        <td height="40" align="center" valign="bottom" class="simple_red_18"><strong><? print $row['trans_fee']; ?>%</strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['qty']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong>5</strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="<? if ($row['can_increase']=="Y") print "simple_green_18"; else print "simple_red_18"; ?>"><strong><? if ($row['can_increase']=="Y") print "Yes"; else print "No"; ?></strong></td>
                      </tr>
                      <tr class="simple_blue_10">
                        <td height="0" align="center" valign="bottom">per transaction</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom"><? print $row['symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">months</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	
	function showOwners($symbol)
	{
		$query="SELECT * 
		          FROM assets_owners 
				 WHERE symbol='".$symbol."'
			  ORDER BY qty DESC";
	    $result=$this->kern->execute($query);	
	    
		?>
           
           <div id="div_owners" name="div_owners">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="78%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="0%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="22%" align="center"><span class="inset_maro_14">Amount</span></td>
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
                          <td width="10%" align="left" class="simple_maro_12">
                          <img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle"/></td>
                        <td width="79%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12">
                        <strong><? print $this->template->formatAdr($row['owner']); ?></strong>
                        </a>
                        </td>
                        
                        <td width="11%" align="center" class="simple_maro_12">
                        <span class="simple_green_12"><strong><? print $row['qty']; ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        
                        </td>
                        </tr>
                        <tr>
                        <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                        </tr>
              
                   
                    
            </table>
            <br><br><br>
            </div>
           
        
        <?
	}
	
	function showTrans($symbol)
	{
		$query="SELECT * 
		          FROM trans 
				 WHERE cur='".$symbol."'
			  ORDER BY ID DESC
			     LIMIT 0,20";
	    $result=$this->kern->execute($query);	
	    
		?>
           
           <div id="div_trans" name="div_trans" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="64%" align="left" class="inset_maro_14">Receiver</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Block</span></td>
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
                          <td width="10%" align="left" class="simple_maro_12"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle"/></td>
                        <td width="68%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12">
                        <strong><? print $this->template->formatAdr($row['src']); ?></strong>
                        </a><br><span class="simple_maro_10"><? print "No explanation"; ?></span>
                        </td>
                        <td width="11%" align="center"><span class="<? if ($row['amount']<0) print "simple_red_12"; else print "simple_green_12"; ?>"><strong><? print $row['amount']; ?></strong></span></td>
                        
                        <td width="11%" align="center" class="simple_maro_12">
                        <span class="simple_maro_12"><strong><? print $row['block']; ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        
                        </td>
                </tr>
                        <tr>
                        <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                        </tr>
              
                   
                    
            </table>
            <br><br><br>
            </div>
           
        
        <?
	}
	
	function showMarkets($symbol)
	{
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE (asset_symbol='".$symbol."' 
				        OR cur_symbol='".$symbol."')
			  ORDER BY ID DESC
			     LIMIT 0,20";
	    $result=$this->kern->execute($query);	
	    
		?>
           
           <div id="div_markets" name="div_markets" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="64%" align="left" class="inset_maro_14">Receiver</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Asset</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Currency</span></td>
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
                          <td width="10%" align="left" class="simple_maro_12"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle"/></td>
                        <td width="68%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12">
                        <strong><? print base64_decode($row['title']); ?></strong>
                        </a><br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span>
                        </td>
                        <td width="11%" align="center"><span class="<? if ($row['asset_symbol']==$symbol) print "simple_green_12"; else print "simple_maro_12"; ?>"><strong><? print $row['asset_symbol']; ?></strong></span></td>
                        
                        <td width="11%" align="center">
                        <span class="<? if ($row['cur_symbol']==$symbol) print "simple_green_12"; else print "simple_maro_12"; ?>"><strong><? print $row['cur_symbol']; ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        
                        </td>
                </tr>
                        <tr>
                        <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                        </tr>
              
                   
                    
            </table>
            <br><br><br>
            </div>
           
        
        <?
	}
}
?>