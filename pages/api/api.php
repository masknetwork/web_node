<?
    include "../../kernel/db.php";
	include "../../kernel/CUserData.php";
	include "../template/template/CTemplate.php";
	include "../../kernel/CSysData.php";
	include "CAPI.php";
	
	$db=new db();
	$sd=new CSysData($db);
	if (isset($_REQUEST['key'])) $ud=new CUserData($db);
	$template=new CTemplate();
	$api=new CAPI($db);
	$api->API($_REQUEST['act']);
	
?>

<form method="post" action="api.php?act=ID_ADR_INFO">
<textarea rows="10" style="width:500px" name="data" id="data"></textarea>
<button type="submit">Submit</button>
</form>
