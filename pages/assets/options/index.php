<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "COptions.php";
   include "../CAssets.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $assets=new CAssets($db, $template);
   $bets=new COptions($db, $template);
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
     $template->showLocation("../../assets/options/index.php", "Binary Options", "", "Binary Options");
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(1,
	                    "../options/index.php", "Options", "",
	                    "../options/my_options.php", "My Positions", "",
						"../options/issued.php", "My Options", "");
	 
	 
	 // Help
	 $template->showHelp("In finance, a binary option is a type of option in which the payoff can take only two possible outcomes, either some fixed monetary amount or nothing at all, in contrast to ordinary financial options that typically have a continuous spectrum of payoff. Binary options pay some fixed amount of cash if some conditions are met in a specified time interval. Over MaskNetwork you can issue your own binary options or invest in existing options launched by other users. Because there is no central server, you can bet on anything for which a data feed exist. Below are listed active binary options.");
	 
	 // Target
	 if (!isset($_REQUEST['target']))
	    $_REQUEST['target']="ID_CRYPTO";
	 
	 // Categ
	 $assets->showSelector($_REQUEST['target']);
	 
	 // Selector
	 if (!isset($_REQUEST['status'])) $_REQUEST['status']="ID_PENDING";
	 $bets->showMyOptionsSel($_REQUEST['status']);
	 
     // Show
	 $bets->showOptions($_REQUEST['target'], $_REQUEST['term'], $_REQUEST['status']);
	
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
