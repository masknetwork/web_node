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
     $template->showLocation("../../help/help/index.php", "Help", "", "Addresses");
	 
	 $help->showMenu(3);
 ?>
 
 <br><br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td align="left" class="font_18"><strong>Addresses</strong></td>
     </tr>
     <tr>
       <td align="left"><hr></td>
     </tr>
     <tr>
       <td align="left" class="font_14">An address is like a bank account number anonymous in the real world. It is a string of characters that you can send to anyone to receive coins, assets or messages. Like a bank account, you can send messages or coins to an address. An address looks like this:</td>
     </tr>
     <tr>
       <td height="80" align="center" class="font_14"><strong>ME4wEAYHKoZIzj0CAQYFK4EEACEDOgAESw6vT5Oz43xw/6Wa7tt0RrUQ<br>
         9Bj4c7Qhr/gj5XZmMLp1ALqUG46+VOiLLII7ua5mzfuylwHaoLU=</strong></td>
     </tr>
     <tr>
       <td align="left" class="font_14">You can own an unlimited number of addresses. To an address you can assign a name or different security options (see chapter Addresses Options).</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" bgcolor="#f0f0f0"><table width="95%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr>
             <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
             <td width="84%" valign="middle" class="font_14">Because an address is actually a public key (public key basic form), you can send messages or encrypted data that only the address owner can read even if the messages cross the entire MaskNetwork network.</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td height="50" align="left">&nbsp;</td>
     </tr>
     <tr>
       <td height="0" align="left" valign="top" class="font_16"><strong>My Addresses</strong></td>
     </tr>
     <tr>
       <td height="25" align="left"  class="font_14"><hr></td>
     </tr>
     <tr>
       <td align="left" class="font_14">Immediately after creating an account using the web wallet, the system will automatically create an address for you. To view the list of addresses that you own, go to page Addresses (click Addresses in the main menu at the top of the page). </td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_14">In this page the addresses and the balance in MSK are listed. If an address has a name associated with it, you'll see only the name of the address. For the rest of addresses, only part of the string of characters is visible. To view the complete address click the Options button next to the address and select the QR Code. A dialogue like the one below will be displayed. You can copy the complete form of the address or you can use your mobile phone to scan the QR code displayed.</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/qr_code.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" bgcolor="#f0f0f0"><table width="95%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr>
             <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
             <td width="84%" valign="middle" class="font_14" style="padding-top:10px; padding-bottom:10px;">Because the addresses have at least 64 random characters they are almost impossible to remember or write on paper. Fortunately you can rent a name for your address, such as Maria or John. You can tell everyone "send me 10 MSK at Maria" which is more convenient and efficient.</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td height="50" align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_16"><strong>Creating a new address</strong></td>
     </tr>
     <tr>
       <td align="left" ><hr></td>
     </tr>
     <tr>
       <td align="left" class="font_14">You can get a new address in three ways. You can use the wallet to generate an address, you can import an address, or someone sends you the private key and public key of and address. The easyest way is to generate a new address using the wallet. </td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_14">To generate a new address, go to page addresses (click Addresses in the main menu bar). Click the green button "New Address" at the bottom of the page. You will see the following dialogue:</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/new_adr.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left"><span class="font_14"><strong>Encryption Type</strong></span> <span class="font_14">- Encryption Type - We recommend you to leave this option unchangeable. The more advanced the encryption type is, the longer the generated address. 224 bits are more than sufficient to protect your funds.</span></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left"><span class="font_14"><strong>Address Tag</strong></span> <span class="font_14">- You can attach a brief description of the address. Not to be confused with the rental of a name. The accompanying description is visible only within your account.</span></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_14">Click the Send button and you will have a brand new address that you can send to your friends / customers. You can hold an unlimited number of addresses.</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td height="150" align="left" bgcolor="#f0f0f0"><table width="95%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr>
             <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
             <td width="84%" valign="middle" class="font_14">MaskNetwork uses Elliptic Curve Cryptography to encrypt data and transactions. Encryption Type is a the method of encryption and the higher the number of bits is, the harder is theoretically for an  attacker to break an encrypted text. We say theoretically because if you add up all the computers in the world and put them to break through brute force an encrypted text with the weakest method (224-bit), they will need a little over 1000 years.</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td height="50" align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_16"><strong>Importing and address</strong></td>
     </tr>
     <tr>
       <td align="left" ><hr></td>
     </tr>
     <tr>
       <td align="left" class="font_14">Another way to get a new address is to 'import' it. For this you will need the public key and private key of an address. If you own the pair, go to Addresses page (click Addresses in the main menu bar) and click the yellow button Import address from the bottom of the page. You will see the following dialogue </td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/import_adr.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left"><span class="font_14"><strong>Public Key </strong></span> <span class="font_14">- The public key of address</span></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left"><span class="font_14"><strong>Private Key</strong></span> <span class="font_14">- The private key of address</span></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left"><span class="font_14"><strong>Address Tag</strong></span> <span class="font_14">- You can attach a brief description of the address. Not to be confused with the rental of a name. The accompanying description is visible only within your account.</span></td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left" class="font_14">Click the Send button. If the public key / private key pair is valid, the address will be imported and you can use it immediately.</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
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
