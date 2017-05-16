<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CPositions.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $pos=new CPositions($db, $template);
   
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
	     $template->showLeftMenu("portofolio");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
	    // Status
		if (!isset($_REQUEST['status']))
		    $_REQUEST['status']="ID_MARKET";
		
     // Location
     $template->showLocation("../../adr/adr/index.php", "Addresses", "", "My Addresses");
	 
	 // Menu
	 $template->showNav(3,
	                    "../assets/index.php", "Assets", "",
	                    "../bets/index.php", "Binary Options", "",
						"../positions/index.php", "Speculative Positions", "",
						"../issued/index.php", "Issued", "");
	 
	 
	 // Help
	 $template->showHelp("Below are the displayed your margin positions. You can't change a position, but you can partially / completely close a position anytime you want. Closing a position costs 0.0001 MSK. Margin positions will be automatically closed when they expire or in the rare eevent of market shutdown. Click Trade story for more details regarding a position.");
	 
	 // Close trade
	 if ($_REQUEST['act']=="close_trade")
	 $pos->closeTrade($_REQUEST['dd_close_net_fee'], 
	                  $_REQUEST['close_posID'], 
					  $_REQUEST['dd_close_percent']);
     
	 // Selector
	 $pos->showSelector($_REQUEST['status']);
							  
	 // Positions
	 $pos->showMyPositions($_REQUEST['status']);
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



