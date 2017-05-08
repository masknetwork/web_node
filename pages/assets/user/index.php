<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAssets.php";
   include "CIssued.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $assets=new CAssets($db, $template);
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
     $template->showLocation("../../assets/user/index.php", "Assets", "", "Assets");
	 
	 
	 // Help
	 $template->showHelp("User issued assets are a type of custom token registered over MaskNetwork, which users can hold and trade. The creator of such an asset publically names, describes, and distributes its tokens, and can specify customized requirements, such as daily interest, or the associated trading and transfer fees. Below are listed available user issued assets.");
	
	// Issue button
	$issued->showIssueBut();
	 
    // Assets
	$assets->showAssets();
	
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
