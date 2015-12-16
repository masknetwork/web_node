<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../template/template/CTemplate.php";
   include "CSignup.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $signup=new CSignup($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
@media only screen and (max-device-width: 667px)
{
 #but_login { height:75px; font-size:30px; }
 #txt_user { height:60px; font-size:20px; }
 #txt_email { height:60px; font-size:20px; }
 #txt_pass_1 { height:60px; font-size:20px; }
 #txt_pass_2 { height:60px; font-size:20px; }
 .txt_login_title { font-size:50px; }
}

</style>

</head>

<body>

<?
   $template->showTopBar();
?>
 

 <div class="container-fluid" style="padding-top:50px">
 
 <?
    $template->showBalanceBar();
 ?>
 
 </div>
 
 <br><br>
 
 <div class="row" style="padding-left:150px; padding-right:150px;">
 <div class="col-md-2">&nbsp;</div>
 <div class="col-md-8" align="center" id="div_container">
 <?
      if ($_REQUEST['act']=="signup")
	     $signup->doSignup($_REQUEST['txt_user'], 
		                   $_REQUEST['txt_pass_1'], 
						   $_REQUEST['txt_pass_2'], 
						   $_REQUEST['txt_email']);
 ?>
 <div style="padding-left:10px; padding-right:10px; background-color:#f5f5f5;">
  
  <?
     $signup->showSignupPanel();
  ?>
  
  </div>
 <div class="col-md-2">&nbsp;</div>
 </div>
 </div>
 
</body>
</html>
