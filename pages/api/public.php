<?
    include "../../kernel/db.php";
	include "CAPI.php";
	
	$db=new db();
	$api=new CAPI($db);
	
	$api->info($_REQUEST['table'], 
	           $_REQUEST['col'], 
			   $_REQUEST['type'], 
			   $_REQUEST['val'], 
			   $_REQUEST['min'], 
			   $_REQUEST['max']);
?>
