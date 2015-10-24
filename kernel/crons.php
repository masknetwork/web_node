<?
    include "db.php";
	include "CCrons.php";
	
	$kern=new db();
	$crons=new CCrons($kern);
	$crons->runPendingAdr();
?>