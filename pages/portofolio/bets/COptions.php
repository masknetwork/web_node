<?
class COptions
{
	function COptions($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showMyOptions($status="ID_ACTIVE")
	{
		// Status
		if ($status=="ID_ACTIVE")
		{
			$status_1="ID_PENDING";
			$status_2="ID_PENDING";
		}
		else
		{
			$status_1="ID_WIN";
			$status_2="ID_LOST";
		}
		
		// Load options
		$query="SELECT * 
		          FROM feeds_bets_pos AS fbp 
				  JOIN feeds_bets AS fb ON fb.betID=fbp.betID 
				 WHERE fbp.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."') 
			       AND (fb.status='".$status_1."' OR fb.status='".$status_2."')  
			  ORDER BY fbp.ID DESC 
			     LIMIT 0,25"; 
		$result=$this->kern->execute($query);	
	
	  
		?>
           
           <br>
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Expire</th>
           <th class="font_14" height="35px" align="center">Profit</th>
           <th class="font_14" height="35px" align="center"><? if ($status=="ID_ACTIVE") print "Invested"; else print "P/L"; ?></th>
           <th class="font_14" height="35px" align="center">Status</th>
           </thead>
           
           <?
		      while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="40%">
                 <a href="../../assets/options/bet.php?betID=<? print $row['betID']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%" style="color:<? if ($row['status']=="ID_PENDING") print "#000000"; else print "#990000"; ?>"><? if ($row['status']=="ID_PENDING") print "~".$this->kern->timeFromBlock($row['end_block']); else print "closed"; ?></td>
                 <td class="font_14" width="15%"><? print $row['win_multiplier']; ?>%</td>
                 <td class="font_14" width="15%" style="color:<? if ($status=="ID_WIN") print "#990000"; else print "#009900"; ?>">
				 <? 
				    if ($status=="ID_ACTIVE") 
					{
					   print $row['amount']." ".$row['cur']; 
					}
					else
					{
						if ($row['status']=="ID_WIN") 
						   print "-".$row['amount']." ".$row['cur'];
						else
						   print "+".($row['amount']+$row['amount']*$row['win_multiplier']/100)." ".$row['cur'];
					}
				 ?>
                 </td>
                 <td class="font_16" width="15%" style="color:#009900" align="center"><strong>
                 
				 <?
				      switch ($row['status'])
					  {
						 case "ID_PENDING" : print "<span class=\"label label-warning\">Pending</span>"; break;
						 case "ID_WIN" : print "<span class=\"label label-danger\">Looser</span>"; break;
						 case "ID_LOST" : print "<span class=\"label label-success\">Winner</span>"; break;
					  }
				 ?>
                 
                 </strong></td>
                 </tr>
                 <tr><td colspan="5"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           <br><br><br>
           
        <?
	}
	
	function showSelector($type="ID_ACTIVE")
	  {
		  ?>
          
             <div align="right" style="width:90%">
             <div class="btn-group" align="right">
          
             <a class="btn btn<? if ($type=="ID_ACTIVE") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_ACTIVE">
             Active
             </a>
          
             <a class="btn btn<? if ($type=="ID_CLOSED") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_CLOSED">
             Closed
             </a>
          
        </div>
        </div>
        <br>
          
          <?
	  }
}
?>