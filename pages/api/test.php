<?
    include "../../kernel/db.php";
	include "../../kernel/CUserData.php";
	include "../template/template/CTemplate.php";
	include "../../kernel/CSysData.php";
	include "CAPI.php";
	
	$db=new db();
	$sd=new CSysData($db);
	$template=new CTemplate();
	$api=new CAPI($db);
	$api->cols("trans");
	//$api->info("adr", "block", "exact", 456, 0, 0);
?>

