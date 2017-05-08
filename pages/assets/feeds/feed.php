<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CMyFeeds.php";
   include "CFeed.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $my_feeds=new CMyFeeds($db, $template);
   $feed=new CFeed($db, $template);
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
	     $template->showLeftMenu("feeds");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../assets/feeds/index.php", "Data feeds", "", "Feed Details");
	 
	 // Details
	 $feed->showPanel($_REQUEST['symbol']);
	 
	 // Modal
	 $feed->showNewFeedBranchModal($_REQUEST['symbol']);
	 
	 // But
	 $feed->showNewBranchBut($_REQUEST['symbol']);
	 
	 // New branch ?
	 switch ($_REQUEST['act'])
	 {
	   case "new_branch" :   $feed->newBranch($_REQUEST['dd_branch_fee_adr'], 
	                                          $_REQUEST['feed_symbol'], 
				                        	  $_REQUEST['txt_branch_name'], 
						                      $_REQUEST['txt_branch_desc'], 
						                      $_REQUEST['dd_branch_type'], 
						                      $_REQUEST['txt_branch_rl'], 
						                      $_REQUEST['txt_branch_symbol'], 
						                      $_REQUEST['txt_branch_fee'], 
						                      $_REQUEST['txt_branch_days']);
							break;
						
	   case "vote" : $template->vote($_REQUEST['dd_vote_net_fee'], 
		                            $_REQUEST['dd_vote_adr'], 
				                    $_REQUEST['vote_target_type'], 
				                    $_REQUEST['vote_targetID'], 
				                    $_REQUEST['vote_type']);
				     break;
	 }
	 
	// Show branches
	$feed->showBranches($_REQUEST['symbol']);
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


