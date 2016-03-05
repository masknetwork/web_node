<?
   class CStatus
   {
	   function CStatus($db, $template)
	   {
		   $this->kern=$db;
		   $this->template=$template;
	   }
	   
	   function stop()
	   {
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SHUTDOWN', 
								par_1='".$IP."',
								par_2='".$port."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
	   }
	   
	   function show_data()
	   {
		   $query="SELECT * FROM web_sys_data";
		   $result=$this->kern->execute($query);	
	       $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		   ?>
              
              <br><br>
              <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
              <tbody>
              <tr>
              <td width="86%" align="left" class="font_14">System Status</td>
              <td width="14%" align="center" class="font_14" style="color:<? if (time()-$row['last_ping']>2) print "#990000"; else print "#009900"; ?>">
			  <? if (time()-$row['last_ping']>2) print "OFFLINE"; else print "ONLINE"; ?>
              </td>
              </tr>
              <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">Max Memory</td>
              <td align="center" class="font_14"><? print round($row['max_memory']/1024000, 2)." MB"; ?></td>
              </tr>
              <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">Total Memory</td>
              <td align="center" class="font_14"><? print round($row['total_memory']/1024000, 2)." MB"; ?></td>
              </tr>
              <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">Free Memory</td>
              <td align="center" class="font_14"><? print round($row['free_memory']/1024000, 2)." MB"; ?></td>
              </tr>
              <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">Processors</td>
              <td align="center" class="font_14">3</td>
              </tr>
              <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">Threads Running</td>
              <td align="center" class="font_14"><? print $row['threads_no']; ?></td>
              </tr>
               <tr>
              <td colspan="2" align="left"><hr></td>
              </tr>
              <tr>
              <td align="left" class="font_14">System Up Time</td>
              <td align="center" class="font_14"><? print $this->kern->getAbsTime($row['uptime']); ?></td>
              </tr>
              </tbody>
              </table>
           
           <?
	   }
	   
	 function showStopBut()
	 {
		 if ($_REQUEST['ud']['user']!="root") return false;
		 ?>
         
             <br>
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right">
                  <a href="index.php?act=stop" class="btn btn-danger btn-sm">
                  <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Shutdown
                  </a>
                  </td>
                </tr>
              </tbody>
            </table>
         
         <?
	 }
   }
?>