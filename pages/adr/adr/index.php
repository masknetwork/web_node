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



