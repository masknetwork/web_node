<?
    session_start();
    
    include "../../../kernel/db.php";
    include "../../../kernel/CUserData.php";
    include "../../../kernel/CSysData.php";
    include "../../template/template/CTemplate.php";
    include "../CTweets.php";
   
    $db=new db();
    $template=new CTemplate($db);
    $ud=new CUserData($db);
    $sd=new CSysData($db);
    $tweets=new CTweets($db, $template);
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
     $template->showLocation("../../explorer/packets/index.php", "Posts", "", "Top Posts");
	 
	 // Time
	 if (!isset($_REQUEST['time'])) $_REQUEST['time']=24;
	 
	 // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 {
		 switch ($_REQUEST['time'])
		 {
			 case "24" :  $sel=2; break;
			 case "7" :  $sel=3; break;
			 case "30" :  $sel=4; break;
		 }
		 
	     $template->showNav($sel,
	                       "../../tweets/home/index.php", "Home", "",
	                       "../../tweets/tweets/index.php?adr=all&time=24", "Top 24 Hours", "",
						   "../../tweets/tweets/index.php?adr=all&time=7", "Top 7 Days", "",
						   "../../tweets/tweets/index.php?adr=all&time=30", "Top 30 Days", "");
	 }
	 else
	 {
		switch ($_REQUEST['time'])
	    {
			 case "24" :  $sel=1; break;
			 case "7" :  $sel=2; break;
			 case "30" :  $sel=3; break;
		}
		 
	    $template->showNav($sel,
	                       "../../tweets/tweets/index.php?adr=all&time=24", "Top 24 Hours", "",
						   "../../tweets/tweets/index.php?adr=all&time=7", "Top 7 Days", "",
						   "../../tweets/tweets/index.php?adr=all&time=30", "Top 30 Days", "");
	 }
	 
	 // Search
	 $tweets->showSearchPanel();
	 
 ?>
 
 <br>
 <table width="90%" border="0" cellspacing="0" cellpadding="0">
   <tbody>
     <tr>
       <td width="75%" height="1000" align="right" valign="top">
         
         <?
	      $tweets->showTweets("all", false, $_REQUEST['time']); 
	   ?>
         
       </td>
     </tr>
   </tbody>
</table>
<br><br><br>
 
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
