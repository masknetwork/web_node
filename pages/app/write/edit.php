<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CWrite.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $write=new CWrite($db, $template);
   
   if (!isset($_SESSION['userID'])) $this->kern->redirect("../../../index.php");
?>

<!doctype html>
<html>
<head>



<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
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


<style type="text/css" media="screen">
    #editor_code { 
        width:90%;
		height:600px;
		margin:0 auto;
    }
	
	#exec_log { 
        width:100%;
		height:600px;
		margin:0 auto;
    }
	
	#run_code { 
        width:100%;
		height:600px;
		margin:0 auto;
    }
</style>

</head>

<body>

<?
   $template->showTopBar(6);
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
     $template->showLocation("../../app/write/index.php", "Applications", "", "Edit");
	 
	 // Menu
	$template->showNav(1,
	                    "edit.php?ID=".$_REQUEST['ID'], "Source Code", "",
	                    "storage.php?ID=".$_REQUEST['ID'], "Storage", "",
						"globals.php?ID=".$_REQUEST['ID'], "Globals", "");
	 print "<br>";
	 
	 // Editor
	 if ($_REQUEST['act']!="test")
	 {
	    $write->showEditor($_REQUEST['ID']);
	 }
     else
	 {
		$write->test($_REQUEST['ID'], 
		             $_REQUEST['dd_test_type'], 
					 $_REQUEST['txt_test_sender'], 
					 $_REQUEST['txt_test_amount'], 
					 $_REQUEST['txt_test_cur'], 
					 $_REQUEST['txt_test_mes'], 
					 $_REQUEST['txt_test_escrower'],
					 $_REQUEST['txt_test_mes_sender'],
					 $_REQUEST['txt_test_mes_subj'],
					 $_REQUEST['txt_test_mes_mes'],
					 $_REQUEST['txt_test_block_hash'],
					 $_REQUEST['txt_test_block_no'],
					 $_REQUEST['txt_test_block_nonce']);
		
	    $write->showRunPage($_REQUEST['ID']);
	 }
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
