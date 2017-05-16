<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../../mes/CMes.php";
   include "../CTweets.php";
   include "CTweet.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $tweets=new CTweets($db, $template);
   $tweet=new CTweet($db, $template, $tweets);
   $mes=new CMes($db, $template);
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
   $template->showBalanceBar();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="15%" align="left" bgcolor="#4c505d" valign="top">
      
      <?
	     $template->showLeftMenu("community");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	
 
 <br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td width="75%" height="1000" align="center" valign="top">
         
         
         <?
              // Modals
	          $template->showVoteModal("ID_POST", $_REQUEST['ID']);
			  
			  // Compose modal
	          $mes->showComposeModal();
	 
              // Location
              $template->showLocation("../tweets/home/index.php", "Posts", "", "Post");
	          
			   // Menu
	           $template->showNav(5,
	                   "../../tweets/home/index.php", "Home", "",
					   "../../tweets/tweets/index.php?adr=all&time=24", "Top 24 Hours", "",
					   "../../tweets/tweets/index.php?adr=all&time=7", "Top 7 Days", "",
					   "../../tweets/tweets/index.php?adr=all&time=30", "Top 30 Days", "",
					   "../../tweets/tweets/index.php?adr=all&time=0", "Last Tweets", ""); 
					   
	          // Target
	          $sel=1;
         	  if ($_REQUEST['target']=="upvoters") $sel=2;
	          if ($_REQUEST['target']=="downvoters") $sel=3;
	 
	          // Menu
	          if ($sel>1)
	          $template->showNav($sel,
	                             "index.php?ID=".$_REQUEST['ID'], "Post", "",
	                             "index.php?ID=".$_REQUEST['ID']."&target=upvoters&target_type=".$_REQUEST['target_type'], "Upvoters", "",
	  			                 "index.php?ID=".$_REQUEST['ID']."&target=downvoters&target_type=".$_REQUEST['target_type'], "Downvoters", ""); 
	
					    
	          print "<br><br>";
	 
	 // New comment
	 $tweets->showNewCommentModal();
 
	      switch ($_REQUEST['act'])
		  {
		     case "new_comment" : $tweet->newComment($_REQUEST['dd_comm_net_fee'], 
	                                                $_REQUEST['dd_comm_net_fee'], 
					                                $_REQUEST['com_target_type'],
								                    $_REQUEST['com_targetID'],
					         	                    $_REQUEST['txt_com_mes']);
			 break;
			 
			 case "vote" : $template->vote($_REQUEST['dd_vote_net_fee'], 
		                                  $_REQUEST['dd_vote_adr'], 
				                          $_REQUEST['vote_target_type'], 
				                          $_REQUEST['vote_targetID'], 
				                          $_REQUEST['vote_type']);
				           break;
			 
			 case "downvote" : $tweets->vote($_REQUEST['dd_downvote_net_fee'], 
			                                 $_REQUEST['dd_downvote_adr'], 
										     $_REQUEST['down_target_type'], 
										     $_REQUEST['down_targetID'], 
										     "ID_DOWN"); 
			 break;
			 
			 case "follow" : $tweets->follow($_REQUEST['dd_follow_net_fee'], 
			                                $_REQUEST['dd_follow_adr'], 
											$_REQUEST['adr'], 
											$_REQUEST['dd_months']); 
			 break;
			 
			 case "unfollow" : $tweets->unfollow($_REQUEST['adr'], 
			                                   $_REQUEST['adr'], 
											   $_REQUEST['unfollow_adr']); 
			 break;
			 
			 case "retweet" : $tweets->newTweet($_REQUEST['dd_retweet_adr'], 
			                                   $_REQUEST['dd_retweet_adr'], 
											   "", 
					                           $_REQUEST['retweet_mes'], 
					                           $_REQUEST['retweet_tweet_ID'], 
					                           ""); 
			 break;
			
		   }
	       
		   switch ($sel)
		   {
		      case 1 : $tweets->showPost($_REQUEST['ID']); break;
			  case 2 : $tweets->showVoters($_REQUEST['target_type'], $_REQUEST['ID'], "ID_UP"); break;
			  case 3 : $tweets->showVoters($_REQUEST['target_type'], $_REQUEST['ID'], "ID_DOWN"); break;
		   }
		   
		   if ($sel==1) 
		   {
			   $tweets->showNewCommentBut($_REQUEST['ID']);
			   $tweets->showComments("ID_POST", $_REQUEST['ID']);
		   }
	   ?>
         
       </td>
     </tr>
   </tbody>
</table>
 
 
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








