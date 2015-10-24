<?
  class CAutoMkts
  {
	function CAutoMkts($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showAutoMkts()
	{
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE tip='ID_AUTO'"; 
		$result=$this->kern->execute($query);	
		
		?>
           
           <br>
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="324">&nbsp;</td>
                  <td width="99" align="right"><a href="#" class="btn btn-success"><span class="glyphicon glyphicon-cog" ></span>&nbsp;My Markets</a></td>
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
                        <td width="61%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Price</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Trade</span></td>
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
                        <td width="63%" align="left"><a href="#" class="maro_12"><strong><? print base64_decode($row['title'])." (".$row['mkt_symbol'].")"; ?></strong></a><br><span class="simple_maro_10"><? print substr(base64_decode($row['description']), 0, 50)." ..."; ?></span></td>
                        <td width="18%" align="center" class="simple_green_12"><strong><? print round($row['price'], 8); ?></strong><br><span class='simple_green_10'><? print $row['cur_symbol']; ?></span></td>
                        <td width="19%" align="center" class="simple_maro_12"><a href="market.php?symbol=<? print $row['mkt_symbol']; ?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Trade</a></td>
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
              </tbody>
</table>
        
        <?
	}
	
  }
?>