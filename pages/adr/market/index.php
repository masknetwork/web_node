<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CMarket.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $adr=new CAdr($db, $template);
   $market=new CMarket($db, $template);
   
   // Not logged in ?
   if (!isset($_REQUEST['ud']['ID']) || 
       $_REQUEST['ud']['ID']==0)
   $db->redirect("../../../index.php");
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
<script>$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });</script>
</head>

<body>

<?
   $template->showBalanceBar();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="15%" align="left" bgcolor="#4c505d" valign="top">
      
      <?
	     $template->showLeftMenu("adr");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
     // Location
     $template->showLocation("../adr/index.php", "Addresses", 
	                         "", "Names Market");
	 
	 // Menu
	 $template->showNav(3,
	                    "../adr/index.php", "My Addresses", "",
	                    "../names/index.php", "Names", "",
						"../market/index.php", "Names Market", "");
	 
	 
	 $template->showHelp("Below we listed the names of addresses on sale. To put on sale a name, go to page Addresses Name. When you buy a name you must specify what address will be associated to. You can attach an unlimited number of names to a unique address.");
			   
	 switch ($_REQUEST['act'])
	 {
		case "buy_domain" :  $market->buyDomain($_REQUEST['dd_pay_adr'], 
			                                   $_REQUEST['buy_domain']);
			                 break;
	 }
			   
	 $market->showMarket();
	 $market->showBuyDomainModal();
 ?>
 
 
 </td>
      <td width="15%" align="center" valign="top" bgcolor="#4c505d">
      
      <?
	     $template->showAds();
	  ?>
      
      </td>
    </tr>
  </tbody>
</table>
 

 
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>




