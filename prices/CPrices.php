<?
class CPrices
{
	function CPrices($db)
	{
		$this->kern=$db;
	}
	
	function stocksRates()
	{
		 
	   $access_key = 'f708843caaa5744ed546b50b6c8eb596-554368eb46706c833142fe193db0ea54';
	   $ins=$ins."EUR_USD%2CBCO_USD%2CCORN_USD%2CNATGAS_USD%2CSOYBN_USD%2CSUGAR_USD%2CWHEAT_USD%2CXCU_USD%2CXAU_USD%2CXAG_USD%2CXPT_USD%2CXPD_USD%2CXPT_USD%2CXPD_USD%2C";
	   $ins=$ins."AU200_AUD%2CCH20_CHF%2CDE30_EUR%2CEU50_EUR%2CFR40_EUR%2CHK33_HKD%2CJP225_JPY%2CNL25_EUR%2CSG30_SGD%2CSPX500_USD%2CUK100_GBP%2CUS2000_USD%2CUS30_USD%2CNAS100_USD";
	   $curl = curl_init("https://api-fxtrade.oanda.com/v1/prices?instruments=".$ins);
	   
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . $access_key ));
       $ticker=curl_exec( $curl );
	  
	   $data=json_decode($ticker, true); print var_dump($data);
	}
	
	function updatePrices($symbol)
	{
	     // BTCUSD	
		 $raw=file_get_contents("http://www.coincap.io/front");
	
	     // Decode to array
		 $data = json_decode($raw);
		 
		 for ($a=0; $a<=1000; $a++)
		 if ($data[$a]->short!="") 
		 {
			 $symbol=substr($data[$a]->short."USD", 0, 6);
			 
			 $query="UPDATE prices 
			            SET price='".$data[$a]->price."' 
					  WHERE symbol='".$symbol."'";
			 $this->kern->execute($query);
		 }
	     
		 print "Done";	  
	}
	
	
	
	
	function getFeed($feed_symbol)
	{
		$query="SELECT * FROM prices";
		$result=$this->kern->execute($query);
		
		$res="{\"response\":[";
		
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		  $res=$res."{\"symbol\" : \"".$row['symbol']."\", \"price\" : \"".$row['price']."\", \"status\":\"ID_OPEN\"},"; 
		
		$res=$res."]}";
		print str_replace("},]", "}]", $res);
	}
}
?>