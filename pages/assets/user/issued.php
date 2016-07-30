<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CIssued.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $issued=new CIssued($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<script src="../../../utils.js"></script>
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
	 
	 if ($_REQUEST['act']!="show_modal")
	 {
	  // Menu
	 $template->showNav(3,
	                    "../user/index.php", "Assets", "",
	                    "../user/my_assets.php", "My Assets", $_REQUEST['ud']['pending_adr'],
						"../user/issued.php", "Issued Assets", "");
	 
	 
	 // Help
	 $template->showHelp("Below are displayed the assets you have issued. You can issue your own tokens for anything you can imagine and even gain a small fee after each transaction. The potential use cases for user-issued assets are innumerable. They could represent corporate shares, reward points, real world currencies and so n. Below are listed assets issued by you.");
	 
	// Issue button
	$issued->showIssueBut(); 
	}
	 
    // Issue modal
	if ($_REQUEST['act']=="show_modal") $issued->showIssueAssetModal($_REQUEST['symbol']);
	
	// Issue ?
	if ($_REQUEST['act']=="issue") $issued->newAsset($_REQUEST['dd_issue_adr'], 
	                                                $_REQUEST['dd_issue_adr'], 
													$_REQUEST['txt_issue_name'], 
													$_REQUEST['txt_issue_desc'], 
													$_REQUEST['txt_issue_buy'], 
													$_REQUEST['txt_issue_sell'], 
													$_REQUEST['txt_issue_website'], 
													$_REQUEST['txt_issue_pic'], 
													$_REQUEST['txt_issue_symbol'], 
													$_REQUEST['txt_issue_init_qty'], 
													$_REQUEST['txt_issue_trans_fee'], 
													$_REQUEST['txt_issue_trans_fee_adr'], 
													$_REQUEST['txt_issue_days'], 
													$_REQUEST['dd_can_issue'],
													$_REQUEST['txt_interest'],
													$_REQUEST['dd_interval']); 
													
	// Issued assets
    if ($_REQUEST['act']!="show_modal") $issued->showAssets();								
	
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
