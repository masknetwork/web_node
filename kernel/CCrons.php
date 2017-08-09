<?
class CCrons
{
	function CCrons($db)
	{
		$this->kern=$db;
	}
	
	function runPendingAdr()
	{
		$query="SELECT * 
		          FROM pending_adr 
				 WHERE parsed=0";
		$result=$this->kern->execute($query);	
	    
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			$query="SELECT * 
			          FROM my_adr 
					 WHERE adr='".$row['share_adr']."'";
		    $res=$this->kern->execute($query);	
			$row2 = mysqli_fetch_array($res, MYSQL_ASSOC);
			
			// Increase unread adr
			$query="UPDATE web_users 
			           SET pending_adr=pending_adr+1 
					 WHERE ID='".$row2['userID']."'";
		    $this->kern->execute($query);
		}
		
		// Parsed
		$query="UPDATE pending_adr SET parsed=0";
		$this->kern->execute($query);
	}
	
	function run()
	{
		$this->runPendingAdr();
	}
}
?>