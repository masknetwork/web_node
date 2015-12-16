<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CHelp.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $help=new CHelp();
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

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_14 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

</style>

</head>

<body>

<?
   $template->showTopBar(7);
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
     $template->showLocation("../../help/help/index.php", "Help", "", "Address Names");
	 
	 $help->showMenu(5);
 ?>
 
 <br><br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td><span class="font_18"><strong>Address Names</strong></span></td>
     </tr>
     <tr>
       <td ><hr></td>
     </tr>
     <tr>
       <td class="font_14">MaskNetwork addresses represent long strings of characters hard to send and impossible to  remember, renting an address name for the addresses that you use most is an indicated option. In this section we will discuss how the wallet helps you manage the address names that you own. The address names can also be transferred or traded on the domestic, specialized market of the network.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Also, you can attach multiple names to the same address. For example "Maria" or "Mary" could 
         represent the same address.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_16"><strong>Address Names Management</strong></span></td>
     </tr>
     <tr>
       <td ><hr></td>
     </tr>
     <tr>
       <td class="font_14">To view the name that you own, go to the Addresses section (click Addresses in the menu bar) and 
         select Addresses Name from the left menu. Here are all the names that you own, and expiry date. Names cannot be purchased but only rented for a certain period. Rental cost is 0.0001 MSK / day.</td>
     </tr>
     <tr>
       <td height="30">&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_16"><strong>Renewing an address name</strong></span></td>
     </tr>
     <tr>
       <td ><hr></td>
     </tr>
     <tr>
       <td class="font_14">Like Internet domain names, addresses names must be renewed when they expire. Otherwise, anyone 
         can rent that name for their own addresses. To extend the validity of a name that you own already, click on the yellow button next to the name and choose "Extend". The dialogue below will appear: </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/renew_name.png" class="img-responsive" /></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- Addresses names can also be transferred to another address for a fee of 0.0001 MSK. It is a useful option if you want to change the address of a name.</span></td>
     </tr>
     <tr>
       <td height="50">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">Transfering an address name</strong></td>
     </tr>
     <tr>
       <td ><hr></td>
     </tr>
     <tr>
       <td class="font_14">Addresses names can also be transferred to another address for a fee of 0.0001 MSK. It is a useful
         option if you want to change the address of a name.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">To transfer a name that you own, go to page Addresses (click Addresses in the main menu bar) and
         select My Names from the left menu. Click the yellow button next to the name and select Transfer Name. The following menu will appear: </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/tranfser_name.png" class="img-responsive" /></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Recipient Address</strong></span> <span class="font_14">- The new address with which you want to associate the name.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the Send button. After the transaction is confirmed by the network (1 minute), the name will be associated with the new specified address.</td>
     </tr>
     <tr>
       <td height="50">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">Selling an address name</strong></td>
     </tr>
     <tr>
       <td ><hr></td>
     </tr>
     <tr>
       <td class="font_14">As we said, the name of address can be sold / bought on the domestic specialized market, where all name payments / transfers are made automatically, without any intermediary. All name transactions are made using MaskCoin.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To put on sale a name that you own, go to page Addresses (click Addresses in the main menu bar) and select My Names from the left menu. Click the yellow button next to the name and select Set sale price. The following menu will appear: </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/sale_name.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Price</strong></span> <span class="font_14">- the price you want in MSK. Once set, this parameter cannot be changed.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- For many days your offer will be on the market. Once set, this parameter cannot be changed.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Market Bid</strong></span> <span class="font_14">- A minimum amount of 0.0001 MSK / day. The offers for sale are displayed depending on the amount offered by the seller for display. Market Bid large bids are displayed first.</span></td>
     </tr>
     <tr>
       <td height="50">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">Updating the selling price of an address</strong></td>
     </tr>
     <tr>
       <td  ><hr></td>
     </tr>
     <tr>
       <td class="font_14">After you put a name on sale, you can change the price any time. To change the selling price of a name, go to page Addresses (click Addresses in the main menu bar) and select My Name (left menu). </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the yellow button next to the name that is on sale and select Modify Sale Price. The following dialogue will be displayed </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/update_name_price.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Price</strong></span> <span class="font_14">- The new price you want (MSK).</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the Send button. After the transaction is confirmed by the network (1 minute), the name price will be automatically changed.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
   </tbody>
 </table>
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
