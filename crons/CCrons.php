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
	
	function runCrons()
	{
		// Check trending app
		$this->checkTrending();
		
		// Check dir app
		$this->checkDirApp();
		
		// Check makret app
		$this->checkMktApp();
		
		// Print
		print "Done.";
	}
}
?>