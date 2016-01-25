<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "COptions.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
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
	 
	  // Menu
	 $template->showNav(3,
	                    "../options/index.php", "Options", "",
	                    "../options/my_options.php", "My Options", "",
						"../options/issued.php", "Issued Options", "");
	 
	 
	 if ($_REQUEST['act']!="show_modal")
	 {
	     // Help
	     $template->showHelp("Below are the addresses that you own. An address is a string of characters and works as an anonymous bank account. In an address you can keep coins or other assets. An address never expires. You can assign it a name or security setting. Click the Options button for more details of the addresses.");
	 
	     // Options
	     $bets->showNewOptionBut();
	
	     switch ($_REQUEST['act'])
	     {
		      case "new_bet" : $bets->newOption($_REQUEST['dd_bet_fee'], 
	                                           $_REQUEST['dd_bet_adr'], 
					                           $_REQUEST['dd_bet_type'], 
					                           $_REQUEST['txt_bet_lev_1'], 
                                               $_REQUEST['txt_bet_lev_2'], 
                                               $_REQUEST['txt_bet_budget'], 
                                               $_REQUEST['txt_bet_cur'], 
                                               $_REQUEST['txt_bet_profit'], 
                                               $_REQUEST['txt_bet_name'], 
                                               $_REQUEST['txt_bet_desc'], 
                                               $_REQUEST['txt_bet_bid'], 
                                               $_REQUEST['txt_bet_expire'], 
                                               $_REQUEST['dd_bet_expire_per'], 
                                               $_REQUEST['txt_bet_accept'], 
                                               $_REQUEST['dd_bet_accept_per'], 
                                               $_REQUEST['txt_bet_feed_1'],
                                               $_REQUEST['txt_bet_branch_1'],
									           $_REQUEST['txt_bet_feed_2'],
                                               $_REQUEST['txt_bet_branch_2'],
										       $_REQUEST['txt_bet_feed_3'],
                                               $_REQUEST['txt_bet_branch_3']);
						      break;
  	   }
	   
	   $bets->showOptions("", "mine");
	}
	else $bets->showNewBetModal();
	
			
				 	       
	
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
