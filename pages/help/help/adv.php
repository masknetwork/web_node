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
     $template->showLocation("../../help/help/index.php", "Help", "", "Advertising");
	 
	 $help->showMenu(7);
 ?>
 
 <br><br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td><span class="font_18"><strong>Advertising</strong></span></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Because MaskNetwork is a global market, advertising is essential to be able to function optimally. It is the first system of peer to peer advertising, anonymously and distributed. When you post an advertising message, there is no pre-approval, or conditions relating to content. Nobody will know who is behind an advertising message.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Advertising messages are displayed by the entire network of web wallets, not only on the current wallet. Also, messages can be viewed on desktop wallets. You can also set wallets to display advertisements only to users in a particular country or geographic area.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14">The cost of an advertisement is at least 0.0001 MSK / hour, but the higher the price / hour you offer, the higher your message will be displayed in the list. You can post a message for an unlimited number of hours and you can also change the price offered for display.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><table width="95%" border="0" cellspacing="0" cellpadding="0">
         <tbody>
           <tr bgcolor="#f0f0f0">
             <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
             <td width="84%" valign="middle" class="font_14" bgcolor="#f0f0f0" style="padding-top:10px; padding-bottom:10px; ">Advertising messages are recorded in the distributed database and only you can modify or delete them. Because administrators of Web nodes are legally liable for the content posted on the site, there is the 
               possibility that an advertisement be "hidden" from the display, if the message content is considered illegal or inappropriate by the node’s administrator. But no one can delete that message from the network and the message will still be viewed by those who use the desktop wallet.</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_16"><strong>Posting an ad message</strong></span></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">To post an advertisement, go to any page and click the big yellow button that says Advertise Here. The button is located just below the ads displayed on the right side of any page. The following dialogue will be displayed:</td>
     </tr>
     <tr>
       <td height="30">&nbsp;</td>
     </tr>
     <tr>
       <td height="30" align="center"><img src="GIF/ads.png" class="img-responsive"/></td>
     </tr>
     <tr>
       <td height="30">&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Network Fee Address</strong></span> <span class="font_14">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Title</strong></span> <span class="font_14">- Title  (5-30 caractere)</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Mesaj</strong></span> <span class="font_14">- Ad message (50-70 caractere)</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Link</strong></span><span class="font_14">- The website where the users who click it will be redirected.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Country </strong></span><span class="font_14">- the country where the ad will be displayed.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Hours </strong></span><span class="font_14">- For how many hours it will be displayed.</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><span class="font_14"><strong>Bid </strong></span><span class="font_14">- How much are you willing to give for this message to be published. The more you give, the higher your message will be displayed in the message list.</span></td>
     </tr>
     <tr>
       <td height="50">&nbsp;</td>
     </tr>
     <tr>
       <td><strong class="font_16">Changing the bid price</strong></td>
     </tr>
     <tr>
       <td background="../../template/template/GIF/lp.png">&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">Once you published a message, it could lose the position on which it was originally displayed as other addresses could offer more. To change the bid price, click the red button next to the "Advertise Here" button. This button is found in the wallet page, just below the list of ads on the right side.</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td class="font_14">The page with the ads posted by you will be displayed. Click the yellow button next to an ad and select "Increase Market Bid". The following dialogue will be displayed:</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td align="center"><img src="GIF/ads_bid.png" class="img-responsive"/></td>
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
       <td><span class="font_14"><strong>New Bid </strong></span><span class="font_14">- The new price that you want to offer</span></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
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



