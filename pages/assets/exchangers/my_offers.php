<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CExchangers.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $ex=new CExchangers($db, $template);
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
     $template->showLocation("../../assets/exchangers/index.php", "Asset Exchangers", "", "My Offers");
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(2,
	                    "../exchangers/index.php", "Exchangers", "",
	                    "../exchangers/my_offers.php", "My Offers", "");
	 
	 
	 // Help
	 $template->showHelp("Any user can post exchange offers. You can trade Maskcoins or any other network asset locally or globally for cash or online paayments of your choise. With no central authority, there are no rules. You choose your own spread, trade currency or payment methods. You can setup a fixed price or a price based on a data feed and include external contact details such as email or telephone for those who want to contact you. ");
	
	// Button
	$ex->showNewOfferBut();

	// Show panel
	switch ($_REQUEST['act'])
	{
		case "show_panel" : $ex->showNewOfferPanel();
		                   break;
						   
	    case "new"  : $ex->newOffer($_REQUEST['dd_new_ex_net_fee_adr'],
	                               $_REQUEST['dd_new_ex_adr'],
	                               $_REQUEST['txt_new_ex_title'],
	                               $_REQUEST['txt_new_ex_desc'],
	                               $_REQUEST['txt_new_ex_webpage'],
	                               $_REQUEST['pos_new_ex_type'],
	                               $_REQUEST['txt_new_ex_asset'],
	                               $_REQUEST['dd_new_ex_cur'],
	                               $_REQUEST['txt_new_ex_pay_method'],
	                               $_REQUEST['txt_new_ex_pay_details'],
	                               $_REQUEST['dd_new_ex_price_type'],
	                               $_REQUEST['txt_new_ex_price'],
	                               $_REQUEST['txt_new_ex_feed'],
                                   $_REQUEST['txt_new_ex_branch'],
				                   $_REQUEST['txt_new_ex_margin'],
				                   $_REQUEST['dd_new_ex_country'],
				                   $_REQUEST['dd_new_ex_town'],
				                   $_REQUEST['txt_new_ex_town'],
				                   $_REQUEST['txt_new_ex_escrowers'],
								   $_REQUEST['txt_new_ex_days']);
				     break;
	}
	
	// Exchangers
	$ex->showExchangers(true);
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
