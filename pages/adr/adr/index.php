<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CAdr.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $adr=new CAdr($db, $template);
   $my_adr=new CMyAdr($db, $template);
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
.popover-content {
    font-size: 12px;
}
</style>

</head>

<body>

<?
   $template->showTopBar("adr");
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
     $template->showLocation("../../adr/adr/index.php", "Addresses", "", "My Addresses");
	 
	 // Menu
	 $template->showNav(1,
	                    "../adr/index.php", "My Addresses", "",
	                    "../names/index.php", "Names", "",
						"../market/index.php", "Names Market", "");
	 
	 
	 // Help
	 $template->showHelp("Below are the addresses that you own. An address is a string of characters and works as an anonymous bank account. In an address you can keep coins or other assets. An address never expires. You can assign it a name or security setting. Click the Options button for more details of the addresses.");
			   
	 // Operations
	 switch ($_REQUEST['act'])
	 {
		 case "new_adr" : $my_adr->newAdr($_REQUEST['dd_curve'], $_REQUEST['txt_tag']); 
				          break;
									
		 case "import_adr" : $my_adr->importAdr($_REQUEST['txt_pub_key'], 
				                               $_REQUEST['txt_priv_key'], 
											   $_REQUEST['txt_imp_tag']); 
				             break;
									   
		 case "show_pending" : $my_adr->showPendingAdr(); 
				               break;
										 
		 
	}
			   
	// My addresses
    $my_adr->showMyAdr();
	
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
