<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAPI.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $api=new CAPI($db, $template);
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



</head>

<body>

<?
   $template->showTopBar("help");
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
     $template->showLocation("../../assets/assets/index.php", "Assets", "", "Assets");
	 
	// Browser
    $api->showMenu(2);
 ?>
 
 <br>
 <table width="90%">
 <tr><td width="22%" valign="top">
  
  <?
     $api->showKeyLeftMenu(2);
  ?>
  
   </td>
   <td width="78%" align="center" valign="top"><table width="90%" border="0" cellpadding="0" cellspacing="0">
     <tbody>
       <tr>
         <td height="40" align="left" valign="top" class="font_18"><strong>Send Coins</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14"><hr></td>
       </tr>
       <tr>
         <td align="left" class="font_16">This call will send MaskCoins / Other Assets from one of your addresses to any another address. You can also include a comment and / or an escrower. The receiver pays a fee of 0.1%. The sender will pay the network transport fee (minimum 0.0001 MSK)</td>
       </tr>
       <tr>
         <td align="center">&nbsp;</td>
       </tr>
       <tr>
         <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>
             <tr>
               <td align="center"><strong class="font_14">net_fee_adr</strong>
                 <p class="font_10">String</p></td>
               <td class="font_14">Network fee address. The address that will pay the network trasnport fee. The network transport fee is minimum 0.0001 MSK. It's paid by sender for any packet that traverse the network, no matter the payload type.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
               </tr>
             <tr>
               <td width="22%" align="center"><strong class="font_14">from_adr</strong>
                 <p class="font_10">String</p></td>
               <td width="78%" class="font_14">Source address. You need to specify one of your addresses. It can be an address name.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">to_adr</strong>
                 <p class="font_10">string</p></td>
               <td class="font_14">Destination address. It can be an address name.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">amount</strong>
                 <p class="font_10">double</p></td>
               <td class="font_14">Amount that will be send. If you send MSK, the minimum amount is 0.0001 MSK. </td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">amount_asset</strong>
                 <p class="font_10">double</p></td>
               <td class="font_14">In case you send an asset, you need to use this field to specify the amount. For assets the minimum amount is 0.00000001</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">cur</strong>
                 <p class="font_10">string</p></td>
               <td class="font_14">Currency. In case you send MaskCoins, specify MSK. In case you send an asset you need tp pass the asset symbol (6 characters).</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">mes</strong>
                 <p class="font_10">string</p></td>
               <td class="font_14">Optional. A short message. The message is encrypted and only the receiver can decrypt it. Keep in mind that if the transaction sends coins to an application, the message will not be encrypted.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">escrower</strong>
                 <p class="font_10">string</p></td>
               <td align="left" class="font_14">Optional. You can specify an escrower. It can be an address name.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
           </tbody>
         </table></td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="font_16"><strong>Samples :</strong></td>
       </tr>
       <tr>
         <td align="left"><hr></td>
       </tr>
       <tr>
         <td align="left" class="font_14" height="40px">Sends 100 MSK from address name maria to address name john with the message &quot;thank you&quot;</td>
       </tr>
       <tr>
         <td align="center" class="font_16" bgcolor="#f0f0f0" height="70px"><strong>http://localhost/wallet/pages/api/api.php?act=ID_SEND_COINS&amp;net_fee_adr=maria&amp; from_adr=maria&amp;to_adr=john&amp;amount=100&amp;cur=MSK&amp;mes=Thank you</strong></td>
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
