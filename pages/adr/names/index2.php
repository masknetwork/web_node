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
<title>MaskNetwwork - Multisignature transactions</title>
<link rel="stylesheet" href="../../../style.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="../../../dd.js" type="text/javascript"></script>
<script src="../../../utils.js" type="text/javascript"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">$(document).ready(function() { $("body").tooltip({ selector: '[data-toggle=tooltip]' }); });</script>


</head>
<center>
<body background="../../template/template/GIF/back.png" style="margin-top:0px; margin-left:0px; margin-right:0px; ">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="66" align="center" valign="top" background="../../template/template/GIF/top_bar.png" style="background-position:center"><table width="1020" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="left">
            <?
			    $template->showTopMenu(2);
			?>
            </td>
            </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<table width="1018" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="800" align="center" valign="top" background="../../template/template/GIF/back_middle.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="205" height="18" align="right" valign="top"><table width="201" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="170" align="center" valign="middle" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">
                  <?
				     $template->showBalancePanel();
                  ?>
                  
                  </td>
                </tr>
              </tbody>
            </table>
            <?
			   $adr->showLeftMenu(2);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
           
           
            <?
			   $template->showHelp("Below the names of addresses owned by you are listed. A name of an address is like a domain name and facilitates the sending of funds or messages. The name of addresses can be rented for 0.0001 MSK / day. They can also be transferred, or sold on the domestic network market.");
			   
			   // Modals
			   $domains->showBuyDomainModal();
			   $domains->showNewDomainModal();
			   $domains->showRenewModal();
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
			         case "renew" : $domains->renewDomain($_REQUEST['dd_my_adr_renew'], 
			                                             $_REQUEST['renew_domain'], 
													     $_REQUEST['txt_days_re']);
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
            <td width="203" align="center" valign="top">
            <?
			   $template->showRightPanel();
			   $template->showAds();
			?>
            </td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom_sep.png" width="1018" height="20" alt=""/></td>
    </tr>
    <tr>
      <td height="50" align="center" background="../../template/template/GIF/bottom_middle.png">
      <?
	     $template->showBottomMenu();
	  ?>
      </td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom.png" width="1018" height="20" alt=""/></td>
    </tr>
  </tbody>
</table>
</body>
</center>
</html>