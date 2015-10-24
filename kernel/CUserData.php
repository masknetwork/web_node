<?
class CUserData
{
  function CUserData($db)
	{
		$this->kern=$db;
		
		$query="SELECT * 
		          FROM web_users 
				  WHERE web_users.ID=".$_SESSION['userID'];
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$_REQUEST['ud']['ID']=$row['ID'];
		$_REQUEST['ud']['user']=$row['user'];
		$_REQUEST['ud']['unread_mes']=$row['unread_mes'];
		$_REQUEST['ud']['unread_trans']=$row['unread_trans'];
		$_REQUEST['ud']['unread_multisig']=$row['unread_multisig'];
		$_REQUEST['ud']['unread_esc']=$row['unread_esc'];
		$_REQUEST['ud']['pending_adr']=$row['pending_adr'];
		
		// Balance
		$query="SELECT sum(adr.balance) AS total 
		          FROM my_adr 
				  JOIN adr ON adr.adr=my_adr.adr 
				 WHERE my_adr.userID='".$_REQUEST['ud']['ID']."'"; 
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Balance
		$_REQUEST['ud']['balance']=round($row['total'], 4);
	}
}
?>