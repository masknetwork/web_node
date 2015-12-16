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
          
               <table width="90%" border="0" cellspacing="0" cellpadding="0">
               <thead>
               <tr bgcolor="#fafafa" class="font_14" height="30px" style="color:#999999">
               <th>Block</th>
               <th>Packets</th>
               <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received</th>
               </tr>
               </thead>
                
                  <?
				      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					  {
				  ?>
                  
                        <tr>
                        <td width="70%" align="left">
                        <span class="font_14"><strong><? print "Block ".$row['block']; ?></strong></span><br>
                        <span class="font_10"><? print $row['hash']; ?></span>
                        </td>
                        <td width="10%" align="center"><strong  class="font_14"><? print $row['packets']; ?></strong></td>
                        <td width="20%" align="center" class="font_14"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
                        </tr>
                        <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                  
                  <?
	                  }
				  ?>
                
                  </table>
                  
                 
            
            <?
			   $query="SELECT * FROM net_stat";
			   $result=$this->kern->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	           print "<span class='font_10'>".$row['net_dif']." (".substr($row['net_dif'], 0, 3)."-".strlen($row['net_dif']).")</span><br><br>";
			
	  }
  }
?>