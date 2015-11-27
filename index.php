<?
   session_start();
   
   if (isset($_REQUEST['act'])) 
      if ($_REQUEST['act']=="logout") 
	     unset($_SESSION['userID']);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>MaskNetwork</title>
<link rel="stylesheet" href="style.css" type="text/css">
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

<body style="margin-left:0px; margin-bottom:0px; margin-right:0px; margin-top:0px" >

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="71" align="center" background="pages/index/index/GIF/top_bar.png"><table width="300" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td><img src="pages/index/index/GIF/sigla.png" width="273" height="62" alt=""/></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="71" align="center" bgcolor="#000000"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td height="500" align="center" valign="top" style="background-position:center" background="pages/index/index/GIF/main.jpg"><table width="700" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="80" align="center"><span class="bold_shadow_white_30">The Market for everything</span><br>
                    <span class="bold_shadow_white_14">The first peer to peer anonymous market, not controlled by anyone, where you can trade anything</span></td>
                </tr>
                <tr>
                  <td height="357" align="center" valign="bottom"><table width="300" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center">
                        <a href="./pages/account/login/index.php" class="btn btn-primary btn-lg" style="width:140px"><span class="glyphicon glyphicon-pencil">&nbsp;</span>Login</a>
                        </td>
                        <td align="center">
                         <a href="./pages/account/signup/index.php" class="btn btn-success btn-lg" style="width:140px"><span class="glyphicon glyphicon-plus">&nbsp;</span>Signup</a>
                        </td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="71" align="center" background="pages/index/index/GIF/blue_back.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="407" height="340" align="left"><img src="pages/index/index/GIF/book.png" width="380" height="333" alt=""/></td>
            <td width="593" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_30">What is MaskWallet ?</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_14"><p>MaskWallet is an online wallet that lets you access MaskNetwork. It's that easy method to access the network. MaskNetwork is a peer to peer network, decentralized, where you can trade any product or digital goods, without asking anyone's consent, outside the control of an individual or group. MaskNetwork is a community of people who want to trade without restrictions, intermediaries or huge taxes imposed by traditional companies such as eBay.</p></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right"><a href="#" class="btn btn-default">Read More</a></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="400" align="center" valign="top" background="pages/index/index/GIF/gray_bar.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td height="40">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" class="simple_gri_30">You own complete control of funds and addresses</td>
          </tr>
          <tr>
            <td align="center" class="simple_gri_14"><p>We all know how important the security of an anonymous peer to peer network is, such as Bitcoin network for example. One of the most difficult aspects of Bitcoin is the safekeeping of private keys that control the funds you own. In MaskNetwork we brought multiple technical innovations by means of which your funds are safe even if the private key of an address has been compromised. Nobody can steal funds from a protected address.</p></td>
          </tr>
          <tr>
            <td height="40" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><table width="1000" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="center"><table width="190" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_top.png" width="190" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="200" align="center" valign="top" background="pages/index/index/GIF/panel_middle.png">
                        
                        <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="center" valign="top" class="simple_porto_16">
                              <img id="img_frozen" name="img_frozen" src="pages/adr/options/GIF/adr_opt_froze.png" width="119" height="110" alt=""/></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_porto_16">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16" id="td_frozen" name="td_frozen"><strong class="simple_gri_14">Frozen Addresses</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>You can freeze an address for a period. From a frozen address one cannot spend funds. If an attacker takes control of the address he/she will not be able to spend funds for as long as the option is active.</p></td>
                            </tr>
                            <tr>
                              <td height="40" align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center">
                              <a href="./pages/help/help/adr_options.php#td_frozen" class="btn btn-default" style="width:160px">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_bottom.png" width="190" height="22" alt=""/></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td align="center">&nbsp;</td>
                  <td align="center"><table width="190" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_top.png" width="190" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="200" align="center" valign="top" background="pages/index/index/GIF/panel_middle.png">
                        
                        <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="110" align="center" valign="middle" class="simple_porto_16">
                              <img src="pages/adr/options/GIF/adr_opt_seal.png" width="80" height="80" id="img_seal" name="img_seal" /></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_porto_16">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16" id="td_seal" name="td_seal"><strong class="simple_gri_14">Sealed Addresses</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>From a sealed address you can spend money but you cannot attach any option. If an attacker takes control of the address, he/she will be able to spend funds but he/she cannot perform other operations without your consent.</p></td>
                            </tr>
                            <tr>
                              <td align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><a href="./pages/help/help/adr_options.php#td_sealed" class="btn btn-default" style="width:160px">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_bottom.png" width="190" height="22" alt=""/></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td align="center">&nbsp;</td>
                  <td align="center"><table width="190" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_top.png" width="190" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="200" align="center" valign="top" background="pages/index/index/GIF/panel_middle.png">
                       
                       
                         <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="center" valign="top" class="simple_porto_16">
                              <img src="pages/adr/options/GIF/adr_opt_restrict.png" name="img_restrict" height="110" id="img_restrict" /></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_porto_16">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_14" id="td_restrict" name="td_restrict" ><strong>Restricted Recipients</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>By activating this option you will be able to send funds from the address only to a group of up to five other addresses. If an attacker takes control of the address, he/she can only send funds than to a specified group of addresses.</p></td>
                            </tr>
                            <tr>
                              <td align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><a href="./pages/help/help/adr_options.php#td_restricted" class="btn btn-default" style="width:160px">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_bottom.png" width="190" height="22" alt=""/></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td align="center">&nbsp;</td>
                  <td align="center">
                  
                  <table width="190" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_top.png" width="190" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="200" align="center" valign="top" background="pages/index/index/GIF/panel_middle.png">
                        
                        
                       <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="center" valign="top" class="simple_porto_16">
                           <img src="pages/adr/options/GIF/adr_opt_multisig.png" width="125" height="110" id="img_multisig" name="img_multisig" />
                            </td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_porto_16">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16" id="td_multisig" name="td_multisig"><strong class="simple_gri_14">Multisignatures</strong></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="simple_gri_12">A <strong>multi</strong>-<strong>signature</strong> address is an address that is associated with more than one private key. Spending funds from a multisig address requires <strong>signatures</strong> from up to 5 other addresses. </td>
                            </tr>
                            <tr>
                              <td height="60" align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center">
                              <a href="./pages/help/help/adr_options.php#td_multisig" class="btn btn-default" style="width:160px">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_bottom.png" width="190" height="22" alt=""/></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td align="center">&nbsp;</td>
                  <td align="center"><table width="190" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_top.png" width="190" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="200" align="center" valign="top" background="pages/index/index/GIF/panel_middle.png">
                        
                        <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="center" valign="top" class="simple_porto_16">
                              <img src="pages/adr/options/GIF/adr_opt_otp.png" width="94" height="110" id="img_opt" name="img_opt" /></td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_porto_16">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_14" id="td_opt" name="td_opt"><strong>One Time Passwords</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12">A <strong>one</strong>-<strong>time password</strong> (<strong>OTP</strong>) is a <strong>password</strong>that is valid for only <strong>one</strong> transaction, on a computer system or other digital device. Spending funds from an OTP address requires an unique password. </td>
                            </tr>
                            <tr>
                              <td height="59" align="left">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><a href="./pages/help/help/adr_options.php#td_otp" class="btn btn-default" style="width:160px">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/panel_bottom.png" width="190" height="22" alt=""/></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="333" align="center" background="pages/index/index/GIF/blue_back.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="616" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_30">Integrated escrow system</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_14"><p>In an anonymous market as MaskNetwork, there will always be cases of fraud in which paid products never reach the destination, especially since any payment is irreversible. For this reason we have integrated an escrow system across the network. When sending funds to an address, you can specify a different address as escrower. When you receive the goods, the money is delivered to sellers by the escrow address. Any dispute will be resolved by the escrow account and you can be sure that you will always receive your products.</p></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><a href="./pages/help/help/transactions.php#td_escrow" class="btn btn-default">Read More</a></td>
                </tr>
              </tbody>
            </table></td>
            <td width="384" align="center"><img src="pages/index/index/GIF/escrow.png" alt=""/></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="333" align="center" background="pages/index/index/GIF/gray_bar.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><span class="simple_gri_30">Communicate efficiently with clients</span></td>
          </tr>
          <tr>
            <td align="center"><p class="simple_gri_14">For any business, close communication with clients and advertising are essential. Therefore we have integrated in MaskNetwork a unique advertising system which along with messaging will allow you to sell more effectively. You can post advertising messages and you can communicate effectively, fast, anonymous and without the approval of a person or group.</p></td>
          </tr>
          <tr>
            <td height="30" align="center">&nbsp;</td>
          </tr>
        </tbody>
      </table>
        <table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td><img src="pages/index/index/GIF/big_panel_top.png" width="993" height="22" alt=""/></td>
          </tr>
          <tr>
            <td align="center" background="pages/index/index/GIF/big_panel_middle.png"><table width="97%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="47%" align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="26%" align="center"><img src="pages/index/index/GIF/ads_off.png" width="145" height="200" alt=""/></td>
                        <td width="74%" align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16"><strong>P2P Advertising</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>Itegrat advertising network system is the first of its kind in the world. It allows you to post anonymous messages that will reach thousands of potential customers whatever wallet they use. You can also restrict the viewing of messages to a specific geographic area.</p></td>
                            </tr>
                            <tr>
                              <td height="40" align="left" valign="bottom"><a href="./pages/help/help/adv.php" class="btn btn-default">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                        </tr>
                      </tbody>
                  </table></td>
                  <td width="2%" align="center" background="pages/index/index/GIF/vsep.png">&nbsp;</td>
                  <td width="51%" align="center"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="37%"><img src="pages/index/index/GIF/mes_off.png" width="163" height="200" alt=""/></td>
                        <td width="63%"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16"><strong>Encrypted Messages</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>The messaging system allows you to send anonymous and encrypted messages to any address in the network. Because the messages are encrypted using the public key of the target address, only the address owner can read the message, even if he/she travels hundreds of nodes. Anonymity is guaranteed by the network structure.</p></td>
                            </tr>
                            <tr>
                              <td height="40" align="left" valign="bottom"><a href="./pages/help/help/messages.php" class="btn btn-default">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td><img src="pages/index/index/GIF/big_panel_bottom.png" width="993" height="22" alt=""/></td>
          </tr>
        </tbody>
      </table>
      <br><br>
      
      </td>
    </tr>
    <tr>
      <td height="333" align="center" background="pages/index/index/GIF/blue_back.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="291" height="340" align="left"><img src="pages/index/index/GIF/interest.png" width="232" height="311" alt=""/></td>
            <td width="709" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_30">Primesti dobanda in fiecare zi</td>
                </tr>
                <tr>
                  <td class="bold_shadow_white_14"><p>MasckCoin is the cryptographic currency underlying the network. The most famous current cryptographic currency is Bitcoin, which is generated through an expensive process requiring specialized hardware. To be able to &quot;mine&quot; Bitcoin needs serious investment. MaskCoin is generated mainly by interest. Basically, any account that has a balance of at least 10 MSK, will receive each day an interest the rate of which is set by the network based on the number of coins in circulation. The current level of interest is 90% per year. All you have to do to cash the interest is to open an account with an address in which to permanently maintain 10 MSK. The Wallet does the rest and it will deliver you the interest day by day in your account.</p></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right"><a href="./pages/help/help/index.php#td_interest" class="btn btn-default">Read More</a></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="333" align="center" background="pages/index/index/GIF/gray_bar.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><span class="simple_gri_30">Tools for traders</span></td>
          </tr>
          <tr>
            <td align="center" class="simple_gri_14"><p>To facilitate the receipt of orders we have implemented in Network Mask very useful functions such as immediate notification if a payment is received / sent or the possibility to request additional information from buyers such as email address, telephone number...</p></td>
          </tr>
          <tr>
            <td height="30" align="center">&nbsp;</td>
          </tr>
        </tbody>
      </table>
        <table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td><img src="pages/index/index/GIF/big_panel_top.png" width="993" height="22" alt=""/></td>
          </tr>
          <tr>
            <td align="center" background="pages/index/index/GIF/big_panel_middle.png"><table width="97%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="47%" align="left"><table width="93%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="43%" align="left"><img src="pages/index/index/GIF/drum_off.png" width="178" height="200" alt=""/></td>
                        <td width="57%" align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16"><strong>Instant Payment Notification</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>You can be automatically notified every time when an address sends or receives funds. Notification can be made by calling a Web address provided by you or by sending an email with all payment details. It is a particularly useful function in some cases.</p></td>
                            </tr>
                            <tr>
                              <td height="45" align="left" valign="bottom">
                              <a href="./pages/help/help/adr_options.php#td_ipn" class="btn btn-default">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td width="2%" align="center" background="pages/index/index/GIF/vsep.png">&nbsp;</td>
                  <td width="51%" align="center"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="50%"><img src="pages/index/index/GIF/notepad_off.png" width="190" height="200" alt=""/></td>
                        <td width="50%"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_gri_16"><strong>Request Additional Data</strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="simple_gri_12"><p>Another feature is requesting additional information. You can for example request the delivery address.Those who want to send you funds will have to fill in a form with the address, which of course is encrypted before being sent to the network.</p></td>
                            </tr>
                            <tr>
                              <td height="45" align="left" valign="bottom">
                              <a href="./pages/help/help/adr_options.php#td_additional" class="btn btn-default">Read More</a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td><img src="pages/index/index/GIF/big_panel_bottom.png" width="993" height="22" alt=""/></td>
          </tr>
        </tbody>
      </table>
      <br><br><br>
      </td>
    </tr>
    <tr>
      <td height="100" align="center" valign="top" background="pages/index/index/GIF/blue_back.png"><table width="1000" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="156" align="left">&nbsp;</td>
            <td width="128" align="left">&nbsp;</td>
            <td width="118" align="left">&nbsp;</td>
            <td width="155" align="left">&nbsp;</td>
            <td width="153" align="left">&nbsp;</td>
            <td width="142" align="left">&nbsp;</td>
            <td width="148" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td width="156" height="30" align="left" valign="top" class="bold_shadow_gri_12">Transactions</td>
            <td width="128" align="left"><span class="bold_shadow_gri_12">Addresses</span></td>
            <td width="118" align="left"><span class="bold_shadow_gri_12">Messages</span></td>
            <td width="155" align="left"><span class="bold_shadow_gri_12">Assets</span></td>
            <td width="153" align="left"><span class="bold_shadow_gri_12">Data Feeds</span></td>
            <td width="142" align="left"><span class="bold_shadow_gri_12">Markets</span></td>
            <td width="148" align="left"><span class="bold_shadow_gri_12">Explorer</span></td>
          </tr>
          <tr>
            <td width="156" height="27" align="left"><a href="#" class="blue_12">My Transactions</a></td>
            <td width="128" height="27" align="left"><a href="#" class="blue_12">My Addresses</a></td>
            <td width="118" height="27" align="left"><a href="#" class="blue_12">Inbox</a></td>
            <td width="155" height="27" align="left"><a href="#" class="blue_12">Browse</a></td>
            <td width="153" height="27" align="left"><a href="#" class="blue_12">Browse Feeds</a></td>
            <td width="142" height="27" align="left"><a href="#" class="blue_12">Phisical Goods</a></td>
            <td width="148" height="27" align="left"><a href="#" class="blue_12">Last Packets</a></td>
          </tr>
          <tr>
            <td width="156" height="27" align="left"><a href="#" class="blue_12">Send Coins</a></td>
            <td width="128" height="27" align="left"><a href="#" class="blue_12">Address Names</a></td>
            <td width="118" height="27" align="left"><a href="#" class="blue_12">Sent</a></td>
            <td width="155" height="27" align="left"><a href="#" class="blue_12">My Assets</a></td>
            <td width="153" height="27" align="left"><a href="#" class="blue_12">My Feeds</a></td>
            <td width="142" height="27" align="left"><a href="#" class="blue_12">Digital Goods</a></td>
            <td width="148" height="27" align="left"><a href="#" class="blue_12">Last Blocks</a></td>
          </tr>
          <tr>
            <td width="156" height="27" align="left"><a href="#" class="blue_12">Escrowed Transactions</a></td>
            <td width="128" height="27" align="left"><a href="#" class="blue_12">Names Market</a></td>
            <td width="118" height="27" align="left">&nbsp;</td>
            <td width="155" height="27" align="left"><a href="#" class="blue_12">Issued Assets</a></td>
            <td width="153" height="27" align="left"><a href="#" class="blue_12">Market Pegged Assets</a></td>
            <td width="142" height="27" align="left"><a href="#" class="blue_12">Services</a></td>
            <td width="148" height="27" align="left"><a href="#" class="blue_12">Browse Addresses</a></td>
          </tr>
          <tr>
            <td width="156" height="27" align="left"><a href="#" class="blue_12">Multisig Transactions</a></td>
            <td width="128" height="27" align="left">&nbsp;</td>
            <td width="118" height="27" align="left">&nbsp;</td>
            <td width="155" height="27" align="left"><a href="#" class="blue_12">Regular Markets</a></td>
            <td width="153" height="27" align="left"><a href="#" class="blue_12">Speculative Markets</a></td>
            <td width="142" height="27" align="left"><a href="#" class="blue_12">Escrowers</a></td>
            <td width="148" height="27" align="left"><a href="#" class="blue_12">Peers</a></td>
          </tr>
          <tr>
            <td width="156" height="27" align="left">&nbsp;</td>
            <td width="128" height="27" align="left">&nbsp;</td>
            <td width="118" height="27" align="left">&nbsp;</td>
            <td width="155" height="27" align="left"><a href="#" class="blue_12">Automated Markets</a></td>
            <td width="153" height="27" align="left"><a href="#" class="blue_12">Bets</a></td>
            <td width="142" height="27" align="left"><a href="#" class="blue_12">Exchangers</a></td>
            <td width="148" height="27" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td width="156" height="27" align="left">&nbsp;</td>
            <td width="128" height="27" align="left">&nbsp;</td>
            <td width="118" height="27" align="left">&nbsp;</td>
            <td width="155" height="27" align="left">&nbsp;</td>
            <td width="153" height="27" align="left">&nbsp;</td>
            <td width="142" height="27" align="left">&nbsp;</td>
            <td width="148" height="27" align="left">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    </tbody>
</table>


  

</body>
</center>
</html>