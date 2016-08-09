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
											        $_REQUEST['txt_days_seal']); 
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
