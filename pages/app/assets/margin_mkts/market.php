<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CSpecMkts.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mkts=new CSpecMkts($db, $template);
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
	     $template->showLeftMenu("margin");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	  <?
     // Location
     $template->showLocation("../../assets/assets/index.php", "Speculative Markets", "", "Markets");
	 
	 // Panel
	 $mkts->showPanel($_REQUEST['ID']);
	 
	 // Show report
	 $mkts->showReport($_REQUEST['ID']);
	 
	 // Trade buttons
	 $mkts->showTradeButs($_REQUEST['ID']);
	 
	 // Modal
	 $mkts->showNewTransModal($_REQUEST['ID']);
	
	 
	 // New trade ?
	 if ($_REQUEST['act']=="new_trade") 
	    $mkts->newTrade($_REQUEST['dd_new_pos_net_fee_adr'], 
	                    $_REQUEST['dd_new_pos_adr'], 
						$_REQUEST['ID'], 
						$_REQUEST['h_type'], 
						$_REQUEST['dd_new_pos_exec'],
						$_REQUEST['txt_new_pos_open'], 
						$_REQUEST['txt_new_pos_sl'], 
						$_REQUEST['txt_new_pos_tp'],
						$_REQUEST['txt_new_pos_leverage'],
						$_REQUEST['txt_new_pos_qty'],
						$_REQUEST['txt_new_pos_days']);  
	 
	 // Chart
	 $mkts->showMktChart($_REQUEST['ID']);
	 				   
	 if ($_REQUEST['ud']['ID']>0)
	 {
		   // Pos
		   if (!isset($_REQUEST['target']) || $_REQUEST['target']=="pos")
		      $sel=1;
		   
		   // My pos
		   else if ($_REQUEST['target']=="my_pos")
		      $sel=2;
		   
		   // Admin
		   else if ($_REQUEST['target']=="admin")
		      $sel=3; 
		   
		   // My  market ?
		   $template->showNav($sel,
	                          "market.php?target=my_pos&ID=".$_REQUEST['ID'], "My Positions", "");
		 
		   
		   // No interval
		   if (!isset($_REQUEST['dd_interval']))
		     $_REQUEST['interval']="24";
		   	
		   // Show	  
		   if ($sel==1)
		      $mkts->showPositions($_REQUEST['ID']);
		   
		   if ($sel==2)
		      $mkts->showPositions($_REQUEST['ID'], "ID_MARKET", "mine");
		   
		   if ($sel==3)
		      $mkts->showAdmin($_REQUEST['dd_interval']);
	 }
	 else $mkts->showPositions($_REQUEST['ID']);
 ?>
 
 <br><br><br><br><br>
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



