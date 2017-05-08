<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CSettings.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $settings=new CSettings($db, $template);
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
	     $template->showLeftMenu("node_settings");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
      <?
     // Location
     $template->showLocation("../../explorer/packets/index.php", "Admin", "", "Settings");
	 
	 
	 switch ($_REQUEST['act'])
	 {
		 case "change_pass" : $settings->changePass($_REQUEST['txt_old_pass'], 
		                                           $_REQUEST['txt_new_pass'], 
												   $_REQUEST['txt_new_pass_retype']); 
							  break;
							  
		 case "reward" : $settings->setReward($_REQUEST['txt_reward_adr'], 
		                                     $_REQUEST['txt_reward_amount']); 
		                 break;
						 
		 case "restrict" : $settings->restrictIP($_REQUEST['txt_ip']); 
		                   break;
	 }
	 
	 // Change password modal
	 $settings->showChangePassModal();
	 
	 // Restrict IP
	 $settings->showRestrictIPModal();
	 
	 // Reward
	 $settings->showRewardModal();
	 
	 // Node settings
	 $settings->showNodeSettings();
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





