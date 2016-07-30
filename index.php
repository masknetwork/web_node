<?
   session_start();
   include "./kernel/db.php";
   include "./pages/template/template/CTemplate.php";
   include "./pages/index/index/CIndex.php";
   
   $db=new db();
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

<nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
</div>

<div class="collapse navbar-collapse" id="myNavbar">
<ul class="nav navbar-nav">
<li class="active"><a href="#">Home</a></li>
<li><a href="./pages/tweets/tweets/index.php">Blogs</a></li>
<li class='dropdown open; <? if ($sel==4) print "active"; ?>'>

<a href="./pages/assets/assets/index.php" class="dropdown-toggle" data-toggle="dropdown">Trade<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="./pages/assets/user/index.php">User Issued Assets</a></li>
<li><a href="./pages/assets/assets_mkts/index.php">Assets Markets</a></li>
<li role="separator" class="divider"></li>
<li><a href="./pages/assets/feeds/index.php">Data Feeds</a></li>
<li><a href="./pages/assets/options/index.php">Binary Options</a></li>
</ul></li>     

 <li class='dropdown open;'><a href="./pages/app/directory/index.php" class="dropdown-toggle" data-toggle="dropdown">Applications<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><a href="./pages/app/directory/index.php">Applications Directory</a></li>
            <li><a href="./pages/app/market/index.php">Applications Market</a></li>
            </ul></li>


<li><a href="./pages/explorer/packets/index.php">Explorer</a></li>
<li><a href="./pages/help/help/index.php">Help</a></li>
</ul>
</div>
</div>
</nav>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td height="520" align="center" valign="middle" bgcolor="#263a4f">
      <br><br>
      <table width="1100" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="400"><img src="pages/index/index/GIF/main.jpg" width="500" height="410" alt=""/></td>
            <td width="19">&nbsp;</td>
            <td width="581" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="font-size:32px; font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; color:#ffffff"><strong>The Descentralized Social Trading Network</strong></td>
                </tr>
                <tr>
                  <td style="font-size:18px; font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; color:#b4bfcb">MaskNetwork is a peer to peer decentralized social trading network where users are rewarded for the content they create. The network allows anyone to share or trade any digital good or asset, with no central server and without asking anyone's consent. It's a community of people who want to communicate or trade without restrictions or intermediaries.</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td>&nbsp;</td>
                        <td width="140" align="center"><a href="#" class="btn btn-success" style="width:120px">Signup</a></td>
                        <td width="140" align="center"><a href="#" class="btn btn-danger" style="width:120px">Login</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table>
    
      </td>
    </tr>
    <tr>
      <td height="70" align="center" valign="middle" bgcolor="#1F2F40"><a href="#" style="color:#b4bfcb" class="font_20">MaskCoin real time price <span style="color:#ffffff"><strong>$1.00</strong></span>. Start trading MaskCoins.</a></td>
    </tr>
  </tbody>
</table>
<table width="1100" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="400" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center" bgcolor="#af5040" style="color:#ffffff" background="pages/index/index/GIF/red_panel.png"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td height="201" align="left" valign="middle"><span class="font_14"><strong>Every 24 hours, users are rewarded for their posts, comments or votes. The rewards are distributed by the network, with no third party intervention or approval, similar to how miners are paid by Bitcoin. The rewards are paid in MaskCoins, a limited supply currency that can be exchanged for real money.</strong></span></td>
                </tr>
                </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="600" align="center" valign="top" bgcolor="#fafafa">
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
              <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="21%"><img src="pages/template/template/GIF/mask.jpg" width="75" height="75" class="img img-responsive img-rounded"/></td>
                          <td width="3%">&nbsp;</td>
                          <td width="76%" valign="top"><a href="#" class="font_14">title</a><p class="font_12">fvfdv fd vdf vf fd vfdd vfd vfdv df bf vfd vfd vfd vfd vfdv fd vfdv ddgb dgb fd vfd vdf bfdd ...</p></td>
                        </tr>
                      </tbody>
                    </table>
                    </td>
                  </tr>
                  <tr><td><hr></td></tr>
                  <tr>
                    <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="39%" align="left"><span class="font_16" style="color:#d03f30"><strong>$12</strong></span><span class="font_12" style="color:#d0675c">.32</span></td>
                          <td width="26%" align="center">&nbsp;</td>
                          <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900">32</span></td>
                          <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000">21</span></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table>
            </div>  
            </div>
            <div class="panel panel-default" style="width:90%">
              <div class="panel-body">
                <table width="90%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="21%"><img src="pages/template/template/GIF/mask.jpg" width="75" height="75" class="img img-responsive img-rounded"/></td>
                            <td width="3%">&nbsp;</td>
                            <td width="76%" valign="top"><a href="#" class="font_14">title</a>
                              <p class="font_12">fvfdv fd vdf vf fd vfdd vfd vfdv df bf vfd vfd vfd vfd vfdv fd vfdv ddgb dgb fd vfd vdf bfdd ...</p></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                    <tr>
                      <td><hr></td>
                    </tr>
                    <tr>
                      <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="39%" align="left"><span class="font_16" style="color:#d03f30"><strong>$12</strong></span><span class="font_12" style="color:#d0675c">.32</span></td>
                            <td width="26%" align="center">&nbsp;</td>
                            <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900">32</span></td>
                            <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000">21</span></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="panel panel-default" style="width:90%">
              <div class="panel-body">
                <table width="90%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="21%"><img src="pages/template/template/GIF/mask.jpg" width="75" height="75" class="img img-responsive img-rounded"/></td>
                            <td width="3%">&nbsp;</td>
                            <td width="76%" valign="top"><a href="#" class="font_14">title</a>
                              <p class="font_12">fvfdv fd vdf vf fd vfdd vfd vfdv df bf vfd vfd vfd vfd vfdv fd vfdv ddgb dgb fd vfd vdf bfdd ...</p></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                    <tr>
                      <td><hr></td>
                    </tr>
                    <tr>
                      <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="39%" align="left"><span class="font_16" style="color:#d03f30"><strong>$12</strong></span><span class="font_12" style="color:#d0675c">.32</span></td>
                            <td width="26%" align="center">&nbsp;</td>
                            <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900">32</span></td>
                            <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000">21</span></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="panel panel-default" style="width:90%">
              <div class="panel-body">
                <table width="90%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="21%"><img src="pages/template/template/GIF/mask.jpg" width="75" height="75" class="img img-responsive img-rounded"/></td>
                            <td width="3%">&nbsp;</td>
                            <td width="76%" valign="top"><a href="#" class="font_14">title</a>
                              <p class="font_12">fvfdv fd vdf vf fd vfdd vfd vfdv df bf vfd vfd vfd vfd vfdv fd vfdv ddgb dgb fd vfd vdf bfdd ...</p></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                    <tr>
                      <td><hr></td>
                    </tr>
                    <tr>
                      <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="39%" align="left"><span class="font_16" style="color:#d03f30"><strong>$12</strong></span><span class="font_12" style="color:#d0675c">.32</span></td>
                            <td width="26%" align="center">&nbsp;</td>
                            <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900">32</span></td>
                            <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000">21</span></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="panel panel-default" style="width:90%">
              <div class="panel-body">
                <table width="90%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="21%"><img src="pages/template/template/GIF/mask.jpg" width="75" height="75" class="img img-responsive img-rounded"/></td>
                            <td width="3%">&nbsp;</td>
                            <td width="76%" valign="top"><a href="#" class="font_14">title</a>
                              <p class="font_12">fvfdv fd vdf vf fd vfdd vfd vfdv df bf vfd vfd vfd vfd vfdv fd vfdv ddgb dgb fd vfd vdf bfdd ...</p></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                    <tr>
                      <td><hr></td>
                    </tr>
                    <tr>
                      <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="39%" align="left"><span class="font_16" style="color:#d03f30"><strong>$12</strong></span><span class="font_12" style="color:#d0675c">.32</span></td>
                            <td width="26%" align="center">&nbsp;</td>
                            <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900">32</span></td>
                            <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000">21</span></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            </td>
          </tr>
          </tbody>
      </table></td>
      <td width="600" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">
            
            <table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="66%" align="left" valign="top"><span class="font_20"><strong>What is this website ?</strong></span><p class="font_14">This is what we call a web node. A web node is a website that allows you to access all MaskNetwork features like sending transactions or securing addresses. A web node is the easiest method of using the network. Running a web node is a great way to spread the word about MaskNetwork and make money in the same time. </p></td>
                  <td width="3%" align="left" valign="top"><p class="font_14">&nbsp;</p></td>
                  <td width="31%" valign="top" class="font_20"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="40" align="center" bgcolor="#f0f0f0" class="font_14"><strong>Other webnodes</strong></td>
                      </tr>
                      <tr>
                        <td width="50%" align="center" bgcolor="#fafafa"><a href="#" class="font_14">www.hiddenwallet.info</a></td>
                      </tr>
                      <tr>
                        <td width="50%" align="center" bgcolor="#fafafa"><a href="#" class="font_14">www.maskwallet.com</a></td>
                      </tr>
                      <tr>
                        <td width="50%" align="center" bgcolor="#fafafa"><a href="#" class="font_14">www.coincenter.info</a></td>
                      </tr>
                      <tr>
                        <td align="center" bgcolor="#fafafa"><a href="#" class="font_14">www.maskplace.net</a></td>
                      </tr>
                    </tbody>
                  </table>                    <p class="font_14">&nbsp;</p></td>
                </tr>
               
              </tbody>
            </table>
            
            </td>
          </tr>
          <tr>
            <td height="50" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="32%" align="center" valign="top" bgcolor="#9f2f48"><img src="pages/index/index/GIF/pig.png" width="150" height="157" alt=""/></td>
                  <td width="68%" height="180" bgcolor="#9f2f48" style="color:#ffffff"><span class="font_20"><strong>Built as store of value</strong></span>
                    <p class="font_14">MaskCoin is the network  underling cryptocurrency. The number of MaskCoins that will ever be created is limited to 21 millions. Less than a million coins per year are distributed to miners and content creators every year making MaskCoin a real store of value.</p></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="50" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="78%" valign="top"><span class="font_20"><strong>P2P Binary Options                    </strong></span>                    <p class="font_14">Binary options are based on a simple 'yes' or 'no' proposition: They are one of the simplest financial assets to trade. In the real world binary options are launched by professional regulated brokers. Over Masknetwork binary options are written by users like you and are bought by other regular users. There are no broker fees and no limitations on what you can trade. If there is a data feed then you can launch binary options using that feed, on your own terms.</p></td>
                  <td width="22%" align="right"><img src="pages/index/index/GIF/options.png" width="180" height="211" alt=""/></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="50" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="39%" align="center" valign="top" bgcolor="#479eaf"><table width="90%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="pages/index/index/GIF/code.jpg" width="200" height="154" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="center"><a href="#" class="btn btn-danger" style="width:90%">App Directory</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td width="61%" height="220" bgcolor="#479eaf"><span class="font_20" style="color:#ffffff"><strong>Smart Contracts Enabled</strong></span>                    <p class="font_14" style="color:#ffffff">Smart contracts are pieces of code that run inside MaskNetowrk, without any possibility of censorship, fraud or third party control. You can write smart contracts using MaskNetwork scripting language. When ready, publish your application in one click or set a price and sell it over the decentralized application store.</p></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="50" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="63%" valign="top"><span class="font_20"><strong>Issue your own asset</strong></span>
                    <p class="font_14">User issued assets are a type of custom token which users can hold and trade within certain rectrictions. Unlike MaskCoin, those tokens can be issued by regular users like you. They could represent a virtual share, a proof of membership, a real world currency or anything else.</p></td>
                  <td width="37%" align="right" valign="top"><img src="pages/index/index/GIF/assets.png" width="200" height="206" alt=""/></td>
                  </tr>
              </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>

<?
   $template->showBottomMenu();
?>

</body>
</html>
