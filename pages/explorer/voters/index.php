<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CExplorer.php";
   include "CVoters.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $explorer=new CExplorer($db, $template);
   $voters=new CVoters($db, $template);
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
    if ($_REQUEST['ud']['ID']>0)
      $template->showTopBar(7);
   else
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
     $template->showLocation("../../explorer/voters/index.php", "Explorer", "", "Voters");
	 
	 // Target not set
	 if (!isset($_REQUEST['tab'])) $_REQUEST['tab']="upvoters_24";
	 
	 // Selection
	 switch ($_REQUEST['tab'])
	 {
		 case "upvoters_24" : $sel=1; break;
		 case "downvoters_24" : $sel=2; break;
		 case "upvoters" : $sel=3; break;
		 case "downvoters" : $sel=4; break;
	 }
	 
	 // Show details
	 $voters->showReport($_REQUEST['target_type'], $_REQUEST['targetID']);
	 
	 // Menu
	 $template->showNav($sel,
	                   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=upvoters_24", "Upvoters 24H", "",
	                   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=downvoters_24", "Downvoters 24H", "",
					   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=upvoters", "Upvoters", "",
					   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=downvoters", "Downvoters", "");
	
	// Show
	switch ($sel)
	{
		case 1 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_UP", 24); break;
		case 2 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_DOWN", 24); break;
		case 3 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_UP", 30); break;
		case 4 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_DOWN", 30); break;
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
