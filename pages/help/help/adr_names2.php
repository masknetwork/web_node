<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CHelp.php";

   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $help=new CHelp($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>MaskNetwwork - Assets</title>
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
			    $template->showTopMenu(8);
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
			   $help->showLeftMenu(5);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
            
            <br>
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><span class="simple_red_18"><strong>Address Names</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">As Mask Network addresses represent long strings of characters hard to send and impossible to  remember, renting an address name for the addresses that you use most is an indicated option. In this section we will discuss how the wallet helps you manage the address names that you own. The address names can also be transferred or traded on the domestic, specialized market of the network.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Also, you can attach multiple names to the same address. For example "Maria" or "Mary" could 
represent the same address.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Address Names Management</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">To view the name that you own, go to the Addresses section (click Addresses in the menu bar) and 
select Addresses Name from the left menu. Here are all the names that you own, and expiry date. Names cannot be purchased but only rented for a certain period. Rental cost is 0.0001 MSK / day.</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Renewing an address name</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Like Internet domain names, addresses names must be renewed when they expire. Otherwise, anyone 
can rent that name for their own addresses. To extend the validity of a name that you own already, click on the yellow button next to the name and choose "Extend". The dialogue below will appear: </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/renew_name.png" width="450" height="333" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Addresses names can also be transferred to another address for a fee of 0.0001 MSK. It is a useful option if you want to change the address of a name.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Transferarea unui nume</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Addresses names can also be transferred to another address for a fee of 0.0001 MSK. It is a useful
option if you want to change the address of a name.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">To transfer a name that you own, go to page Addresses (click Addresses in the main menu bar) and
select My Names from the left menu. Click the yellow button next to the name and select Transfer Name. The following menu will appear: </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/tranfser_name.png" width="450" height="274" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Recipient Address</strong></span> <span class="simple_gri_inchis_12">- The new address with which you want to associate the name.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Send button. After the transaction is confirmed by the network (1 minute), the name will be associated with the new specified address.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Selling an address name</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">As we said, the name of address can be sold / bought on the domestic specialized market, where all name payments / transfers are made automatically, without any intermediary. All name transactions are made using MaskCoin.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To put on sale a name that you own, go to page Addresses (click Addresses in the main menu bar) and select My Names from the left menu. Click the yellow button next to the name and select Set sale price. The following menu will appear: </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/sale_name.png" width="450" height="325" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Price</strong></span> <span class="simple_gri_inchis_12">- the price you want in MSK. Once set, this parameter cannot be changed.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- For many days your offer will be on the market. Once set, this parameter cannot be changed.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Market Bid</strong></span> <span class="simple_gri_inchis_12">- A minimum amount of 0.0001 MSK / day. The offers for sale are displayed depending on the amount offered by the seller for display. Market Bid large bids are displayed first.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Updating the price of an address</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">After you put a name on sale, you can change the price any time. To change the selling price of a name, go to page Addresses (click Addresses in the main menu bar) and select My Name (left menu). </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the yellow button next to the name that is on sale and select Modify Sale Price. The following dialogue will be displayed </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/update_name_price.png" width="450" height="325" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Price</strong></span> <span class="simple_gri_inchis_12">- The new price you want (MSK).</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Send button. After the transaction is confirmed by the network (1 minute), the name price will be automatically changed.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
           
            
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