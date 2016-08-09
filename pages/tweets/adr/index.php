<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../../mes/CMes.php";
   include "../CTweets.php";
   include "CAdr.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mes=new CMes($db, $template);
   $tweets=new CTweets($db, $template);
   $adr=new CAdr($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>

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
        dataType: 'json',
		url: './server/php/index.php',
		
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
			 $.each(data.result.files, function (index, file) 
			 {
				$('#img_'+i).attr('src', '../../../crop.php?src=./pages/tweets/home/server/php/files/'+file.name+'&w=50&h=50');
				$('#h_img_'+i).val(file.name); 
				$('#img_'+i).css('display', 'block');
				i++;
            });
			
			$('#row_drop').css('display', 'none');
			$('#row_progress').css('display', 'none');
        }
    });
}));
</script>

</head>

<body>

<?
   $template->showTopBar("blogs");
?>
 

<div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1 col-sm-0">&nbsp;</div>
 <div class="col-md-8 col-sm-12" align="center" style="height:100%; background-color:#ffffff">
 
 
 <?
     // Domain ?
     if (strlen($_REQUEST['adr'])<30) 
	     $adr=$this->kern->adrFromDomain($_REQUEST['adr']);
	 
	 if ($adr!="")
	 {
        // Follow modal
	    $tweets->showFollowModal($_REQUEST['adr']);
	 
	    // Unfollow modal
	    $tweets->showUnfollowModal($_REQUEST['adr']);
	 
        // Show top panel
	    $adr->showPanel($_REQUEST['adr']);
		
		// Message modal
		$mes->showComposeModal();
	 
        // Menu
	    $target=2;
	    switch ($_REQUEST['target'])
	    {
		    case "followers" : $target=3; break;
		    case "following" : $target=4; break;
		    case "trans" : $target=5; break;
	    }
	 
	    $template->showNav($target,
	                   "../home/index.php", "Home", "",
	                   "index.php?adr=".urlencode($_REQUEST['adr']), "Posts", "",
	                   "index.php?target=followers&adr=".urlencode($_REQUEST['adr']), "Followers", "", 
					   "index.php?target=following&adr=".urlencode($_REQUEST['adr']), "Following", "", 
					   "index.php?target=trans&adr=".urlencode($_REQUEST['adr']), "Transactions"); 
	 
	    
	 }
	 else $this->template->showErr("Invalid address");
	 
 ?>
 
 <br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td width="25%" height="1000px" valign="top">
       
       <?
	      $tweets->showLeftMenu($_REQUEST['adr']); 
	   ?>
       
       </td>
       <td width="75%" valign="top" align="right">
       
       <?
	      switch ($_REQUEST['act'])
		   {
			   case "follow" : $tweets->follow($_REQUEST['dd_follow_net_fee'], 
			                                  $_REQUEST['dd_follow_adr'], 
									          $_REQUEST['adr']); 
					           break;
					  
			   case "unfollow" : $tweets->unfollow($_REQUEST['dd_unfollow_net_fee'], 
			                                      $_REQUEST['adr']); 
					             break;
		   }
		   
	       if ($_REQUEST['target']=="followers")
		       $tweets->showFollowers($_REQUEST['adr']);
		   else if ($_REQUEST['target']=="following")
		       $tweets->showFollowing($_REQUEST['adr']);
		   else if ($_REQUEST['target']=="trans")
		       $tweets->showTrans($_REQUEST['adr']);
		   else	   
		       $tweets->showTweets($_REQUEST['adr']); 
	   ?>
       
       </td>
     </tr>
   </tbody>
</table>
 
 </div>
 <div class="col-md-2 col-sm-0" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1 col-sm-0">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
</body>
</html>
