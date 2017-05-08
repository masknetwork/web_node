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
         $template->showLocation("../../assets/options/index.php", "Binary Options", "", "Binary Options");
	 
	     // Help
	     $template->showHelp("In finance, a binary option is a type of option in which the payoff can take only two possible outcomes, either some fixed monetary amount or nothing at all, in contrast to ordinary financial options that typically have a continuous spectrum of payoff. Binary options pay some fixed amount of cash if some conditions are met in a specified time interval. Over MaskNetwork you can issue your own binary options or invest in existing options launched by other users. Because there is no central server, you can bet on anything for which a data feed exist. Below are listed active binary options.");
	 
	     // Target
	     if (!isset($_REQUEST['target']))
	        $_REQUEST['target']="ID_CRYPTO";
	 
	     // Categ
	     $assets->showSelector($_REQUEST['target']);
	 
	     // Selector
	     if (!isset($_REQUEST['status'])) $_REQUEST['status']="ID_PENDING";
	     $bets->showMyOptionsSel($_REQUEST['status']);
	 
         // Show
		 if ($_REQUEST['act']=="new_bet")
		   $bets->newOption($_REQUEST['dd_bet_adr'], 
	                        $_REQUEST['dd_bet_adr'], 
					        $_REQUEST['dd_bet_type'], 
					        $_REQUEST['txt_bet_lev_1'], 
                            $_REQUEST['txt_bet_lev_2'], 
                            $_REQUEST['txt_bet_budget'], 
                            $_REQUEST['txt_bet_cur'], 
                            $_REQUEST['txt_bet_profit'], 
                            $_REQUEST['txt_bet_name'], 
                            $_REQUEST['txt_bet_desc'], 
                            $_REQUEST['txt_bet_bid'], 
                            $_REQUEST['txt_bet_expire'], 
                            $_REQUEST['dd_bet_expire_per'], 
                            $_REQUEST['txt_bet_accept'], 
                            $_REQUEST['dd_bet_accept_per'], 
                            $_REQUEST['txt_bet_feed'],
                            $_REQUEST['txt_bet_branch']);
		 else
	        $bets->showOptions($_REQUEST['target'], $_REQUEST['term'], $_REQUEST['status']);
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
