<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CRegMkt.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $reg_mkt=new CRegMkt($db, $template);
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
	 
	 // Action
	 switch ($_REQUEST['act'])
	 {
		 case "new_position" : $reg_mkt->newMarketPos($_REQUEST['ID'],
	                                              $_REQUEST['tip'],
	                                              $_REQUEST['dd_new_trade_net_fee_adr'], 
	                                              $_REQUEST['dd_new_pos_adr'],
												  $_REQUEST['dd_new_pos_adr_asset'], 
					                              $_REQUEST['txt_new_trade_price'], 
					                              $_REQUEST['txt_new_trade_qty'], 
					                              $_REQUEST['txt_new_trade_days']); 
							break;
							
		case "close_order" : $reg_mkt->closeOrder($_REQUEST['dd_close_order_net_fee_adr'], 
		                                         $_REQUEST['orderID']); 
							break;
							
		case "new_trade" : $reg_mkt->newTrade($_REQUEST['dd_order_net_fee_adr'], 
		                                     $_REQUEST['dd_new_trade_adr'], 
		                                     $_REQUEST['new_trade_orderID'], 
											 $_REQUEST['txt_new_trade_order_qty']); 
		                  break;
	 }
	 
	 // Show panel
	 $reg_mkt->showPanel($_REQUEST['ID']);
	 
	 // Show report
	 $reg_mkt->showReport($_REQUEST['ID']);
	 
	 // Target
	 if (!isset($_REQUEST['target'])) $_REQUEST['target']="sellers";
	 
	 switch ($_REQUEST['target'])
	 {
		 case "sellers" : $sel=1; break;
		 case "buyers" : $sel=2; break;
		 case "trades" : $sel=3; break;
	 }
	 
	 // Menu
	 $template->showNav($sel,
	                    "market.php?target=sellers&ID=".$_REQUEST['ID'], "Sellers", "",
	                    "market.php?target=buyers&ID=".$_REQUEST['ID'], "Buyers", "",
						"market.php?target=trades&ID=".$_REQUEST['ID'], "Last Trades", "");
	 
	 
	 // Traders
	 switch ($sel) 
	 {
		 case 1 : $reg_mkt->showTraders($_REQUEST['ID'], "ID_SELL");
		          break;
				  
		 case 2 : $reg_mkt->showTraders($_REQUEST['ID'], "ID_BUY");
		          break;
				  
		 case 3 : $reg_mkt->showLastTrades($_REQUEST['ID']);
		          break;
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
