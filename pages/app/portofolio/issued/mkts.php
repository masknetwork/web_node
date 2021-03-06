<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CIssued.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $issued=new CIssued($db, $template);
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
	     $template->showLeftMenu("portofolio");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
     // Location
     $template->showLocation("../../potofolio/assets/index.php", "Issued", "", "Assets");
	 
	 // Menu
	 $template->showNav(4,
	                    "../assets/index.php", "Assets", "",
	                    "../bets/index.php", "Bets", "",
						"../positions/index.php", "Speculative Positions", "",
						"../issued/index.php", "Issued", "");
	 
	 
	 // Help
	 $template->showHelp("Below are the displayed the assets you own. Assets can be send to other addresses just like MaskCoins and enjoy the same protection level / features. Below are listed the assets you own. Any address can issue a new asset. If you want to issue an asset go to Issued tab and click Issue Asset.");
			   
	// Issued
    $issued->showSelector("ID_SPEC_MKTS"); 
	
	// Withdraw ?
	 if ($_REQUEST['act']=="wth")
	 $issued->withdraw($_REQUEST['wth_mkt_ID'], 
	                 $_REQUEST['dd_wth_net_fee'], 
					 $_REQUEST['txt_wth_rec'], 
					 $_REQUEST['txt_wth_amount']);
					 
	 // Close market ?
	 if ($_REQUEST['act']=="close_mkt")
	 $issued->closeMkt($_REQUEST['close_mkt_ID'], 
	                 $_REQUEST['dd_close_mkt_net_fee'], 
					 $_REQUEST['txt_close_mkt_pass']);
	
	// Markets
	$issued->showMyMarkets();
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



