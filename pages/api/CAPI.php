<?
class CAPI
{
	function CAPI($db)
	{
		$this->kern=$db;
		
		// Adr
		$this->adr_cols=array("ID", "adr", "balance", "created", "block", "sealed", "rowhash", "last_interest");
		
		// Ads
		$this->ads_cols=array("ID", "adr", "country", "title", "message", "link", "mkt_bid", "expire", "block", "rowhash");
		
		// Agents
		$this->agents_cols=array("ID", "adr", "owner", "name", "description", "pay_adr", "website", "pic", "globals", "signals", "interface", "code", "status", "exec_log", "categ", "ver", "run_period", "sealed", "price", "storage", "expire", "aID", "dir", "block", "hash");
		
		// Assets
		$this->assets_cols=array("ID", "adr", "symbol", "title", "description", "how_buy", "how_sell", "web_page", "pic", "expire", "qty", "trans_fee_adr", "trans_fee", "linked_mktID", "rowhash", "block");
		
		// Assets markets
		$this->assets_mkts_cols=array("ID", "adr", "asset", "cur", "name", "description", "decimals", "block", "expire", "last_price", "ask", "bid", "rowhash", "mktID");
		
		// Assets markets pos
		$this->assets_mkts_pos=array("ID", "adr", "mktID", "tip", "qty", "price", "block", "orderID", "rowhash", "expire");
		
		// Assets owners
		$this->assets_owners=array("ID", "owner", "symbol", "qty", "invested", "rowhash", "block");
		
		// Escrowed
		$this->escrowed_cols=array("ID", "trans_hash", "sender_adr", "rec_adr", "escrower", "amount", "cur", "block", "rowhash");
		
		// Profiles
		$this->profiles_cols=array("ID", "adr", "name", "pic_back", "pic", "description", "website", "rowhash", "email", "email",  "expire",  "block");
		
		// Tweets
		$this->escrowed_cols=array("ID", "tweetID", "adr", "mes", "pic_1", "pic_2", "pic_3", "pic_4", "pic_5", "video", "rowhash", "block", "retweet", "retweet_tweet_ID", "likes", "comments", "retweets");
		
		// Tweets comments
		$this->comments=array("ID", "adr", "parent_type", "parentID", "comID", "mes", "status", "rowhahs", "block");
		
		// Tweets follow
		$this->tweets_follow=array("ID", "adr", "follows", "expire", "block", "rowhash");
		
		// Tweets likes
		$this->tweets_follow=array("ID", "adr", "tweetID", "block", "rowhash");
		
		// Blocks
		$this->blocks=array("ID", "hash", "block", "prev_hash", "signer", "packets", "tstamp", "nonce", "size", "net_dif", "commited", "confirmations", "payload_hash", "tab_1", "tab_2", "tab_3", "tab_4", "tab_5", "tab_6", "tab_7", "tab_8", "tab_9", "tab_10", "tab_11", "tab_12", "tab_13", "tab_14", "tab_15", "signer_balance");
		
		// packets
		$this->packets=array("ID", "packet_hash", "par_1_name", "par_1_val", "par_2_name", "par_2_val", "par_3_name", "par_3_val", "par_4_name", "par_4_val", "par_5_name", "par_5_val", "block", "tstamp", "confirms", "block_hash", "payload_hash", "payload_size", "packet_type", "fee_src", "fee_amount", "fee_hash");
		
		// Trans
		$this->trans=array("ID", "src", "amount", "invested", "cur", "escrower", "hash", "tID", "block", "status", "tstamp");
		
		// Net stat
		$this->net_stat=array("ID");
	}
	
   
	function rowAdr($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"balance\" : \"".$row['balance']."\", ";
        $json=$json."\"created\" : \"".$row['created']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"sealed\" : \"".$row['sealed']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"last_interest\" : \"".$row['last_interest']."\"";
        $json=$json."}";
		
		// Return
		return $json;
	}
	
	function rowAds($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"country\" : \"".$row['country']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"title\" : \"".$row['title']."\", ";
        $json=$json."\"message\" : \"".$row['message']."\", ";
        $json=$json."\"link\" : \"".$row['link']."\", ";
        $json=$json."\"mkt_bid\" : \"".$row['mkt_bid']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

       // Return
	   return $json;
	}
	
	function rowAgents($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"owner\" : \"".$row['owner']."\", ";
        $json=$json."\"name\" : \"".$row['name']."\", ";
        $json=$json."\"description\" : \"".$row['description']."\", "; 
        $json=$json."\"pay_adr\" : \"".$row['pay_adr']."\", ";
        $json=$json."\"website\" : \"".$row['website']."\", ";
        $json=$json."\"pic\" : \"".$row['pic']."\", ";
        $json=$json."\"globals\" : \"".$row['globals']."\", ";
        $json=$json."\"signals\" : \"".$row['signals']."\", ";
        $json=$json."\"interface\" : \"".$row['interface']."\", ";
        $json=$json."\"code\" : \"".$row['code']."\", ";
        $json=$json."\"status\" : \"".$row['status']."\", ";
        $json=$json."\"exec_log\" : \"".$row['exec_log']."\", ";
        $json=$json."\"categ\" : \"".$row['categ']."\", ";   
        $json=$json."\"ver\" : \"".$row['ver']."\", ";
        $json=$json."\"run_period\" : \"".$row['run_period']."\", ";  
        $json=$json."\"sealed\" : \"".$row['sealed']."\", ";
        $json=$json."\"price\" : \"".$row['price']."\", "; 
        $json=$json."\"storage\" : \"".$row['storage']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"aID\" : \"".$row['aID']."\", ";
        $json=$json."\"dir\" : \"".$row['dir']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowAssets($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"symbol\" : \"".$row['symbol']."\", ";
        $json=$json."\"title\" : \"".$row['title']."\", ";
        $json=$json."\"description\" : \"".$row['description']."\", ";
        $json=$json."\"how_buy\" : \"".$row['how_buy']."\", ";
        $json=$json."\"how_sell\" : \"".$row['how_sell']."\", ";
        $json=$json."\"web_page\" : \"".$row['web_page']."\", ";
        $json=$json."\"pic\" : \"".$row['pic']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"qty\" : \"".$row['qty']."\", ";
        $json=$json."\"trans_fee_adr\" : \"".$row['trans_fee_adr']."\", ";
        $json=$json."\"trans_fee\" : \"".$row['trans_fee']."\", ";
        $json=$json."\"linked_mktID\" : \"".$row['linked_mktID']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowAssetsMkts($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"asset\" : \"".$row['asset']."\", ";
        $json=$json."\"cur\" : \"".$row['cur']."\", ";
        $json=$json."\"name\" : \"".$row['name']."\", ";
        $json=$json."\"description\" : \"".$row['description']."\", ";
        $json=$json."\"decimals\" : \"".$row['decimals']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"last_price\" : \"".$row['last_price']."\", ";
        $json=$json."\"ask\" : \"".$row['ask']."\", ";
        $json=$json."\"bid\" : \"".$row['bid']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"mktID\" : \"".$row['mktID']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function assetsMktsPos($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"mktID\" : \"".$row['mktID']."\", ";
        $json=$json."\"tip\" : \"".$row['tip']."\", ";
        $json=$json."\"qty\" : \"".$row['qty']."\", ";
        $json=$json."\"price\" : \"".$row['price']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"orderID\" : \"".$row['orderID']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function assetsOwners($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";  
        $json=$json."\"owner\" : \"".$row['owner']."\", ";
        $json=$json."\"symbol\" : \"".$row['symbol']."\", ";
        $json=$json."\"qty\" : \"".$row['qty']."\", ";
        $json=$json."\"invested\" : \"".$row['invested']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowDomains($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"domain\" : \"".$row['domain']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"sale_price\" : \"".$row['sale_price']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowEscrowed($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"trans_hash\" : \"".$row['trans_hash']."\", ";
        $json=$json."\"sender_adr\" : \"".$row['sender_adr']."\", ";
        $json=$json."\"rec_adr\" : \"".$row['rec_adr']."\", ";
        $json=$json."\"escrower\" : \"".$row['escrower']."\", ";
        $json=$json."\"amount\" : \"".$row['amount']."\", ";
        $json=$json."\"cur\" : \"".$row['cur']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowTrans($row)
	{
		$json="{"; 
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"src\" : \"".$row['src']."\", ";
        $json=$json."\"amount\" : \"".$row['amount']."\", ";
        $json=$json."\"invested\" : \"".$row['invested']."\", ";
        $json=$json."\"cur\" : \"".$row['cur']."\", ";
        $json=$json."\"escrower\" : \"".$row['escrower']."\", ";
        $json=$json."\"hash\" : \"".$row['hash']."\", ";
        $json=$json."\"tID\" : \"".$row['tID']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"status\" : \"".$row['status']."\", ";
        $json=$json."\"tstamp\" : \"".$row['tstamp']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowpackets($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"packet_hash\" : \"".$row['packet_hash']."\", ";
        $json=$json."\"par_1_name\" : \"".$row['par_1_name']."\", ";
        $json=$json."\"par_1_val\" : \"".$row['par_1_val']."\", ";
        $json=$json."\"par_2_name\" : \"".$row['par_2_name']."\", ";
        $json=$json."\"par_2_val\" : \"".$row['par_2_val']."\", ";
        $json=$json."\"par_3_name\" : \"".$row['par_3_name']."\", ";
        $json=$json."\"par_3_val\" : \"".$row['par_3_val']."\", ";
        $json=$json."\"par_4_name\" : \"".$row['par_4_name']."\", ";
        $json=$json."\"par_4_val\" : \"".$row['par_4_val']."\", ";
        $json=$json."\"par_5_name\" : \"".$row['par_5_name']."\", ";
        $json=$json."\"par_5_val\" : \"".$row['par_5_val']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"tstamp\" : \"".$row['tstamp']."\", ";
        $json=$json."\"confirms\" : \"".$row['confirms']."\", ";
        $json=$json."\"block_hash\" : \"".$row['block_hash']."\", ";
        $json=$json."\"payload_hash\" : \"".$row['payload_hash']."\", ";
        $json=$json."\"payload_size\" : \"".$row['payload_size']."\", ";
        $json=$json."\"packet_type\" : \"".$row['packet_type']."\", ";
        $json=$json."\"fee_src\" : \"".$row['fee_src']."\", "; 
        $json=$json."\"fee_amount\" : \"".$row['fee_amount']."\", ";
        $json=$json."\"fee_hash\" : \"".$row['fee_hash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowBlocks($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"hash\" : \"".$row['hash']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"prev_hash\" : \"".$row['prev_hash']."\", ";
        $json=$json."\"signer\" : \"".$row['signer']."\", ";
        $json=$json."\"packets\" : \"".$row['packets']."\", ";
        $json=$json."\"tstamp\" : \"".$row['tstamp']."\", ";
        $json=$json."\"nonce\" : \"".$row['nonce']."\", ";
        $json=$json."\"size\" : \"".$row['size']."\", ";
        $json=$json."\"net_dif\" : \"".$row['net_dif']."\", ";
        $json=$json."\"commited\" : \"".$row['commited']."\", ";
        $json=$json."\"confirmations\" : \"".$row['confirmations']."\", ";
        $json=$json."\"payload_hash\" : \"".$row['payload_hash']."\", ";
        $json=$json."\"tab_1\" : \"".$row['tab_1']."\", ";
        $json=$json."\"tab_2\" : \"".$row['tab_2']."\", ";
        $json=$json."\"tab_3\" : \"".$row['tab_3']."\", ";
        $json=$json."\"tab_4\" : \"".$row['tab_4']."\", ";
        $json=$json."\"tab_5\" : \"".$row['tab_5']."\", ";
        $json=$json."\"tab_6\" : \"".$row['tab_6']."\", ";
        $json=$json."\"tab_7\" : \"".$row['tab_7']."\", ";
        $json=$json."\"tab_8\" : \"".$row['tab_8']."\", ";
        $json=$json."\"tab_9\" : \"".$row['tab_9']."\", ";
        $json=$json."\"tab_10\" : \"".$row['tab_10']."\", ";
        $json=$json."\"tab_11\" : \"".$row['tab_11']."\", ";
        $json=$json."\"tab_12\" : \"".$row['tab_12']."\", ";
        $json=$json."\"tab_13\" : \"".$row['tab_13']."\", ";
        $json=$json."\"tab_14\" : \"".$row['tab_14']."\", ";
        $json=$json."\"tab_15\" : \"".$row['tab_15']."\", ";   
        $json=$json."\"signer_balance\" : \"".$row['signer_balance']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowTweetsComments()
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"parent_type\" : \"".$row['parent_type']."\", ";
        $json=$json."\"parentID\" : \"".$row['parentID']."\", ";
        $json=$json."\"comID\" : \"".$row['comID']."\", ";
        $json=$json."\"mes\" : \"".$row['mes']."\", ";
        $json=$json."\"status\" : \"".$row['status']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowTweetsFollow()
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"follows\" : \"".$row['follows']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowTweetsLikes($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"tweetID\" : \"".$row['tweetID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function runTweets()
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"tweetID\" : \"".$row['tweetID']."\", "; 
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"mes\" : \"".$row['mes']."\", ";
        $json=$json."\"pic_1\" : \"".$row['pic_1']."\", ";
        $json=$json."\"pic_2\" : \"".$row['pic_2']."\", ";
        $json=$json."\"pic_3\" : \"".$row['pic_3']."\", ";
        $json=$json."\"pic_4\" : \"".$row['pic_4']."\", ";
        $json=$json."\"pic_5\" : \"".$row['pic_5']."\", ";
        $json=$json."\"video\" : \"".$row['video']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\", ";
        $json=$json."\"retweet\" : \"".$row['retweet']."\", ";
        $json=$json."\"retweet_tweet_ID\" : \"".$row['retweet_tweet_ID']."\", ";
        $json=$json."\"likes\" : \"".$row['likes']."\", "; 
        $json=$json."\"comments\" : \"".$row['comments']."\", ";
        $json=$json."\"retweets\" : \"".$row['retweets']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowProfiles($row)
	{
		$json="{";
        $json=$json."\"ID\" : \"".$row['ID']."\", ";
        $json=$json."\"adr\" : \"".$row['adr']."\", ";
        $json=$json."\"name\" : \"".$row['name']."\", ";
        $json=$json."\"pic_back\" : \"".$row['pic_back']."\", ";
        $json=$json."\"pic\" : \"".$row['pic']."\", ";
        $json=$json."\"description\" : \"".$row['description']."\", "; 
        $json=$json."\"website\" : \"".$row['website']."\", ";
        $json=$json."\"rowhash\" : \"".$row['rowhash']."\", ";
        $json=$json."\"email\" : \"".$row['email']."\", ";
        $json=$json."\"expire\" : \"".$row['expire']."\", ";
        $json=$json."\"block\" : \"".$row['block']."\"";
        $json=$json."}";

        // Return
        return $json;
	}
	
	function rowNetStat($row)
	{
		$json="{";
        $json=$json."\"last_block\" : \"".$row['last_block']."\", ";
        $json=$json."\"last_block_hash\" : \"".$row['last_block_hash']."\", ";
        $json=$json."\"net_dif\" : \"".$row['net_dif']."\", ";
        $json=$json."}";

        // Return
        return $json;
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
	
	function colExist($col, $array)
	{
		for ($a=0; $a<=sizeof($array); $a++)
		  if ($col==$array[$a])
		    return true;
		 
		 return false;
	}
	
	function info($table, $col, $req_type, $val, $min, $max)
	{
		// Table
		if ($table!="adr" && 
		    $table!="ads" && 
			$table!="agents" && 
			$table!="assets" && 
			$table!="ssets_mkts" && 
			$table!="assets_mkts_pos" && 
			$table!="assets_owners" && 
			$table!="domains" && 
			$table!="escrowed" && 
			$table!="profiles" && 
			$table!="tweets" && 
			$table!="tweets_likes" && 
			$table!="tweets_follow" && 
			$table!="net_stat" && 
			$table!="comments")
		$this->err("Invalid table");
		
		// Net stat ?
		if ($table=="net_stat")
		{
			$col="ID";
			$range="exact";
			$val=1;
		}
		
	    // Column
		if (($table=="adr" && $this->colExist($col, $this->adr_cols)==false) || 
		    ($table=="ads" && $this->colExist($col, $this->ads_cols)==false) || 
			($table=="agents" && $this->colExist($col, $this->agents_cols)==false) || 
			($table=="assets" && $this->colExist($col, $this->assets_cols)==false) || 
			($table=="assets_mkts" && $this->colExist($col, $this->assets_mkts_cols)==false) || 
			($table=="assets_mkts_pos" && $this->colExist($col, $this->assets_mkts_pos_cols)==false) || 
			($table=="assets_owners" && $this->colExist($col, $this->assets_owners_cols)==false) || 
			($table=="domains" && $this->colExist($col, $this->domains_cols)==false) || 
			($table=="escrowed" && $this->colExist($col, $this->escrowed_cols)==false) || 
			($table=="profiles" && $this->colExist($col, $this->profiles_cols)==false) || 
			($table=="tweets" && $this->colExist($col, $this->tweets_cols)==false) || 
			($table=="tweets_likes" && $this->colExist($col, $this->tweets_likes_cols)==false) || 
			($table=="tweets_follow" && $this->colExist($col, $this->tweets_follow_cols)==false) || 
			($table=="comments" && $this->colExist($col, $this->comments_cols)==false))	
		{
		  	$this->err("Invalid column name");
			return false;
		}
		
		// Req type
		if ($req_type!="exact" && 
		    $req_type!="range")
		{
			$this->err("Invalid request type");
			return false;
		}
		
		// Value, min, max
		if (strlen($val)>100 || 
		    strlen($min)>100 || 
			strlen($max)>100)
		{
			$this->err("Invalid value, min or max");
			return false;
		}
		
		
		// Query
		if ($req_type=="exact")
		   $query="SELECT * 
		             FROM ".$table." 
					WHERE ".$col."='".$val."'";
		else
		   $query="SELECT * 
		             FROM ".$table." 
					WHERE ".$col.">=".$min." 
					  AND ".$col."<=".$max;
		
		// Execute
		$result=$this->kern->execute($query);
		
	    // Has data
		if (mysql_num_rows($result)==0)
		{
			$this->err("No data");
			return false;
		}
		
		// Return data
		$a=0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$a++;
			
			switch ($table)
			{
				// adr
				case "adr" : $json=$json.", ".$this->rowAdr($row); break;
				
				// Ads
				case "ads": $json=$json.", ".$this->rowAds($row); break;
				
				// Agents
				case "agents" : $json=$json.", ".$this->rowAgents($row); break;
				
				// Assets
				case "assets" : $json=$json.", ".$this->rowAssets($row); break;
				
				// Assets markets
				case "assets_mkts" : $json=$json.", ".$this->rowAssetsMkts($row); break;
				
				// Assets markets pos
				case "assets_mkts_pos" : $json=$json.", ".$this->rowAssetsMktsPos($row); break;
				
				// Assets owners
				case "assets_owners" : $json=$json.", ".$this->rowAssetsOwners($row); break;
				
				// Domains
				case "domains" : $json=$json.", ".$this->rowDomains($row); break;
				
				// Escrowed
				case "escrowed" : $json=$json.", ".$this->rowEscrowed($row); break;
				
				// Profiles
				case "profiles" : $json=$json.", ".$this->rowProfiles($row); break;
				
				// Tweets
				case "tweets" : $json=$json.", ".$this->rowTweets($row); break;
				
				// Tweets likes 
				case "tweets_likes" : $json=$json.", ".$this->rowTweetsLikes($row); break;
				
				// Tweets follow
				case "tweets_follow" : $json=$json.", ".$this->rowTweetsFollow($row); break;
				
				// Tweets comments
				case "comments" : $json=$json.", ".$this->rowTweetsComments($row); break;
				
				// Blocks
				case "blocks" : $json=$json.", ".$this->rowBlocks($row); break;
				
				// packets
				case "packets" : $json=$json.", ".$this->rowpackets($row); break;
				
				// transactions
				case "trans" : $json=$json.", ".$this->rowTrans($row); break;
				
				// net_stat
				case "net_stat" : $json=$json.", ".$this->rowNetStat($row); break;
			}
		}
		
		$json=substr($json, 1, strlen($json));
		$json="{\"result\" : \"passed\", \"rows\" : [".$json."]}";
		$json=str_replace(", },", "},", $json);
		$json=str_replace(",},", "},", $json);
		
		print $json;
	}
	
	
	
	function generate($table)
	{
		$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'wallet' AND TABLE_NAME = '".$table."'";
		$result=$this->kern->execute($query);
		
		print "#son=\"{\";<br>";
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			print "#json=#json.\"\\\"".$row['COLUMN_NAME']."\\\" : \\\"\".#row['".$row['COLUMN_NAME']."'].\"\\\", \";<br>";
		
		print "#json=#json.\"}\";<br><br>";
		print "// Return<br>
	          return #json;";
	}
	
	function cols($table)
	{
		$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'wallet' AND TABLE_NAME = '".$table."'";
		$result=$this->kern->execute($query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		   print "\"".$row['COLUMN_NAME']."\", ";
	}
	
	
}
?>