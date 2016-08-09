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

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_msk { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_12 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}

.font_141 {font-size:22px;  }
</style>

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
     $api->showKeyLeftMenu(1);
  ?>
  
   </td>
   <td width="78%" align="center" valign="top"><table width="90%" border="0" cellpadding="0" cellspacing="0">
     <tbody>
       
       <tr>
         <td height="40" align="left" valign="top" class="font_18"><strong>Overview</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14">MaskNetwork wallet key API allows users to execute actions that involves funds or other account changes. In order to access the key API, you will need an aPI Key. You can get an API key by going to <? if ($_REQUEST['ud']['ID']>0) print "<a href=\"../../profile/API/index.php\">Profile / API</a>"; else print "Profile / API"; ?>, in your account. You can revoke / renew the key anytime you want.</td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="font_14">Please note that making more than 5 calls per second to the public API, or repeatedly and needlessly fetching excessive amounts of data, can result in your IP being banned.  If you require more than this, please consider optimizing your application or contact support to discuss a limit raise.</td>
       </tr>
       <tr>
         <td align="left" class="font_14">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="font_14">All calls to the trading API are sent via HTTP POST to <strong>https://www.mskwallet.com/pages/api/api.php</strong> and must contain the following parameters</td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>
             <tr>
               <td width="18%" align="center" class="font_14"><strong>req</strong>
                 <p class="font_10">required</p></td>
               <td width="2%">&nbsp;</td>
               <td width="80%" class="font_14">The request type. For example ID_SEND_TRANS will initiate a transaction.</td>
             </tr>
             <tr>
               <td colspan="3" align="center"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>key</strong>
                 <p class="font_10">required</p></td>
               <td>&nbsp;</td>
               <td class="font_14">Your API key.</td>
             </tr>
             <tr>
               <td colspan="3" align="center" class="font_14"><hr></td>
             </tr>
           </tbody>
         </table></td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td height="0" align="left" valign="top" class="font_16"><strong>Examples</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14"><hr></td>
       </tr>
       <tr>
         <td height="40" align="left" class="font_14">Sends 10 MSK to address john from address maria.</td>
       </tr>
       <tr>
         <td height="50" align="center" bgcolor="#fafafa" class="font_14"><strong>https://www.mskwallet.org/pages/api/key.php?req=ID_SEND_TRANS&amp;key=3ED2-A3CA-2669-328E-1050&amp; src=maria&amp;dest=john&amp;amount=10&amp;cur=MSK</strong></td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left"><strong>Returned Data</strong></td>
       </tr>
       <tr>
         <td align="left"><hr></td>
       </tr>
       <tr>
         <td align="left" class="font_14">The data is returned in JSON format. Returned data depends on request type. check requests documentation for more details.</td>
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
