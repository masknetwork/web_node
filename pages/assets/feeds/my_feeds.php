<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CMyFeeds.php";
   include "CFeeds.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $my_feeds=new CMyFeeds($db, $template);
   $feeds=new CFeeds($db, $template);
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
	     $template->showLeftMenu("feeds");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	<?
     // Location
     $template->showLocation("../../assets/feeds/index.php", "Data feeds", "", "My Feeds");
	 
	  // Menu
	 $template->showNav(2,
	                    "../feeds/index.php", "Feeds", "",
	                    "../feeds/my_feeds.php", "My Feeds", "");
	 
	 
	 // Help
	 $template->showHelp("If you have access to a reliable datasource you can launch your own data feed and gain a fee everytime users use your data feed to launch binary options, start speculative markets and so on. Below are displayed data feeds issued by you.");
	 
	// New feed modal
	$my_feeds->showNewFeedModal(); 
	 
    // Button
	$my_feeds->showNewFeedBut();
	
	// New feed ?
	if ($_REQUEST['act']=="new_feed")
	   $my_feeds->newFeed($_REQUEST['dd_new_feed_adr'], 
	                      $_REQUEST['dd_new_feed_adr'], 
					      $_REQUEST['txt_new_feed_name'], 
					      $_REQUEST['txt_new_feed_desc'],
					      $_REQUEST['txt_new_feed_source'],
					      $_REQUEST['txt_new_feed_website'], 
						  $_REQUEST['txt_new_feed_symbol'], 
					      $_REQUEST['txt_new_feed_days']);
						  
	// Data feeds
	$feeds->showFeeds("mine");
						  
						
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


