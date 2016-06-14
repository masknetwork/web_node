<?
    include "../db.php";
	include "../CKernel.php";
	include "../CTemplate.php";
	include "../CSysData.php";
	include "../CAPI.php";
	
	$db=new db();
	$sd=new CSysData($db);
	$template=new CTemplate();
	$kernel=new CKernel($db, $template);
	$api=new CAPI($db);
?>

<form action="test.php?act=inject" method="post">
<textarea name="txt_data" id="txt_data" rows="10" style="width:300px"></textarea>
</form>