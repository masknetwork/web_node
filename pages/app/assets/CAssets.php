<?
class CAssets
{
	function CAssets($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
            <table width="201" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <tr style="cursor:pointer" onClick="window.location='../assets/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==1) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/all_<? if ($sel==1) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==1) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Assets</td>
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
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==2) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">My Assets</td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                  <tr style="cursor:pointer" onClick="window.location='../issued/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==3) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==3) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==3) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Issued Assets</td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                   <tr style="cursor:pointer" onClick="window.location='../reg_mkts/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==4) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==4) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==4) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Regular Markets</td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                   <tr style="cursor:pointer" onClick="window.location='../auto_mkts/index.php'">
                    <td height="40" align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==5) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <img src="../GIF/esc_<? if ($sel==5) print "on"; else print "off"; ?>.png" alt=""/></td>
                          <td <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==5) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Auto Markets</td>
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
	
	
	function showSelector($target="ID_CRYPTO")
	{
		?>
       
        <div class="btn-group" style="width:90%" align="center">
        
        <a class="btn btn<? if ($target=="ID_CRYPTO") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_CRYPTO" style="width:18%">Cryptocoins</a>
        
        <a class="btn btn<? if ($target=="ID_FX") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_FX" style="width:14%">Forex</a>
        
        <a class="btn btn<? if ($target=="ID_COMM") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_COMM" style="width:16%">Commodities</a>
        
        <a class="btn btn<? if ($target=="ID_IND") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_IND" style="width:14%">Indices</a>
        
        <a class="btn btn<? if ($target=="ID_STOCKS") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_STOCKS" style="width:12%">Stocks</a>
        
        <a class="btn btn<? if ($target=="ID_BONDS") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_BONDS" style="width:12%">Bonds</a>
        
        <a class="btn btn<? if ($target=="ID_OTHER") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?target=ID_OTHER" style="width:12%">Other</a>
        
        </div>
             
        <br><br><br>
        
        <?
	}
	
	
}
?>