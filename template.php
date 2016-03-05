<?
   include "./kernel/db.php";
   include "./pages/template/template/CTemplate.php";
   
   $db=new db();
   $template=new CTemplate($db);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<script src="./flat/js/vendor/jquery.min.js"></script>
<script src="./flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href=".//flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="./flat/css/flat-ui.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link rel="shortcut icon" href="./flat/img/favicon.ico">

<style>
@media screen and (max-width: 1000px)
{
 
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
 
 <div class="row">
 <div class="col-md-1" style="background-color:#f0f0f0">&nbsp;</div>
 <div class="col-md-8" align="center">
 
dfgf
 </div>
 <div class="col-md-2" style="background-color:#fafafa">&nbsp;</div>
 <div class="col-md-1" style="background-color:#f0f0f0">&nbsp;</div>
 </div>
 </div>
 
</body>
</html>
