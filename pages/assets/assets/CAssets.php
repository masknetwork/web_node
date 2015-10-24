<?
class CAssetsList
{
	function CAssetsList($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showAssets()
	{
		$query="SELECT * 
		          FROM assets 
			  ORDER BY mkt_bid DESC 
			     LIMIT 0,20";
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;Assets List</td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td height="100" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  
                  <table width="93%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="8%"><img src="../../template/template/GIF/empty_pic_prod.png" width="40" height="40" alt="" class="img-circle"/></td>
                            <td width="54%">
                            <span class="simple_maro_12"><strong><? print base64_decode($row['title'])." (".$row['symbol'].")"; ?></strong><br>
                            <span class="simple_maro_10"><? print substr(base64_decode($row['description']), 0, 100)."..."; ?></span></td>
                            <td width="21%" align="center" valign="middle">
                            
                            <?
							   if ($row['mkt_symbol']!="")
							   {
							?>
                            
                                 <a href="asset.php?symbol=<? print $row['symbol']; ?>">
                                 <img src="../../transactions/GIF/multisig_off.png" width="13" height="25" title="Market pegged" data-toggle="tooltip" data-placement="top"/>
                                 </a>
                            
                            <?
							   }
							?>
                            
                            </td>
                            <td width="17%" align="right" valign="top"><a class="btn btn-warning" href="asset.php?symbol=<? print $row['symbol']; ?>">Details</a></td>
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
              </tbody>
            </table>
        
        <?
	}
	
	
}
?>