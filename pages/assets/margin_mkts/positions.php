<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CSpecMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mkts=new CSpecMkts($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
 <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
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
   $template->showTopBar(4);
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
     $template->showLocation("../../assets/assets/index.php", "Speculative Markets", "", "Markets");
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(2,
	                    "../margin_mkts/index.php", "Markets", "",
	                    "../margin_mkts/positions.php", "My Positions", "",
						"../margin_mkts/my_mkts.php", "My Markets", "");
	 
	 
	 // Help
	 $template->showHelp("Below are listed your open / pending or closed leveraged positions. You can partially / completely close a position anytime you want. You can also adjust a position stop loss or take profit. Only positions opened over leveraged markets are listed here. Keep in mind that leverage is totally flexible and customizable to each trader's needs. A highly leveraged trade can quickly deplete your trading account if it goes against you, as you will rack up greater losses due to bigger lot sizes.");
	 
	 // Close position
	 switch ($_REQUEST['act'])
	 {
		 case "close_trade" : $mkts->closeTrade($_REQUEST['dd_close_net_fee'], 
		                                       $_REQUEST['close_posID'], 
											   $_REQUEST['dd_close_percent']); 
							 break;
							 
		 case "change_trade" : $mkts->updateTrade($_REQUEST['dd_change_net_fee'], 
		                                        $_REQUEST['h_change_posID'],
												$_REQUEST['txt_change_sl'], 
											    $_REQUEST['txt_change_tp']); 
							   break;
	 }
	 
	 // Show my positions
	 if (!isset($_REQUEST['status'])) $_REQUEST['status']="ID_MARKET";
	  
	 // Selector
	 $mkts->showPosSel($_REQUEST['status']);
	 
	 // Display
	 switch ($_REQUEST['status']) 
	 {
		 case "ID_MARKET" : $mkts->showPositions(0, "ID_MARKET", "mine", true); 
		                 break;
						 
		case "ID_PENDING" : $mkts->showPositions(0, "ID_PENDING", "mine", true); 
		                    break;
						 
		case "ID_CLOSED" : $mkts->showPositions(0, "ID_CLOSED", "mine", true); 
		                   break;
	 }
	
 ?>
 
 <br><br><br>
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
