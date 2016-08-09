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
    $api->showMenu();
 ?>
 
 <br>
 <table width="90%">
 <tr><td width="22%" valign="top">
  
  <?
     $api->showLeftMenu(1);
  ?>
  
   </td>
   <td width="78%" align="center" valign="top"><table width="90%" border="0" cellpadding="0" cellspacing="0">
     <tbody>
       
       <tr>
         <td height="40" align="left" valign="top" class="font_18"><strong>Overview</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14">MaskNetwork wallet API allows anybody to access public data like the address list, balances or the last published tweets. You don't need an API keyto access the information presented in this section. If you want to send transactions or use other account functions, please refer to Private API section. </td>
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
         <td align="left" class="font_14">All calls to the trading API are sent via HTTP POST to <strong>https://www.mskwallet.com/pages/api/public.php</strong> and must contain the following parameters</td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>
             <tr>
               <td width="18%" align="center" class="font_14"><strong>table</strong><p class="font_10">required</p></td>
               <td width="2%">&nbsp;</td>
               <td width="80%" class="font_14">The table you want to access. There are over 20 tables containing data such as addresses balances or registered names.</td>
             </tr>
             <tr>
               <td colspan="3" align="center"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>col</strong><p class="font_10">required</p></td>
               <td>&nbsp;</td>
               <td class="font_14">The query column. Specifies what column will be used to query the data. The column name depends on what table you query.</td>
             </tr>
             <tr>
               <td colspan="3" align="center" class="font_14"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>type</strong><p class="font_10">required</p></td>
               <td>&nbsp;</td>
               <td class="font_14">Query type. It can be <strong>exact</strong> or <strong>range</strong>. If you specify <strong>exact</strong> as query type only rows where the column <strong>col</strong> has the certain value will be returned. Otherwise, all rows where column <strong>col</strong> has a value within a range will be returned.</td>
             </tr>
             <tr>
               <td colspan="3" align="center" class="font_14"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>val</strong><p class="font_10">optional</p></td>
               <td>&nbsp;</td>
               <td class="font_14">Required only if <strong>type</strong> parameter is <strong>exact</strong>. Specify the value for column col. You can send multiple values ( comma separated ).</td>
             </tr>
             <tr>
               <td colspan="3" align="center" class="font_14"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>min</strong><p class="font_10">optional</p></td>
               <td>&nbsp;</td>
               <td class="font_14"><span class="font_14">Required only if <strong>type</strong> parameter is <strong>range</strong>. Specify the minimum value for column <strong>col</strong>. </span></td>
             </tr>
             <tr>
               <td colspan="3" align="center" class="font_14"><hr></td>
               </tr>
             <tr>
               <td align="center" class="font_14"><strong>max</strong><p class="font_10">optional</p></td>
               <td>&nbsp;</td>
               <td class="font_14"><span class="font_14">Required only if <strong>type</strong> parameter is <strong>range</strong>. Specify the maximum value for column <strong>col</strong>. </span></td>
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
         <td height="40" align="left" class="font_14">Returns the list of addresses created at block 2000.</td>
       </tr>
       <tr>
         <td height="50" align="center" bgcolor="#fafafa" class="font_14"><strong>https://www.mskwallet.org/pages/api/public.php?table=adr&amp;col=block&amp;type=exact&amp;val=2000</strong></td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td height="40" align="left" valign="top"><span class="font_14">Returns the list of addresses having the balance over 100</span></td>
       </tr>
       <tr>
         <td align="center" bgcolor="#fafafa" class="font_14"><strong>https://www.mskwallet.org/pages/api/public.php?table=adr&amp;col=block&amp;type=range&amp;min=100&amp;max=10000</strong></td>
       </tr>
       <tr>
         <td align="left">&nbsp;</td>
       </tr>
       <tr>
         <td height="40" align="left" valign="top"><span class="font_14">Returns the owners of an asset (BITUSD).</span></td>
       </tr>
       <tr>
         <td align="center" bgcolor="#fafafa" class="font_14"><strong>https://www.mskwallet.org/pages/api/public.php?table=assets_owners&amp;col=symbol&amp;type=exact&amp;val=BITUSD</strong></td>
       </tr>
       <tr>
         <td height="50" align="left">&nbsp;</td>
       </tr>
       <tr>
         <td align="left" class="font_16"><strong>Returned Data</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14"><hr></td>
       </tr>
       <tr>
         <td align="left" class="font_14">The data is returned in JSON format. This is an example : </td>
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
