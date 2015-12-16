<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CPending.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $adr=new CAdr($db, $template);
   $pending=new CPending($db, $template);
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
     $template->showLocation("../adr/index.php", "My Addresses", 
	                         "", "Pending Addresses");
	 
	 // Menu
	 $template->showNav(2,
	                    "../adr/index.php", "My Addresses", "",
	                    "../pending/index.php", "Pending", "",
						"../names/index.php", "Names", "",
						"../market/index.php", "Names Market", "");
	 
	 
	 // Help
	 $template->showHelp("You can share an address with another address, by sharing the private key. Below are displayed addresses that can be imported into your wallet. The owners of those addresses want to share the address with you. After receiving the private key, the you will be able to use this address exactly like the owner.");
			   
	 // Operations
	 switch ($_REQUEST['act'])
	 {
		 case "add_adr" : $pending->aprovesPending($_REQUEST['ID']); 
				          break;
										 
		 case "reject_adr" : $pending->rejectPending($_REQUEST['ID']); 
				            break;
	}
			   
	  // Show pending
	  $pending->showPending();
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
