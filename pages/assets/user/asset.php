<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAsset.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $asset=new CAsset($db, $template);
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
	     // Trust modal
		 $asset->showTrustModal();
		 
         // Location
         $template->showLocation("../../assets/assets/index.php", "Assets", "", "Assets");
	 
	 // Vote
	 if ($_REQUEST['act']=="trust_asset")
	    $asset->trust($_REQUEST['dd_net_fee_trust'], 
		                $_REQUEST['dd_trust_adr'],
						$_REQUEST['symbol']);
	 
	 
	 // Panel
	 $asset->showPanel($_REQUEST['symbol']);
	
	  // Menu
	  print "<br>";
	 
	  // Selected
	  if (!isset($_REQUEST['target'])) $_REQUEST['target']="owners";
	  switch ($_REQUEST['target'])
	  {
		  case "owners" : $sel=1; break;
		  case "trans" : $sel=2; break;
		  case "markets" : $sel=3; break;
	  }
	 
	  $template->showNav($sel,
	                    "../user/asset.php?target=owners&symbol=".$_REQUEST['symbol'], "Owners", "",
	                    "../user/asset.php?target=trans&symbol=".$_REQUEST['symbol'], "Transactions", $_REQUEST['ud']['pending_adr'],
						"../user/asset.php?target=markets&symbol=".$_REQUEST['symbol'], "Markets", "");
						
	  switch ($_REQUEST['target'])
	  {
		  case "owners" : $asset->showOwners($_REQUEST['symbol']); break;
		  case "trans" : $asset->showTrans($_REQUEST['symbol']); break;
		  case "markets" : $asset->showMarkets($_REQUEST['symbol']); break;
	  }
						
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

