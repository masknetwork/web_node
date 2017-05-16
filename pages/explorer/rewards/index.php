<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CExplorer.php";
   include "CRewards.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $explorer=new CExplorer($db, $template);
   $rewards=new CRewards($db, $template);
   
   // Undistributed
   $u=$db->getBalance("default", "MSK")/365/20;
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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Miners (25% to voters)',     3],
          ['Blog Posts (50% to voters)',  2],
          ['Comments (50% to voters)',    1],
		  ['Data Feeds',    0.5],
		  ['Assets',    0.5],
		  ['Binary Options (50% to voters)',    1.5],
		  ['Margin Markets',    1.5]
        ]);

        var options = {
          title: 'Rewards Distribution',
		  pieHole : 0.25
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
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
	     $template->showLeftMenu("explorer");
	  ?>
      
      </td>
      <td width="55%" align="center" valign="top">
	  
	  <?
	     // QR modal
		 $template->showQRModal();
		  
         // Location
         $template->showLocation("../../explorer/packets/index.php", "Explorer", "", "Peers");
		 
		 // Menu
	     $template->showNav(5,
	                       "../packets/index.php", "Packets", "",
	                       "../blocks/index.php", "Blocks", "", 
					       "../adr/index.php", "Addresses", "",
					       "../delegates/index.php", "Delegates", "",
						   "../rewards/index.php", "Rewards", "",
					       "../status/index.php", "Status", "");
						    
		 // Help
		 $template->showHelp("Every 24 hours, the network rewards traders and content creators for their effort. There are 11 kinds of rewarded content like assets, binary options, blog posts and so on. Every year 5% of the remaining undistributed coins goes to content creators and traders. The total daily reward pool is calculated by formula DailyPool=U/20/365, where U is the amount of undistributed coins. Each category has its own reward pool. Below is a graph detailing how the coins are divided, as well as a list of the latest rewards received by users. <strong>Because the MaskCoins number is limited to 21 milions and the amount of undistributed coins decreases each day, reward pools become smaller every day.</strong> For example, block rewards decreases 0.00000001 MSK every 2 minutes.");
	    
		 // Show data
		 print "<div id='piechart' style='width: 90%; height: 500px;'></div>";
						   
	     // Rewards
		 $rewards->showLastRewards();
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

