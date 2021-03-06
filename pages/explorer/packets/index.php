<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CExplorer.php";
   include "CPackets.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $explorer=new CExplorer($db, $template);
   $packets=new CPackets($db, $template);
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
	     $template->showLeftMenu("explorer");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	  <?
       // Location
       $template->showLocation("../../explorer/packets/index.php", "Explorer", "", "Last Packets");
	 
	   // Menu
	   $template->showNav(1,
	                   "../packets/index.php", "Packets", "",
	                   "../blocks/index.php", "Blocks", "", 
					   "../adr/index.php", "Addresses", "",
					   "../delegates/index.php", "Delegates", "",
					   "../rewards/index.php", "Rewards", "",
					   "../status/index.php", "Status", "");
	 
	   // Help
       $template->showHelp("Below are the last packages received. A package can contain any instruction such as renting a name or a transaction. There are dozens of types of packages. Click on the name of the package for more details.");
	 
	   // Show
	   $packets->showLastPackets();
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

