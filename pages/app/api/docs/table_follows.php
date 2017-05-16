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
   .balance_MSK { font-size: 40px; }
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
     $api->showLeftMenu(20);
  ?>
  
   </td>
   <td width="78%" align="center" valign="top"><table width="90%" border="0" cellpadding="0" cellspacing="0">
     <tbody>
       
       <tr>
         <td height="40" align="left" valign="top" class="font_18"><strong>Follows</strong></td>
       </tr>
       <tr>
         <td align="left" class="font_14"><hr></td>
       </tr>
       <tr>
         <td align="left" class="font_16">This table  table contains data about follow relations between addresses.</td>
       </tr>
       <tr>
         <td align="center">&nbsp;</td>
       </tr>
       <tr>
         <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>
             <tr>
               <td width="33%" align="center"><strong class="font_14">adr</strong>
                 <p class="font_10">string</p></td>
               <td width="67%" class="font_14">Address that follows.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
               </tr>
             <tr>
               <td align="center"><strong class="font_14">follows</strong>
                 <p class="font_10">string</p></td>
               <td class="font_14">Followed address.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">expire</strong>
                 <p class="font_10">string</p></td>
               <td class="font_14">Expire block. When the follow relations will be deleted.</td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">block</strong>
                 <p class="font_10">long</p></td>
               <td class="font_14"><span class="font_14">The block number at which the last change was made to this table row.</span></td>
             </tr>
             <tr>
               <td colspan="2" align="center"><hr></td>
             </tr>
             <tr>
               <td align="center"><strong class="font_14">rowhash</strong><p class="font_10">string</p></td>
               <td align="left" class="font_14">Rowhash is a sha256 hash of all / certain table columns. It uniquely identifies a table row. The columns used in calculating the rowhash are different for each table.</td>
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
         <td align="left" class="font_14" height="40px">Returns all followers for address ME4wEAYHKoZIzj0CAQYFK4EEACEDOgAED2PvHmaRk1DS0DA1JD xh3E+5mn6UH5ANKE8VtR/V88EKuOW1ggjUJg37oin0P+QWZfDyPvrt8zc=</td>
       </tr>
       <tr>
         <td align="center" class="font_16" bgcolor="#f0f0f0" height="70px"><strong>http://localhost/wallet/pages/api/public.php?table=tweets_follows&amp;col=follows &amp;type=exact&amp;val=ME4wEAYHKoZIzj0CAQYFK4EEACED OgAED2PvHmaRk1DS0DA1JDxh3E +5mn6UH5ANKE8VtR/V88EKuOW1ggjUJg37oin0P+QWZfDyPvrt8zc=</strong></td>
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
