<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CMktPeggedAssets.php";
   include "../user/CAsset.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $asset=new CAsset($db, $template);
   $assets=new CMktPeggedAssets($db, $template, $asset);
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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>


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
	 $template->showNav(1,
	                    "../pegged_assets/index.php", "Assets", "",
	                    "../pegged_assets/my_assets.php", "My Assets", "",
						"../pegged_assets/issued.php", "Issued Assets", "");
	 
	 
	 // Help
	 $template->showHelp("In finance, a binary option is a type of option in which the payoff can take only two possible outcomes, either some fixed monetary amount or nothing at all, in contrast to ordinary financial options that typically have a continuous spectrum of payoff. Binary options pay some fixed amount of cash if some conditions are met in a specified time interval. Over MaskNetwork you can issue your own binary options or invest in existing options launched by other users. Because there is no central server, you can bet on anything for which a data feed exist. Below are listed active binary options.");
	
	// Action
	if ($_REQUEST['buy_modal_act']=="ID_BUY" || 
	    $_REQUEST['buy_modal_act']=="ID_SELL") 
	$assets->newOrder($_REQUEST['dd_buy_net_fee_adr'],
	                  $_REQUEST['dd_buy_adr'], 
					  $_REQUEST['dd_sell_adr'], 
					  $_REQUEST['symbol'], 
					  $_REQUEST['buy_modal_act'], 
					  $_REQUEST['txt_buy_qty']);
	
	// Show Panel 
	$assets->showPanel($_REQUEST['symbol']);
	
	 // Selected
	 if (!isset($_REQUEST['target'])) $_REQUEST['target']="owners";
	  
	  switch ($_REQUEST['target'])
	  {
		  case "owners" : $sel=1; break;
		  case "trans" : $sel=2; break;
		  case "markets" : $sel=3; break;
	  }
	 
	  $template->showNav($sel,
	                    "../user/asset.php?target=owners&symbol=".$_REQUEST['symbol'], "Owners", "",
	                    "../user/asset.php?target=trans&symbol=".$_REQUEST['symbol'], "Transactions", $_REQUEST['ud']['pending_adr'],
						"../user/asset.php?target=markets&symbol=".$_REQUEST['symbol'], "Markets", "");
						
	  $asset->showOwners($_REQUEST['symbol']);
	
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
