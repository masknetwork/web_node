<?
class CCrons
{
	function CCrons($db)
	{
		$this->kern=$db;
	}
	
	function getReward($categ)
	{
		// Undistributed coins
		$balance=$this->kern->getBalance("default", "MSK");
		
		// Balance
		$u=$balance/365/20; 
		
		// Reward
		switch ($categ)
		{
			// Posts
			case "ID_POST" : $reward=$u*20/100; break;
			
			// Comments
			case "ID_COM" : $reward=$u*10/100; break;
			
			// Feeds
			case "ID_FEEDS" : $reward=$u*5/100; break;
			
			// Assets
			case "ID_ASSETS" : $reward=$u*5/100; break;
			
			// Bets
			case "ID_BETS" : $reward=$u*10/100; break;
			
			// Margin
			case "ID_MKTS" : $reward=$u*10/100; break;
			
			// Miners
			case "ID_MINERS" : $reward=$u*40/100; break;
		}
		
		// Return
		return round($reward);
	}
	
	function loadTotalVotes()
	{
		// Total
		$this->upvotes_total_posts=0;
		
		// Total upvotes power for blog posts
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_POST' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_posts=$row['total'];
		   
		// Total upvotes power for comments
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_COM' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_com=$row['total'];
		   
		// Total upvotes power for feeds
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_FEEDS' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_feeds=$row['total'];
		   
		// Total upvotes power for assets
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_ASSETS' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_assets=$row['total'];
		   
		// Total upvotes power for bets
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_BETS' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_bets=$row['total'];
		   
		// Total upvotes power for markets
		$query="SELECT SUM(vp.vote_power) AS total
		          FROM votes 
				  JOIN votes_power AS vp ON votes.ID=vp.voteID
				  WHERE votes.target_type='ID_MKTS' 
				    AND votes.type='ID_UP' 
				    AND votes.block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->upvotes_total_mkts=$row['total'];
	}
	
	function getTotalVotesPowerFor($categ)
	{
		switch ($categ)
	    {
			// Posts
			case "ID_POST" : $power=$this->upvotes_total_posts; break;
			
			// Comments
			case "ID_COM" : $power=$this->upvotes_total_com; break;
			
			// Comments
			case "ID_FEEDS" : $power=$this->upvotes_total_feeds; break;
			
			// Comments
			case "ID_ASSETS" : $power=$this->upvotes_total_assets; break;
			
			// Comments
			case "ID_BETS" : $power=$this->upvotes_total_bets; break;
			
			// Comments
			case "ID_MKTS" : $power=$this->upvotes_total_mkts; break;
		}
		
		// Power
		return $power;
	}
	
	function mskPrice()
	{
		$data = file_get_contents("http://www.maskexchange.com/api/ticker.php"); 
		$decode=json_decode($data);
		$price=$decode->{'price'};
		
		// Update
		$query="UPDATE web_sys_data SET msk_price='".$price."'";
		$this->kern->execute($query);
	}
	
	function sendEmails()
	{
		$query="SELECT * FROM out_emails WHERE status='ID_PENDING'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    mail($row['dest'], base64_decode($row['subj']), base64_decode($row['mes']));
	}
	
	function getCreationBlock($target_type, $targetID)
	{
		switch ($target_type)
		{
		   // Posts
		   case "ID_POST" : $query="SELECT * 
		                             FROM tweets 
									WHERE tweetID='".$targetID."'"; 
						    break;
		  
		  // Comments		
		  case "ID_COM" : $query="SELECT * 
		                             FROM comments 
									WHERE comID='".$targetID."'"; 
						    break;
							
		  // Comments		
		  case "ID_BETS" : $query="SELECT * 
		                             FROM feeds_bets 
									WHERE betID='".$targetID."'"; 
						    break;
		}
		
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $row['block'];
	}
	
	function getVotePower($adr, $target_type, $targetID, $block)
	{
		// Count votes
		$query="SELECT COUNT(*) AS total 
		          FROM votes 
				 WHERE adr='".$adr."' 
				   AND block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$no=$row['total'];
		
		// Return power
		$power=round($this->kern->getBalance($adr, "MSK")/$no, 2); 
		
		// Distance from posting
		if ($target_type=="ID_POST" || 
		    $target_type=="ID_COM" || 
			$target_type=="ID_BETS")
		{
			// Block
			$block=$this->getCreationBlock($target_type, $targetID);
			
			// Percent
			$p=0.07*($_REQUEST['sd']['last_block']-$block); 
			
			// Power
			$power=round($power-$p*$power/100, 2);
		}
		
		return $power;
	}
	
	function updateVotesPower()
	{
		// Delete from votes_power
		$query="DELETE FROM votes_power";
		$this->kern->execute($query);	
		
		// Load votes
		$query="SELECT * FROM votes";
		$result=$this->kern->execute($query);	
	    
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		   // Power
		   $power=$this->getVotePower($row['adr'], 
		                             $row['target_type'], 
									 $row['targetID'],
									 $row['block']);
									 
		   // Insert
		   $query="INSERT INTO votes_power 
		                   SET voteID='".$row['ID']."', 
						        vote_power='".$power."'";	
		   $this->kern->execute($query);
		}
	}
	
	function updateContent($target_type, $targetID)
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
		$query="SELECT SUM(vp.vote_power) AS total
				  FROM votes 
				  JOIN votes_power AS vp ON vp.voteID=votes.ID
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_UP'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$upvotes_power_24=$row['total'];
		
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
		$query="SELECT SUM(vp.vote_power) AS total
				  FROM votes 
				  JOIN votes_power AS vp ON vp.voteID=votes.ID
				 WHERE votes.target_type='".$target_type."' 
				   AND votes.type='ID_DOWN'
	   		       AND votes.targetID='".$targetID."' 
	    		   AND votes.block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$downvotes_power_24=$row['total'];
		
		// Net
		$net=$upvotes_power_24-$downvotes_power_24; 
		
		// Payment
		$pay=0;
		
		// Calculate payment
		if ($net>0)
		{
			// Percent out of total
			$p=$net*100/$this->getTotalVotesPowerFor($target_type); 
			
			// Payment
			$pay=round($p*$this->getReward($target_type)/100, 4);
			
			// Posts or comments ?
			if ($target_type=="ID_POST" || 
			    $target_type=="ID_COM")
			    $pay=$pay/2;		
				
			// Minimum payment ?
			if ($pay<0.0001) $pay=0;
		}
		   
		// Add
	    $this->addToVotesStat($target_type, 
	                          $targetID, 
							  $upvotes_24, 
							  $upvotes_power_24, 
							  $downvotes_24, 
							  $downvotes_power_24, 
							  $pay); 
		print "----------------------------------------------------<br>";
	}
	
	
	function notEmpty($var)
	{
		if ($var=="") 
		   return 0;
		else 
		   return $var;
	}
	
	function addToVotesStat($target_type, 
	                        $targetID, 
							$upvotes_24, 
							$upvotes_24_power, 
							$downvotes_24, 
							$downvotes_24_power,
							$pay)
	{
		    // Format
			$upvotes_24=$this->notEmpty($upvotes_24);
			$upvotes_24_power=$this->notEmpty($upvotes_24_power);
			$upvotes_total=$this->notEmpty($upvotes_total);
			$upvotes_total_power=$this->notEmpty($upvotes_total_power);
			$downvotes_24=$this->notEmpty($downvotes_24);
			$downvotes_24_power=$this->notEmpty($downvotes_24_power);
			$downvotes_total=$this->notEmpty($downvotes_total);
			$downvotes_total_power=$this->notEmpty($downvotes_total_power);
			
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
							         downvotes_24='".$downvotes_24."', 
							         downvotes_power_24='".$downvotes_24_power."', 
									 pay='".$pay."',
						      	     tstamp='".time()."'"; 
		   else
		       $query="UPDATE votes_stats 
		                  SET target_type='".$target_type."', 
						      targetID='".$targetID."', 
							  upvotes_24='".$upvotes_24."', 
							  upvotes_power_24='".$upvotes_24_power."', 
							  downvotes_24='".$downvotes_24."', 
							  downvotes_power_24='".$downvotes_24_power."', 
							  pay='".$pay."',
							  tstamp='".time()."'
						WHERE target_type='".$target_type."' 
						  AND targetID='".$targetID."'"; 
						  			  
	      $this->kern->execute($query);	
	}
	
	
	
	function checkContent()
	{
		// Delete votes stats
		$query="DELETE FROM votes_stats";
		$this->kern->execute($query);
		
		// Load votes in the last 24 hours
		$query="SELECT DISTINCT(targetID), target_type 
		          FROM votes"; 
		$result=$this->kern->execute($query);
			
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  $this->updateContent($row['target_type'], $row['targetID']);
	}
	
	function votesPayment()
	{
		// Load votes
		$query="SELECT * 
		          FROM votes 
				  JOIN votes_power AS vp ON vp.voteID=votes.ID 
				 WHERE (votes.target_type='ID_POST' || 
				        votes.target_type='ID_COM' || 
						votes.target_type='ID_BETS')"; 
		$result=$this->kern->execute($query);
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		    $query2="SELECT * 
			          FROM votes_stats 
					 WHERE target_type='".$row['target_type']."' 
					   AND targetID='".$row['targetID']."'";	
					   
		    $result2=$this->kern->execute($query2);	
	        $row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
		    $upvotes_power=$row2['upvotes_power_24']; 
		  
		    // Find percent
		    $vote_power=$row['vote_power'];
		  
		    // Percent
		    $p=round($vote_power*100/$upvotes_power, 2); 
		  
		    // Pay
		    $pay=round($p*$row2['pay']/100, 4);
		  
		    // Update
		    $query="UPDATE votes_power 
		               SET vote_pay='".$pay."' 
				     WHERE ID='".$row['ID']."'";
		    $this->kern->execute($query);	
		}
	}
	
	function checkBalances()
	{
		$pi_1=file_get_contents("http://192.168.1.111/balance.php"); 
		$pi_2=file_get_contents("http://192.168.1.112/balance.php"); 
		$pi_3=file_get_contents("http://192.168.1.113/balance.php"); 
		$pi_4=file_get_contents("http://192.168.1.114/balance.php"); 
		$block=file_get_contents("http://192.168.1.114/block.php"); 
		
		if ($pi_1==$pi_2 && $pi_2==$pi_3 && $pi_3==$pi_4)
		{
		  print "";
		}
		else
		{
		  $query="INSERT INTO err_log SET mes='Not match at block ".$block."', tstamp='".time()."'";
		  $this->kern->execute($query);	
		}
	}
	
	function runCrons()
	{
		// Update votes power
		$this->updateVotesPower();
		
		// Load total votes
		$this->loadTotalVotes();
		
		// Check votes
		$this->checkContent();
		
		// Votes pay
		$this->votesPayment();
		
		// MSK price
		//$this->mskPrice();
		
		// Print
		print "Done.";
	}
}
?>