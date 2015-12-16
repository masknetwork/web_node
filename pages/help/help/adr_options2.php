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
			   $help->showLeftMenu(4);
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
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td class="simple_red_18">Address Options</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="51%" height="30" align="left"><a href="#" class="maro_14">Daily Interest</a></td>
                              <td width="49%" height="30"><a href="#" class="maro_14">Multisignatures</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Rent an address name</a></td>
                              <td height="30"><a href="#" class="maro_14">One Time Password</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Share an address</a></td>
                              <td height="30"><a href="#" class="maro_14">Instant Payment Notification</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Setting up a profile</a></td>
                              <td height="30"><a href="#" class="maro_14">Request Additional Data</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">How to froze and address</a></td>
                              <td height="30"><a href="#" class="maro_14">Autoresponders</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">How to seal and address</a></td>
                              <td height="30"><a href="#" class="maro_14">The private key</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Restricted Recipients</a></td>
                              <td height="30">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Interest</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><p>MasckCoin is distributed mainly through interest. That means that any holder of at least 5 MSK will receive an interest daily. The interest rate is variable and depends on the amount of coins in circulation. Every day, maximum 2400 MSK are distributed to coins owners. As the amount of coins in circulation varies permanently the interest also varies but on the long-term it has the tendency to decrease.</p></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"> To set an address to receive interest, go to page Addresses (click Addresses, on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Setup button next to Receive Daily Interest and the following menu will appear.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/interest.png" width="450" height="317" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Receive the interest to this address</strong></span> <span class="simple_gri_inchis_12">- As the name suggests, you need to specify an address where you will receive the daily interest. The address can be even the address that requires the interest.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click Activate and that is all. Every 24 hours, the wallet will make a request for interest for your address. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="120" bgcolor="#f6f2e7" class="simple_gri_inchis_12"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                                <td width="84%" valign="middle" class="simple_maro_12">Warning!!! After receiving interest for 24 hours you cannot send money from that address. To stop automatic receipt of interest, clear the field Deliver interest to this address. To avoid blocking the interest, we recommend using a different address to receive interest in it.</td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Rent Adress Names</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">MaskNetwork addresses are strings of at least 64 characters, impossible to remember and hard to
convey otherwise than in a digital format. To facilitate transmission among users the address names were introduced. The names of addresses are exactly what the domains are for web sites. Instead of navigating to an IP such as 90.21.18.32, you can navigate directly to www.masknetwork.com</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">In MaskNetwork, you can rent an address name like Maria or John address and instead of sending customers / friends an unintelligible string of characters you can send a short catchy name. The network will know the address to which to deliver the coins or sent messages.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">You can rent an unlimited number of address names. Also, an address can have an unlimited number of address names associated. Renting costs 0.0001 MSK / day. You can always extend the validity of a name. Also, the address name can be sold on a specialized domestic market.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To rent a name for the TGA address, go to Addresses page (click Addresses on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Rent a Name option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/adr_name.png" width="450" height="300" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Name</strong></span> <span class="simple_gri_inchis_12">- The name that you want associated with the address. It can be any string of characters with a length between 3 and 30 characters. We accept letters, numbers plus the characters "-" and ".". No spaces allowed.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Send button. The request will be sent to the network and after confirmation (1 minute), your address will have a new name. You can see a full list of the names that you own in My Names page.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Adress Sharing</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">With this option, you send the private key of an address to another person (address). The recipient will be able to spend coins and will have the same rights on the address as you. We recommend using this option carefully. It is very useful when you want to use your address on multiple web wallets or when you want to give full access to the address to a trustworthy person who may use another web wallet than you.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To send address data go to the Address page (click on Addresses on the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Share Address option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/share.png" width="450" height="325" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Recipient Address</strong></span> <span class="simple_gri_inchis_12">- –The address to which you want to convey the control of this address. Be very careful when you write the name to avoid transmitting the private key to a wrong address.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Send button. The Wallet will send the encrypted data in the network. The recipient will be notified and will have the option to import the new address in the local wallet.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Setting a profile</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">If you don't wish to remain anonymous, you can set a profile for your address to provide information such as email, website or profile picture. All this information will become public and will be associated with your address. An address can have one profile associated.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To associate a profile to your address, go to page Addresses (click on the Address in the menu bar) and click the Options button next to the address and choose Address. You will navigate to the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the setup button next to Associate Profile option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/profile.png" width="450" height="615" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Profile Pic</strong></span> <span class="simple_gri_inchis_12">- A link to a profile picture. The maximum allowable size of the picture is 1 Mb. It is an optional field.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Name</strong></span> <span class="simple_gri_inchis_12">- any name. May contain spaces. Not to be confused with the name of the address. A rented address name can be used to receive coins and messages. The profile name is only shown in the profile and has no other role.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Description, Email, Telephone, Website, Facebook</strong></span> <span class="simple_gri_inchis_12">- Optional fields.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_frozen"><strong class="simple_red_14">How to froze and address</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">By activating this option, you will not be able to send money from this address for a period of time. We recommend using this option with caution, because once activated it can no longer be canceled. It is a useful option if you no longer want to use the address for a while and you want to be carefree. Although sending funds is frozen, you can receive the related interest to a different address. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To freeze an address go to the Addresses page (click on the Addresses in the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Freeze Address option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/froze.png" width="450" height="288" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Click the Activate button. After acknowledgment of request (1 minute), you will not be able to send funds from the address for a certain period.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_sealed"><strong class="simple_red_14">How to seal an address</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">From a sealed address funds can be transferred but the address cannot be used for anything else. You cannot attach security features, cannot send messages or perform other actions. If an attacker takes control of the address, he/she could only transfer funds (unless the address is not protected by other means). </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">This option is used in conjunction with other security options. For example, if an address protected by multiple signatures is compromised, the attacker could not spend funds but instead he/she could attach a new option which restricts the recipients of the address. In this case the initial holder of the address may no longer transfer funds to his/her addresses, even if he/she protects their address through multiple signatures. An address sealed, protected by multiple signatures is immune to this attack.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To seal an address go to the Addresses page (click on the Address from the menu bar) and than click the Options button next to the address and choose the Address Options. You will navigate in the options page.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to the Seal Address options and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/seal.png" width="450" height="245" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_restricted"><strong class="simple_red_14">Restricted Recipients</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">An address with restricted recipients can send funds only to certain specific addresses, which usually belong to the same person / organization. It is a very useful option especially if you own accounts in multiple online wallets. An attacker can only send funds to the other addresses owned by you, which in turn could be protected by other ways. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To restrict the recipients of an address, go to page Addresses (click on Addresses from the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Restrict Recipients options and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/restrict.png" width="450" height="531" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Recipients</strong></span> <span class="simple_gri_inchis_12">- You can specify up to five recipients. Only the first recipient is required. </span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_multisig"><strong class="simple_red_14">Multisignatures</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">An address protected by this option needs the approval of other addresses to be able to send funds. It is one of the most common security options. A potential attacker will not be able to spend funds without approval and other specified addresses.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">You can also specify a minimum number of signatures for a transaction to be executed. For example you can ask for the signature of 3 additional addressed but you can specify that only 2 of 3 signatures are required for a transaction to be approved by the network.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To restrict the recipients of an address, go to page Addresses (click on Addresses from the menu bar) and than click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Multiple Signatures option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/multisig.png" width="450" height="582" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Signers</strong></span> <span class="simple_gri_inchis_12">- You can specify up to co-signatories. You must specify at least 1 co-signatory.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Minimum</strong></span> <span class="simple_gri_inchis_12">- The minimum number of signatures for a transaction to be approved by the network.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent this service. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_otp"><strong class="simple_red_14">Parola Unica</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">By activating this option, before each transaction you will need to enter a password that changes with every transaction. If an attacker takes control of the address, he/she cannot spend money because he/she will need this password. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">You can also specify an address where funds may be transferred without the need for this password. This option is very useful if you lose the last password generated by the system.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">The operating mechanism is relatively simple. Before any transaction the wallet will request the last generated password. After sending the funds, the wallet will display the following password, you will need to remember. The Wallet sends to the network an encrypted form (hash256) of the new password. The network will approve a new transaction only if accompanied by the proper password. The network does not know the password but only the encrypted form but can easily check if the supplied password is correct or not.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to the Unique Password option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/otp.png" width="450" height="407" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Emergency address</strong></span> <span class="simple_gri_inchis_12">- Optional. Emergency address. If you forget or lose the password generated you can recover the funds by sending them to the emergency address.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want the rental of this service. The minimum is 10 days. There is no maximum. The fee is fixed 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Next Password</strong></span> <span class="simple_gri_inchis_12">- The password you will need to enter at the next transaction from this address.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_ipn"><strong class="simple_red_14">Instant Payment Notification</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">By activating this option, you can be notified every time the address receives or sends funds.
Notification can be done in two ways. By using a web address specified by you or by sending an email. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">It is a very useful option especially for online shops selling digital services that could deliver products immediately after receiving payment.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To activate the Payment Reminder for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Payment Reminder option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/ipn.png" width="450" height="458" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Web Address </strong></span> <span class="simple_gri_inchis_12">- The web address that will be called when sending or receiving funds. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Email Address </strong></span> <span class="simple_gri_inchis_12">- The email address where messages are sent when sending or receiving funds. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Web Password </strong></span> <span class="simple_gri_inchis_12">- A password you specified which will be included in the data sent to the web site. It is optional but we recommend specifying a password in order to verify that the request was sent by the web wallet and not by an attacker.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_additional"><strong class="simple_red_14">Requesting Additional Data</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">You can request additional data from those who send funds to the address. Because you can specify the data format, there are no restrictions on the type of data required. After enabling this option, anyone who wants to send you funds will have to fill out a small form.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Additional Data option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/request_data.png" width="450" height="602" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Short Message</strong></span> <span class="simple_gri_inchis_12">- A brief message in which you can explain for example why you need additional data.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Field Name</strong></span> <span class="simple_gri_inchis_12">- The name of the required field, such as "Email" or "Phone number"</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Min Length</strong></span> <span class="simple_gri_inchis_12">- The minimum length of the field. For a Bitcoin address, for example, you should specify a minimum length of 33 characters.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Max Length</strong></span> <span class="simple_gri_inchis_12">- The maximum length of the field. For a postal code, for example, you should specify a maximum length of 10 characters.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- As this is a rented service you must specify for how long you want to rent the address. The minimum is 10 days. There is no maximum. The fee is fixed of 0.0001 MSK / day.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Autoresponders</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>You can set the wallet to send automated messages after each payment / message received, to the address that initiated the payment or sent the message. It is a very useful option especially for traders that could send automated confirmation of the receipt of the funds.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To activate this option for an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Automated Responses option and the following menu will appear.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/autoresp.png" width="450" height="389" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Short Message</strong></span> <span class="simple_gri_inchis_12">- The message you want to send automatically.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">View Private Key</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">You can view the private key to any address that you own. This option is used if you want to keep a copy beside the one maintained by the wallet. With the private key you can control an address. Do not disclose this key to anyone.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">To view the private key of an address, go to page Addresses (click on Addresses on the menu bar) and click the Options button next to the address and choose Address Options. You will navigate in the options page. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Click the Setup button next to Reveal Private Key option. Enter your account password and the wallet will display public key / private key combination. You can use the data to import the address into any other wallet.</span></td>
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