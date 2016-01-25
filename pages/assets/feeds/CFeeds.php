<?
class CFeeds
{
	function CFeeds($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showFeeds($type="mine", $term="")
    {
	   if ($type!="mine") 
	   $query="SELECT *
	             FROM feeds
				WHERE adr IN (SELECT adr 
				                FROM my_adr 
							   WHERE userID='".$_REQUEST['ud']['ID']."')
		          AND (name LIKE '%".$term."%' OR description LIKE '%".$term."%')
			 ORDER BY ID DESC";
	   else
	   $query="SELECT *
	             FROM feeds
				WHERE (name LIKE '%".$term."%' OR description LIKE '%".$term."%')
			 ORDER BY ID DESC";
			 
	   $result=$this->kern->execute($query);	
	   
	   
	   ?>
          
             <br>
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
						   $query="SELECT COUNT(*) AS branches 
						                     FROM feeds_branches 
											WHERE feed_symbol='".$row['symbol']."'";
						   $res=$this->kern->execute($query);	
	                       $r = mysql_fetch_array($res, MYSQL_ASSOC);
						   $branches=$r['branches'];
					?>
                     
                        <tr>
                        <td width="8%" align="left">
                        <img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle" /></td>
                        <td width="64%" align="left" class="font_14">
                        <a href="#" class="font_14"><strong><? print base64_decode($row['name']); ?>&nbsp;&nbsp;(<? print $row['symbol']; ?>)</strong></a><p class="font_10"><? print base64_decode($row['description']); ?></p></td>
                        <td width="12%" align="center" class="font_14"><strong><? print $branches; ?></strong><p class="font_10">branches</p></td>
                        <td width="16%" align="right" class="font_14">
                        <a href="feed.php?symbol=<? print $row['symbol']; ?>" class="btn btn-warning" style="color:#000000">Details</a>             
                        </td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                     <?
                         }
					 ?>
                     </table>
                  
                 
       
       <?
   }
   
}
?>