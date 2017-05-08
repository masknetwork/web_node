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
	     $template->showLeftMenu("help");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../help/help/index.php", "Help", "", "Address Options");
	 
	 $help->showMenu(4);
 ?>
 
 <br><br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td class="font_18"><strong>Address Options</strong></td>
     </tr>
     <tr>
       <td><hr></td>
     </tr>
     <tr>
       <td height="50" id="cap_interest">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_16"><strong>Rent Adress Names</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">MaskNetwork addresses are strings of at least 64 characters, impossible to remember and hard to
         convey otherwise than in a digital format. To facilitate transmission among users the address names were introduced. The names of addresses are exactly what the domains are for web sites. Instead of navigating to an IP such as 90.21.18.32, you can navigate directly to www.MaskNetwork.com</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">In MaskNetwork, you can rent an address name like Maria or John address and instead of sending customers / friends an unintelligible string of characters you can send a short catchy name. The network will know the address to which to deliver the coins or sent messages.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can rent an unlimited number of address names. Also, an address can have an unlimited number of address names associated. Renting costs 0.0001 MSK / day. You can always extend the validity of a name. Also, the address name can be sold on a specialized domestic market.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To rent a name for the TGA address, go to Addresses page (click Addresses on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Rent a Name option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/adr_name.png" class="img-responsive" /></td>
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
       <td><span class="font_14"><strong>Name</strong></span> <span class="font_14">- The name that you want associated with the address. It can be any string of characters with a length between 3 and 30 characters. We accept letters, numbers plus the characters "-" and ".". No spaces allowed.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the Send button. The request will be sent to the network and after confirmation (1 minute), your address will have a new name. You can see a full list of the names that you own in My Names page.</td>
     </tr>
     <tr>
       <td height="50" id="cap_share">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_16"><strong>Setting a profile</strong></td>
     </tr>
     <tr>
       <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">If you don't wish to remain anonymous, you can set a profile for your address to provide information such as email, website or profile picture. All this information will become public and will be associated with your address. An address can have one profile associated.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To associate a profile to your address, go to page Addresses (click on the Address in the menu bar) and click the Options button next to the address and choose Address. You will navigate to the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the setup button next to Associate Profile option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/profile.png" class="img-responsive" /></td>
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
       <td><span class="font_14"><strong>Profile Pic</strong></span> <span class="font_14">- A link to a profile picture. The maximum allowable size of the picture is 1 Mb. It is an optional field.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Name</strong></span> <span class="font_14">- any name. May contain spaces. Not to be confused with the name of the address. A rented address name can be used to receive coins and messages. The profile name is only shown in the profile and has no other role.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Description, Email, Telephone, Website, Facebook</strong></span> <span class="font_14">- Optional fields.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_froze">&nbsp;</td>
     </tr>
     <tr>
       <td id="td_sealed"><strong class="font_16">How to seal an address</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">From a sealed address funds can be transferred but the address cannot be used for anything else. You cannot attach security features, cannot send messages or perform other actions. If an attacker takes control of the address, he/she could only transfer funds (unless the address is not protected by other means). </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">This option is used in conjunction with other security options. For example, if an address protected by multiple signatures is compromised, the attacker could not spend funds but instead he/she could attach a new option which restricts the recipients of the address. In this case the initial holder of the address may no longer transfer funds to his/her addresses, even if he/she protects their address through multiple signatures. An address sealed, protected by multiple signatures is immune to this attack.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To seal an address go to the Addresses page (click on the Address from the menu bar) and than click the Options button next to the address and choose the Address Options. You will navigate in the options page.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to the Seal Address options and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/seal.png" class="img-responsive" /></td>
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
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent the service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td id="td_ipn"><strong class="font_16">Instant Payment Notification</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">By activating this option, you can be notified every time the address receives or sends funds.
         Notification can be done in two ways. By using a web address specified by you or by sending an email. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">It is a very useful option especially for online shops selling digital services that could deliver products immediately after receiving payment.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To activate the Payment Reminder for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Payment Reminder option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/ipn.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Web Address </strong></span> <span class="font_14">- The web address that will be called when sending or receiving funds. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Email Address </strong></span> <span class="font_14">- The email address where messages are sent when sending or receiving funds. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Web Password </strong></span> <span class="font_14">- A password you specified which will be included in the data sent to the web site. It is optional but we recommend specifying a password in order to verify that the request was sent by the web wallet and not by an attacker.</span></td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Short Message</strong></span> <span class="font_14">- The message you want to send automatically.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_pvt">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">View Private Key</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can view the private key to any address that you own. This option is used if you want to keep a copy beside the one maintained by the wallet. With the private key you can control an address. Do not disclose this key to anyone.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To view the private key of an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Reveal Private Key option. Enter your account password and the wallet will display public key / private key combination. You can use the data to import the address into any other wallet.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
   </tbody>
 </table>
 
 
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




