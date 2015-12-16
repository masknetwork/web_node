<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAds.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $ads=new CAds($db, $template);
   
   if (!isset($_SESSION['userID'])) $this->kern->redirect("../../../index.php");
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
<script type="text/javascript">$(document).ready(function() { $("body").tooltip({ selector: '[data-toggle=tooltip]' }); });</script>
<script src="../../../utils.js"></script>

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
   $template->showTopBar(2);
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
     $template->showLocation("../../ads/ads/index.php", "My Ads", "", "My Ads");
	 
	
	 // Help
	 $template->showHelp("Listed below are your advertisements. The ads are displayed based on the bid price. Only the top 10 advertising messages are displayed. The bid price is for one hour of display. You can increase the bid price for an advertisement but you cannot lower it. Also you cannot stop an advertisement since it has been paid and displayed. Ads are displayed in the entire MAskNetwork network including desktop nodes.");
			   
	if ($_REQUEST['act']=="renew")
	    $ads->increaseBid($_REQUEST['dd_fee_adr'], 
				                   $_REQUEST['rowhash'], 
								   $_REQUEST['txt_new_bid']);
			   
	// New ad
	if ($_REQUEST['act']=="new_ad")
			      $ads->newAd($_REQUEST['dd_ads_fee_adr'], 
				              $_REQUEST['txt_ads_title'], 
							  $_REQUEST['txt_ads_mes'], 
							  $_REQUEST['txt_ads_link'], 
							  $_REQUEST['dd_country'], 
							  $_REQUEST['txt_ads_hours'], 
							  $_REQUEST['txt_ads_bid']);
							  
	// Ads
	$ads->showMyAds();
				
	// Increase bid
	$template->showIncreaseBidModal();
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
