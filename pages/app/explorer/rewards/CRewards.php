<?
class CRewards
{
	function CRewards($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLastRewards()
	{
		$query="SELECT * 
		          FROM rewards 
			  ORDER BY ID DESC 
			     LIMIT 0,100";
		$result=$this->kern->execute($query);	
		
		?>
        
        <br>
        <table class="table table-responsive table-hover table-striped" style="width:90%">
        <thead class="font_14">
        <td width="55%"><strong>Address</strong></td>
        <td align="center" width="15%"><strong>Content</strong></td>
        <td align="center" width="15%"><strong>Reward</strong></td>
        <td align="center" width="15%"><strong>Block</strong></td>
        </thead>
        
        <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
		?>
        
               <tr class="font_14">
               <td><? print $this->template->formatAdr($row['adr']); ?></td>
               <td style="color:#999999" align="center">
               
			   <?
			       if ($row['reward']=="ID_VOTERS")
				   {
					   print "Voter Reward";
				   }
				   else
				   {
			           switch ($row['target_type'])
				       {
					       // Blog post
					       case "ID_POST" : print "Blog Post"; break;
					   
					       // Comment
					       case "ID_COM" : print "Comment"; break;
					   
					       // Data feed
					       case "ID_FEED" : print "Data Feed"; break;
					   
					       // Asset
					       case "ID_ASSET" : print "Asset"; break;
					   
					       // Binary option
					       case "ID_BET" : print "Binary Option"; break;
					   
					       // Margin market
					       case "ID_MKT" : print "Margin Markets"; break;
					}
				   }
			   ?>
               
               <br><span class="font_10" style="color:#999999"><? print "ID : ".$row['targetID']; ?></span>
               </td>
               
               <td align="center"><strong style="color:#009900"><? print "$".round($row['amount']*$_REQUEST['sd']['MSK_price'], 2); ?></strong><br><span style="color:#999999; font-size:10px"><? print $row['amount']." MSK"; ?></span></td>
             
               <td align="center" style="color:#999999"><? print $row['block']; ?><br><span style="font-size:10px">~<? print $this->kern->timeFromBlock($row['block']); ?> ago</span></td>
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