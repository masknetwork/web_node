<?
class CCrons
{
	function CCrons($db)
	{
		$this->kern=$db;
	}
	
	function checkTrending()
	{
		// Delete trending
		$query="DELETE FROM tweets_trends";
		$this->kern->execute($query);
		
		$query="SELECT * 
		          FROM tweets 
				 WHERE received>".(time()-864000);
		$result=$this->kern->execute($query);	
	    
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {
			$mes=explode(" ", base64_decode($row['mes']));
			
			for ($a=0; $a<=sizeof($mes); $a++)
			{
				// --------------------------------- Hashtags ----------------------------------------------- 
				if (substr($mes[$a], 0, 1)=="#")
				{
					$query="SELECT * 
					          FROM tweets_trends 
							 WHERE term='".$mes[$a]."' 
							   AND type='ID_HASHTAG'";
				    $res=$this->kern->execute($query);
					
					if (mysql_num_rows($res)>0)
					  $query="UPDATE tweets_trends 
					             SET tweets=tweets+5, 
								     retweets=retweets+".$row['retweets'].", 
									 comments=comments+".$row['comments'].", 
									 likes=likes+".$row['likes']." 
							   WHERE term='".$mes[$a]."' 
							     AND type='ID_HASHTAG'";
					else
					  $query="INSERT INTO tweets_trends 
					                  SET term='".$mes[$a]."', 
									      type='ID_HASHTAG', 
										  tweets='5', 
										  retweets='0', 
										  comments='0', 
										  likes='0'";
										  
					// Execute
					$this->kern->execute($query);
				}
			}
		}
	}
	
	function checkDirApp()
	{
		$query="SELECT * FROM agents_categs";
		$result=$this->kern->execute($query);	
		
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['categID']=="ID_ALL")
			   $query="SELECT COUNT(*) AS no
			             FROM agents 
						WHERE status='ID_ONLINE'
						  AND dir>0";
		    else
			   $query="SELECT COUNT(*) AS no
			             FROM agents 
						WHERE status='ID_ONLINE'
						  AND categ='".$row['categID']."'
						  AND dir>0";
		    // Load
			$res=$this->kern->execute($query);
			
			// Number
			$r = mysql_fetch_array($res, MYSQL_ASSOC);
			$no=$r['no'];
			
			// Update
			$query="UPDATE agents_categs 
			           SET dir_no='".$no."' 
					 WHERE categID='".$row['categID']."'"; 
			$this->kern->execute($query);
		}
	}
	
	function checkMktApp()
	{
		$query="SELECT * FROM agents_categs";
		$result=$this->kern->execute($query);	
		
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if ($row['categID']=="ID_ALL")
			   $query="SELECT COUNT(*) AS no
			             FROM agents 
						WHERE status='ID_ONLINE'
						  AND price>0";
		    else
			   $query="SELECT COUNT(*) AS no
			             FROM agents 
						WHERE status='ID_ONLINE'
						  AND categ='".$row['categID']."'
						  AND price>0";
		    // Load
			$res=$this->kern->execute($query);
			
			// Number
			$r = mysql_fetch_array($res, MYSQL_ASSOC);
			$no=$r['no'];
			
			// Update
			$query="UPDATE agents_categs 
			           SET mkt_no='".$no."' 
					 WHERE categID='".$row['categID']."'"; 
			$this->kern->execute($query);
		}
	}
	
	function sendEmails()
	{
		$query="SELECT * FROM out_emails WHERE status='ID_PENDING'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    mail($row['dest'], base64_decode($row['subj']), base64_decode($row['mes']));
	}
	
	function getVotePower($adr, $block)
	{
		// Count votes
		$query="SELECT COUNT(*) AS total 
		          FROM votes 
				 WHERE adr='".$adr."' 
				   AND block>".($_REQUEST['sd']['last_block']-$block); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$no=$row['total'];
		
		// Return power
		return round($this->kern->getBalance($adr, "MSK")/$no, 2); 
	}
	
	function updateVotes($target_type, $targetID)
	{
		// Upvotes 24 count
		$query="SELECT COUNT(*) AS total 
				  FROM votes 
	   		     WHERE target_type='".$target_type."' 
				   AND type='ID_UP'
	   		       AND targetID='".$targetID."' 
	    		   AND block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$upvotes_24=$row['total']; 
		if ($upvotes_24=="") $upvotes_24=0;
		
		// Upvotes 24 power
		$upvotes_power_24=0;
		$query="SELECT adr  
				  FROM votes 
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_UP'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $upvotes_power_24=$upvotes_power_24+$this->getVotePower($row['adr'], 1440); 
		   
		// Upvotes total count
		$query="SELECT COUNT(*) AS total 
				  FROM votes 
	   		     WHERE target_type='".$target_type."' 
				   AND type='ID_UP'
	   		       AND targetID='".$targetID."' 
	    		   AND block>".($_REQUEST['sd']['last_block']-43200); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$upvotes_total=$row['total']; 
		if ($upvotes_total=="") $upvotes_total=0;
		
		// Upvotes 24 power
		$upvotes_power_total=0;
		$query="SELECT adr  
				  FROM votes 
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_UP'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-43200); 
		$result=$this->kern->execute($query);	
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $upvotes_power_total=$upvotes_power_total+$this->getVotePower($row['adr'], 43200); 
		
		//-------------------------------------------------------------------------------------
		
		// Downvotes 24 count
		$query="SELECT COUNT(*) AS total 
				  FROM votes 
	   		     WHERE target_type='".$target_type."' 
				   AND type='ID_DOWN'
	   		       AND targetID='".$targetID."' 
	    		   AND block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$downvotes_24=$row['total']; 
		if ($downvotes_24=="") $downvotes_24=0;
		
		// Downvotes 24 power
		$downvotes_power_24=0;
		$query="SELECT adr  
				  FROM votes 
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_DOWN'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $downvotes_power_24=$downvotes_power_24+$this->getVotePower($row['adr'], 1440); 
		   
		// Downvotes total count
		$query="SELECT COUNT(*) AS total 
				  FROM votes 
	   		     WHERE target_type='".$target_type."' 
				   AND type='ID_DOWN'
	   		       AND targetID='".$targetID."' 
	    		   AND block>".($_REQUEST['sd']['last_block']-43200); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$downvotes_total=$row['total']; 
		if ($downvotes_total=="") $downvotes_total=0;
		
		// Downvotes total power
		$downvotes_power_total=0;
		$query="SELECT adr  
				  FROM votes 
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_DOWN'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-43200); 
		$result=$this->kern->execute($query);	
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $downvotes_power_total=$downvotes_power_total+$this->getVotePower($row['adr'], 43200); 
		   
		   
		// Add
	    $this->addToVotesStat($target_type, 
	                          $targetID, 
							  $upvotes_24, 
							  $upvotes_power_24, 
							  $upvotes_total, 
							  $upvotes_power_total, 
							  $downvotes_24, 
							  $downvotes_power_23, 
							  $downvotes_total, 
							  $downvotes_power_total); 
						  			  
	   $this->kern->execute($query);	
	}
	
	
	function updateVoters()
	{
		// Load voters in 24h
		$query="SELECT distinct(adr) 
		          FROM votes 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// Upvotes 24 hours
			$query="SELECT COUNT(*) AS no
			          FROM votes 
					 WHERE adr='".$row['adr']."' 
					   AND type='ID_UP' 
					   AND block>".($_REQUEST['sd']['last_block']-1440);
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			$upvotes_24=$row_2['no'];
			
			// Upvotes total
			$query="SELECT COUNT(*) AS no
			          FROM votes 
					 WHERE adr='".$row['adr']."' 
					   AND type='ID_UP' 
					   AND block>".($_REQUEST['sd']['last_block']-43200);
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			$upvotes_total=$row_2['no'];
			
			// Downvotes 24 hours
			$query="SELECT COUNT(*) AS no
			          FROM votes 
					 WHERE adr='".$row['adr']."' 
					   AND type='ID_DOWN' 
					   AND block>".($_REQUEST['sd']['last_block']-1440);
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			$downvotes_24=$row_2['no'];
			
			// Downvotes total
			$query="SELECT COUNT(*) AS no
			          FROM votes 
					 WHERE adr='".$row['adr']."' 
					   AND type='ID_DOWN' 
					   AND block>".($_REQUEST['sd']['last_block']-43200);
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			$downvotes_total=$row_2['no'];
			
			// Votes 24 no
			$votes_24_no=$upvotes_24+$downvotes_24;
			
			// Votes total no
			$votes_total_no=$upvotes_total+$downvotes_total;
			
			// Balance
			$balance=$this->kern->getBalance($row['adr']); 
			
			// Upvotes 24h power
			$upvotes_24_power=round($balance/$votes_24_no*$upvotes_24, 2);
			
			// Downvotes 24h power
			$downvotes_24_power=round($balance/$votes_24_no*$downvotes_24, 2);
			
			// Upvotes total power
			$upvotes_total_power=round($balance/$votes_total_no*$upvotes_total, 2);
			
			// Downvotes total power
			$downvotes_total_power=round($balance/$votes_total_no*$downvotes_total, 2);
			
			// Add
			$this->addToVotesStat("ID_VOTER", 
	                              $row['adr'], 
							      $upvotes_24, 
							      $upvotes_24_power, 
							      $upvotes_total, 
							      $upvotes_total_power, 
							      $downvotes_24, 
							      $downvotes_24_power, 
							      $downvotes_total, 
							      $downvotes_total_power);
		}
	}
	
	function addToVotesStat($target_type, 
	                        $targetID, 
							$upvotes_24, 
							$upvotes_24_power, 
							$upvotes_total, 
							$upvotes_total_power, 
							$downvotes_24, 
							$downvotes_24_power, 
							$downvotes_total, 
							$downvotes_total_power)
	{
		    // Exist ?
		    $query="SELECT * 
		              FROM votes_stats 
				     WHERE target_type='".$target_type."' 
				       AND targetID='".$targetID."'";
		    $res=$this->kern->execute($query);	
		
		    if (mysql_num_rows($res)==0)
		         $query="INSERT INTO votes_stats 
		                         SET target_type='".$target_type."', 
						             targetID='".$targetID."', 
							         upvotes_24='".$upvotes_24."', 
							         upvotes_power_24='".$upvotes_24_power."', 
							         upvotes_total='".$upvotes_total."', 
						             upvotes_power_total='".$upvotes_total_power."', 
							         downvotes_24='".$downvotes_24."', 
							         downvotes_power_24='".$downvotes_24_power."', 
						      	     downvotes_total='".$downvotes_total."', 
							         downvotes_power_total='".$downvotes_total_power."', 
							         tstamp='".time()."'"; 
		   else
		       $query="UPDATE votes_stats 
		                  SET target_type='".$target_type."', 
						      targetID='".$targetID."', 
							  upvotes_24='".$upvotes_24."', 
							  upvotes_power_24='".$upvotes_24_power."', 
							  upvotes_total='".$upvotes_total."', 
							  upvotes_power_total='".$upvotes_total_power."', 
							  downvotes_24='".$downvotes_24."', 
							  downvotes_power_24='".$downvotes_24_power."', 
							  downvotes_total='".$downvotes_total."', 
							  downvotes_power_total='".$downvotes_total_power."', 
							  tstamp='".time()."'
						WHERE target_type='".$target_type."' 
						  AND targetID='".$targetID."'"; 
			
		  // print $query."<br><br>";			  			  
	      $this->kern->execute($query);	
	}
	
	function updatePayments()
	{
		// Load total voting power in 24 hours for posts, apps...
		$query="SELECT DISTINCT(adr) 
		          FROM votes
				  WHERE target_type='ID_POST' 
				 AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $total_posts=$total_posts+$this->kern->getBalance($row['adr']);
		   
		// Load total voting power in 24 hours for comments
		$query="SELECT DISTINCT(adr) 
		          FROM votes
				  WHERE target_type='ID_COM' 
				 WHERE block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   $total_com=$total_com+$this->kern->getBalance($row['adr']);
		   
		// Load overall total voting power in 24 hours
		$total=$total_posts+$total_com;
		
		// Load votes for posts
		$query="SELECT DISTINCT(targetID), target_type 
		          FROM votes 
				 WHERE target_type='ID_POST' 
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// Load data from stats
			$query="SELECT * 
			          FROM votes_stats 
					 WHERE target_type='".$row['target_type']."' 
					   AND targetID='".$row['targetID']."'";
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			
			// Power
			$power=$row_2['upvotes_power_24']-$row_2['downvotes_power_24']; 
			
			// Power percent
			$p=round($power*100/$total_posts, 2); 
			
			// Payment
			$pay=$p*4;
			
			// Negative
			if ($p<0) $p=0;
			
			// Update
			$query="UPDATE votes_stats 
			           SET pay='".$pay."' 
					 WHERE target_type='".$row['target_type']."' 
					   AND targetID='".$row['targetID']."'";
			$this->kern->execute($query);	
		}
		
		// Load votes for comments
		$query="SELECT DISTINCT(targetID), target_type 
		          FROM votes 
				 WHERE target_type='ID_COM' 
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// Load data from stats
			$query="SELECT * 
			          FROM votes_stats 
					 WHERE target_type='".$row['target_type']."' 
					   AND targetID='".$row['targetID']."'";
			$res=$this->kern->execute($query);	
			$row_2 = mysql_fetch_array($res, MYSQL_ASSOC);
			
			// Power
			$power=$row_2['upvotes_power_24']-$row_2['downvotes_power_24']; 
			
			// Power percent
			$p=round($power*100/$total_posts, 2); 
			
			// Payment
			$pay=$p*2;
			
			// Negative
			if ($p<0) $p=0;
			
			// Update
			$query="UPDATE votes_stats 
			           SET pay='".$pay."' 
					 WHERE target_type='".$row['target_type']."' 
					   AND targetID='".$row['targetID']."'"; 
			$this->kern->execute($query);	
		}
	}
	
	function checkVotes()
	{
		// Load votes in the last 24 hours
		$query="SELECT DISTINCT(targetID), target_type 
		          FROM votes"; 
		$result=$this->kern->execute($query);	
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  $this->updateVotes($row['target_type'], $row['targetID']);
	}
	
	function runCrons()
	{
		// Check trending app
		$this->checkTrending();
		
		// Check dir app
		$this->checkDirApp();
		
		// Check makret app
		$this->checkMktApp();
		
		// Check votes
		$this->checkVotes();
		
		// Update voters
		$this->updateVoters();
		
		// Update payments
		$this->updatePayments();
		
		// Print
		print "Done.";
	}
}
?>