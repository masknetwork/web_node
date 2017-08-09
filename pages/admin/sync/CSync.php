<?
class CSync
{
	function CSync($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showStat()
	{
		// Load sync target
		$query="SELECT * FROM net_stat";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
       
       <td width="25%">
       <div class="panel panel-default" style="width:90%">
       <div class="panel-body">
       <table width="100%">
       <tr><td class="font_10" align="center">Target Block</td></tr>
       <tr><td class="font_20" align="center"><? print $row['sync_target']; ?></td></tr>
       <tr><td class="font_10" align="center">block</td></tr>
       </table>
       </div>
       </div>
       </td>
       
       <td width="25%">
       <div class="panel panel-default" style="width:90%">
       <div class="panel-body">
       <table width="100%">
       <tr><td class="font_10" align="center">Actual Block</td></tr>
       <tr><td class="font_20" align="center"><? print $_REQUEST['sd']['last_block']; ?></td></tr>
       <tr><td class="font_10" align="center">block</td></tr>
       </table>
       </div>
       </div>
       </td>
       
       <td width="25%">
       <div class="panel panel-default" style="width:90%">
       <div class="panel-body">
       <table width="100%">
       <tr><td class="font_10" align="center">Progress</td></tr>
       <tr><td class="font_20" align="center"><? print round($_REQUEST['sd']['last_block']*100/$row['sync_target'], 2); ?>%</td></tr>
       <tr><td class="font_10" align="center">block</td></tr>
       </table>
       </div>
       </div>
       </td>
       
       <td width="25%">
       <div class="panel panel-default" style="width:90%">
       <div class="panel-body">
       <table width="100%">
       <tr><td class="font_10" align="center">Elapsed</td></tr>
       <tr><td class="font_20" align="center"><? $time=$this->kern->getAbsTime($row['sync_start']); $time=explode(" ", $time); print $time[0]; ?></td></tr>
       <tr><td class="font_10" align="center"><? print $time[1]; ?></td></tr>
       </table>
       </div>
       </div>
       </td>
     </tr>
   </tbody>
 </table>
        
        <?
	}
	
	function showOps()
	{
		$query="SELECT * FROM sync";
	    $result=$this->kern->execute($query);	
	    
		?>
        
        <br>
        <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-hover table-striped table-responsive" style="width:90%">
        <tbody>
        <tr>
        <td class="font_14">Operation</td>
        <td class="font_14" align="center">Time</td>
        <td class="font_14" align="center">Status</td>
        </tr>
        
        <?
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
            <tr>
            <td class="font_14" width="70%" style="
            <?
			   switch ($row['status'])
			   {
				   case "ID_PENDING" : print "color:#999999"; break;
				   case "ID_DOWNLOADING" : print "color:#999900"; break;
				   case "ID_PROCESSING" : print "color:#009900"; break;
			   }
			?>
            ">
            <?
			   if ($row['type']=="ID_GET_NETSTAT") 
			      print "Request net status";
			   else  if ($row['type']=="ID_BLOCKS") 
			      print "Blocks (".$row['start']." - ".$row['end'].")";
			?>
            </td>
            <td class="font_14" align="center" style="
            <?
			   switch ($row['status'])
			   {
				   case "ID_PENDING" : print "color:#999999"; break;
				   case "ID_DOWNLOADING" : print "color:#999900"; break;
				   case "ID_PROCESSING" : print "color:#009900"; break;
			   }
			?>
            ">
			<? 
			    if ($row['status']!="ID_PENDING") 
				   print $this->kern->getAbsTime($row['tstamp']); 
		    ?>
            </td>
            <td class="font_14" align="center" style="
            <?
			   switch ($row['status'])
			   {
				   case "ID_PENDING" : print "color:#999999"; break;
				   case "ID_DOWNLOADING" : print "color:#999900"; break;
				   case "ID_PROCESSING" : print "color:#009900"; break;
			   }
			?>">
			<?
			   switch ($row['status'])
			   {
				   case "ID_PENDING" : print "<span class='glyphicon glyphicon-time'></span>&nbsp;&nbsp;pending"; break;
				   case "ID_DOWNLOADING" : print "<span class='glyphicon glyphicon-download'></span>&nbsp;&nbsp;downloading"; break;
				   case "ID_PROCESSING" : print "<span class='glyphicon glyphicon-cog'></span>&nbsp;&nbsp;processing"; break;
			   }
			?>
            </td>
            </tr>
        
        <?
	       }
		?>
        
        </tbody>
        </table>
        
        <?
	}
}
?>