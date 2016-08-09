<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "COptions.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $bets=new COptions($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

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
   $template->showTopBar("trade");
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
     $template->showLocation("../../assets/options/index.php", "Binary Options", "", "Option Details");
	 
	 // Buy Modal 
	 $bets->showBuyOptionBut($_REQUEST['betID']);
	 
	 
	 // Buy
	 switch ($_REQUEST['act'])
	 {
	    case "buy_bet" : $bets->buyOption($_REQUEST['dd_buy_bet_adr'], 
		                                 $_REQUEST['dd_buy_bet_adr'], 
						                 $_REQUEST['uid'],
						                 $_REQUEST['txt_buy_bet_amount']);
						 break;
						 
		case "vote" : $template->vote($_REQUEST['dd_vote_net_fee'], 
		                             $_REQUEST['dd_vote_adr'], 
				                     $_REQUEST['vote_target_type'], 
				                     $_REQUEST['vote_targetID'], 
				                     $_REQUEST['vote_type']);
				     break;
	 }
	 
	 // Bet
	 $bets->showPanel($_REQUEST['betID']);
	 
	 // Modal
	 $bets->showBuyBetModal($_REQUEST['betID']);
	 
	// Report
	$bets->showReport($_REQUEST['betID']);
	
	// Chart
	$bets->showChart($_REQUEST['betID']);
	
	// Buyers
	$bets->showBuyers($_REQUEST['betID']);
	
	
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
