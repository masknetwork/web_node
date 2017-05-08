<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CExplorer.php";
   include "CVoters.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $explorer=new CExplorer($db, $template);
   $voters=new CVoters($db, $template);
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
     $template->showLocation("../../explorer/voters/index.php", "Explorer", "", "Voters");
	 
	 // Target not set
	 if (!isset($_REQUEST['tab'])) $_REQUEST['tab']="upvoters_24";
	 
	 // Selection
	 switch ($_REQUEST['tab'])
	 {
		 case "upvoters_24" : $sel=1; break;
		 case "downvoters_24" : $sel=2; break;
		 case "rewards" : $sel=3; break;
	 }
	 
	 // Show details
	 $voters->showReport($_REQUEST['target_type'], $_REQUEST['targetID']);
	 
	 // Menu
	 $template->showNav($sel,
	                   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=upvoters_24", "Upvoters 24H", "",
	                   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=downvoters_24", "Downvoters 24H", "",
					   "index.php?target_type=".$_REQUEST['target_type']."&targetID=".$_REQUEST['targetID']."&tab=rewards", "Rewards", "");
	
	// Show
	switch ($sel)
	{
		case 1 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_UP", 24); break;
		case 2 : $voters->showVoters($_REQUEST['target_type'], $_REQUEST['targetID'], "ID_DOWN", 24); break;
		case 3 : $voters->showRewards($_REQUEST['target_type'], $_REQUEST['targetID']); break;
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



