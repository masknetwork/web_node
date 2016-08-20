<?php  
 //if ($_SERVER['HTTP_CF_CONNECTING_IP']!="89.149.21.42") die ("Maintainance in progress. Try again in a few hours.");
   
 class db
  {
     var $html=array("<", ">", "http://", "http:", "http", "javascript");

	 function db()
	 {
		 error_reporting(E_ERROR);
         $host=$_SERVER['HTTP_HOST'];
		
		 if ($host=="localhost")
		 {
			 $ho="127.0.0.1:3306:/tmp/mysql.sock";
			 $user="root";
			 $pass="";
			 $db="wallet";
			 $_REQUEST['sd']['server']="localhost";
		 }
		 else
		 {
			 $user="wallet";
			 $pass="dicatrenu";
			 $db="wallet";
			 $ho="localhost";
		 }
		
         $this->con = mysql_connect($ho, $user, $pass)
            or die("Could not connect: " . mysql_error());
 		 mysql_select_db($db, $this->con);	
		 
		 error_reporting(E_ERROR);
         ini_set("display_errors", "1");
		 
		 foreach($_REQUEST as $key => $value)
		 {
		    if (!strpos($_REQUEST[$key], "'")===false) 
			die ("Invalid characters");
		 }
	}
	
	 function new_con($host, $db, $user, $pass)
	 {
         $con = mysql_connect($host, $user, $pass)
            or die("Could not connect: " . mysql_error()." local");
 		 mysql_select_db($db, $con);
		 return $con;
	}	
	 	
	  function isSealed($adr)
	{
		if ($this->hasAttr($adr, "ID_SEALED")==true)
		  return true;
		else
		  return false;
	}
	
	function hasAttr($adr, $attr)
	{
		$adr=$this->adrFromDomain($adr);
		
		$query="SELECT * 
		          FROM adr_options 
				 WHERE adr='".$adr."' 
				   AND op_type='".$attr."'"; 
		$result=$this->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;
	}
	
	function myDomain($domain)
	{
		 $query="SELECT * 
	             FROM my_adr 
				 JOIN domains AS dom ON my_adr.adr=dom.adr 
				 WHERE domain='".$domain."' 
				   AND userID='".$_REQUEST['ud']['ID']."'";
	   $result=$this->execute($query);
	   
	   if (mysql_num_rows($result)==0)
	      return false;
   	   else 
	      return true;
	}
	
	
	function domainExist($domain)
	{
		if ($this->isDomain($domain)==false) 
		   return false;
		
		$query="SELECT * 
		          FROM domains 
				 WHERE domain='".$domain."'";
		$result=$this->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;
	}
	
	
	  function execute($query)
	  {  
	     if ($_REQUEST['mode']=="ID_DEBUG") 
		   print $query."<br><br>";
		 $result=mysql_query($query, $this->con); 
		 return $result;
	  }
	
	
	 function feeAdrValid($adr)
	 {
		// Is contract ?
		$query="SELECT * FROM agents WHERE adr='".$adr."'"; 
		$result=$this->execute($query);	
		if (mysql_num_rows($result)>0) return false;
		
		// Has attributes
		$query="SELECT * FROM adr_options WHERE adr='".$adr."'"; 
		$result=$this->execute($query);	
		if (mysql_num_rows($result)>0) return false;
		
		return true;
	 }
	
	  function adrExist($adr)
	{
		
		if ($this->adrValid($adr)==false) return false;
		
		if (strlen($adr)>30)
		{
		   $query="SELECT * FROM adr WHERE adr='".$adr."'";
		   $result=$this->execute($query);	
		
		   if (mysql_num_rows($result)>0)
		      return true;
		   else
		      return false;
		}
		else
		{
			if ($this->domainExist($adr)==false)
			   return false;
			 else
			   return true;
		}
	}
	
	function adrValid($adr)
	{
		if (strlen($adr)!=108 && 
		    strlen($adr)!=124 && 
		    strlen($adr)!=160 && 
		    strlen($adr)!=212) 
	       return false;
					
		   for ($a=0; $a<=strlen($adr)-1; $a++)
	  	   {
			   if (ord($adr[$a])!=47 && 
			    ord($adr[$a])!=43 && 
			    ord($adr[$a])!=61 && 
				$this->isLetter(ord($adr[$a]))==false && 
				$this->isFigure(ord($adr[$a]))==false) 
			 return false;
		   }
		
		
		return true;
	}
	
	function isLetter($char)
	{
		if ($char>=65 && $char<=90) return true;
		if ($char>=97 && $char<=122) return true;
		return false;
	}
	
	function isFigure($char)
	{
		if ($char>=48 && $char<=57) return true;
		return false;
	}
	
	  function redirect($target)  
	  {
	     print "<script>window.location.href='".$target."'</script>"; 
	  }
	   	  

	 function getTrackID()
	  {
         $t=str_replace(".","",microtime());
	     $t=str_replace(" ","",$t);	  
		 $t=$t.rand(0,9);
		 return $t;
	  }
	  
	  
	  function makeNumber($str, $tip="integer", $precizion=2)
	  {
        $num="";
		
		$str=trim($str);
		$str=str_replace(",", ".", $str);
	    for ($a=0; $a<=strlen($str)-1; $a++) 
		{
		  if ($tip=="integer")
		  {
		    if (ord($str[$a])>=48 && ord($str[$a])<=57)
		      $num=$num.$str[$a];
		    else
		      return "0";
		  }

		  if ($tip=="decimal")
		  {
		    if ((ord($str[$a])>=48 && ord($str[$a])<=57) || ord($str[$a])==46)
		      $num=$num.$str[$a];
		    else
		      {
			    print ord($str[$a]); return "0";
			  }
		  }	  
		}   
		if (strlen($num)==0) $num="0";   
		
		if ($tip=="integer") return $num;
		  else return round($num, $precizion);
	  }


       function begin()  {  mysql_query("BEGIN");  }
       function commit() {  mysql_query("COMMIT");  }
       function rollback() { mysql_query("ROLLBACK"); }
	   
  function getAbsTime($interval, $tip="ID_PAST")
  {
	 
	if ($tip=="ID_PAST")  
	  $interval=time()-$interval; 
	else
	  $interval=$interval-time();
	  
	
    if ($force_interval=="")
	{
    if ($interval<60) 
	  {
	     $time=$interval." seconds";		 
		 if ($interval==1) $time=$interval." second";
	  }	 
    if ($interval>=60 && $interval<3600) 
	{
	   $time=round($interval/60)." minutes";
	   if (round($interval/60)==1) $time=round($interval/60)." minut";
	}
	   
    if ($interval>=3600 && $interval<86400) 
	{
	   $time=round($interval/3600)." hours";
	   if (round($interval/3600)==1) $time=round($interval/3600)." hour";
	}
	   
    if ($interval>=86400 && $interval<2592000) 
	{
	   $time=round($interval/86400)." days";
	   if (round($interval/86400)==1) $time=round($interval/86400)." day";
	}
	   
    if ($interval>=2592000 && $interval<31104000) 
	{
	   $time=round($interval/2592000)." months";
	   if (round($interval/2592000)==1) $time=round($interval/2592000)." month";
	}
	
	if ($interval>=31104000) 
	{
	   $time=round($interval/31104000)." years";
	   if (round($interval/31104000)==1) $time=round($interval/31104000)." year";
	}   
	}
	
	return $time;
  }
	
	function encrypt($txt)
	{
		return ($this->strToHex($txt));
	}
	
	
	
    function new_act($act, $par_1, $par_2, $par_3, $tID)
    {
	    $expl=mysql_real_escape_string($expl);
        $query="INSERT INTO actions
                                  SET userID='".$_REQUEST['ud']['ID']."',
			                             act='".$act."',
                                         par_1='".$par_1."',
				                         par_2='".$par_2."',
				                         par_3='".$par_3."',
                                         tID='".$tID."',
                                         tstamp='".time()."',
                                         IP='".$_SERVER['HTTP_CF_CONNECTING_IP']."',
				                         session='".$_REQUEST['key']."',
									     URL='".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."',
										 tara='".$_SERVER["HTTP_CF_IPCOUNTRY"]."'";
	   $this->execute($query);
	   
	   $query="UPDATE users 
	                      SET last_action='".time()."' 
					 WHERE ID='".$_REQUEST['ud']['ID']."'";
		$this->execute($query);
    }
  
  
    function bb_parse($string) 
	{
	    $string=str_replace(chr(13), "<br>", $string);
        while (preg_match_all('`\[(.+?)=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {			    
                case 'b': $replacement = "<strong>$innertext</strong>"; break;
                case 'i': $replacement = "<em>$innertext</em>"; break;
                case 'size': $replacement = "<span style=\"font-size: $param;\">$innertext</a>"; break;
                case 'color': $replacement = "<span style=\"color: $param;\">$innertext</a>"; break;
                case 'center': $replacement = "<div class=\"centered\">$innertext</div>"; break;
                case 'quote': $replacement = "<blockquote>$innertext</blockquote>" . $param? "<cite>$param</cite>" : ''; break;
                case 'url': $replacement = '<a target="blank" class="marox12" href="' . ($param? $param : $innertext) . "\">$innertext</a>"; break;
                case 'img':
                    list($width, $height) = preg_split('`[Xx]`', $param);
                    $replacement = "<img style=\"max-width:550px;\" border=\"0\" src=\"$innertext\" " . (is_numeric($width)? "width=\"$width\" " : '') . (is_numeric($height)? "height=\"$height\" " : '') . '/>';
                break;
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
    }
	
	function txtExplode($str)
	{
		$f=0;
		$s="";
		
		for ($a=0; $a<=strlen($str)-1; $a++)
		{
			if ($str[$a]!=" ") 
			   $f++;
			else
			   $f=0;
			   
		    $s=$s.$str[$a]; 
			
			if ($f>=50)
			{
			  $s=$s." "; 
			  $f=0;
			}
		}
		
		return $s;
	}
	
	function split($amount, $prec=2)
	{
		$amount=round($amount, 2);
		$v=explode(".", $amount);
		if (sizeof($v)==1) $v[1]="00";
		return $v;
	}
	
	function showErr($err, $size=490, $class="inset_rosu_10")
   {	   
   ?>

        <table width="<? print $size; ?>" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="50"><img src="../../../global/panel_err_left.gif"  /></td>
        <td width="<? print ($size-55); ?>" background="../../../global/panel_err_middle.gif" class="<? print $class; ?>" align="left">
        <? print $err; ?>
        </td>
        <td width="5"><img src="../../../global/panel_err_right.gif"  /></td>
        </tr>
        </table>

   <?
   }

     function showOk($err, $size=460, $class="text_verdex10")
   {
   ?>

        <table width="<? print $size; ?>" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="50"><img src="../../../global/panel_ok_left.gif" /></td>
        <td width="<? print ($size-55); ?>" background="../../../global/panel_ok_middle.gif" class="<? print $class; ?>">
        <div align="left">
		<? 
		   print $err; 
		   $timp=round((microtime()-$_REQUEST['start']), 2);
		   if ($_REQUEST['debug']==1) print " ( <strong>".$_REQUEST['qrs']." queries - ".$timp." seconds</strong> )";
		?></div>
        </td>
        <td width="5"><img src="../../../global/panel_ok_right.gif" /></td>
        </tr>
        </table>

   <?
   } 
   
   
  

function strToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function hexToStr($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}


	function newAct($tip, $par_1="", $par_2="", $par_3="", $par_4="")
	{
		$query="INSERT INTO web_actions 
		                SET userID='".$_SESSION['userID']."', 
						    tip='".$tip."', 
							par_1='".$par_1."', 
							par_2='".$par_2."', 
							par_3='".$par_3."', 
							tstamp='".time()."', 
							IP='".$_SERVER['REMOTE_ADDR']."'";
	}
	
	function paginateNextPrev($afisate,$pn, $total_pag, $act, $div, $par=0) {
	if($_REQUEST['txt_search'])
	{
		$par=$_REQUEST['txt_search'];	
	}
	
		?>

<table width="70" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td width="70">&nbsp;</td>
	<? 
        if ($pn > 1 && $total_pag>1) print "<td align='center'><img src='../template/GIF/paging_left_off.png' width='30' height='30' id='back'/></td>";
        if ($pn <$total_pag && $total_pag>1) print "<td align='center'><img src='../template/GIF/paging_right_off.png'  width='30' height='30' id='forw'/></td>";
     ?>
 </tr>
</table> 

<script>

$("#forw").hover(
		function() {$(this).attr("src","../template/GIF/paging_right_on.png"); },
		function() {$(this).attr("src","../template/GIF/paging_right_off.png");
});

$("#back").hover(
		function() {$(this).attr("src","../template/GIF/paging_left_on.png"); },
		function() {$(this).attr("src","../template/GIF/paging_left_off.png");
});

$('#forw').css("cursor", "pointer");
	$("#forw").click(function() {
		$("#<?php echo $div; ?>").fadeOut("fast", function(){
		$("#<?php echo $div; ?>").load("../main/get_page.php?data=<?php print $this->URLEncrypt("act=".$act."&pn=".($pn+1)."&par=".$par);?>", function() {
		$("#<?php echo $div; ?>").fadeIn("fast");
		});
		});
	 });

$('#back').css("cursor", "pointer");
	$("#back").click(function() {
		$("#<?php echo $div; ?>").fadeOut("fast", function(){
		$("#<?php echo $div; ?>").load("../main/get_page.php?data=<?php print $this->URLEncrypt("act=".$act."&pn=".($pn-1)."&par=".$par);?>", function() {
		$("#<?php echo $div; ?>").fadeIn("fast");
		});
		});
	 });
</script>

<?php
	}

	function splitAdr($adr, $length)
	{
		$c=0;
		$str="";
		
		for ($a=0; $a<=strlen($adr)-1; $a++)
		{
			$str=$str.$adr[$a]; 
			$c++;
			
			if ($c>=$length)
			{
				$c=0;
			    $str=$str."<br>";	
			}
		}
		
		return $str;
	}
	
	function block()
	{
		return floor(time()/60);
	}
	
	function sendBlock()
	{
		$query="INSERT INTO web_ops 
		                SET user='root',
						    op='ID_SEND_CBLOCK', 
						    status='ID_PENDING', 
							tstamp='".time()."'";
		$this->execute($query);
	}
	
	function isMarketAdr($adr)
	{
	   // Assets markets
	   $query="SELECT * 
	             FROM assets_markets 
				WHERE mkt_adr='".$adr."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	  
	   // Feeds markets
	   $query="SELECT * 
	             FROM feeds_markets 
				WHERE adr='".$adr."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	   
	   // Bets ?
	    $query="SELECT * 
	             FROM feeds_bets 
				WHERE adr='".$adr."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	  
	   // Return
	   return false;
	}
	
	function isMarketSymbol($symbol)
	{
	   // Assets markets
	   $query="SELECT * 
	             FROM assets_markets 
				WHERE mkt_symbol='".$symbol."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	  
	   // Feeds markets
	   $query="SELECT * 
	             FROM feeds_markets 
				WHERE mkt_symbol='".$symbol."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	   
	   // Bets ?
	    $query="SELECT * 
	             FROM feeds_bets 
				WHERE bet_symbol='".$symbol."'";
	   $result=$this->execute($query);	
	   if (mysql_num_rows($result)>0)
	     return true;
	  
	   // Return
	   return false;
	}
	
	function isMine($adr)
	{
		$query="SELECT * 
		          FROM my_adr 
				 WHERE userID='".$_REQUEST['ud']['ID']."' 
				   AND adr='".$adr."'"; 
		$result=$this->execute($query);
		
		 if (mysql_num_rows($result)>0)
	        return true;
		 else
		    return false;
	}
	
	
	function getBalance($adr, $asset="MSK")
	{
		if ($asset=="MSK")
		{
		   $query="SELECT * 
		          FROM adr 
				 WHERE adr='".$adr."'"; 
		   $result=$this->execute($query);	
		
	       if (mysql_num_rows($result)>0) 
		   {
			  $row = mysql_fetch_array($result, MYSQL_ASSOC);
			  return $row['balance'];
		   }
		   else
		      return 0;
		}
		else
		{
			$query="SELECT * 
		          FROM assets_owners 
				 WHERE owner='".$adr."'
				   AND symbol='".$asset."'"; 
		   $result=$this->execute($query);	
		
	      if (mysql_num_rows($result)>0) 
		   {
			  $row = mysql_fetch_array($result, MYSQL_ASSOC);
			  return $row['qty'];
		   }
		   else
		      return 0;
		}
	}
	
	function getMyBalance($asset="MSK")
	{
		// Balance
		$balance=0;
		
		// Load addresses
		$query="SELECT * 
		          FROM my_adr 
				 WHERE userID='".$_REQUEST['ud']['ID']."'";
		 $result=$this->execute($query);	
	     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		    $adr_list=$adr_list.", '".$row['adr']."'";
	     
		 // Format list
		 $adr_list=substr($adr_list, 1);
		 
		 if ($asset=="MSK")
		 {
		   $query="SELECT * 
		             FROM adr 
				    WHERE adr IN (".$adr_list.")";
		   $result=$this->execute($query);	
		
	       if (mysql_num_rows($result)>0) 
		     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			     $balance=$balance+$row['balance'];
		   else
		      return 0;
		 }
		 else
		 {
			$query="SELECT * 
		              FROM assets_owners 
				     WHERE symbol='".$asset."'
				       AND owner IN (".$adr_list.")"; 
		   $result=$this->execute($query);	
		
	      if (mysql_num_rows($result)>0) 
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			     $balance=$balance+$row['qty'];
		   else
		      return 0;
		}
		
		return $balance;
	}
	
	function symbolValid($symbol)
	{
		if (preg_match("/^[A-Z0-9]{6}$/", $symbol)!=1)
		   return false;
		else
		   return true;
	}
	
	
	
	function getMyFirstAdr()
	{
		$query="SELECT ma.adr, adr.balance, dom.domain 
		           FROM my_adr AS ma 
			  LEFT JOIN adr ON ma.adr=adr.adr
			  LEFT JOIN domains AS dom ON dom.adr=ma.adr
			  WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
			ORDER BY balance DESC"; 
			
		 $result=$this->execute($query);	
		 $row = mysql_fetch_array($result, MYSQL_ASSOC);
		 return $row['adr'];
	}
	
	function feedExist($feed)
	{
		$query="SELECT * 
		          FROM feeds 
				 WHERE symbol='".$feed."'";
		$result=$this->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		 else 
		   return false; 
	}
	
	function branchExist($feed, $branch)
	{
		$query="SELECT * 
		          FROM feeds_branches 
				 WHERE feed_symbol='".$feed."'
				   AND symbol='".$branch."'"; 
		$result=$this->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		 else 
		   return false; 
	}
	
	function adrFromDomain($domain)
	{
		// Is address
		if ($this->isAdr($domain)==true) 
		   return $domain;
	    
		// Is domain ?
		if ($this->isDomain($domain)==true)
	    {
		   $query="SELECT * 
		             FROM domains 
				    WHERE domain='".$domain."'"; 
		   $result=$this->execute($query);
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   return $row['adr'];
		}
		else return "";
	}
	
	function domainFromAdr($adr)
	{
		$query="SELECT * FROM domains WHERE adr='".$adr."'";
		$result=$this->execute($query);
		
		if (mysql_num_rows($result)==0)
		{ 
		   return $adr;
		}
		else
		{
		  $row = mysql_fetch_array($result, MYSQL_ASSOC);
		  return $row['domain'];
		}
	}
	
	function assetExist($asset)
	{
		$query="SELECT * 
		          FROM assets 
				 WHERE symbol='".$asset."'";
		$result=$this->execute($query);
			
		if (mysql_num_rows($result)>0)
		  return true;
		else
		  return false;
	}
	
	function privKeyValid($key)
	{
		return true;
	}
	
	
	
	function timeFromBlock($block)
	{
		$dif=abs($block-$_REQUEST['sd']['last_block']); 
	    	
		if ($dif<60) return $dif." minutes";
		else if ($dif>60 && $dif<=1440) return round($dif/60)." hours";
		else if ($dif>1440 && $dif<=43200) return round($dif/1440)." days";
		else if ($dif>43200 && $dif<=525600) return round($dif/43200)." months";
		else if ($dif>525600) return round($dif/525600)." years";
	}
	
	function getFeedVal($feed, $branch)
	{
		$query="SELECT * 
		          FROM feeds_branches 
				 WHERE feed_symbol='".$feed."' 
				   AND symbol='".$branch."'";
		$result=$this->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    return $row['val'];
	}
	
	function isCur($symbol)
	{
		// Upper case
		$symbol=strtoupper($symbol);
		
		// MSK ?
		if ($symbol=="MSK") return true;
		
		// Length
		if (strlen($symbol)!=6)
		  return false;
		
		// Six characters
		if (preg_match("/^[A-Z]{6}$/", $symbol)!=1)
		   return false;
		   
		// Match
		return true;
	}
	
	function isTitle($title)
	{
		// Valid characters
		if ($this->isString($title)==false)
		   return false;
	    
		// Length
		if (strlen($title)>100 || strlen($title)<2)
		   return false;
	    
		// Pass
	 	return true;
	}
	
	function isDesc($desc, $max_len=1000)
	{
		// Valid characters
		if ($this->isString($desc)==false)
		   return false;
	    
		// Length
		if (strlen($desc)>$max_len || strlen($desc)<5)
		   return false;
	    
		// Pass
	 	return true;
	}
	
	function isLong($var)
	{
		if (preg_match("/^[0-9]{1,10}$/", $var)!=1)
		   return false;
		else 
		   return true;
	}
	
	function isDecimal($var)
	{
		if (preg_match("/^[0-9]{1,10}\.[0-9]{1,10}$/", $var)!=1)
		   return false;
		else 
		   return true;
	}
	
	function isNumber($var)
	{
		if (preg_match("/^[0-9]{1,10}(\.[0-9]{1,10})?$/", $var)!=1)
		   return false;
		else 
		   return true;
	}
	
	function isHAsh($var)
	{
		if (preg_match("/^[A-Fa-f0-9]{64}$/", $var)!=1)
		   return false;
		else 
		   return true;
	}
	
	function isDomain($domain)
	{
		// Domain
		$domain=strtolower($domain);
		
		// Check
		if (preg_match("/^[a-z0-9]{0,30}$/", $var)!=1)
		   return false; 
		else 
		   return true;
	}
	
	function isString($var)
	{
		for ($a=0; $a<=strlen($var)-1; $a++)
		{
		   $code=ord($var[$a]);
		   if ($code<32 || $code>126)
		   {
			  if (ord($var[$a])!=10)
		      {
				  print $code;
			      return false;
			  }
		   }
		}
		
		return true;
	}
	
	function toString($var)
	{
		$str="";
		
		for ($a=0; $a<=strlen($var)-1; $a++)
		{
		   $code=ord($var[$a]);
		   
		   if ($code<32 || $code>126)
		   {
			  if (ord($var[$a])==10)
		         $str=$str.$var[$a];
		   }
		   else $str=$str.$var[$a];
		}
		
		return $str;
	}
	
	function isAdr($var)
	{
		// Length
		if (strlen($var)!=108 && 
		    strlen($var)!=124 && 
		    strlen($var)!=160 && 
		    strlen($var)!=212) 
	       return false;
	   
		// Characters	
		if (preg_match("/^[a-zA-Z0-9\+\/]+={0,2}$/", $var)!=1)
		   return false;
		else
		   return true;
	}
	
	function canSpend($adr)
	{
		// Contract ?
		$query="SELECT * 
		          FROM agents
				 WHERE adr='".$adr."'";
		$result=$this->execute($query);	
	    if (mysql_num_rows($result)>0) return false;
		
		// Passed
		return true;
	}
	
	function isLink($link)
	{
		// Max length
		if (strlen($link)>100) return false;
		
		if (filter_var($link, FILTER_VALIDATE_URL) === false) 
           return false;
		 else
		   return true;
	}	
	
	function isEmail($email)
	{
		// Max length
		if (strlen($link)>50) return false;
		
		if (filter_var($link, FILTER_VALIDATE_EMAIL) === false) 
           return false;
		 else
		   return true;
	}	
	
	function isPic($link)
	{
		// Link ?
		if ($this->isLink($link)==false) 
		   return false;
		
		if (strpos($link, ".jpg")===false && 
			strpos($link, ".jpeg")===false)
		        return false;
			else
			    return true;
	}
	
	function getReward($content)
	{
		// Load default balance
		$query="SELECT * FROM adr WHERE adr='default'";
		$result=$this->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// Per day
		$daily=($row['balance']/20)/365;  
		
		switch ($content)
		{
			case "ID_MINER" : return round($daily*0.20/1440, 4); break;
			case "ID_CONTENT" : return round($daily*0.30, 2); break;
			case "ID_VOTER" : return round($daily*0.20, 2); break;
			case "ID_COM" : return round($daily*0.15, 2); break;
			case "ID_APP" : return round($daily*0.15, 2); break;
		}
	}
	
    function isDelegate($adr)
	{
		// Valid address
		if ($this->isAdr($adr)==false) 
		   return false;
		   
		// My address ?   
		if ($this->isMine($adr)==false)
		   return false;
		   
		// Delegate ?
		$query="SELECT * 
    		      FROM delegates 
				 WHERE delegate='".$adr."'";
	    $result=$this->execute($query);	
	    
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;   
	}
	
	function isIP($ip)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP)===false)
		   return flase;
		else
		   return true;
	}
}
?>
