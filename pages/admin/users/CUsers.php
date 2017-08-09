<?
class CUsers
{
	function CUsers($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
		if ($_REQUEST['ud']['user']!="root") die ("Invalid credentials");
	}
	
	function getBalance($userID)
	{
		$query="SELECT sum(adr.balance) AS total
		          FROM my_adr AS ma 
				  JOIN adr ON adr.adr=ma.adr 
				 WHERE ma.userID='".$userID."'"; 
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		return $row['total'];
	}
	
	function showUsers($search="")
	{
		$query="SELECT * 
		          FROM web_users 
				 WHERE (user LIKE '%".$search."%' 
				        OR email LIKE '%".$search."%') ORDER BY ID DESC LIMIT 0,20";
	    $result=$this->kern->execute($query);	
			  
	  
		?>
        
        <table class="table table-responsive table-striped table-hover table-bordered" style="width:93%">
        <thead class="font_14">
        <th width="55%">User</th>
        <th width="25%">Balance</th>
        <th width="20%">Signup</th>
        </thead>
        <tbody>
        
        <?
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
              <tr>
              <td><a href="#" class="font_14"><? print $row['user']; ?></a></td>
              <td class="font_14"><? print $this->getBalance($row['ID'])." MSK"; ?></td>
              <td class="font_14"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
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