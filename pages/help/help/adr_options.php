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
       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr>
             <td width="51%" height="30" align="left"><a href="#cap_interest" class="font_14">Daily Interest</a></td>
             <td width="49%" height="30"><a href="#cap_multi" class="font_14">Multisignatures</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_rent_name" class="font_14">Rent an address name</a></td>
             <td height="30"><a href="#cap_otp" class="font_14">One Time Password</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_share" class="font_14">Share an address</a></td>
             <td height="30"><a href="#cap_ipn" class="font_14">Instant Payment Notification</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_profile" class="font_14">Setting up a profile</a></td>
             <td height="30"><a href="#cap_request" class="font_14">Request Additional Data</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_froze" class="font_14">How to froze and address</a></td>
             <td height="30"><a href="#cap_autoresp" class="font_14">Autoresponders</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_seal" class="font_14">How to seal and address</a></td>
             <td height="30"><a href="#cap_pvt" class="font_14">The private key</a></td>
           </tr>
           <tr>
             <td height="30" align="left"><a href="#cap_restrict" class="font_14">Restricted Recipients</a></td>
             <td height="30">&nbsp;</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td height="50" id="cap_interest">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_16"><strong>Interest</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">MasckCoin is distributed mainly through interest. That means that any holder of at least 5 MSK will receive an interest daily. The interest rate is variable and depends on the amount of coins in circulation. Every day, maximum 2400 MSK are distributed to coins owners. As the amount of coins in circulation varies permanently the interest also varies but on the long-term it has the tendency to decrease.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14"> To set an address to receive interest, go to page Addresses (click Addresses, on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the Setup button next to Receive Daily Interest and the following menu will appear.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/interest.png" class="img-responsive" /></td>
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
       <td><span class="font_14"><strong>Receive the interest to this address</strong></span> <span class="font_14">- As the name suggests, you need to specify an address where you will receive the daily interest. The address can be even the address that requires the interest.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click Activate and that is all. Every 24 hours, the wallet will make a request for interest for your address. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td height="120" bgcolor="#f0f0f0" class="font_14"><table width="95%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr>
             <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
             <td width="84%" valign="middle" class="font_14">Warning!!! After receiving interest for 24 hours you cannot send money from that address. To stop automatic receipt of interest, clear the field Deliver interest to this address. To avoid blocking the interest, we recommend using a different address to receive interest in it.</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td height="50" id="cap_rent_name">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_16"><strong>Rent Adress Names</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">MaskNetwork addresses are strings of at least 64 characters, impossible to remember and hard to
         convey otherwise than in a digital format. To facilitate transmission among users the address names were introduced. The names of addresses are exactly what the domains are for web sites. Instead of navigating to an IP such as 90.21.18.32, you can navigate directly to www.masknetwork.com</td>
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
       <td class="font_16"><strong>Adress Sharing</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">With this option, you send the private key of an address to another person (address). The recipient will be able to spend coins and will have the same rights on the address as you. We recommend using this option carefully. It is very useful when you want to use your address on multiple web wallets or when you want to give full access to the address to a trustworthy person who may use another web wallet than you.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To send address data go to the Address page (click on Addresses on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Share Address option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/share.png" class="img-responsive" /></td>
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
       <td><span class="font_14"><strong>Recipient Address</strong></span> <span class="font_14">- –The address to which you want to convey the control of this address. Be very careful when you write the name to avoid transmitting the private key to a wrong address.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Click the Send button. The Wallet will send the encrypted data in the network. The recipient will be notified and will have the option to import the new address in the local wallet.</td>
     </tr>
     <tr>
       <td height="50" id="cap_profile">&nbsp;</td>
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
       <td id="td_frozen"><strong class="font_16">How to froze and address</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">By activating this option, you will not be able to send money from this address for a period of time. We recommend using this option with caution, because once activated it can no longer be canceled. It is a useful option if you no longer want to use the address for a while and you want to be carefree. Although sending funds is frozen, you can receive the related interest to a different address. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To freeze an address go to the Addresses page (click on the Addresses in the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Freeze Address option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/froze.png" class="img-responsive"/></td>
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
       <td class="font_14">Click the Activate button. After acknowledgment of request (1 minute), you will not be able to send funds from the address for a certain period.</td>
     </tr>
     <tr>
       <td height="50" id="cap_seal">&nbsp;</td>
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
       <td height="50" id="cap_restrict">&nbsp;</td>
     </tr>
     <tr>
       <td id="td_restricted"><strong class="font_16">Restricted Recipients</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">An address with restricted recipients can send funds only to certain specific addresses, which usually belong to the same person / organization. It is a very useful option especially if you own accounts in multiple online wallets. An attacker can only send funds to the other addresses owned by you, which in turn could be protected by other ways. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To restrict the recipients of an address, go to page Addresses (click on Addresses from the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Restrict Recipients options and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/restrict.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td height="0">&nbsp;</td>
     </tr>
     <tr>
       <td height="0"><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
     </tr>
     <tr>
       <td height="0">&nbsp;</td>
     </tr>
     <tr>
       <td height="0"><span class="font_14"><strong>Recipients</strong></span> <span class="font_14">- You can specify up to five recipients. Only the first recipient is required. </span></td>
     </tr>
     <tr>
       <td height="0">&nbsp;</td>
     </tr>
     <tr>
       <td height="0"><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent the service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_multi">&nbsp;</td>
     </tr>
     <tr>
       <td id="td_multisig"><strong class="font_16">Multisignatures</strong></td>
     </tr>
     <tr>
       <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">An address protected by this option needs the approval of other addresses to be able to send funds. It is one of the most common security options. A potential attacker will not be able to spend funds without approval and other specified addresses.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can also specify a minimum number of signatures for a transaction to be executed. For example you can ask for the signature of 3 additional addressed but you can specify that only 2 of 3 signatures are required for a transaction to be approved by the network.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To restrict the recipients of an address, go to page Addresses (click on Addresses from the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Multiple Signatures option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/multisig.png" class="img-responsive"/></td>
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
       <td><span class="font_14"><strong>Signers</strong></span> <span class="font_14">- You can specify up to co-signatories. You must specify at least 1 co-signatory.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Minimum</strong></span> <span class="font_14">- The minimum number of signatures for a transaction to be approved by the network.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent this service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_otp">&nbsp;</td>
     </tr>
     <tr>
       <td id="td_otp"><strong class="font_16">One Time Password</strong></td>
     </tr>
     <tr>
       <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">By activating this option, before each transaction you will need to enter a password that changes with every transaction. If an attacker takes control of the address, he/she cannot spend money because he/she will need this password. </td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can also specify an address where funds may be transferred without the need for this password. This option is very useful if you lose the last password generated by the system.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">The operating mechanism is relatively simple. Before any transaction the wallet will request the last generated password. After sending the funds, the wallet will display the following password, you will need to remember. The Wallet sends to the network an encrypted form (hash256) of the new password. The network will approve a new transaction only if accompanied by the proper password. The network does not know the password but only the encrypted form but can easily check if the supplied password is correct or not.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to the Unique Password option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/otp.png" class="img-responsive"/></td>
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
       <td><span class="font_14"><strong>Emergency address</strong></span> <span class="font_14">- Optional. Emergency address. If you forget or lose the password generated you can recover the funds by sending them to the emergency address.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want the rental of this service. The minimum is 10 days. There is no maximum. The fee is fixed 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Next Password</strong></span> <span class="font_14">- The password you will need to enter at the next transaction from this address.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_ipn">&nbsp;</td>
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
       <td height="50" id="cap_request">&nbsp;</td>
     </tr>
     <tr>
       <td id="td_additional"><strong class="font_16">Requesting Additional Data</strong></td>
     </tr>
     <tr>
       <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can request additional data from those who send funds to the address. Because you can specify the data format, there are no restrictions on the type of data required. After enabling this option, anyone who wants to send you funds will have to fill out a small form.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Additional Data option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/request_data.png" class="img-responsive"/></td>
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
       <td><span class="font_14"><strong>Short Message</strong></span> <span class="font_14">- A brief message in which you can explain for example why you need additional data.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Field Name</strong></span> <span class="font_14">- The name of the required field, such as "Email" or "Phone number"</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Min Length</strong></span> <span class="font_14">- The minimum length of the field. For a Bitcoin address, for example, you should specify a minimum length of 33 characters.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Max Length</strong></span> <span class="font_14">- The maximum length of the field. For a postal code, for example, you should specify a maximum length of 10 characters.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Days</strong></span> <span class="font_14">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
     </tr>
     <tr>
       <td height="50" id="cap_autoresp">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">Autoresponders</strong></td>
     </tr>
     <tr>
       <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">You can set the wallet to send automated messages after each payment / message received, to the address that initiated the payment or sent the message. It is a very useful option especially for traders that could send automated confirmation of the receipt of the funds.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">Click the Setup button next to Automated Responses option and the following menu will appear.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/autoresp.png" class="img-responsive"/></td>
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
 <br>
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
