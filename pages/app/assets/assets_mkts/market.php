<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CRegMkt.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $reg_mkt=new CRegMkt($db, $template);
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
     $template->showLocation("../../assets/assets/index.php", "Speculative Markets", "", "Markets");
	 
	 // Action
	 switch ($_REQUEST['act'])
	 {
		 case "new_position" : $reg_mkt->newMarketPos($_REQUEST['ID'],
	                                              $_REQUEST['tip'],
	                                              $_REQUEST['dd_new_trade_net_fee_adr'], 
	                                              $_REQUEST['dd_new_pos_adr'],
												  $_REQUEST['dd_new_pos_adr_asset'], 
					                              $_REQUEST['txt_new_trade_price'], 
					                              $_REQUEST['txt_new_trade_qty'], 
					                              $_REQUEST['txt_new_trade_days']); 
							break;
							
		case "close_order" : $reg_mkt->closeOrder($_REQUEST['dd_close_order_net_fee_adr'], 
		                                         $_REQUEST['orderID']); 
							break;
							
		case "new_trade" : $reg_mkt->newTrade($_REQUEST['dd_order_net_fee_adr'], 
		                                     $_REQUEST['dd_new_trade_adr'], 
		                                     $_REQUEST['new_trade_orderID'], 
											 $_REQUEST['txt_new_trade_order_qty']); 
		                  break;
						  
		case "vote" : $template->vote($_REQUEST['dd_vote_net_fee'], 
		                                  $_REQUEST['dd_vote_adr'], 
				                          $_REQUEST['vote_target_type'], 
				                          $_REQUEST['vote_targetID'], 
				                          $_REQUEST['vote_type']);
						   break;
	 }
	 
	 // Show panel
	 $reg_mkt->showPanel($_REQUEST['ID']);
	 
	 // Show report
	 $reg_mkt->showReport($_REQUEST['ID']);
	 
	 // Target
	 if (!isset($_REQUEST['target'])) $_REQUEST['target']="sellers";
	 
	 switch ($_REQUEST['target'])
	 {
		 case "sellers" : $sel=1; break;
		 case "buyers" : $sel=2; break;
		 case "trades" : $sel=3; break;
	 }
	
	 // Trade buts
	 $reg_mkt->showButs();
	 
	 // Menu
	 $template->showNav($sel,
	                    "market.php?target=sellers&ID=".$_REQUEST['ID'], "Sellers", "",
	                    "market.php?target=buyers&ID=".$_REQUEST['ID'], "Buyers", "",
						"market.php?target=trades&ID=".$_REQUEST['ID'], "Last Trades", "");
	 
	 
	 // Traders
	 switch ($sel) 
	 {
		 case 1 : $reg_mkt->showTraders($_REQUEST['ID'], "ID_SELL");
		          break;
				  
		 case 2 : $reg_mkt->showTraders($_REQUEST['ID'], "ID_BUY");
		          break;
				  
		 case 3 : $reg_mkt->showLastTrades($_REQUEST['ID']);
		          break;
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

