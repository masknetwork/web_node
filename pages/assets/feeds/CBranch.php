<?
class CBranch
{
	function CBranch($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showPanel($feed, $symbol)
	{
		$query="SELECT *
		          FROM feeds_branches AS fb 
				 WHERE feed_symbol='".$feed."' 
				   AND symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="15%"><img src="../../template/template/GIF/empty_pic.png" class="img-responsive img-circle"></td>
            <td width="3%">&nbsp;</td>
            <td width="83%" valign="top"><span class="font_16"><strong><? print base64_decode($row['name']); ?></strong></span><p class="font_14"><? print base64_decode($row['description']); ?></p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table class="table-responsive" width="100%">
            
            <tr>
            <td width="20%" align="center"><span class="font_12">Feed Symbol</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="feed.php?symbol=<? print $row['feed_symbol']; ?>"><strong><? print $row['feed_symbol']; ?></strong></a></td>
            <td width="40%" class="font_12" align="center">Creation&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></strong></td>
            <td width="40%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></strong></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="20%" align="center"><span class="font_12">Branch </span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="chart.php?symbol=<? print $row['rl_symbol']; ?>"><strong><? print $row['symbol']; ?></strong></a></td>
            <td width="40%" class="font_12" align="center">Fee&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['fee']." MSK daily"; ?></strong></td>
            <td width="40%" class="font_12" align="center">Market Status&nbsp;&nbsp;&nbsp;&nbsp;<strong style="color:<? if ($row['mkt_status']=="online") print "#009900"; else print "#990000"; ?>"><? if ($row['mkt_status']=="online") print "Open"; else print "Closed"; ?></strong></td>
            </tr>
            
            
            
            </table>
    
            </td></tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
	function showReport($feed, $symbol)
	{
		// Last value
		$query="SELECT * 
		          FROM feeds_branches 
				 WHERE feed_symbol='".$feed."' 
				   AND symbol='".$symbol."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $last_value=$row['val'];
		
		// Min, max, count
		$query="SELECT MIN(val) AS minimum, 
		               MAX(val) AS maximum, 
					   COUNT(*) AS total
		          FROM feeds_data
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$symbol."'
				   AND block>".($_REQUEST['sd']['last_block']-1440); 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		?>
            
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table>
            <tr>
            <td width="25%" valign="top" align="center"><span class="font_10">Last Value</span><br><span class="font_20">
			<? print round($last_value, 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Min Value 24H</span><br><span class="font_20">
			<? print round($row['minimum'], 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Max Value 24H</span><br><span class="font_20">
			<? print round($row['maximum'], 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Update 24H</span><br><span class="font_20">
			<? print $row['total']; ?></span></td>
            </tr>
            </table>
            </div>
            </div>
        
        <?
	} 
	
	function showChart($feed, $branch)
	{
		// Feed is mine ?
		$query="SELECT * 
		          FROM feeds 
				 WHERE symbol='".$feed."' 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
							    WHERE userID='".$_REQUEST['ud']['ID']."')";
	   $result=$this->kern->execute($query);	
	   if (mysql_num_rows($result)>0) 
	      $mine=true;
	   else
	     $mine=false;
		 
		 // Load branch
		 $query="SELECT * 
		           FROM feeds_branches 
				  WHERE feed_symbol='".$feed."' 
				    AND symbol='".$branch."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$rl_symbol=$row['rl_symbol'];
		
		// Last 100 records
		$query="SELECT AVG(val) AS val
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."'
				   AND block>".($_REQUEST['sd']['last_block']-1440)." 
			  GROUP BY ROUND(block/10)"; 
		$result=$this->kern->execute($query);	
		
	   
		?>
           
           <script type="text/javascript">
	       google.load('visualization', '1', {packages: ['corechart', 'line']});
           google.setOnLoadCallback(drawChart);

      function drawChart() 
	  {
         
		 var data = new google.visualization.DataTable();
         data.addColumn('string', 'Date');
		 data.addColumn('number', 'Price');
		 
         data.addRows([
		 <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  print "['', ".$row['val']."],";
		 ?>
		 ]);

        var options = {
          title: '<? print $symbol; ?> Chart',
          curveType: 'function',
		  legend:'none',
	      tooltip: { isHtml: true },
	      chartArea: {'width': '80%', 'height': '85%'},
	      backgroundColor : '#ffffff'
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
    
           <br>
           <table width="90%">
           <tr>
           <td width="90%"><div id="curve_chart" style="width: 100%; height: 400px"></div></td>
           <td width="10%" valign="top">
           <? 
		      if ($_REQUEST['ud']['ID']>0)
			  {
		         if ($mine==true) 
			     {
				  ?>
                  
                       <div class="btn-group">
                       <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">
                       <span class="glyphicon glyphicon-cog"></span>
                       <span class="caret"></span></button>
                       <ul role="menu" class="dropdown-menu">
                       <li><a href="#">Update Branch Data</a></li>
                       <li><a href="#">Renew</a></li>
                       </ul>
                       </div>
                       </div>
                  
                  <?
			  }
			  
			 
		   ?>
           
                       <div style="height:10px">&nbsp;</div>
                       <div class="btn-group">
                       <button data-toggle="dropdown" class="btn btn-success dropdown-toggle" type="button">
                       <span class="glyphicon glyphicon-plus"></span>
                       <span class="caret"></span></button>
                       <ul role="menu" class="dropdown-menu">
                       <li><a href="../../assets/options/issued.php?act=show_modal&feed=<? print $feed; ?>&branch=<? print $branch; ?>">Launch Binary Option</a></li>
                       <li><a href="#">Start a Speculative Market</a></li>
                       <li><a href="#">Issue a Market Pegged Asset</a></li>
                       </ul>
                       </div>
                       
           <?
			  }
           ?>
                       
                       </div>
           <div style="height:10px">&nbsp;</div>
           
           <a href="chart.php?symbol=<? print $rl_symbol; ?>" class="btn btn-default" style="width:90%"><span class="glyphicon glyphicon-signal"></span></a>
                       
           </td>
           </tr>
           </table>
           
           <br><br>
        
        <?
	}
	
	function showLastData($feed, $branch)
	{
		$query="SELECT * 
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."' 
			 ORDER BY ID DESC 
			    LIMIT 0,25";
		$result=$this->kern->execute($query);
		?>
            
            <br>
            <table class="table-responsive" width="90%" align="center">
            
            <?
			   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			   {
			?>
            
                 <tr>
                 <td class="font_14" width="60%"><? print $feed."/".$branch; ?></td>
                 <td class="font_14" width="20%"><? print $row['block']; ?></td>
                 <td class="font_14" width="20%"><? print $row['val']; ?></td>
                 </tr>
                 <tr>
                 <td colspan="3"><hr></td>
                 </tr>
            
            <?
			   }
			?>
            
            </table>
            <br><br>
        
        <?
	}
	
}
?>