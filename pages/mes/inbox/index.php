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
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_12 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

</style>

</head>

<body>

<?
   $template->showTopBar("mes");
?>
 

 <div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1">&nbsp;</div>
 <div class="col-md-8" align="center" style="height:100%; background-color:#ffffff">
 
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
 </div>
 <div class="col-md-2" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>
