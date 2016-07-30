<?
    include "./kernel/db.php";
    include "CTest.php";
	
	$db=new db();
	$test=new CTest($db);
	$test->generate("ads");
	print "<br><br>";
	
	$test->generate("assets");
	print "<br><br>";
	
	$test->generate("agents");
	print "<br><br>";
	
	$test->generate("assets_owners");
	print "<br><br>";
	
	$test->generate("assets_mkts");
	print "<br><br>";
	
	$test->generate("assets_mkts_pos");
	print "<br><br>";
	
	$test->generate("blocks");
	print "<br><br>";
	
	$test->generate("domains");
	print "<br><br>";
	
	$test->generate("escrowed");
	print "<br><br>";
	
	$test->generate("feeds_bets");
	print "<br><br>";
	
	$test->generate("feeds_bets_pos");
	print "<br><br>";
	
	$test->generate("feeds_spec_mkts");
	print "<br><br>";
	
	$test->generate("feeds_spec_mkts_pos");
	print "<br><br>";
	
	$test->generate("footprints");
	print "<br><br>";
	
	$test->generate("tweets");
	print "<br><br>";
	
	$test->generate("tweets_likes");
	print "<br><br>";
	
	$test->generate("tweets_follow");
	print "<br><br>";
	
	$test->generate("tweets_comments");
	print "<br><br>";
?>
