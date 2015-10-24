<?
class CFeeds
{
	function CFeeds($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
            <table width="201" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <tr style="cursor:pointer" onClick="window.location='../feeds/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==1) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/all_<? if ($sel==1) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==1) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Feeds</td>
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                   <tr style="cursor:pointer" onClick="window.location='../mine/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==2) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==2) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==2) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">My feeds</td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                  <tr style="cursor:pointer" onClick="window.location='../reg_mkts/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==3) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==3) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==3) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">  Assets</td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                   <tr style="cursor:pointer" onClick="window.location='../margin_mkts/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==4) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==4) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==4) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Margin   Markets</td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                   <tr style="cursor:pointer" onClick="window.location='../bets/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==5) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==5) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==5) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Bets</td>
                          <td <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                  
                 
                  <tr>
                    <td height="40" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="40" align="left">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
        
        <?
	}
	
	
}
?>