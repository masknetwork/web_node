<?
    include "../../kernel/db.php";
	include "../../kernel/CUserData.php";
	include "../template/template/CTemplate.php";
	include "../../kernel/CSysData.php";
	include "../transactions/CTransactions.php";
	include "CAPI.php";
	
	$db=new db();
	$sd=new CSysData($db);
	$ud=new CUserData($db);
	$template=new CTemplate($db);
	$api=new CAPI($db);
	$trans=new CTransactions($db, $template);
	
	// API Key
	if (!isset($_REQUEST['key']))
	   die("Invalid API key");
	
	switch ($_REQUEST['act'])
	{
		case "ID_SEND_COINS" : $trans->sendCoins(urldecode($_REQUEST['net_fee_adr']), 
	                                            urldecode($_REQUEST['from_adr']), 
					                            urldecode($_REQUEST['to_adr']), 
					                            $_REQUEST['amount'], 
					                            $_REQUEST['amount_asset'], 
					                            $_REQUEST['cur'], 
					                            urldecode($_REQUEST['mes']), 
					                            urldecode($_REQUEST['escrower'])); 
							break; 
	}
	
?>
