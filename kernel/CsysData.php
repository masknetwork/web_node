<?
class CSysData
{
	function CSysData($db)
	{
		$this->kern=$db;
		
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$_REQUEST['sd']['status']=$row['status'];
		$_REQUEST['sd']['msk_price']=$row['msk_price'];
		
		$query="SELECT * FROM status";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$_REQUEST['sd']['engine_status']=$row['engine_status'];
		$_REQUEST['sd']['last_tables_block']=$row['last_tables_block'];
		$_REQUEST['sd']['last_blocks_block']=$row['last_blocks_block'];
		$_REQUEST['sd']['version']=	$row['version'];
		$_REQUEST['sd']['alive']=$row['alive'];
		$_REQUEST['sd']['netstat']=$row['netstat'];
		$_REQUEST['sd']['bkp_email']=$row['bkp_email'];
		$_REQUEST['sd']['bkp_last_send']=$row['bkp_last_send'];	 	 	 	 			                        
		$_REQUEST['sd']['bkp_pending']=$row['bkp_pending'];	 	 	 	 			                    
		$_REQUEST['sd']['last_block_hash']=$row['last_block_hash'];	 	 	 	 			                        
		$_REQUEST['sd']['last_packet_hash']=$row['last_packet_hash'];	 	 	 	 			                     
		$_REQUEST['sd']['update_required']=$row['update_required'];	 	 	 	 			                       
	}
}
?>