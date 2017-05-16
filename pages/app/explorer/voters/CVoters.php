<?
class CVoters
{
	function CVoters($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showReport($target_type, $targetID)
	{
		$query="SELECT * 
		          FROM votes_stats 
				 WHERE target_type='".$target_type."' 
				   AND targetID='".$targetID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
        <br>
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
        <td width="24%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td><img src="../../template/template/GIF/mask.jpg" width="200" height="200" class="img img-responsive img-rounded"/></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>
              <div class="panel panel-default">
              <div class="panel-heading font_14" align="center">Pending Payment</div>
              <div class="panel-body" style="color:#009900" align="center">
              <span class="font_20">
			  
			  <? 
			      $pay=round($row['pay']*$_REQUEST['sd']['MSK_price'], 2);
			      print "$".$this->kern->split($pay, 2)[0]; 
			  ?>
              
              </span><span class="font_12" style="color:#5CB859">
			  <? print ".".$this->kern->split($pay, 2)[1]; ?>
              
              </span>
              </div>
              </div>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
        <td width="3%">&nbsp;</td>
        <td width="73%" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tbody class="font_14">
           <tr>
             <td width="36%" align="right">Category</td>
             <td width="4%">&nbsp;</td>
             <td width="60%" height="30" align="left">
             <strong>
             
			 <?
			    switch ($target_type)
				{
					// Post
					case "ID_POST" : print "Blog Post"; break;
					
					// Comment
					case "ID_COM" : print "Comment"; break;
					
					// Asset
					case "ID_ASSET" : print "Asset"; break;
					
					// Data Feed
					case "ID_FEED" : print "Data Feed"; break;
					
					// Binary option
					case "ID_OPTION" : print "Binary Option"; break;
					
					// MArgin market
					case "ID_MARKET" : print "Margin Market"; break;
				}
			 ?>
           
            </strong></td>
           </tr>
           <tr>
             <td align="right">ID</td>
             <td align="right">&nbsp;</td>
             <td height="30" align="left"><strong><? print $targetID; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Upvotes 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? if ($row['upvotes_24']=="") print "0"; else print $row['upvotes_24']; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Upvotes Power 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? if ($row['upvotes_power_24']=="") print "0 MSK"; else print $row['upupvotes_power_24votes_24']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? if ($row['downvotes_24']=="") print "0"; else print $row['downvotes_24']; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes Power 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? if ($row['downvotes_power_total']=="") print "0 MSK"; else print $row['downvotes_power_total']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Pending Payment </td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print round($row['pay'], 4)." MSK ($".round($row['pay']*$_REQUEST['sd']['MSK_price'], 2).")"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Payment Block</td>
             <td>&nbsp;</td>
             <td height="30" align="left">
             <strong>
			 <? 
			    $block=((floor($_REQUEST['sd']['last_block']/1440)+1)*1440); 
				print $block." (~".$this->kern->timeFromBlock($block)." from now)";
		     ?>
             </strong></td>
           </tr>
         </tbody>
         </table></td>
         </tr>
         </tbody>
         </table>
         <br>
        
        <?
	}
	
	function showVoters($target_type, $targetID, $type, $period)
	{
		// Period
		switch ($period)
		{
			case "24" : $blocks=1440; break;
			case "30" : $blocks=43200; break;
		}
		
		// Voters reward
		$total_reward=$this->kern->getReward("ID_VOTER")*$_REQUEST['sd']['MSK_price'];
		
		// Total votes 24 hours
		$query="SELECT SUM(power) AS total 
   		          FROM votes 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$total_power=$row['total'];
		
		$query="SELECT * 
		          FROM votes 
				  JOIN votes_power AS vp ON vp.voteID=votes.ID
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.targetID='".$targetID."' 
				   AND votes.type='".$type."' 
				ORDER BY vp.vote_power DESC";
	    $result=$this->kern->execute($query);	
	   
		
		if (mysql_num_rows($result)==0) 
		{
			print "<div class='font_14' style='color:#990000'><br>No results found</div>";
	        return false;
		}
		
		?>
        
        <br>
        <table style="width:90%" border="0" cellpadding="0" cellspacing="0" class="table table-bordered table-responsive table-hover table-striped">
        <thead  class="font_14">
        <th align="center" width="5%">#</th>
        <th align="left" width="30%">Address</th>
        <th align="center" width="15%">Power</th>
        <th align="center" width="25%">Reward</th>
        <th align="center" width="30%">Time</th>
        </thead>
        <tbody>
        
        <?
		    $a=0;
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				
		?>
        
                <tr>
                <td width="8%" align="center" class="font_14"><? print $a; ?></td>
                
                <td width="30%" align="left" class="font_14"><? print $this->template->formatAdr($row['adr']); ?></td>
                
                <td width="12%" align="center" class="font_14" style="color:<? if ($row['type']=="ID_UP") print "#009900"; else print "#990000"; ?>">
				<? if ($type=="ID_UP") print "+".$row['vote_power']; else print "-".$row['vote_power']; ?></td>
                
                <td width="12%" align="center" class="font_14" style="color:<? if ($reward>0) print "#009900"; else print "#999999"; ?>">
				<strong><? print $row['vote_pay']." MSK ($".round($row['vote_pay']*$_REQUEST['sd']['MSK_price'], 2).")"; ?></strong></td>
                
                <td width="25%" align="left" class="font_14"><? print "~".$this->kern->timeFromBlock($row['block'])." ago"; ?></td>
                </tr>
        
        <?
			}
		?>
        
        </tbody>
        </table>
        
        <?
	}
	
	function showRewards($target_type, $targetID)
	{
		$query="SELECT * 
		          FROM rewards
				 WHERE target_type='".$target_type."' 
				   AND targetID='".$targetID."' 
				   AND reward='ID_CONTENT'
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
			      switch ($target_type)
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
				?>
               
               <br><span class="font_10" style="color:#999999"><? print "ID : ".$row['targetID']; ?></span>
               </td>
               
               <td align="center"><strong style="color:#009900"><? print "$".round($row['amount']*$_REQUEST['sd']['MSK_price'], 2); ?></strong><br><span style="color:#999999; font-size:10px"><? print $row['amount']." MSK"; ?></span></td>
             
               <td align="center" style="color:#999999"><? print $row['block']; ?><br><span style="font-size:10px">~<? print $this->kern->timeFromBlock($row['block']); ?></span></td>
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