<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CFeeds.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
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
     $template->showLocation("../../assets/feeds/index.php", "Data feeds", "", "Feeds");
	 
	  // Menu
	 if ($_REQUEST['ud']['ID']>0)
	 $template->showNav(1,
	                    "../feeds/index.php", "Feeds", "",
	                    "../feeds/my_feeds.php", "My Feeds", "");
	 
	 
	 // Help
	 $template->showHelp("Data feed is a mechanism for network to receive updated data from data sources. A data feed could provide real time stock rates, currencies rates or any other data like the actual temperature in London. Data feeds are used by users to launch decentralized binary options (bets) or to trade any imaginable asset for wich a data feed exist with no central server / authority. Below are listed available data feeds.");
	 
    // Feeds
	$feeds->showFeeds("all");
	
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

