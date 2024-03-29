<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CDirectory.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $dir=new CDirectory($db, $template);
   
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
     $template->showLocation("../../app/directory/index.php", "Applications", "", "Directory");
	 
	 
 ?>
 
 <br>
 <table width="90%" class="table-responsive">
 <td width="25%">
 <div class="panel panel-default">
  <div class="panel-body font_14">
    
    <?
	   if (!isset($_REQUEST['categ'])) $_REQUEST['categ']="ID_ALL";
	   $dir->showCategs($_REQUEST['categ']);
	?>
    
  </div>
</div>
 </td>
 <td width="75%" align="right" valign="top">
 <?
    // Categ
	if (!isset($_REQUEST['categ'])) $_REQUEST['categ']="ID_ALL";
	
	// Target
	if (!isset($_REQUEST['target'])) $_REQUEST['target']="ID_NOT_SELAED";
	
	// Search
	if (!isset($_REQUEST['search'])) $_REQUEST['search']="";
	
	// Display
    $dir->showApps($_REQUEST['categ'], 
	               $_REQUEST['search'], 
				   $_REQUEST['target']);
 ?>
 </td>
 </table>
 
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
