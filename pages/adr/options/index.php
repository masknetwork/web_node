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
   $ud=new CSysData($db);
   
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
<title>MaskNetwwork - My Addresses</title>
<link rel="stylesheet" href="../../../style.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="../../../utils.js" type="text/javascript"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">$(document).ready(function() { $("body").tooltip({ selector: '[data-toggle=tooltip]' }); });</script>


</head>
<center>
<body background="../../template/template/GIF/back.png" style="margin-top:0px; margin-left:0px; margin-right:0px; ">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="66" align="center" valign="top" background="../../template/template/GIF/top_bar.png" style="background-position:center"><table width="1020" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="left">
            <?
			    $template->showTopMenu(2);
			?>
            </td>
            </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<table width="1018" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="800" align="center" valign="top" background="../../template/template/GIF/back_middle.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="205" height="18" align="right" valign="top"><table width="201" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="170" align="center" valign="middle" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">
                  <?
				    $template->showBalancePanel();
				  ?>
                  </td>
                </tr>
              </tbody>
            </table>
            <?
			   $adr->showLeftMenu(1);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
           
           
            <?
			   $template->showHelp("Din aceasta pagina pot fi facute setari pentru adresa selectata. Poti seta plata dobanzii, inchiriera unui nume sau poti atasa adresei optiuni de securitate cum ar fi semnaturile multiple. Tot de aici poti seta optiuni specifice comerciantilor.");
			   
			   // Interest modal
			   $interest->showModal();
			   
			    // Rent name modal
			   $name->showModal();
			   
			   // Share address modal
			   $share->showModal();
			   
			   // Profile
			   $profile->showModal();
			   
			   // Froze address
			   $froze->showModal();
			   
			   // Seal address
			   $seal->showModal();
			   
			   // Restrict recipients
			   $restrict->showModal();
			   
			   // Multisignatures
			   $multisig->showModal();
			   
			   // OTP
			   $otp->showModal();
			   
			   // IPN
			   $ipn->showModal();
			   
			   // Aditional
			   $aditional->showModal();
			   
			   // Autoresponders
			   $autoresp->showModal();
			   
			   // Private key
			   $pkey->showModal();
			   
			   // Load address
			   $query="SELECT * 
			             FROM my_adr 
						WHERE ID='".$_REQUEST['ID']."'";
			   $result=$db->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	           $adr=$row['adr']; 
			   
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
											 
				   case "update_profile" : $profile->updateProfile($_REQUEST['dd_net_fee'], 
				                                                  $adr,
				                                                  $_REQUEST['txt_prof_name'], 
																  $_REQUEST['txt_desc'], 
																  $_REQUEST['txt_email'], 
																  $_REQUEST['txt_tel'], 
																  $_REQUEST['txt_web'], 
																  $_REQUEST['txt_fb'],
																  $_REQUEST['txt_pic'],
																  $_REQUEST['txt_days']); 
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
            
            
            </td>
            <td width="203" align="center" valign="top">
            <?
			   $template->showRightPanel();
			   $template->showAds();
			?>
            </td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom_sep.png" width="1018" height="20" alt=""/></td>
    </tr>
    <tr>
      <td height="50" align="center" background="../../template/template/GIF/bottom_middle.png">
      <?
	     $template->showBottomMenu();
	  ?>
      </td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom.png" width="1018" height="20" alt=""/></td>
    </tr>
  </tbody>
</table>
</body>
</center>
</html>