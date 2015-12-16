<?
   session_start();
   include "../../../kernel/db.php";
   include "../../template/template/CTemplate.php";
   include "CLogin.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $login=new CLogin($db, $template);
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
@media screen and (max-width: 1000px)
{
 #but_login { height:75px; font-size:30px; }
 #txt_user { height:60px; font-size:20px; }
 #txt_pass { height:60px; font-size:20px; }
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
     if ($_REQUEST['act']=="login") 
	   $login->doLogin($_REQUEST['txt_user'], $_REQUEST['txt_pass']); 
 ?>
 
  <div style="padding-left:10px; padding-right:10px; background-color:#f5f5f5;">
  <?   
     $login->showLoginPanel();
  ?>
  </div>
 <div class="col-md-2">&nbsp;</div>
 </div>
 </div>
 
</body>
</html>
