<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CSpecMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mkts=new CSpecMkts($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
	 
	 // Panel
	 $mkts->showPanel($_REQUEST['ID']);
	 
	 // Show report
	 $mkts->showReport($_REQUEST['ID']);
	 
	 // Trade buttons
	 $mkts->showTradeButs($_REQUEST['ID']);
	 
	 // Modal
	 $mkts->showNewTransModal($_REQUEST['ID']);
	 
	 // New trade ?
	 if ($_REQUEST['act']=="new_trade") $mkts->newTrade($_REQUEST['dd_new_pos_net_fee_adr'], 
	                                                   $_REQUEST['dd_new_pos_adr'], 
													   $_REQUEST['ID'], 
													   $_REQUEST['h_type'], 
													   $_REQUEST['dd_new_pos_exec'],
													   $_REQUEST['txt_new_pos_open'], 
													   $_REQUEST['txt_new_pos_sl'], 
													   $_REQUEST['txt_new_pos_tp'],
													   $_REQUEST['txt_new_pos_leverage'],
													   $_REQUEST['txt_new_pos_qty']);  
	 
	 // Chart
	 $mkts->showMktChart($_REQUEST['ID']);
	 				   
	 if ($_REQUEST['ud']['ID']>0)
	 {
		   if (!isset($_REQUEST['target']) || $_REQUEST['target']=="pos")
		      $sel=1;
		   else 
		      $sel=2;
			  
	       $template->showNav($sel,
	                          "market.php?target=pos&ID=".$_REQUEST['ID'], "Positions", "",
	                          "market.php?target=my_pos&ID=".$_REQUEST['ID'], "My Positions", "");
							  
		   if ($sel==1)
		      $mkts->showPositions($_REQUEST['ID']);
		   else
		      $mkts->showPositions($_REQUEST['ID'], "mine");
	 }
	 else $mkts->showPositions($_REQUEST['ID']);
 ?>
 <br><br><br>
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
