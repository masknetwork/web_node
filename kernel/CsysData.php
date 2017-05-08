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
		$_REQUEST['sd']['MSK_price']=$row['MSK_price'];
		
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
		
		// Net stat
		$query="SELECT * FROM net_stat";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);	 
		
		$_REQUEST['sd']['last_block']=$row['last_block']; 		
		$_REQUEST['sd']['net_dif']=$row['net_dif']; 		
		$_REQUEST['sd']['delegate']=$row['delegate']; 		
		$_REQUEST['sd']['blocks_per_minute']=3;	 	
		$_REQUEST['sd']['blocks_per_hour']=$_REQUEST['sd']['blocks_per_minute']*60;	 	 	
		$_REQUEST['sd']['blocks_per_day']=$_REQUEST['sd']['blocks_per_hour']*24;
		$_REQUEST['sd']['blocks_per_month']=$_REQUEST['sd']['blocks_per_day']*30;
		$_REQUEST['sd']['blocks_per_year']=$_REQUEST['sd']['blocks_per_day']*365; 	
		
		// Interest rate
	    $query="SELECT * FROM adr WHERE adr='default'";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// In circulation
		$in_circ=21000000-$row['balance'];
		
	    // Net stat
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$_REQUEST['sd']['mining_threads']=$row['mining_threads'];
		$_REQUEST['sd']['last_ping']=$row['last_ping'];
		$_REQUEST['sd']['status']=$row['status'];
		
		
		// Offline ?
		/*if ($_REQUEST['sd']['status']=="ID_OFFLINE" ||
            (time()-$_REQUEST['sd']['last_ping'])>10)
			{
			   if (strpos($_SERVER['REQUEST_URI'], "pages")>0)
                  $db->redirect("../../maintainance/maintainance/index.php");
			   else
			      $db->redirect("./pages/maintainance/maintainance/index.php");
			}*/
	}
}
?>