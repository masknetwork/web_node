<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CMining.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mining=new CMining($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>

<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="../../../gallery.css">
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">

<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="../../../gallery.min.js"></script>

<script src="../../../jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="../../../jupload/js/jquery.iframe-transport.js"></script>
<script src="../../../jupload/js/jquery.fileupload.js"></script>

<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
.loader {
    border: 10px solid #f3f3f3; /* Light grey */
    border-top: 10px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 70px;
    height: 70px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

</head>

<body>

<?
   $template->showTopBar("admin");
?>
 

<div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1 col-sm-0">&nbsp;</div>
 <div class="col-md-8 col-sm-12" align="center" style="height:100%; background-color:#ffffff">
 
 
 <?
    // Action
	if ($_REQUEST['act']=="start") 
	   $mining->startMiners($_REQUEST['txt_delegate'], $_REQUEST['dd_cores']);
	   
	if ($_REQUEST['act']=="stop") 
	   $mining->stopMiners();
	
    // Top bar
    $mining->showTopBar();
    
	// Panels
	$mining->showPanels();
    
	// Cores
	$mining->showCores();
	
	// Network dif
	$mining->showNetDif();
	
	// Last blocks
	$mining->showLastBlocks();
 ?>
 
<br>
 
 

       
       
 </div>
 <div class="col-md-2 col-sm-0" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1 col-sm-0">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
</body>
</html>
