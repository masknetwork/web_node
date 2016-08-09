<?
    session_start();
      
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CTweets.php";
   include "CHome.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $tweets=new CTweets($db, $template);
   $home=new CHome($db, $template);
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
			
			 $.each(data.result.files, function (index, file) 
			 {
				$('#img_'+i).attr('src', '../../../crop.php?src=./pages/tweets/home/server/php/files/'+file.name+'&w=50&h=50');
				$('#h_img_'+i).val(file.name); 
				$('#img_'+i).css('display', 'block');
				i++;
            });
			
			$('#row_drop').css('display', 'none');
			$('#row_progress').css('display', 'none');
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
     // Location
     $template->showLocation("../../explorer/packets/index.php", "Explorer", "", "Last Blocks");
	 
	 // Menu
	 $template->showNav(1,
	                   "../../tweets/home/index.php", "Home", "",
	                   "../../tweets/tweets/index.php?adr=all", "Top 24 Hours", "",
					   "../../tweets/tweets/index.php?adr=all", "Top 7 Days", "",
					   "../../tweets/tweets/index.php?adr=all", "Top 30 Days", ""); 
	 
?>
 
 <br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td width="25%" height="1000px" valign="top">
       
       <? $tweets->showLeftMenu($_REQUEST['adr']); ?>
       
       </td>
       <td width="75%" valign="top" align="right">
       
       
       <?
	       switch ($_REQUEST['act'])
		   {
		     case "like" : $tweets->like($_REQUEST['dd_like_net_fee'], 
			                            $_REQUEST['dd_like_adr'], 
										$_REQUEST['like_tweetID']);
						   break;
						   
						   
			case "remove" : $tweets->remove($_REQUEST['dd_remove_net_fee'], 
			                               $_REQUEST['remove_tweet_ID']);
						   break;
						   
			 case "retweet" : $home->newTweet($_REQUEST['dd_retweet_net_fee'], 
			                                 $_REQUEST['dd_retweet_adr'],
											 $_REQUEST['dd_retweet_adr'],
											 $_REQUEST['retweet_mes'], 
											 $_REQUEST['retweet_tweet_ID']);
			                  break;
						   
		   }
		   
		  if ($_REQUEST['target']=="pending")
		     $home->showPendingTweets($_REQUEST['adr']);
		  else 
	         $tweets->showTweets($_REQUEST['adr'], true); 
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
