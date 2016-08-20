<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CSettings.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $settings=new CSettings($db, $template);
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

</style>

</head>

<body>

<?
   $template->showTopBar(7);
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
     $template->showLocation("../../explorer/packets/index.php", "Admin", "", "Settings");
	 
	 
	 switch ($_REQUEST['act'])
	 {
		 case "change_pass" : $settings->changePass($_REQUEST['txt_old_pass'], 
		                                           $_REQUEST['txt_new_pass'], 
												   $_REQUEST['txt_new_pass_retype']); 
							  break;
							  
		 case "reward" : $settings->setReward($_REQUEST['txt_reward_adr'], 
		                                     $_REQUEST['txt_reward_amount']); 
		                 break;
						 
		 case "restrict" : $settings->restrictIP($_REQUEST['txt_ip']); 
		                   break;
	 }
	 
	 // Modals
	 $settings->showChangePassModal();
	 $settings->showRestrictIPModal();
	 $settings->showRewardModal();
	 
 ?>
 <table width="90%" border="0" cellpadding="0" cellspacing="0">
   <tbody>
     <tr>
       <td width="82%" align="left" class="font_16">Wallet Status<p class="font_10">Change wallet status from online to offline. If you set the wallet as offline, users will be redirected to a default maintainance page</p></td>
       <td width="18%" align="center">
       <select class="form-control" id="dd_status" name="dd_status">
       <option value="">Online</option>
       <option value="">Offline</option>
       </select>
       </td>
     </tr>
     <tr>
       <td colspan="2" align="left"><hr></td>
       </tr>
     <tr>
       <td align="left" class="font_16">Change root password<p class="font_10">Change the root password. You should change the password from time to time and eventually restrict the root login to whitelisted IPs.</p></td>
       <td align="center"><a href="javascript:void(0)" onClick="$('#modal_change_pass').modal()" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Change</a></td>
     </tr>
     <tr>
       <td colspan="2" align="left"><hr></td>
       </tr>
     <tr>
       <td align="left" class="font_16">Restrict root login by IP<p class="font_10">Restrict the root access to wallet by IP. You can set up to 10 whitelisted IPs.</p></td>
       <td align="center"><a href="javascript:void(0)" onClick="$('#modal_restrict').modal()" class="btn btn-success"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Restrict</a></td>
     </tr>
     <tr>
       <td colspan="2" align="left"><hr></td>
       </tr>
     <tr>
       <td align="left" class="font_16">Set new accounts reward<p class="font_10">Set a reward in MSK for newly created accounts. You have to define a payment address and an ammount.</p></td>
       <td align="center"><a href="javascript:void(0)" onClick="$('#modal_reward').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;Setup</a></td>
     </tr>
     <tr>
       <td colspan="2" align="left"><hr></td>
       </tr>
     <tr>
       <td align="left">&nbsp;</td>
       <td align="center">&nbsp;</td>
     </tr>
     <tr>
       <td align="left">&nbsp;</td>
       <td align="center">&nbsp;</td>
     </tr>
   </tbody>
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
