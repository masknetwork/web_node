<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "COptions.php";
   include "../CAssets.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $assets=new CAssets($db, $template);
   $bets=new COptions($db, $template);
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
	     $template->showLeftMenu("bets");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
     // Location
     $template->showLocation("../../assets/options/index.php", "Binary Options", "", "Option Details");
	 
	 // Buy Modal 
	 $bets->showBuyOptionBut($_REQUEST['betID']);
	 
	 
	 // Buy
	 switch ($_REQUEST['act'])
	 {
	    case "buy_bet" : $bets->buyOption($_REQUEST['dd_buy_bet_adr'], 
		                                 $_REQUEST['dd_buy_bet_adr'], 
						                 $_REQUEST['betID'],
						                 $_REQUEST['txt_buy_bet_amount']);
						 break;
						 
		case "vote" : $template->vote($_REQUEST['dd_vote_net_fee'], 
		                             $_REQUEST['dd_vote_adr'], 
				                     $_REQUEST['vote_target_type'], 
				                     $_REQUEST['vote_targetID'], 
				                     $_REQUEST['vote_type']);
				     break;
	 }
	 
	 // Bet
	 $bets->showPanel($_REQUEST['betID']);
	 
	 // Modal
	 $bets->showBuyBetModal($_REQUEST['betID']);
	 
	// Report
	$bets->showReport($_REQUEST['betID']);
	
	// Chart
	$bets->showChart($_REQUEST['betID']);
	
	// Buyers
	$bets->showBuyers($_REQUEST['betID']);
	
	
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
