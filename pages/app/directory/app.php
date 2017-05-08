<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../../mes/CMes.php";
   include "CDirectory.php";
   include "CStorage.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mes=new CMes($db, $template);
   $dir=new CDirectory($db, $template, $mes);
   $storage=new CStorage($db, $template, $_REQUEST['ID']);

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
   .balance_MSK { font-size: 40px; }
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

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>

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
     $template->showLocation("../../app/directory/index.php", "Applications", "", "Application");
	 
	 // Vote
	 if ($_REQUEST['act']=="vote")
	    $template->vote($_REQUEST['dd_vote_net_fee'], 
		                $_REQUEST['dd_vote_adr'], 
				        $_REQUEST['vote_target_type'], 
				        $_REQUEST['vote_targetID'], 
				        $_REQUEST['vote_type']);
	
	 // Buttons
	 $dir->showButs($_REQUEST['ID']);
	
	 // Show
	 $dir->showPanel($_REQUEST['ID']);
	 
	 // Target
	 if (!isset($_REQUEST['target']))
	    $_REQUEST['target']="trans";
	 
	 // Selection
	 switch ($_REQUEST['target'])
	 {
		 case "trans" : $sel=1; break;
		 case "code" : $sel=2; break;
		 case "storage" : $sel=3; break;
	 }
	 
	 // Sealed panel
	 $dir->showSealPanel($_REQUEST['ID']);
	 
	 // Details
	 print "<br>";
	 $template->showNav($sel,
	                    "app.php?target=trans&ID=".$_REQUEST['ID'], "Transactions", "",
	                    "app.php?target=code&ID=".$_REQUEST['ID'], "Source Code", "",
						"app.php?target=storage&ID=".$_REQUEST['ID'], "Storage", "");
						
     // Target
	 switch ($_REQUEST['target'])
	 {
		 case "trans" : $dir->showTrans($_REQUEST['ID']); break;
		 case "code" : $dir->showSource($_REQUEST['ID']); break;
		 case "storage" : $storage->showStorage($_REQUEST['ID']); break;
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
