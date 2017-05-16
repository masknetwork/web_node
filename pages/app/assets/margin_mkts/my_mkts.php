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
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_MSK { font-size: 40px; }
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
	 
	 // Modal
	 $mkts->showNewMktModal();
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(3,
	                    "../margin_mkts/index.php", "Markets", "",
	                    "../margin_mkts/positions.php", "My Positions", "",
						"../margin_mkts/my_mkts.php", "My Markets", "");
	 
	 
	 // Help
	 $template->showHelp("Starting your own leveraged market over MaskNetwork is one of the best ways to make money. All you need is a trusted data feed and some coins / assets to cover the collateral. You can start a margin markets on any asset like stocks, currencies, cryptocoins or other real world or virtual assets. You can customize the spread, leverage and other important aspects of your market. The rules are simple. All traders looses are your gains and vice versa. You can also setup multiple markets using the same address (collateral). 90% of traders loose money so it's an easy job for you.");

	 // New market
	 if ($_REQUEST['act']=="show_new_mkt_panel") 
	    $mkts->showNewMarketPanel();
		
	 else if ($_REQUEST['act']=="new_mkt")
	    $mkts->newMarket($_REQUEST['dd_new_mkt_net_fee_adr'], 
	                     $_REQUEST['dd_new_mkt_adr'], 
					     $_REQUEST['txt_new_mkt_feed'], 
					     $_REQUEST['txt_new_mkt_branch'], 
						 $_REQUEST['txt_new_mkt_cur'], 
					     $_REQUEST['txt_new_mkt_max_leverage'],
					     $_REQUEST['txt_new_mkt_spread'], 
					     $_REQUEST['txt_new_mkt_days'],
						 $_REQUEST['txt_new_mkt_max_total_margin'],
					     $_REQUEST['txt_new_mkt_title'],
					     $_REQUEST['txt_new_mkt_desc']);
	
	 else
	    $mkts->showNewMktBut();
		
     // My markets
	 if ($_REQUEST['act']!="show_new_mkt_panel") $mkts->showMarkets(true);
	
	
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
