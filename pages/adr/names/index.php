<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CDomains.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $adr=new CAdr($db, $template);
   $domains=new CDomains($db, $template); 
   
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
<script>$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });</script>
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
	     $template->showLeftMenu("adr");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	 <?
     // Location
     $template->showLocation("../adr/index.php", "Addresses", 
	                         "", "Address Names");
	 
	 // Menu
	 $template->showNav(2,
	                    "../adr/index.php", "My Addresses", "",
	                    "../names/index.php", "Names", "",
						"../market/index.php", "Names Market", "");
	 
	 
	 $template->showHelp("Below the names of addresses owned by you are listed. A name of an address is like a domain name and facilitates the sending of funds or messages. The name of addresses can be rented for 0.0001 MSK / day. They can also be transferred, or sold on the built-in names market.");
			   
			   // Modals
			   $domains->showBuyDomainModal();
			   $domains->showNewDomainModal();
			   $template->showRenewModal();
			   $domains->showSetPriceModal();
			   $domains->showTransferModal();
			   
			    switch ($_REQUEST['act'])
		        {
			          // New address
			          case "new_adr" : $adr->newAdr($_REQUEST['dd_curve'], 
			                                       $_REQUEST['txt_tag']); 
								       break;
			   
			          // Import address	
			          case "import_adr" : $adr->importAdr($_REQUEST['txt_pub_key'], 
			                                             $_REQUEST['txt_priv_key'],
												         $_REQUEST['txt_tag']); 
								          break;
			   
			         // New domain   
			         case "new_domain" : $domains->newDomain($_REQUEST['dd_net_fee_adr'], 
			                                                $_REQUEST['dd_adr'], 
													        $_REQUEST['txt_name'], 
													        $_REQUEST['txt_days_nd']); 
									     break;
			   
			         // Renew		
			         case "renew" : $template->renew($_REQUEST['dd_renew_net_fee'], 
			                                        $_REQUEST['renew_table'], 
										            $_REQUEST['txt_renew_days'],
											        $_REQUEST['renew_rowhash']);
			                        break;
			   
			         // Transfer	  
			         case "transfer" : $domains->transferDomain($_REQUEST['dd_my_adr_transfer'], 
			                                                    $_REQUEST['transfer_domain'], 
													            $_REQUEST['txt_rec']);
			                           break;
							  
				     // Set price
			         case "set_price" : $domains->setSalePrice($_REQUEST['dd_my_adr_set_sale_price'], 
			                                                  $_REQUEST['set_price_domain'], 
														      $_REQUEST['txt_price'], 
														      $_REQUEST['txt_days_sp'], 
													      	  $_REQUEST['txt_bid_sp']);
			                           break;
								  
			        // Buy domain
			        case "buy_domain" :  $domains->buyDomain($_REQUEST['dd_net_fee_adr'], 
			                                                $_REQUEST['dd_pay_adr'], 
													        $_REQUEST['dd_adr'], 
													        $_REQUEST['buy_adr']);
			                             break;
		         }
				 
				 // My domains
			     $domains->showMyDomains();
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



