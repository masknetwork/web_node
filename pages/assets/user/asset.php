<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAsset.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $asset=new CAsset($db, $template);
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
     $template->showLocation("../../assets/assets/index.php", "Assets", "", "Assets");
	 
	 // Trade
	 
	 
	 // Panel
	 $asset->showPanel($_REQUEST['symbol']);
	
	  // Menu
	  print "<br>";
	 
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
						
	  switch ($_REQUEST['target'])
	  {
		  case "owners" : $asset->showOwners($_REQUEST['symbol']); break;
		  case "trans" : $asset->showTrans($_REQUEST['symbol']); break;
		  case "markets" : $asset->showMarkets($_REQUEST['symbol']); break;
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
