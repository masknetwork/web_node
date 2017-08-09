<?
class CVote
{
	function CVote($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showVotes($adr, $time="24h")
	{
		// Total votes
		$total_votes=$this->getTotalVotesPower();
		
		// Voting total reward
		$reward=$this->kern->getReward("ID_VOTER");
		
		if ($time=="24h")
		$query="SELECT * 
		          FROM votes 
				 WHERE adr='".$adr."' 
				   AND block>".($_REQUEST['sd']['last_block']-1440)." 
			  ORDER BY block DESC";
	    else
		$query="SELECT * 
		          FROM votes 
				 WHERE adr='".$adr."' 
				   AND block<".($_REQUEST['sd']['last_block']-1440)." 
			  ORDER BY block DESC LIMIT 0,100";
		
		$result=$this->kern->execute($query);	
	
		?>
        
        <br>
        <table style="width:90%">
        <tr><td>
         <?
		   if ($time=="24h") 
		      print "<span class='font_16' style='color:#aaaaaa'>Last 24 hours votes</p>"; 
		   else
		      print "<p class='font_16' style='color:#aaaaaa' align='left'>Older votes</p>";           
		   
		?>
        </td></tr>
        </table>
       
        <table class="table table-bordered table-hover table-striped" style="width:90%">
        <thead class="font_14">
        <th width="40%">Content</th>
        <th width="15%">Vote Type</th>
        <th width="15%">Power</th>
        <th width="15%">Reward</th>
        <th width="15%">Block</th>
       
        <tbody>
        
        <?
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		   {
			   $p=$row['power']*100/$total_votes;
			   $pay_MSK=round($p/100*$reward, 4);
			   $pay_usd=round($pay_MSK*$_REQUEST['sd']['MSK_price'], 2);
		?>
        
              <tr class="font_14">
              <td><strong>
              <a href="#">
              <?
			      switch ($row['type'])
				  {
					  case "ID_UP" : print "Upvoted"; break;
					  case "ID_DOWN" : print "Downvoted"; break;
				  }
				  
				  switch ($row['target_type'])
				  {
					  case "ID_POST" : print " Blog Post"; break;
					  case "ID_COM" : print " Comment"; break;
					  case "ID_ASSET" : print " User Issued Asset"; break;
					  case "ID_APP" : print " User Application"; break;
					  case "ID_BET" : print " User Issued Bet"; break;
				  }
			  ?>
              </a>
              </strong><p class="font_10" style="color:#999999"><? print "Content ID : ".$row['targetID']; ?></p></td>
              <td align="center" style="color:<? if ($row['type']=="ID_UP") print "#009900"; else print "#990000"; ?>"><strong>
			  <? 
			      if ($row['type']=="ID_UP") 
				     print "Upvote"; 
				  else 
				     print "Downvote";
				?>
              </strong></td>
              <td align="center" style="color:#<? if ($row['type']=="ID_DOWN") print "990000"; ?>"><strong>
			  <? 
			      if ($row['type']=="ID_UP") 
				      print "+"; 
				  else 
				      print "-"; 
					  
				  print $row['power']; 
			  ?>
              </strong><p class="font_10" style="color:#aaaaaa">points</p></td>
              <td align="center" style="color:<? if ($pay_MSK>0) print "#009900"; else print "#aaaaaa"; ?>"><? print $pay_MSK." MSK"; ?><p class="font_10"><? print "$".$pay_usd; ?></p></td>
              <td align="center"><? print $row['block']; ?><p class="font_10"><? print "~".$this->kern->timeFromBlock($row['block']); ?></p></td>
              </tr>
        
        <?
		   }
		?>
        
        </tbody>
        </table>
        
        <?
	}
	
	function showPanel($adr)
	{
		// Comments
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type='ID_COM' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $votes_com=$row['total'];
		if ($votes_com=="") $votes_com=0;
		
		// Img 1
		if ($votes_com>=5)
		  $img_1="p10.png";
		else
		  $img_1="p".($votes_com*2).".png";
		  
		// Other content
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type='ID_POST' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $votes_posts=$row['total']; 
		if ($votes_posts=="") $votes_posts=0;
		
		// Img 2
		if ($votes_posts>=3)
		  $img_2="p10.png";
		else
		  $img_2="p".($votes_posts*3).".png";
		  
		  // Other content
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type<>'ID_POST' 
				   AND target_type<>'ID_COM' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $votes_other=$row['total']; 
		if ($votes_other=="") $votes_other=0;
		
		// Img 3
		if ($votes_other>=1)
		  $img_3="p10.png";
		else
		  $img_3="p0.png";
		  
		// Reward
		$reward=$this->kern->getReward("ID_VOTER");
		
		// Total votes
		$total_votes=$this->getTotalVotesPower();
		
		// My votes
		$adr_votes=$this->getAdrVotesPower($adr);
		
		// Percent
		$p=$adr_votes*100/$total_votes;
		
		// Amount
		$pay=round($p/100*$reward*$_REQUEST['sd']['MSK_price'], 2);
		
		// Pay block
		$pay_block=(floor($_REQUEST['sd']['last_block']/1440)+1)*1440;
		$dif=$pay_block-$_REQUEST['sd']['last_block'];
		$p=round($dif*100/14400);
		?>
        
        <br>
        <div class="panel panel-default" style="width:90%">
  <div class="panel-heading">
    <h3 class="panel-title font_14">Overview</h3>
  </div>
  <div class="panel-body font_14">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td>Every 24 hours, users are rewarded for content they create like applications, blog posts, comments or even votes. The most voted content receive the biggest reward. Content is voted by regular users like you, and voters get also a reward. <strong>In order to receive your voting reward, you need to vote at least 5 comments, 3 blog posts and one other kind of content</strong> like applications, data feeds, assets or even bets. Below are displayed your last votes with this address. Keep in mind that voting power decreases after each vote. Voting power depends on voting address balance and number of votes in the last 24 hours.</td>
            </tr>
          <tr>
            <td><hr></td>
            </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="25%" height="40" align="center">Voted Comments</td>
                  <td width="25%" align="center">Blog posts</td>
                  <td width="25%" align="center">Other content</td>
                  <td width="25%" align="center">Voting Reward</td>
                </tr>
                <tr>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/<? print $img_1; ?>" style="background-size:contain" class="font_20"><? if ($votes_com<5) print $votes_com."/5"; ?></td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/<? print $img_2; ?>" style="background-size:contain" class="font_20"><? if ($votes_posts<3) print $votes_posts."/3"; ?></td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/<? print $img_3; ?>" style="background-size:contain" class="font_20"><? if ($votes_other<1) print $votes_other; ?></td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" align="center">
  <tbody>
    <tr>
      <td align="center" valign="top" height="130">
      
      <table width="120" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="120" align="center" background="GIF/p<? print $p; ?>.png" style="background-size:contain; color:#<? if ($pay==0) print "aaaaaa"; else print "#009900"; ?>" class="font_18"><? print "$".$this->kern->split($pay, 2)[0]; ?><span class="font_12"><? print ".".$this->kern->split($pay, 2)[1]; ?></span></td>
                      </tr>
                    </tbody>
                  </table>
                  
                  </td>
    </tr>
    <tr>
      <td bgcolor="#fafafa" class="font_12" align="center">
	  <? 
	      $next=(floor($_REQUEST['sd']['last_block']/1440)+1)*1440;
	      print "~".$this->kern->timeFromBlock($next)." from now" ;
	  ?>
      </td>
    </tr>
  </tbody>
</table>
                  </td>
                  
                </tr>
              </tbody>
            </table></td>
            </tr>
        </tbody>
      </table>
      
  </div>
</div>
        
        <?
	}
	
	function getTotalVotesPower()
	{
		// Total votes
		$query="SELECT SUM(power) AS total 
     		      FROM votes 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$total_votes=$row['total'];
		
		return $total_votes;
	}
	
	function getAdrVotesPower($adr)
	{
		// My votes
		$query="SELECT SUM(power) AS total 
     		      FROM votes 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440)." 
				   AND adr='".$adr."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$adr_votes=$row['total'];
		return $adr_votes;
	}
}
?>