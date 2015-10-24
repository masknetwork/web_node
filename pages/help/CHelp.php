<?
class CHelp
{
	function CHelp($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
            <table width="201" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <tr>
                    <td height="40" align="left">
                    
                     <a href="index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==1) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-th-list" style="color:<? if ($sel==1) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==1) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Overview</td>
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                   <tr>
                    <td height="40" align="left">
                    
                    <a href="transactions.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==2) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                           <span class="glyphicon glyphicon-send" style="color:<? if ($sel==2) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==2) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Transactions</td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                 
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="addresses.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==3) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"> <span class="glyphicon glyphicon-folder-open" style="color:<? if ($sel==3) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==3) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Addresses</td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="adr_options.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==4) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-list-alt" style="color:<? if ($sel==4) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==4) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Address Options</td>
                          <td <? if ($sel==4) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="adr_names.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==5) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-tags" style="color:<? if ($sel==5) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==5) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Address Names</td>
                          <td <? if ($sel==5) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="messages.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==6) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==6) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-envelope" style="color:<? if ($sel==6) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==6) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==6) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Messages</td>
                          <td <? if ($sel==6) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                     <a href="adv.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==7) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==7) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-bullhorn" style="color:<? if ($sel==7) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==7) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==7) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Advertising</td>
                          <td <? if ($sel==7) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                     <a href="assets.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==8) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==8) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-usd" style="color:<? if ($sel==8) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==8) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==8) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Assets</td>
                          <td <? if ($sel==8) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                     <a href="feeds.php"> 
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==9) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==9) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-sort-by-attributes" style="color:<? if ($sel==9) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==9) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==9) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Data Feeds</td>
                          <td <? if ($sel==9) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                   </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                     <a href="mkts.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==10) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==10) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-shopping-cart" style="color:<? if ($sel==10) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==10) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==10) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Markets</td>
                          <td <? if ($sel==10) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                  <tr>
                    <td height="40" align="left">
                    
                    
                     <a href="explorer.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==11) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==11) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center"><span class="glyphicon glyphicon-briefcase" style="color:<? if ($sel==11) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==11) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==11) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Explorer</td>
                          <td <? if ($sel==11) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    </a>
                    
                    </td>
                  </tr>
                </tbody>
              </table>
        
        <?
	}
	
	
}
?>