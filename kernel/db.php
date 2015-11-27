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
			 $db="wallet_lite";
			 $_REQUEST['sd']['server']="localhost";
		 }
		 else
		 {
			 $user="root";
			 $pass="dicatrenu";
			 $db="wallet";
			 $ho="localhost";
		 }
		
         $this->con = mysql_connect($ho, $user, $pass)
            or die("Could not connect: " . mysql_error());
 		 mysql_select_db($db, $this->con);	
		 
		 error_reporting(E_ERROR);
         ini_set("display_errors", "1");
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
		if ($this->domainValid($domain)==false) return false;
		
		$query="SELECT * FROM domains WHERE domain='".$domain."'";
		$result=$this->execute($query);	
		
		if (mysql_num_rows($result)>0)
		   return true;
		else
		   return false;
	}
	
	function domainValid($domain)
	{
		return true;
	}
	
	  function execute($query)
	  {  
	     //print $query."<br><br>";
		 $result=mysql_query($query, $this->con); 
		 return $result;
	  }
	
	
	 function feeAdrValid($adr)
	 {
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
		if (strlen($adr)<31)
		{
			if ($this->domainExist($adr)==false)
			   return false;
		}
		else
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
	  
	  // Verifica daca un string este numar
	  function isNumber($str, $tip="integer")
	  {
		if (strlen($str)==0) return false;
        $str=str_replace(" ", "", $str);
	    if ($tip=="integer")
		{
	      for ($a=0; $a<=strlen($str)-1; $a++) 
		   if (ord($str[$a])<48 || ord($str[$a])>57)
		     return false;
	    }
		
		if ($tip=="decimal")
		{
		  $str=str_replace(",",  ".", $str);
	      for ($a=0; $a<=strlen($str)-1; $a++) 
		   if ((ord($str[$a])<48 || ord($str[$a])>57) && ord($str[$a])!=46)
		     return false;
		}		 
		
		return true;
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
                    $replacement = "<img style=\"max-width:600px;\" border=\"0\" src=\"$innertext\" " . (is_numeric($width)? "width=\"$width\" " : '') . (is_numeric($height)? "height=\"$height\" " : '') . '/>';
                break;
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
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
		if (strlen($symbol)!=6)
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
		          FROM feeds_components 
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
		$query="SELECT * FROM domains WHERE domain='".$domain."'";
		$result=$this->execute($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $row['adr'];
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
	
	function isLink($link)
	{
		return true;
	}	
}
?>
