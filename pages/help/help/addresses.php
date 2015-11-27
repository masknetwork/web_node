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
			   $help->showLeftMenu(3);
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
                        <td align="left" class="simple_red_16"><strong>Addresses</strong></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">An address is like a bank account number anonymous in the real world. It is a string of characters that you can send to anyone to receive coins, assets or messages. Like a bank account, you can send messages or coins to an address. An address looks like this:</td>
                      </tr>
                      <tr>
                        <td height="80" align="center" class="simple_gri_12">ME4wEAYHKoZIzj0CAQYFK4EEACEDOgAESw6vT5Oz43xw/6Wa7tt0RrUQ<br>9Bj4c7Qhr/gj5XZmMLp1ALqUG46+VOiLLII7ua5mzfuylwHaoLU=</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">You can own an unlimited number of addresses. To an address you can assign a name or different security options (see chapter Addresses Options).</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">Because an address is actually a public key (public key basic form), you can send messages or encrypted data that only the address owner can read even if the messages cross the entire MaskNetwork network.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0" align="left" valign="top" class="simple_red_14"><strong>My Addresses</strong></td>
                      </tr>
                      <tr>
                        <td height="25" align="left" background="../../template/template/GIF/lp.png" class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Immediately after creating an account using the web wallet, the system will automatically create an address for you. To view the list of addresses that you own, go to page Addresses (click Addresses in the main menu at the top of the page). </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">In this page the addresses and the balance in MSK are listed. If an address has a name associated with it, you'll see only the name of the address. For the rest of addresses, only part of the string of characters is visible. To view the complete address click the Options button next to the address and select the QR Code. A dialogue like the one below will be displayed. You can copy the complete form of the address or you can use your mobile phone to scan the QR code displayed.</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/qr_code.png" width="402" height="275" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">Because the addresses have at least 64 random characters they are almost impossible to remember or write on paper. Fortunately you can rent a name for your address, such as Maria or John. You can tell everyone "send me 10 MSK at Maria" which is more convenient and efficient.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_red_14"><strong>Cum creezi o adresa noua</strong></td>
                      </tr>
                      <tr>
                        <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">You can get a new address in three ways. You can use the wallet to generate an address, you can import an address, or someone sends you the private key and public key of and address. The easyest way is to generate a new address using the wallet. </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">To generate a new address, go to page addresses (click Addresses in the main menu bar). Click the green button "New Address" at the bottom of the page. You will see the following dialogue:</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/new_adr.png" width="453" height="248" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Encryption Type</strong></span> <span class="simple_gri_inchis_12">- Encryption Type - We recommend you to leave this option unchangeable. The more advanced the encryption type is, the longer the generated address. 224 bits are more than sufficient to protect your funds.</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Address Tag</strong></span> <span class="simple_gri_inchis_12">- You can attach a brief description of the address. Not to be confused with the rental of a name. The accompanying description is visible only within your account.</span> </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Click the Send button and you will have a brand new address that you can send to your friends / customers. You can hold an unlimited number of addresses.</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="150" align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">MaskNetwork uses Elliptic Curve Cryptography to encrypt data and transactions. Encryption Type is a the method of encryption and the higher the number of bits is, the harder is theoretically for an  attacker to break an encrypted text. We say theoretically because if you add up all the computers in the world and put them to break through brute force an encrypted text with the weakest method (224-bit), they will need a little over 1000 years.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_red_14"><strong>Importing and address</strong></td>
                      </tr>
                      <tr>
                        <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Another way to get a new address is to 'import' it. For this you will need the public key and private key of an address. If you own the pair, go to Addresses page (click Addresses in the main menu bar) and click the yellow button Import address from the bottom of the page. You will see the following dialogue </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/import_adr.png" width="451" height="363" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Public Key </strong></span> <span class="simple_gri_inchis_12">- The public key of address</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Private Key</strong></span> <span class="simple_gri_inchis_12">- The private key of address</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Address Tag</strong></span> <span class="simple_gri_inchis_12">- You can attach a brief description of the address. Not to be confused with the rental of a name. The accompanying description is visible only within your account.</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Click the Send button. If the public key / private key pair is valid, the address will be imported and you can use it immediately.</td>
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