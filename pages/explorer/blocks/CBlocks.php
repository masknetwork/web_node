<?
  class CBlocks
  {
	  function CBlocks($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function showBlocks()
	  {
		  $query="SELECT * 
		            FROM blocks 
			    ORDER BY ID DESC 
				   LIMIT 0,25";
		  $result=$this->kern->execute($query);	
	     
	  
		  ?>
          
               <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="67%" align="left" class="inset_maro_14">Block hash</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Packets</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Received</span></td>
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
                        <td width="67%" align="left">
                        <span class="simple_maro_12"><strong><? print "Block ".$row['block']; ?></strong></span><br>
                        <span class="simple_maro_10"><? print $row['hash']; ?></span>
                        </td>
                        <td width="17%" align="center" class="simple_green_12"><strong><? print $row['packets']; ?></strong></td>
                        <td width="16%" align="center" class="simple_maro_12"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
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
			   $query="SELECT * FROM net_stat";
			   $result=$this->kern->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	           print "<span class='simple_maro_10'>".$row['net_dif']." (".substr($row['net_dif'], 0, 3)."-".strlen($row['net_dif']).")</span><br><br>";
			
	  }
  }
?>