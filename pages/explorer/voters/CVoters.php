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
              <span class="font_20"><? print "$".$this->kern->split($row['pay'])[0]; ?></span><span class="font_12" style="color:#5CB859"><? print ".".$this->kern->split($row['pay'])[1]; ?></span>
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
					case "ID_POST" : print "Blog"; break;
					case "ID_COM" : print "Comment"; break;
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
             <td height="30" align="left"><strong><? print $row['upvotes_24']; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Upvotes Power 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['upvotes_power_24']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Upvotes Total</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['upvotes_total']; ?></strong></td>
            </tr>
           <tr>
             <td align="right">Upvotes Power Total</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['upvotes_power_total']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['downvotes_24']; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes Power 24 Hours</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['downvotes_power_total']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes Total</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['downvotes_total']; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Downvotes Power Total</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print $row['downvotes_power_total']." MSK"; ?></strong></td>
           </tr>
           <tr>
             <td align="right">Payment Amount</td>
             <td>&nbsp;</td>
             <td height="30" align="left"><strong><? print round($row['pay']/$_REQUEST['sd']['msk_price'], 4)." MSK ($".round($row['pay'],2).")"; ?></strong></td>
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
		$total_reward=$this->kern->getReward("ID_VOTER")*$_REQUEST['sd']['msk_price'];
		
		// Total votes 24 hours
		$query="SELECT SUM(power) AS total 
   		          FROM votes 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$total_power=$row['total'];
		
		$query="SELECT * 
		          FROM votes 
				 WHERE target_type='".$target_type."' 
				   AND targetID='".$targetID."' 
				   AND type='".$type."' 
				   AND block>".($_REQUEST['sd']['last_block']-$blocks)." 
			  ORDER BY power DESC";
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
        <th align="left" width="40%">Address</th>
        <th align="center" width="15%">Power</th>
        <th align="center" width="15%">Reward</th>
        <th align="center" width="30%">Time</th>
        </thead>
        <tbody>
        
        <?
		    $a=0;
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$p=$row['power']*100/$total_power;
				$reward=round($p/100*$total_reward, 2);
				
				$a++;
		?>
        
                <tr>
                <td width="8%" align="center" class="font_14"><? print $a; ?></td>
                
                <td width="30%" align="left" class="font_14"><? print $this->template->formatAdr($row['adr']); ?></td>
                
                <td width="12%" align="center" class="font_14" style="color:<? if ($row['type']=="ID_UP") print "#009900"; else print "#990000"; ?>">
				<? if ($type=="ID_UP") print "+".$row['power']; else print "-".$row['power']; ?></td>
                
                <td width="12%" align="center" class="font_14" style="color:<? if ($reward>0) print "#009900"; else print "#999999"; ?>">
				<strong><? print "$".$reward; ?></strong></td>
                
                <td width="25%" align="left" class="font_14"><? print "~".$this->kern->timeFromBlock($row['block'])." ago"; ?></td>
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