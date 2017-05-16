<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CTransactions.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $trans=new CTransactions($db, $template);
   
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
         $template->showLocation("../../transactions/all/index.php", "Transactions", "", "All");
	 
	     // Menu
	     $template->showNav(1,
	                        "../all/index.php", "All", $_REQUEST['ud']['unread_trans'],
	                        "../escrowed/index.php", "Escrowed", $_REQUEST['ud']['unread_esc']);
	 
	     // Help
         $template->showHelp("Below the last transactions executed are displayed. A transaction is confirmed in the network in about 2 minutes. Then, the transaction is final and it cannot be reversed. The transactions can have attached data such as a message or other information.");
	   
	     // Simpel send
		 if ($_REQUEST['act']=="send_coins")
		 $trans->sendCoins($_REQUEST['dd_net_fee_send'], 
			               $_REQUEST['dd_from'], 
						   $_REQUEST['txt_to'], 
						   $_REQUEST['txt_MSK'], 
						   $_REQUEST['txt_asset_amount'], 
						   $_REQUEST['txt_cur'], 
						   $_REQUEST['txt_mes'], 
						   $_REQUEST['txt_escrower']);
		
	 
	     $trans->showTrans("ID_ALL"); 		  
	  
	  if ($_REQUEST['act']=="send_block")
	  {
		$db->sendBlock();
	    $template->showOK("Your request has been successfully recorded"); 
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



