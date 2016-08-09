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
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">
<script src="../../../utils.js"></script>

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_12 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

</style>

</head>

<body>

<?
   $template->showTopBar("adr");
?>
 

 <div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1">&nbsp;</div>
 <div class="col-md-8" align="center" style="height:100%; background-color:#ffffff">
 
 <?
     // Location
     $template->showLocation("../adr/index.php", "Addresses", 
	                         "", "Address Names");
	 
	 // Menu
	 $template->showNav(2,
	                    "../adr/index.php", "My Addresses", "",
	                    "../names/index.php", "Names", "",
						"../market/index.php", "Names Market", "");
	 
	 
	 $template->showHelp("Below the names of addresses owned by you are listed. A name of an address is like a domain name and facilitates the sending of funds or messages. The name of addresses can be rented for 0.0001 MSK / day. They can also be transferred, or sold on the domestic network market.");
			   
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
 
 </div>
 <div class="col-md-2" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>
