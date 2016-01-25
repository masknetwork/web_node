<?
   include "../db.php";   
   include "CPrices.php";
   
   $db=new db();
   
    // BTCUSD	
    $raw=file_get_contents("http://www.coincap.io/coins");
	
	// Decode to array
	$data = json_decode($raw);
	$coin=$data[0];
	for ($a=0; $a<=1000; $a++)
	 if ($data[$a]!="") 
	   {
		   $symbol=substr($data[$a]."USD", 0, 6);
		   $query="INSERT INTO prices SET symbol='".$symbol."', price='0'"; 
		   $db->execute($query);
	   }
	   
	   print "Done.";
?>