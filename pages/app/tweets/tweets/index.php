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
	     $template->showLeftMenu("community");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
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
			 case "0" :  $sel=5; break;
		 }
		 
	     $template->showNav($sel,
	                       "../../tweets/home/index.php", "Home", "",
	                       "../../tweets/tweets/index.php?adr=all&time=24", "Top 24 Hours", "",
						   "../../tweets/tweets/index.php?adr=all&time=7", "Top 7 Days", "",
						   "../../tweets/tweets/index.php?adr=all&time=30", "Top 30 Days", "",
						   "../../tweets/tweets/index.php?adr=all&time=0", "Last Tweets", "");
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
						   "../../tweets/tweets/index.php?adr=all&time=30", "Top 30 Days", "",
						   "../../tweets/tweets/index.php?adr=all&time=0", "Last Tweets", "");
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



