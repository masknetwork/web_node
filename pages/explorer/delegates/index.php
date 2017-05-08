<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CExplorer.php";
   include "CDelegates.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $explorer=new CExplorer($db, $template);
   $delegates=new CDelegates($db, $template);
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

<script>
$(function () 
{
  $('[data-toggle="tooltip"]').tooltip();
})
</script>

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
	     $template->showLeftMenu("explorer");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	  <?
         // Location
         $template->showLocation("../../explorer/packets/index.php", "Explorer", "", "Delegates");
	     
		 // QR Modal
		 $template->showQRModal();
		 
	     // Menu
	     $template->showNav(4,
	                       "../packets/index.php", "Packets", "",
	                       "../blocks/index.php", "Blocks", "", 
					       "../adr/index.php", "Addresses", "",
					       "../delegates/index.php", "Delegates", "",
						   "../rewards/index.php", "Rewards", "",
					       "../status/index.php", "Status", "");
	 				   
	    $template->showHelp("Below are listed the elected delegates. The consensus algorithm implemented by MaskNetwork is called Delegated Proof of Work (DPOW). Under DPOW, miners don't work using the same difficulty. The difficulty depends on the number of votes received from stakeholders. For example an address voted by 1000 MSK will mine at a difficulty x5 times lower than an address voted by 200 MSK. Stakeholders (any address holding at least 1 MSK), can elect any number of addresess as 'delegates'. While any address is allowed to create new blocks and get the reward, delegated addressess will have to work much less to find a new block.");
     
	     // Modal
	     $delegates->showVoteModal();
	 
	     // Action
	     if ($_REQUEST['act']=="vote")
	         $delegates->vote($_REQUEST['dd_vote_fee_adr'], 
	                          $_REQUEST['dd_vote_adr'], 
					       	  $_REQUEST['txt_vote_delegate'],
						     $_REQUEST['txt_vote_type']);
	 
	     // Buttons
	     $delegates->showAddBut();
	 
	     // Delegates
		 if (!isset($_REQUEST['type'])) 
		    $_REQUEST['type']="real_time";
	     
		 // Show delegates
		 $delegates->showDelegates($_REQUEST['type']);
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


