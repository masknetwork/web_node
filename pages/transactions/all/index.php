<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CTransactions.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $trans=new CTransactions($db, $template);
 ?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">
<script type="text/javascript">$(document).ready(function() { $("body").tooltip({ selector: '[data-toggle=tooltip]' }); });</script>
<script src="../../../utils.js"></script>

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_12 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

</style>

</head>

<body>

<?
   $template->showTopBar();
?>
 

 <div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1">&nbsp;</div>
 <div class="col-md-8" align="center" style="height:100%; background-color:#ffffff">
 
 <?
     // Location
     $template->showLocation("../../transactions/all/index.php", "Transactions", "", "All");
	 
	 // Menu
	 $template->showNav(1,
	                   "../all/index.php", "All", $_REQUEST['ud']['unread_trans'],
	                   "../escrowed/index.php", "Escrowed", $_REQUEST['ud']['unread_esc']);
	 
	 // Help
     $template->showHelp("Below the last transactions executed are displayed. A transaction is confirmed in the network in about 2 minutes. Then, the transaction is final and it cannot be reversed. The transactions can have attached data such as a message or other information.");
	 
	  
	   
	  // Simpel send
				  if ($_REQUEST['act']=="send_coins")
		          $trans->sendCoins($_REQUEST['dd_from'], 
			                        $_REQUEST['dd_from'], 
								    $_REQUEST['txt_to'], 
								    $_REQUEST['txt_msk'], 
									$_REQUEST['txt_asset_amount'], 
								    $_REQUEST['txt_cur'], 
								    $_REQUEST['txt_mes'], 
								    $_REQUEST['txt_escrower']);
		
	 
	     $trans->showTrans("ID_ALL"); 		  
	  
	  if ($_REQUEST['act']=="send_block")
	  {
		$db->sendBlock();
	    $template->showOK("Your request has been successfully recorded"); 
	  }
 ?>
 </div>
 <div class="col-md-2" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>
