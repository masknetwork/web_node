<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CAds.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $ads=new CAds($db, $template);
   
   if (!isset($_SESSION['userID'])) $this->kern->redirect("../../../index.php");
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
	     $template->showLeftMenu("settings");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../ads/ads/index.php", "My Ads", "", "My Ads");
	 
	
	 // Help
	 $template->showHelp("Listed below are your advertisements. The ads are displayed based on the bid price. Only the top 10 advertising messages are displayed. The bid price is for one hour of display. You can increase the bid price for an advertisement but you cannot lower it. Also you cannot stop an advertisement since it has been paid and displayed. Ads are displayed in the entire MaskNetwork network including desktop nodes.");
			   
	if ($_REQUEST['act']=="renew")
	    $ads->increaseBid($_REQUEST['dd_fee_adr'], 
				                   $_REQUEST['rowhash'], 
								   $_REQUEST['txt_new_bid']);
			   
	// New ad
	if ($_REQUEST['act']=="new_ad")
			      $ads->newAd($_REQUEST['dd_ads_fee_adr'], 
				              $_REQUEST['txt_ads_title'], 
							  $_REQUEST['txt_ads_mes'], 
							  $_REQUEST['txt_ads_link'], 
							  $_REQUEST['dd_country'], 
							  $_REQUEST['txt_ads_hours'], 
							  $_REQUEST['txt_ads_bid']);
							  
	// Ads
	$ads->showMyAds();
				
	// Increase bid
	$template->showIncreaseBidModal();
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



