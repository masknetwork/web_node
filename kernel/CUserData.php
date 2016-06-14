<?
class CUserData
{
  function CUserData($db)
	{
		$this->kern=$db;
		
		if (!isset($_REQUEST['key']))
		{
		    $query="SELECT * 
		              FROM web_users 
				     WHERE ID=".$_SESSION['userID'];
		    $result=$this->kern->execute($query);
			if (mysql_num_rows($result)==0) $this->kern->redirect("../../index.php");
		}
		else
		{
		    $query="SELECT * 
		              FROM web_users 
				     WHERE api_key='".hash("sha256", $_REQUEST['key'])."'"; 
			$result=$this->kern->execute($query);
			if (mysql_num_rows($result)==0) die("{\"result\" : \"error\", \"reason\" : \"Invalid API key\"}");
		}
		
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
		$balance=round($row['total'], 8);
		
		// Pending
		$query="SELECT sum(tp.amount) AS total 
		          FROM trans_pool AS tp
				  JOIN my_adr ON my_adr.adr=tp.src 
				 WHERE my_adr.userID='".$_REQUEST['ud']['ID']."' 
				   AND tp.amount<0
				   AND cur='MSK'"; 
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$balance_pending=round($row['total'], 8);
		
		// Balance
		$_REQUEST['ud']['balance']=$balance+$balance_pending;
		
		// Update unsigned escrowed transactions
		   $query="SELECT COUNT(*) AS total 
		             FROM escrowed 
					WHERE sender_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					   OR rec_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					   OR escrower IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')";
		   $result=$this->kern->execute($query);
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $unread_esc=$row['total'];
		   
		  
		   // Update unsigned multisig transactions
		   $query="SELECT COUNT(*) AS total 
		             FROM multisig 
					WHERE sender_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					   OR rec_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					   OR signer_1 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					   OR signer_2 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					   OR signer_3 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					   OR signer_4 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					   OR signer_5 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')";
		   $result=$this->kern->execute($query);
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $unread_multisig=$row['total'];
		    
		   // Unread messages
		   $query="SELECT COUNT(*) AS total 
		             FROM mes 
					WHERE (from_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') OR 
					       to_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')) 
					  AND status=0";
		   $result=$this->kern->execute($query);
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $unread_mes=$row['total'];
		   
		   // Pending addresses
		   $query="SELECT COUNT(*) AS total 
		             FROM pending_adr 
					WHERE share_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')";
		   $result=$this->kern->execute($query);
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $pending_adr=$row['total'];
		   
		   // Update
		   $query="UPDATE web_users 
		              SET unread_multisig='".$unread_multisig."', 
					      unread_esc='".$unread_esc."', 
						  unread_mes='".$unread_mes."',
						  pending_adr='".$pending_adr."'  
				    WHERE ID='".$_REQUEST['ud']['ID']."'"; 
		   $this->kern->execute($query);
	}
}
?>