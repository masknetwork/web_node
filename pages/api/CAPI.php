<?
class CAPI
{
	function CAPI($db)
	{
		$this->kern=$db;
	}
	
    function adrTrans($adr)
	{
	}
	
	function getAdrInfo($adr)
	{
		$query="SELECT * FROM adr WHERE adr='".$adr."'";
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)>0)
		{
	       $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		   $json="{";
		   $json=$json."\"adr\" : \"".$row['adr']."\", ";
		   $json=$json."\"balance\" : \"".$row['balance']."\", ";
		   $json=$json."\"created\" : \"".$row['created']."\", ";
		   $json=$json."\"last_interest\" : \"".$row['last_interest']."\", ";
		   $json=$json."\"block\" : \"".$row['block']."\", ";
		   $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
		   $json=$json."}";
		}
		else
		{
			$json="{\"adr\" : \"not found\"}";
		}
		
		// Return
		return $json;
	}
	
	function adrInfo($adr)
	{
		$json="{\"status\" : \"ok\", \"data\" : [";
		
		$adr=json_decode($adr); 
		for ($a=0; $a<=sizeof($adr)-1; $a++)
		   $json=$json.$this->getAdrInfo($adr[$a]).", ";
		
		$json=$json."]}";
		$json=str_replace(", ]}", "]}", $json);
		print $json;
	}
	
	function packetInfo($hash)
	{
	}
	
	function inject($data)
	{
	   if (strlen($data)<1000) 
	      $this->err("Invalid raw packet data");
	   
	   $query="INSERT INTO web_ops 
	                   SET op='ID_RAW_PACKET', 
					       par_1='".$data."', 
						   status='ID_PENDING'";	
	   $this->kern->execute($query);
	   $this->ok();   
	}
	
	function ok()
	{
		die("{\"result\" : \"passed\"}");
	}
	
	function err($reason)
	{
		die("{\"result\" : \"error\", \"reason\" : \"".$reason."\"}");
	}
	
	function auth($ses_key)
	{
	}
	
	function keyAPI($ses_key, $request)
	{
	}
	
    function freeAPI($request)
	{
	}
	
	function API($request)
	{
		switch ($request)
		{
			case "ID_INJECT_PACKET" : $this->inject($_REQUEST['data']); break;
			case "ID_ADR_INFO" : $this->adrInfo($_REQUEST['data']); break;
		}
	}
}
?>