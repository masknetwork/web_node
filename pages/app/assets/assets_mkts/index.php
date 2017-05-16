<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CRegMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $reg_mkts=new CRegMkts($db, $template);
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
	     $template->showLeftMenu("assets");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
         // Location
         $template->showLocation("../../assets/assets_mkts/index.php", "Assets Markets", "", "Markets");
	     
		 // Modal
		 $reg_mkts->showNewRegularMarketModal();
		 
		 // Menu
	     $template->showNav(2,
	                    "../user/index.php", "Assets", "",
	                    "../assets_mkts/index.php", "Assets Markets", "");
	 
	 
	     // Help
	     $template->showHelp("An asset market is a decentralized peer-to-peer exchange built directly into the MaskNetwork software, allowing secure and fast decentralized trading of any asset pair. This eliminates the need to transfer assets or to put trust in an outside agency or business, and as assets can be used to represent literally anything (from Bitcoin to coffee beans) there are a wide range of potential investments or trades to be made on MaskNetwork. All asset markets transactions are free (you pay only the regular transfer fees. Below are listed the available asset markets.)");
	 
	     // New button
		 $reg_mkts->showNewMktBut();
		 
		 // New market ?
	     switch ($_REQUEST['act'])
	     {
		     case "new_market" : $reg_mkts->newMarket($_REQUEST['dd_new_mkt_adr'], 
	                                                 $_REQUEST['dd_new_mkt_adr'], 
					                                 $_REQUEST['txt_new_asset_symbol'], 
					                                 $_REQUEST['txt_new_cur'], 
					                                 $_REQUEST['dd_decimals'],
					                                 $_REQUEST['txt_new_name'], 
					                                 $_REQUEST['txt_new_desc'], 
					                                 $_REQUEST['txt_new_days']); 
						        break;
	     }
		 
	     // Show markets
	     $reg_mkts->showMarkets();
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

