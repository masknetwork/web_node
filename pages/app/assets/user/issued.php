<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CIssued.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $issued=new CIssued($db, $template);
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
	     $template->showLeftMenu("assets");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
         // Location
         $template->showLocation("../../assets/user/index.php", "Assets", "", "Issued Assets");
	 
	     // Issue modal   
	     if ($_REQUEST['act']=="show_modal")
		     $issued->showIssueAssetModal($_REQUEST['symbol']);
	
	     // Issue ?
	     if ($_REQUEST['act']=="issue") $issued->newAsset($_REQUEST['dd_issue_adr'], 
	                                                $_REQUEST['dd_issue_adr'], 
													$_REQUEST['txt_issue_name'], 
													$_REQUEST['txt_issue_desc'], 
													$_REQUEST['txt_issue_buy'], 
													$_REQUEST['txt_issue_sell'], 
													$_REQUEST['txt_issue_website'], 
													$_REQUEST['txt_issue_pic'], 
													$_REQUEST['txt_issue_symbol'], 
													$_REQUEST['txt_issue_init_qty'], 
													$_REQUEST['txt_issue_trans_fee'], 
													$_REQUEST['txt_issue_trans_fee_adr'], 
													$_REQUEST['txt_issue_days'], 
													$_REQUEST['dd_can_issue'],
													$_REQUEST['txt_interest'],
													$_REQUEST['dd_interval']); 
													
	   // Issued assets
       if ($_REQUEST['act']!="show_modal") $issued->showAssets();								
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
