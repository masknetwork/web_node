<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CMes.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mes=new CMes($db, $template);
   
   if (!isset($_SESSION['userID'])) $this->kern->redirect("../../../index.php");
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
	     $template->showLeftMenu("mes");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../mes/inbox/index.php", "Messages", "", "Inbox");
	 
	 // Menu
	 $template->showNav(1,
	                   "../inbox/index.php", "Inbox", "",
	                   "../sent/index.php", "Sent", "");
	 
	 // Help
	 $template->showHelp("Listed below are your messages. You can send a message to any address. Even if it crosses the entire network no one can see the message content. All messages are encrypted and only the recipient can decrypt the content. Messages are limited to 512 characters in length. Attachments are not allowed.");
			   
	 // Send
	 if ($_REQUEST['act']=="send")
	     $mes->sendMes($_REQUEST['fee_adr'], 
				       $_REQUEST['sender_adr'], 
					   $_REQUEST['txt_rec'], 
					   $_REQUEST['txt_subject'], 
					   $_REQUEST['txt_mes']);
			   
	if ($_REQUEST['act']=="del")
	   $mes->delMes($_REQUEST['par_1']);
			   
	$mes->showButs();
			   
	if ($_REQUEST['act']=="show_mes") 
	   $mes->showMessage($_REQUEST['mesID']);
	else
	   $mes->showMes();
			   
	$mes->showComposeModal();
	$template->showConfirmModal("Delete email", "Are you sure you want to delete this email ? This action can't be rollbacked."); 
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

