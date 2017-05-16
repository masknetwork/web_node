<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CTransactions.php";
   include "CEscrowed.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $trans=new CTransactions($db, $template);
   $esc=new CEscrowed($db, $template);
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
	     $template->showLeftMenu("overview");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
     // Location
     $template->showLocation("../../transactions/all/index.php", "Transactions", "", "Escrowed");
	 
	 // Menu
	 $template->showNav(2,
	                   "../all/index.php", "All", $_REQUEST['ud']['unread_trans'],
	                   "../escrowed/index.php", "Escrowed", $_REQUEST['ud']['unread_esc']);
	 
	// Help
	$template->showHelp("Below the last escrow transactions are displayed. Blocked funds in escrow transactions can be released by escrower or can be returned to the sender by escrower or recipient. Escrow transactions that are not signed in maximum 50,000 blocks from initiation are deleted and the funds are returned to the sender.");
	
	 // Sign
	 if ($_REQUEST['esc_act']!="") 
		 $esc->signTransaction($_REQUEST['esc_fee_adr'], 
				               $_REQUEST['transID'], 
							   $_REQUEST['esc_act']);
									  
	 $esc->showEscrowed();
	 $esc->showSignModal();
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



