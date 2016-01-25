<?
    session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CAdrOptions.php";
   include "CInterest.php";
   include "CName.php";
   include "CShare.php";
   include "CProfile.php";
   include "CFroze.php";
   include "CSeal.php";
   include "CRestrict.php";
   include "COTP.php";
   include "CMultisig.php";
   include "CWebIPN.php";
   include "CAditionalData.php";
   include "CAutoresponders.php";
   include "CReveal.php";
   
   // Database
   $db=new db();
   
   // Template
   $template=new CTemplate($db);
   
   // User data
   $ud=new CUserData($db);
   
   // Sys data
   $sd=new CSysData($db);
   
   // Address
   $adr=new CAdr($db, $template);
   
   // Address Options
   $adr_options=new CAdrOptions($db, $template);
   
   // Interest
   $interest=new CInterest($db, $template);
   
   // Domains
   $name=new CName($db, $template);
   
   // Share
   $share=new CShare($db, $template);
   
   // Profile
   $profile=new CProfile($db, $template);
   
   // Froze
   $froze=new CFroze($db, $template);
   
   // Seal
   $seal=new CSeal($db, $template);
   
   // Restrict
   $restrict=new CRestrict($db, $template);
   
   // OTP
   $otp=new COTP($db, $template);
   
   // Multisig
   $multisig=new CMultisig($db, $template);
   
   // Web IPN
   $ipn=new CWebIPN($db, $template);
   
   // Aditional data
   $aditional=new CAditionalData($db, $template);
   
   // Autoresponders
   $autoresp=new CAutoresponders($db, $template);
   
   // Private key
   $pkey=new CReveal($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<script src="../../../utils.js"></script>

<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="../../../gallery.css">
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">

<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="../../../gallery.min.js"></script>

<script src="../../../jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="../../../jupload/js/jquery.iframe-transport.js"></script>
<script src="../../../jupload/js/jquery.fileupload.js"></script>

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

<script>
$(document).ready(

$(function (e) 
{
	var i=0;
	
    $('#fileupload').fileupload({
        url: './server/php/index.php',
		dataType : 'json',
		autoUpload:true,
		
		add: function(e, data) 
		{ 
		    data.files.forEach(function(file) 
		   { 
		      if (file.name.indexOf('.jpg')<0 && 
			      file.name.indexOf('.jpeg')<0) 
			  return false;  
		   });
		   
		   data.submit();
		},
		
		progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10); 
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        },
        
		done: function (e, data) 
		{
			 $('#progress .progress-bar').css(
                'width',
                '0%'
            );
			
			 $.each(data.result.files, function (index, file) 
			 {
				if (i==0) 
				{
					$('#pic_back').attr('src', '../../../crop.php?src=./pages/tweets/home/server/php/files/'+file.name+'&w=350&h=100');
				    $('#h_pic_back').val(file.name);
				}
				
				if (i==1) 
				{
					$('#pic').attr('src', '../../../crop.php?src=./pages/tweets/home/server/php/files/'+file.name+'&w=100&h=100');
				    $('#h_pic').val(file.name);
				}
				
				i++;
            });
		
        },
		
		fail: function(e, data) 
		{
			   console.log(data);
              alert(data.errorThrown+", "+data.textStatus);
        }
		
		
    });
}));
</script>

</head>

<body>

<?
   $template->showTopBar(2);
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
     $template->showLocation("../adr/index.php", "My Addresses", 
	                         "", "Address Options");
	 
	 
	 $template->showHelp("From this page you can manage an address. You can apply security options like multisignatures, rent a name, share an address and many other features. Almost all network level features are rented. The rent price is 0.0001 MSK / day.");
			   
			    // Load address
			   $query="SELECT * 
			             FROM my_adr 
						WHERE ID='".$_REQUEST['ID']."'";
			   $result=$db->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	           $adr=$row['adr']; 
			   
			   // Interest modal
			   $interest->showModal($adr);
			   
			    // Rent name modal
			   $name->showModal($adr);
			   
			   // Share address modal
			   $share->showModal($adr);
			   
			   // Profile
			   $profile->showModal($adr);
			   
			   // Froze address
			   $froze->showModal($adr);
			   
			   // Seal address
			   $seal->showModal($adr);
			   
			   // Restrict recipients
			   $restrict->showModal($adr);
			   
			   // Multisignatures
			   $multisig->showModal($adr);
			   
			   // OTP
			   $otp->showModal($adr);
			   
			   // IPN
			   $ipn->showModal($adr);
			   
			   // Aditional
			   $aditional->showModal($adr);
			   
			   // Autoresponders
			   $autoresp->showModal($adr);
			   
			   // Private key
			   $pkey->showModal($adr);
			   
			   // Action
			   switch ($_REQUEST['act'])
			   {
				   case "restrict" : $restrict->restrict($_REQUEST['dd_restrict_net_fee'], 
				                                            $adr, 
													        $_REQUEST['txt_target_1'],
													        $_REQUEST['txt_target_2'],
													        $_REQUEST['txt_target_3'],
															$_REQUEST['txt_target_4'],
															$_REQUEST['txt_target_5'],
													        $_REQUEST['txt_restrict_days']);
				                         break;
									  
				   case "otp_adr" : $otp->otp($_REQUEST['dd_otp_net_fee'], 
				                             $adr, 
											 $_REQUEST['txt_next_pass'], 
											 $_REQUEST['txt_otp_adr'], 
											 $_REQUEST['txt_otp_days']); 
				                    break;
									  
				   case "multisig" : $multisig->multisig($_REQUEST['dd_multi_net_fee'], 
				                                        $adr, 
														$_REQUEST['txt_signer_1'], 
														$_REQUEST['txt_signer_2'], 
														$_REQUEST['txt_signer_3'], 
														$_REQUEST['txt_signer_4'], 
														$_REQUEST['txt_signer_5'],
														$_REQUEST['txt_sig_min'], 
														$_REQUEST['txt_sig_days']); 
				                      break;
									  
				   case "seal_adr" : $seal->sealAdr($_REQUEST['dd_seal_net_fee'], 
				                                   $adr, 
												   $_REQUEST['txt_seal_days']);
				                      break;
									  
				  case "froze_adr" : $froze->frozeAdr($_REQUEST['dd_froze_net_fee'], 
				                                      $adr, 
													  $_REQUEST['txt_froze_days']);
				                      break;
									  
				   case "rent_domain" : $name->newDomain($_REQUEST['dd_net_fee_adr'], 
				                                        $adr, 
													    $_REQUEST['txt_domain'], 
													    $_REQUEST['txt_days_domain']);
				                      break;
									  
				   case "share_adr" : $share->share($_REQUEST['dd_net_fee'], 
				                                   $adr, 
												   $_REQUEST['txt_adr']);
				                      break;
									 
				   case "receive_interest" : $interest->setup($adr, 
				                                             $_REQUEST['dd_net_fee_adr'], 
															 $_REQUEST['txt_adr']); 
									         break;
											 
				   case "update_profile" :  if ($_REQUEST['txt_pic_back']=="" && $_REQUEST['txt_pic']=="")
				                            { 
											   // Pic back
	                                           if ($_REQUEST['h_pic_back']!="") 
		                                       {
			                                       if (strpos($_SERVER['DOCUMENT_ROOT'], "localhost")===false)
			                                           $pic_back="http://www.".$_SERVER['HTTP_HOST']."/pages/tweets/home/server/php/files/".$_REQUEST['h_pic_back'];
			                                       else
		                                               $pic_back="http://localhost/wallet/pages/tweets/home/server/php/files/".$_REQUEST['h_pic_back'];
		                                       }
		                                       else
		                                       {
		                                           $pic_3="";
		                                       }
			  
		                                       // Pic 
	                                           if ($_REQUEST['h_pic']!="") 
		                                       {
			                                       if (strpos($_SERVER['DOCUMENT_ROOT'], "localhost")===false)
			                                           $pic="http://www.".$_SERVER['HTTP_HOST']."/pages/tweets/home/server/php/files/".$_REQUEST['h_pic'];
			                                       else
		                                               $pic="http://localhost/wallet/pages/tweets/home/server/php/files/".$_REQUEST['h_pic'];
		                                       }
		                                       else
		                                       {
		                                           $pic="";
		                                       }
												   
											   // Encode
											   $pic_back=base64_encode($pic_back);
											   $pic=base64_encode($pic);
											   
											   // Profile
											   $profile->updateProfile($_REQUEST['dd_net_fee'], 
				                                                      $adr,
				                                                      $_REQUEST['txt_prof_name'], 
																      $_REQUEST['txt_desc'], 
																      $_REQUEST['txt_email'], 
																      $_REQUEST['txt_web'], 
																      $pic_back,
																      $pic,
																	  $_REQUEST['txt_prof_days']);
											}
											else
											{
												// Profile
											   $profile->updateProfile($_REQUEST['dd_net_fee'], 
				                                                      $adr,
				                                                      $_REQUEST['txt_prof_name'], 
																      $_REQUEST['txt_desc'], 
																      $_REQUEST['txt_email'], 
																      $_REQUEST['txt_web'], 
																      $_REQUEST['txt_pic_back'],
																      $_REQUEST['txt_pic'],
																	  $_REQUEST['txt_prof_days']);
											}
											
											break;
											
					case "web_ipn" : $ipn->update($adr,
					                             $_REQUEST['txt_ipn_email'], 
												 $_REQUEST['txt_ipn_web_adr'], 
					                             $_REQUEST['txt_ipn_pass']);
					                 break;
									 
					case "aditional" : $aditional->setup($_REQUEST['dd_req_net_fee'],
					                                    $adr, 
	                                                    $_REQUEST['txt_req_mes'], 
				                                        $_REQUEST['txt_field_1_name'], $_REQUEST['txt_field_1_min'], $_REQUEST['txt_field_1_max'],
				                                        $_REQUEST['txt_field_2_name'], $_REQUEST['txt_field_2_min'], $_REQUEST['txt_field_2_max'],
														$_REQUEST['txt_field_3_name'], $_REQUEST['txt_field_3_min'], $_REQUEST['txt_field_3_max'],
														$_REQUEST['txt_field_4_name'], $_REQUEST['txt_field_4_min'], $_REQUEST['txt_field_4_max'],
														$_REQUEST['txt_field_5_name'], $_REQUEST['txt_field_5_min'], $_REQUEST['txt_field_5_max'],
														$_REQUEST['txt_field_6_name'], $_REQUEST['txt_field_6_min'], $_REQUEST['txt_field_6_max'],
				                                        $_REQUEST['txt_req_days']); 
									   break;
									  
					case "reveal" : $pkey->reveal($adr, $_REQUEST['txt_pass']); 
					                break;
			   }
			   
			   if ($_REQUEST['act']!="reveal") $adr_options->showOptions($_REQUEST['ID']);
 ?>
 
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
