<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CMktPeggedAssets.php";
   include "../CAssets.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $as=new CAssets($db, $template);
   $assets=new CMktPeggedAssets($db, $template);
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
     $template->showLocation("../../assets/pegged_assets/index.php", "Market Pegged Assets", "", "Assets");
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(1,
	                    "../pegged_assets/index.php", "Assets", "",
	                    "../pegged_assets/my_assets.php", "My Assets", "",
						"../pegged_assets/issued.php", "Issued Assets", "");
	 
	 
	 // Help
	 $template->showHelp("MaskNetwork market pegged assets are a new type of freely traded digital asset whose value is meant to track the value of a conventional asset such as the U.S. dollar or gold. While Bitcoin has demonstrated many useful properties as a currency, its price volatility makes it risky to hold, especially for merchants. A currency with the properties and advantages of Bitcoin that maintains price parity with a globally adopted currency such as the US dollar has high utility for merchants because they eliminate the volatility risk. Market pegged assets are not limited to stablle currencies like USD. Any user can launch a market pegged token, linked to any real life asset like Apple shares or Bitcoin. Below are listed the available market pegged assets.");
	 
	 // Target
	 if (!isset($_REQUEST['target']))
	    $_REQUEST['target']="ID_CRYPTO";
	 
	 // Categ
	 $as->showSelector($_REQUEST['target']);
	 
	// Show assets
	$assets->showAssets($_REQUEST['target']);
	
	
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
