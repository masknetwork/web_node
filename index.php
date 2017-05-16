<?
   session_start();
   
   include "./kernel/db.php";
   include "./kernel/CSysData.php";
   include "./pages/template/template/CTemplate.php";
   include "./pages/index/index/CIndex.php";
   
   $db=new db();
   $sd=new CSysData($db);
   $template=new CTemplate($db);
   $index=new CIndex($db, $template);
   
   if ($_REQUEST['act']=="logout") unset($_SESSION['userID']);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>MaskNetwork Web Node</title>
<script src="./flat/js/vendor/jquery.min.js"></script>
<script src="./flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href=".//flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="./flat/css/flat-ui.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link rel="shortcut icon" href="./flat/img/favicon.ico">


</head>

<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td height="770" align="center" valign="top" background="pages/index/index/GIF/main.jpg" style="background-position:center">
      <table width="1200" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td height="55" align="center"><table width="1200" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="10%">&nbsp;</td>
                  <td width="8%">&nbsp;</td>
                  <td width="10%" align="center"><a href="./pages/assets/user/index.php" class="font_16" style="color:#FFFFFF">Assets</a></td>
                  <td width="12%" align="center"><a href="./pages/tweets/tweets/index.php" class="font_16" style="color:#FFFFFF">Blogs</a></td>
                  <td width="12%" align="center"><a href="./pages/assets/options/index.php" class="font_16" style="color:#FFFFFF">Binary Options</a><a href="./pages/assets/margin_mkts/index.php" class="font_16" style="color:#FFFFFF"></a></td>
                  <td width="10%" align="center"><a href="./pages/assets/margin_mkts/index.php" class="font_16" style="color:#FFFFFF">Margin Markets</a><a href="./pages/explorer/blocks/index.php" class="font_16" style="color:#FFFFFF"></a></td>
                  <td width="10%" align="center"><a href="./pages/explorer/blocks/index.php" class="font_16" style="color:#FFFFFF">Explorer</a><a href="./pages/help/faq/index.php" class="font_16" style="color:#FFFFFF"></a></td>
                  <td width="10%" align="center"><a href="./pages/help/faq/index.php" class="font_16" style="color:#FFFFFF">Help</a></td>
                  <td width="8%">&nbsp;</td>
                  <td width="10%"><a href="./pages/account/login/index.php" class="font_16"><span class="glyphicon glyphicon-pencil">&nbsp;</span>Sign In</a></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="180" align="right" valign="bottom" class="font_30" style="color:#ffffff"><table width="500" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center"><span style="color:#ffffff; font-size:30px; font-weight:bold; text-shadow:2px 2px 2px #000000">Start trading with no central server or broker</span></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="100" align="right" valign="middle"><table width="500" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center"><table width="100%" border="0">
                    <tbody>
                        <tr>
                          <td><span style="color:#ffffff; font-size:18px;">MaskNetwork is a peer to peer decentralized social trading network where users are rewarded for the content they create. MaskNetwork pays both the content creators like bloggers or traders when their work gets upvoted, as well as the people who curate the best content on the site by upvoting others work. No central server means no regulation and free trade of any asset.</span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="40" style="color:#deffde; font-size:14px"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;Trade cryptocoins, forex, indices or any other asset for which a data feed exist</td>
                        </tr>
                        <tr>
                          <td height="40" style="color:#deffde; font-size:14px"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;Write your own binary options or invest in existing options launched by other users.</span></td>
                        </tr>
                        <tr>
                          <td height="40" style="color:#deffde; font-size:14px"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;No central authority means no minimum margin, huge leverage and total transparency</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="75" align="right" valign="bottom"><a href="./pages/account/signup/index.php" class="btn btn-success btn-lg">Open an account</a></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#0c1126" class="font_16" style="color:#ffffff" height="50px">MaskCoin real time price $1 - <a href="http://www.masknetwork.com">Buy MaskCoins</a> </td>
    </tr>
    <tr>
      <td height="150" align="center" bgcolor="#eaf3ff"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center" class="font_14" height="100px" style="color:#292e37">Every 24 hours, bloggers and traders are rewarded for their content like posts, comments, binary options, assets or margin markets. Voters are also rewarded. The rewards are distributed by the network, with no third party intervention or approval, similar to how miners are paid by Bitcoin. The rewards are paid in MaskCoins, a limited supply currency that can be exchanged for real money.</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="200" align="center" bgcolor="#fafafa">
      
	  <?
	     $index->showLastPosts();
      ?>
      
      </td>
    </tr>
    <tr>
      <td height="500" align="center" bgcolor="#494949"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="318"><img src="pages/index/index/GIF/assets.jpg" width="300" height="344" alt=""/></td>
            <td width="682" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left" class="font_30" style="color:#ffffff; font-weight:bold">Issue your own asset</td>
                  </tr>
                <tr>
                  <td align="left" style="color:#ffffff; font-size:20px">User issued assets are a type of custom token which users can hold and trade with no. Unlike MaskCoins, those tokens can be issued by regular users like you. They could represent a virtual share, a proof of membership, a real world currency or anything else. The network rewards assets issuers every single day.</td>
                  </tr>
                <tr>
                  <td height="60" align="left" valign="bottom"><a class="btn btn-primary" href="./pages/assets/user/index.php">Find More</a></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="500" align="center"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="761" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" class="font_30" style="color:#999999; font-weight:bold">Binary Options</td>
                </tr>
                <tr>
                  <td align="left" style="color:#999999; font-size:16px">In finance, a binary option is a type of option in which the payoff can take only two possible outcomes, either some fixed monetary amount or nothing at all. Over MaskNetwork you can issue your own binary options or invest in existing options launched by other users. Because there is no central server, you can bet on anything for which a data feed exist. The network rewards both options issuers and buyers every 24 hours.</td>
                </tr>
                <tr>
                  <td height="60" align="left" valign="bottom"><a class="btn btn-primary" href="./pages/assets/options/index.php">Find More</a></td>
                </tr>
              </tbody>
            </table></td>
            <td width="239" align="right"><img src="pages/index/index/GIF/options.jpg" height="322" alt=""/></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="500" align="center" bgcolor="#f0f0f0"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="267"><img src="pages/index/index/GIF/margin.jpg" width="232" height="370" alt=""/></td>
            <td width="733" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" class="font_30" style="color:#999999; font-weight:bold">Margin Markets</td>
                </tr>
                <tr>
                  <td align="left" style="color:#999999; font-size:16px">In the real world trading on margin means borrowing money from your broker to buy a stock and using your investment as collateral. Usually investors will pay interest for borrowed money. Over MaskNetwork, a margin market alows you to place leveraged bets against the market owner. Margin markets prices are provided by data feeds. All your losses are market owner's gains and vice versa. Not only that traders don't pay any interest but margin market operators are rewarded by network every 24 hours. Start your own decentralized market today.</td>
                </tr>
                <tr>
                  <td height="60" align="left" valign="bottom"><a class="btn btn-primary" href="./pages/assets/margin_mkts/index.php">Find More</a></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="200" align="center" bgcolor="#494949"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="30%" align="center"><img src="pages/index/index/GIF/open_source.png" width="60" height="46" alt=""/></td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center"><img src="pages/index/index/GIF/shield.png" width="42" height="50" alt=""/></td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center"><img src="pages/index/index/GIF/web_based.png" width="57" height="50" alt=""/></td>
          </tr>
          <tr>
            <td width="30%" height="40" align="center" style="font-size:20px; color:#ffffff">Open Source</td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center"><span style="font-size:20px; color:#ffffff">Secure</span></td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center"><span style="font-size:20px; color:#ffffff">Web Based</span></td>
          </tr>
          <tr>
            <td width="30%" align="center" style="font-size:14px; color:#ffffff">MaskNetwork will always be a 100% open source project. Check it on <a href="#" style="color:#F0F0F0">GitHub</a></td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center" style="font-size:14px; color:#ffffff">Masknetwork uses an inovatime POW algorithm based on 16 hash functions.</td>
            <td width="5%" align="center">&nbsp;</td>
            <td width="30%" align="center"><span style="font-size:14px; color:#ffffff">Nothing to download. Open an account and start trading using any node.</span></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="500" align="center"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="761" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" class="font_30" style="color:#999999; font-weight:bold">Build as store of value</td>
                </tr>
                <tr>
                  <td align="left" style="color:#999999; font-size:16px">MaskCoin the cryptocurrency  that powers up the network. Users need maskcoins for every message they send over the network. MaskCoin has been designed as a deflationary currency, so it has a strictly limited money supply. The number of MaskCoins that will ever be created is limited to 21 millions. Broadly speaking, a deflationary currency is one that increases in value over time. Goods and services priced in a deflationary currency will therefore tend to reduce in price - all other things being equal.</td>
                </tr>
                <tr>
                  <td height="60" align="left" valign="bottom"><a class="btn btn-primary" href="http://www.masknetwork.com">Find More</a></td>
                </tr>
              </tbody>
            </table></td>
            <td width="239" align="right"><img src="pages/index/index/GIF/pig.png" width="236" height="360" alt=""/></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#f0f0f0">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#f0f0f0"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="48%" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center"><img src="pages/index/index/GIF/mes.png" width="150" height="149" alt="" class="img img-circle"/></td>
                </tr>
                <tr>
                  <td height="50" align="center" style="color:#999999" class="font_20"><strong>Built-in Secure Messaging</strong></td>
                </tr>
                <tr>
                  <td align="center" style="color:#999999;" class="font_16">One of the most important features of the network is the messaging system. You can send messages to any address and any address can send you messages. Even if it crosses the entire network no one can see the message content. All messages are encrypted and only the recipient can decrypt the content. You can use any web node to send / receive messages.</td>
                </tr>
              </tbody>
            </table></td>
            <td width="4%" align="center">&nbsp;</td>
            <td width="48%" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td height="120" align="center"><img src="pages/index/index/GIF/escrow.png" width="150" height="150" alt="" class="img img-circle"/></td>
                </tr>
                <tr>
                  <td height="50" align="center" style="color:#999999" class="font_20"><strong>Integrated Escrow System</strong></td>
                </tr>
                <tr>
                  <td align="center" style="color:#999999;" class="font_16">Over an anonymous soacial network as MaskNetwork, there will always be cases of fraud in which paid products never reach the destination, especially since any payment is irreversible. For this reason we have integrated an escrow system across the network. When sending funds to an address, you can specify a different address as escrower. </td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="40" align="center" bgcolor="#f0f0f0">&nbsp;</td>
    </tr>
    <tr>
      <td height="400" align="center"><table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="340"><img src="pages/index/index/GIF/book.jpg" width="300" height="304" alt=""/></td>
            <td width="660" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" class="font_30" style="color:#999999; font-weight:bold">What is this website ?</td>
                </tr>
                <tr>
                  <td align="left" style="color:#999999; font-size:16px">This is what we call a web node. A web node is a website that allows you to access all MaskNetwork features like sending transactions or securing addresses. A web node is the easiest method of using the network. Running a web node is a great way to spread the word about MaskNetwork and make money in the same time.</td>
                </tr>
                <tr>
                  <td height="60" align="left" valign="bottom"><a class="btn btn-primary" href="http://www.masknetwork.com">Find More</a></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="40" align="center" bgcolor="#f0f0f0">&nbsp;</td>
    </tr>
  </tbody>
</table>
<?
   $template->showBottomMenu(true);
?>

</body>
</html>
