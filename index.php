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

<style>
@media only screen and (max-width: 1000px)
{
  .txt_title { font-size:60px; }
  .txt_expl { font-size:30px; color:#d0e4f7; }
  .presale_txt { font-size:40px; }
  .img_book_left { display: block; }
  .txt_title_inverse { font-size:60px; }
  .txt_expl_inverse { font-size:30px; }
  .txt_title_inverse_small { font-size:44px; }
  .txt_title_inverse_24 { font-size:44px; }
  .txt_expl_inverse_small { font-size:26px; }
  .txt_title_medium { font-size:50px;  padding-top:40px; }
  .txt_expl_medium { font-size:26px;  }
  .font_16 { font-size:26px;  }
  a.yellow_link { font-size:30px; }
  
  #but_signup { height:100px; font-size:50px; }
  #but_signin { height:100px; font-size:50px; }
  #presale_but { height:60px; font-size:30px; }
  #img_book_right { display: none; }
  #but_read_more_book { height:80px; font-size:40px; }
  
  #img_escrow_left { display: block; }
  #img_escrow_right { display: none; }
  
  .font_18 { font-size:35px; }
}

</style>

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
<li><a href="./pages/tweets/tweets/index.php">Tweets</a></li>
<li class='dropdown open; <? if ($sel==4) print "active"; ?>'>

<a href="./pages/assets/assets/index.php" class="dropdown-toggle" data-toggle="dropdown">Trade<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="./pages/assets/options/index.php">Binary Options</a></li>
<li><a href="./pages/assets/margin_mkts/index.php">Margin Markets</a></li>
<li><a href="./pages/assets/pegged_assets/index.php">Market Pegged Assets</a></li>
<li role="separator" class="divider"></li>
<li><a href="./pages/assets/user/index.php">User Issued Assets</a></li>
<li><a href="./pages/assets/assets_mkts/index.php">Assets Markets</a></li>
<li role="separator" class="divider"></li>
<li><a href="./pages/assets/exchangers/index.php">Assets Exchangers</a></li>
<li><a href="./pages/assets/feeds/index.php">Data Feeds</a></li>
</ul></li>     
            
<li class='dropdown open; <? if ($sel==5) print "active"; ?>'><a href="./pages/shop/goods/index.php" class="dropdown-toggle" data-toggle="dropdown">Shop<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="./pages/shop/goods/index.php">Goods and Services</a></li>
<li><a href="./pages/shop/escrowers/index.php">Escrowers</a></li>
</ul></li>
<li><a href="./pages/explorer/packets/index.php">Explorer</a></li>
<li><a href="./pages/help/help/index.php">Help</a></li>
</ul>
</div>
</div>
</nav>

 <div class="container-fluid">
 <div class="row" style="padding-left:30px; padding-top:100px; padding-bottom:50px; background-color:#2e4f71">
 <div class="col-md-5" align="center"><img src="./pages/index/index/GIF/main.jpg" class="img-responsive"></div>
 <div class="col-md-7">
 <p class="txt_title">The Descentralized Social Trading Network </p>
 <p class="txt_expl">MaskNetwork is a peer to peer decentralized social network / marketplace, where you can share or trade any product or digital goods, with no central server and without asking anyone's consent, outside the control of an individual or group. MaskNetwork is a community of people who want to communicate and trade without restrictions, intermediaries or huge taxes imposed by traditional companies. </p><br>
 
 
 <a href="./pages/account/signup/index.php" class="btn btn-lg btn-success"  id="but_signup">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Testers Needed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 
 <a href="./pages/account/login/index.php" class="btn btn-lg btn-warning" id="but_signin">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#000000">Sign In</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
 
 <br><br>
 </div>
 </div>
 
 <div class="row" style="background-color:#1c4369">
 <br>
 <table align="center">
 <tr><td><h4 class="presale_txt">Presale has started. Buy real MSK.</h4></td>
 <td width="20px">&nbsp;</td>
 <td><a href="http://www.masknetwork.com" class="btn btn-danger" id="presale_but">Buy Now</a></td></tr>
 </table>
 <br>
 </div>

 <br><br>
 <div class="row" style="padding-left:30px; padding-bottom:30px;">
 
 <div class="col-md-5; img_book_left" align="center" id="img_book_left" name="img_book_left">
   <img src="pages/index/index/GIF/book.jpg" class="img-responsive">
 </div>
 
 <br>

 <div class="col-md-4" align="center" style="padding-top:30px; padding-right:60px;" id="img_book_right">
    <img src="pages/index/index/GIF/book.jpg" class="img-responsive">
    <a type="button" href="./pages/help/help/index.php" class="btn btn-lg btn-info" id="but_read_more_book" style="width:100%">Read More</a>
 </div> 
 <div class="col-md-4">
    <p class="txt_title_inverse">What is <? print $_SERVER['SERVER_NAME']; ?> ?</p>
    <p class="txt_expl_inverse"><? print $_SERVER['SERVER_NAME']; ?> is what we call a web node. A web node is a website that allows you to access all MaskNetwork features like sending transactions or securing addresses. A web node is the easiest method of using the network. Running a web node is a great way to spread the word about MaskNetwork and because web node operators can impose a fee on all transactions, it is also a great way to make money.</p>
    <br>
    
</div>
<div class="col-md-1" style="height:100px">&nbsp;</div>
<div class="col-md-3">
    <p class="txt_title_inverse_24">Check other web nodes</p>
    <p><hr></p>
    <a class="font_18" href="http://www.hiddenwallet.org" target="_blank">www.hiddenwallet.org</a>
    <p><hr></p>
    <a class="font_18" href="http://www.maskplace.net" target="_blank">www.maskplace.net</a>
    <p><hr></p>
    <a class="font_18" href="http://www.maskwallet.com" target="_blank">www.maskwallet.com</a>
    <p><hr></p>
    <a class="font_18" href="http://www.maskcentral.info" target="_blank">www.maskcentral.info</a>
    <p><hr></p>
    <a class="font_18" href="http://www.hidenmarket.info" target="_blank">www.hidenmarket.info</a>
 
</div>

 
 </div>
 <br><br>
 
 <!-- --------------------------------------------------- SPECULATIVE -------------------------------------------------------------------->
 
 <div class="row" style="padding-left:30px; padding-top:30px; padding-bottom:50px; padding-right:50px; background-color:#2e4f71">>
 <div class="col-md-7" style="padding-left:30px"><p class="txt_title">Decentralized margin trading</p>
 <p class="txt_expl">Trade anything. Currencies, stocks, cryptocoins, outside temperature, you name it. If there is a data feed, you can trade it on margin. No central server means that no regulated company is behind the scene. No regulation means, huge leverages, minimum spreads and no minimum trade size. Don't like to trade ? Then start your own leveraged markets and allow others to trade. Masknetwork is the first truly p2p deceentralized trading platform. </p>
  <a type="button" href="./pages/help/help/index.php" class="btn btn-lg btn-danger" id="but_read_more_margin" style="width:20%">Read More</a>
 </div>
 
 <br>
 <div class="col-md-5" align="center">
 <div class="panel panel-default">
 <div class="panel-body">
 
 <?
    $index->showTopTrades();
 ?>
 
 </div></div></div></div>
 
 
  
 
 <!-- --------------------------------------------------- BETS -------------------------------------------------------------------->
 <div class="row" style="padding-left:30px; padding-bottom:30px;">
 <br><br>
 <div class="col-md-5" align="center">
 <div class="panel panel-default">
 <div class="panel-body">
 <?
    $index->showTopBets();
 ?>
 </div></div></div>
 
 

 <div class="col-md-7">
    <p class="txt_title_inverse">P2P Binary Options</p>
    <p class="txt_expl_inverse">Binary options are based on a simple 'yes' or 'no' proposition: Will an underlying asset be above a certain price at a certain time ?   Traders place trades based on whether they believe the answer is yes or no, making it one of the simplest financial assets to trade. In the real world binary options are launched by professional regulated brokers and usually traders have no chance of long time profits, because they are betting against the house and the house always wins. Over Masknetwork binary options are written by users like you and are bought by other regular users. There are no broker fees and no limitations on what you can trade. If there is a data feed then you can launch binary options using that feed, on your own terms. </p>
    <br>
</div>
 
 </div></div>
 <br><br>
 
 
 <!-- --------------------------------------------------- SMART ASSETS -------------------------------------------------------------------->
 
 <div class="row" style="padding-left:30px; padding-top:30px; padding-bottom:50px; padding-right:50px; background-color:#2e4f71">>
 <div class="col-md-7" style="padding-left:30px"><p class="txt_title">Market pegged assets</p>
 <p class="txt_expl">MaskNetwork market pegged assets are a new type of freely traded digital asset whose value is meant to track the value of a conventional asset such as the U.S. dollar or gold. Market pegged assets can be issued by regular users like you. While Bitcoin for example has demonstrated many useful properties as a currency, its price volatility makes it risky to hold especially for merchants. A currency with the properties and advantages of Bitcoin that maintains price parity with a globally adopted currency such as the US dollar has high utility for a decentralized eCommerce platform like MaskNetwork. Anyway market pegged assets are not limited to stable currencies like USD. Any user can launch a market pegged token, linked to any real life asset like Apple shares or Bitcoin. </p>
  <a type="button" href="./pages/help/help/index.php" class="btn btn-lg btn-danger" id="but_read_more_margin" style="width:20%">Read More</a>
 </div>
 
 <br>
 <div class="col-md-5" align="center">
 <div class="panel panel-default">
 <div class="panel-body">
 
 <?
    $index->showTopAssets();
 ?>
 
 </div></div></div></div>
 
 
  
 
 <!-- --------------------------------------------------- EBAY -------------------------------------------------------------------->
 <div class="row" style="padding-left:30px; padding-bottom:30px;">
 <br><br>
 <div class="col-md-4" align="center">
 <img src="./pages/index/index/GIF/shop.jpg" class="img img-responsive">
 </div>
 
 

 <div class="col-md-8">
    <p class="txt_title_inverse">Ebay with no central server</p>
    <p class="txt_expl_inverse">Masknetwork is the first truly web based decentralized peer-to-peer marketplace. It's the eBay with no central server, no restrictions on what you can sell or buy. Because there's no company or organization running MaskNetwork, there's no one to charge you fees to list your products and no one to register an account with. You don't have to download anything or maintain a web server. Just open an eStore, post your products or services and start selling in minutes. It's you and your customers with no thirsd party. No terms of service, no PayPal as the only payment processor. You can accept any type of payment you want. Maskcoins, assets, market-pegged assets, you name it. Whant to sell books for Microsoft shares ? It's possible.</p>
    <br>
</div>
 
 </div></div>
 <br><br>
 
 
 <!-- --------------------------------------------------- RELAX -------------------------------------------------------------------->
 <div class="row" style="padding-left:30px; padding-top:30px; padding-bottom:50px; padding-right:50px; background-color:#2e4f71">
 <br><br>
 <div class="row">
 <div class="col-md-5" align="center"><img src="pages/index/index/GIF/relax.jpg" class="img-responsive"></div>
 <div class="col-md-7" style="padding-left:30px"><p class="txt_title">Relax. Everything is under your control.</p>
 <p class="txt_expl">We all know how important the security of an anonymous peer to peer network is, such as Bitcoin network for example. One of the most difficult aspects of Bitcoin is the safekeeping of private keys that control the funds you own. In MaskNetwork we brought multiple technical innovations by means of which your funds are safe even if the private key of an address has been compromised. Nobody can steal funds from a protected address.</p>
 </div></div>
 
  <div class="row" style="padding-left:20px; padding-right:20px; ">
  <br><div class="col-md-3" style="padding-top:20px"><span class="txt_title_inverse_small">Frozen Addresses</span><br>
  <span class="txt_expl_inverse_small">You can freeze an address for a period. From a frozen address one cannot spend funds. If an attacker takes control of the address he/she will not be able to spend funds for as long as the option is active.</span><br><a href="./pages/help/help/adr_options.php#cap_froze" class="yellow_link">read more...</a>
  <br><br>
  </div>
  
  <div class="col-md-3" style="padding-top:20px"><span class="txt_title_inverse_small">Restricted Recipients</span><br>
  <span class="txt_expl_inverse_small">By activating this option you will be able to send funds from the address only to a group of up to five other addresses. If an attacker takes control of the address, he/she can only send funds than to a specified group of addresses.</span><br><a href="./pages/help/help/adr_options.php#cap_restrict" class="yellow_link">read more...</a>
  </div>
  
   <div class="col-md-3" style="padding-top:20px"><span class="txt_title_inverse_small">Multisignatures</span><br>
  <span  class="txt_expl_inverse_small">A multi-signature address is an address that is associated with more than one private key. Spending funds from a multisig address requires signatures from up to 5 other addresses.</span><br><a href="./pages/help/help/adr_options.php#cap_multi" class="yellow_link">read more...</a>
  </div>
  
  <div class="col-md-3" style="padding-top:20px"><span class="txt_title_inverse_small">One Time Passwords</span><br>
  <span class="txt_expl_inverse_small">A one-time password (OTP) is a passwordthat is valid for only one transaction, on a computer system or other digital device. Spending funds from an OTP address requires an unique password.</span><br><a href="./pages/help/help/adr_options.php#cap_otp" class="yellow_link">read more...</a>
  </div>
  
  </div>
  </div>
 
 <br><br>
 <div class="row" style="padding-left:30px; padding-bottom:30px;">
 
 <div class="col-md-5; img_book_left" align="center" id="img_book_left" name="img_book_left">
   <img src="pages/index/index/GIF/escrow.jpg" class="img-responsive">
 </div>
 
 <div class="col-md-7">
 <span class="txt_title_inverse">Integrated Escrow System</span><br>
 <span class="txt_expl_inverse">In an anonymous market as MaskNetwork, there will always be cases of fraud in which paid products never reach the destination, especially since any payment is irreversible. For this reason we have integrated an escrow system across the network. When sending funds to an address, you can specify a different address as escrower. When you receive the goods, the money is delivered to sellers by the escrow address. Any dispute will be resolved by the escrow account and you can be sure that you will always receive your products.</span><br>
<br>
<a type="button" href="./pages/help/help/transactions.php#td_escrow" class="btn btn-lg btn-info" id="but_read_more_book">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Read More&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
 </div>
 
 <div class="col-md-5" align="center" style="padding-top:30px; padding-right:60px;" id="img_escrow_right">
 <img src="pages/index/index/GIF/escrow.jpg" class="img-responsive">
 </div>
 
 </div>
 <br><br>
 </div>
 
 <div class="row" style="background-color:#1c4369">
 <br>
 <div class="txt_title_medium" align="center">Communicate efficiently with clients</div>
 <div class="txt_expl_medium" align="center" style="padding-left:50px; padding-right:50px; ">For any business, close communication with clients and advertising are essential. Therefore we have integrated in MaskNetwork a unique advertising system which along with messaging will allow you to sell more effectively. You can post advertising messages and you can communicate effectively, fast, anonymous and without the approval of a person or group.</div>
 <br>
 </div>
 
 <div class="row" style="background-color:#2e4f71; padding-left:30px; padding-right:30px; padding-bottom:20px" align="center">
 <br>
 
 <div class="col-md-1" align="center">&nbsp;</div>
 
 <div class="col-md-5" align="left">
 <div class="row">
 <div class="col-md-5"><img src="pages/index/index/GIF/ads.png" class="img-responsive"></div>
 <div class="col-md-7"><div class="txt_title_medium">P2P Advertising</div><div class="txt_expl_medium">The integrated advertising network system is the first of its kind in the world. It allows you to post anonymous messages that will reach thousands of potential customers whatever wallet they use. You can also restrict the viewing of messages to a specific geographic area.</div><a href="./pages/help/help/adv.php" class="yellow_link">read more...</a></div>
 </div></div>
 
 
 
 <div class="col-md-5" align="center">
  <div class="row">
 <div class="col-md-5"><img src="pages/index/index/GIF/mes.png" class="img-responsive"></div>
 <div class="col-md-7" align="left"><div class="txt_title_medium" align="left">Encrypted Messages</div><div class="txt_expl_medium" align="left">The messaging system allows you to send anonymous and encrypted messages to any address in the network. Because the messages are encrypted using the public key of the target address, only the address owner can read the message, even if he/she travels hundreds of nodes. </div><a href="./pages/help/help/messages.php" class="yellow_link">read more...</a></div>
 </div></div>

 <div class="col-md-1" align="center">&nbsp;</div>
 </div>
 <br>
 </div>
 

<br><br>
 <div class="row" style="padding-left:30px; padding-bottom:30px;">
 
 <div class="col-md-5" align="center" id="img_book_left" name="img_book_left">
   <img src="pages/index/index/GIF/interest.png" class="img-responsive">
 </div>
 
 <br>
 
 <div class="col-md-7">
    <p class="txt_title_inverse">Receive Interest Every Day</p>
    <p class="txt_expl_inverse">MasckCoin is the cryptographic currency underlying the network. The most famous current cryptographic currency is Bitcoin, which is generated through an expensive process requiring specialized hardware. To be able to "mine" Bitcoin needs serious investment. MaskCoin is generated mainly by interest. Basically, any account that has a balance of at least 10 MSK, will receive each day an interest the rate of which is set by the network based on the number of coins in circulation.  All you have to do to cash the interest is to open an account with an address in which to permanently maintain 10 MSK. </p>
    <br>
    <a type="button" href="./pages/help/help/index.php#td_interest" class="btn btn-lg btn-info" id="but_read_more_book">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Read More&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
</div>

 
 </div>
 <br><br>
 

 </div>
 
 <?
   $template->showBottomMenu();
 ?>
  
</body>
</html>
