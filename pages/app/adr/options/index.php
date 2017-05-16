<?
    session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAdr.php";
   include "CAdrOptions.php";
   include "CName.php";
   include "CProfile.php";
   include "CWebIPN.php";
   include "CReveal.php";
   include "CSeal.php";
   
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
  
   // Domains
   $name=new CName($db, $template);
   
   // Seal
   $seal=new CSeal($db, $template);
   
   // Profile
   $profile=new CProfile($db, $template);
   
   // Web IPN
   $ipn=new CWebIPN($db, $template);
   
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
	     $template->showLeftMenu("adr");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
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
			   
			   // Rent name modal
			   $name->showModal($adr);
			   
			   // Profile
			   $profile->showModal($adr);
			   
			   // IPN
			   $ipn->showModal($adr);
			   
			   // Private key
			   $pkey->showModal($adr);
			   
			   // Seal
			   $seal->showModal();
			   
			   // Action
			   switch ($_REQUEST['act'])
			   {
				   case "rent_domain" : $name->newDomain($_REQUEST['dd_net_fee_adr'], 
				                                        $adr, 
													    $_REQUEST['txt_domain'], 
													    $_REQUEST['txt_days_domain']);
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
												   
											   // Profile
											   $profile->updateProfile($adr,
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
											   $profile->updateProfile($adr,
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
									 
					case "reveal" : $pkey->reveal($adr, $_REQUEST['txt_pass']); 
					                break;
									
					case "seal_adr" : $seal->sealAdr($_REQUEST['dd_net_fee_adr_seal'], 
					                                $adr, 
											        $_REQUEST['txt_rec_1'],
													$_REQUEST['txt_rec_2'],
													$_REQUEST['txt_rec_3'],
													$_REQUEST['txt_days']); 
					                  break;
			   }
			   
			   if ($_REQUEST['act']!="reveal") $adr_options->showOptions($_REQUEST['ID']);
 ?>
 
 
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




