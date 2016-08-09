<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CApp.php";
   include "CAppStore.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $app=new CApp($db, $template);
   $store=new CAppStore($db, $template, $app);
   
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

<style type="text/css" media="screen">
    #editor_code { 
        width:90%;
		height:600px;
		margin:0 auto;
    }
</style>

</head>

<body>

<?
   $template->showTopBar("app");
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
     $template->showLocation("../../app/market/index.php", "Applications Market", "", "Application Details");
	
	 // Action
	 switch ($_REQUEST['act'])
	 {
		 case "rent" : $store->rent($_REQUEST['dd_rent_net_fee_adr'], 
		                           $_REQUEST['dd_rent_adr'], 
								   $_REQUEST['rent_appID'], 
								   $_REQUEST['txt_rent_days']); 
				      break;
	 }
	 
	 // Show
	 $store->showPanel($_REQUEST['ID']);
	 
	 // Won
	 $store->showWonPanel($_REQUEST['ID']);
	 
	 // Target
	 if (!isset($_REQUEST['target']))
	    $_REQUEST['target']="installs";
	 
	 // Selection
	 switch ($_REQUEST['target'])
	 {
		 case "installs" : $sel=1; break;
		 case "code" : $sel=2; break;
	 }
	 
	 // Details
	 $template->showNav($sel,
	                    "app.php?target=installs&ID=".$_REQUEST['ID'], "Installed", "",
	                    "app.php?target=code&ID=".$_REQUEST['ID'], "Source Code", $_REQUEST['ud']['pending_adr']);
						
	 switch ($_REQUEST['target'])
	 {
		 case "installs" : $store->showInstalls($_REQUEST['ID']); break;
		 case "code" : $app->showSource($_REQUEST['ID']); break;
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
