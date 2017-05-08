<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAssets.php";
   include "CSpecMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $assets=new CAssets($db, $template);
   $mkts=new CSpecMkts($db, $template);
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
	     $template->showLeftMenu("margin");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../assets/assets/index.php", "Speculative Markets", "", "Markets");
	
	 
	 // Help
	 $template->showHelp("In the real world trading on margin means borrowing money from your broker to buy a stock and using your investment as collateral. Investors generally use margin to increase their purchasing power so that they can own more stock without fully paying for it. Usually you will pay interest for borrowed money. Over MaskNetwork, a margin market alows you to place leveraged bets against the market owner. Margin markets prices are provided by data feeds. All your losses are market owner's gains and vice versa. Below are listed available margin markets. ");
	
	if ($_REQUEST['act']=="new_mkt")
	   $mkts->newMarket($_REQUEST['dd_new_mkt_net_fee_adr'], 
	                   $_REQUEST['dd_new_mkt_adr'], 
					   $_REQUEST['txt_new_mkt_feed'], 
					   $_REQUEST['txt_new_mkt_branch'], 
					   $_REQUEST['txt_new_mkt_cur'], 
					   $_REQUEST['txt_new_mkt_max_leverage'], 
					   $_REQUEST['txt_new_mkt_spread'], 
					   $_REQUEST['txt_new_mkt_days'], 
					   $_REQUEST['txt_new_mkt_max_total_margin'], 
					   $_REQUEST['txt_new_mkt_title'], 
					   $_REQUEST['txt_new_mkt_desc']);
					  
	
	 // New market
	 $mkts->showNewMktBut();
	 
	// Target
	if (!isset($_REQUEST['target']))
	   $_REQUEST['target']="ID_CRYPTO";
	
	// Selector
	$assets->showSelector($_REQUEST['target']);
	
	$mkts->showMarkets(false, $_REQUEST['target']); 
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

