<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CRegMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $reg_mkts=new CRegMkts($db, $template);
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
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(3,
	                    "../assets_mkts/index.php", "Markets", "",
	                    "../assets_mkts/my_pos.php", "My Positions", "",
						"../assets_mkts/issued.php", "My Markets", "");
	 
	 
	 // Help
	 $template->showHelp("Any user can start an asset market. The fee is 0.0001 MSK / day. You don't need to provide a collateral as in margin markets case. You only need to specify what asset you want to be exchanged and a currency. The currency can be MaskCoin or any other network asset. Market pegged assets are accepted as currencies also. Below are listed the asset markets launched by you.");
	 
	// New Market
	$reg_mkts->showNewMktBut();
	
	// New market modal
	$reg_mkts->showNewRegularMarketModal();
	
	// New market ?
	switch ($_REQUEST['act'])
	{
		case "new_market" : $reg_mkts->newMarket($_REQUEST['dd_new_net_fee_adr'], 
	                                            $_REQUEST['dd_new_mkt_adr'], 
					                            $_REQUEST['txt_new_asset_symbol'], 
					                            $_REQUEST['txt_new_cur'], 
					                            $_REQUEST['dd_decimals'],
					                            $_REQUEST['txt_new_name'], 
					                            $_REQUEST['txt_new_desc'], 
					                            $_REQUEST['txt_new_days']); 
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
