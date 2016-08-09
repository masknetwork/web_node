<?
class CTweets
{
	function CTweets($db, $template, $mes)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->mes=$mes;
	}
	
	function newTweet($net_fee_adr, 
	                  $adr,
					  $title, 
					  $mes, 
					  $retweet_tweet_id=0, 
					  $pic="")
	{
		// Decode
		$title=base64_decode($title); 
		$mes=base64_decode($mes); 
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// My address
	    if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Network fee address can't spend funds", 550);
			return false;
		}
		
		// Balance
		$balance=$this->kern->getBalance($net_fee_adr);
		
		// Funds
		if ($balance<0.0001)
		{
			$this->template->showErr("Insufficient funds to execute this operation", 550);
			return false;
		}
		
		
		// Pic 1
		if ($pic_1!="")
		{
		  if (filter_var($pic_1, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 1 link", 550);
			return false;
		  }
		}
		
		// Message
		if ($retweet_tweet_id==0)
		{
		   if ($this->kern->isDesc($mes, 10000))
		   {
			  $this->template->showErr("Invalid message", 550);
			  return false;
		   }
		   
		   if ($this->kern->isTitle($title))
		   {
			  $this->template->showErr("Invalid title", 550);
			  return false;
		   }
		}
		else
		{
		   if ($mes!="")
		   {
		     if (strlen($mes)<5 || strlen($mes)>500)
		     {
			    $this->template->showErr("Invalid message length (10-500 characters)", 550);
			    return false;
		     }
		   }
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates a profile");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_NEW_TWEET', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".base64_encode($title)."',
							   par_2='".base64_encode($mes)."',
							   par_3='".$retweet_tweet_id."',
							   par_4='".base64_encode($pic)."',
							   status='ID_PENDING', 
							   tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function unfollow($net_fee_adr, $adr, $unfollow_adr)
	{
		// Valid addresses
		if (!$this->kern->adrValid($net_fee_adr) || 
		    !$this->kern->adrValid($adr) || 
			!$this->kern->adrValid($unfollow_adr))
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Address valid
		if ($this->kern->adrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid target address", 550);
			return false;
		}
		
		// My address
	    if (!$this->kern->adrValid($net_fee_adr) || 
		    !$this->kern->adrValid($adr))
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Network fee address can't spend funds", 550);
			return false;
		}
		
		// Balance
		$balance=$this->kern->getBalance($net_fee_adr);
		
		// Funds
		if ($balance<0.0001)
		{
			$this->template->showErr("Insufficient funds to execute this operation", 550);
			return false;
		}
		
		// Following this adddress ?
		$query="SELECT * 
		          FROM tweets_follow 
		         WHERE follows='".$unfollow_adr."' 
				   AND adr='".$adr."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("You don't follow this address", 550);
			return false;
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Follows an address");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_UNFOLLOW', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".$unfollow_adr."',
							   status='ID_PENDING', 
							   tstamp='".time()."'"; 
	       $this->kern->execute($query); 
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function follow($net_fee_adr, 
	                $follow_with_adr, 
					$follow_adr, 
					$months)
	{
		// Addresses valid
		if (!$this->kern->adrValid($net_fee_adr) || 
	        !$this->kern->adrValid($follow_with_adr) || 
			!$this->kern->adrValid($follow_adr))
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// My address
	    if (!$this->kern->isMine($net_fee_adr) || 
		    !$this->kern->isMine($follow_with_adr))
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Months
		if ($months!=3 && 
		    $months!=6 && 
			$months!=9 && 
			$months!=12 && 
			$months!=24 && 
			$months!=36)
		{
			$this->template->showErr("Invalid months", 550);
			return false;
		}
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Fee address can spend ?
		if (!$this->kern->canSpend($net_fee_adr))
		{
			$this->template->showErr("Net fee address can't spedn funds", 550);
			return false;
		}
		
		// Fee
		$fee=$months*0.0001;
		
		// Balance
		$balance=$this->kern->getBalance($net_fee_adr);
		
		// Funds
		if ($balance<$fee)
		{
			$this->template->showErr("Insufficient funds to execute this operation", 550);
			return false;
		}
		
		// Address has tweets ?
		$query="SELECT * 
		          FROM tweets 
				 WHERE adr='".$follow_adr."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("You can't follow an address with no tweets", 550);
			return false;
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Follows an address");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_FOLLOW', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$follow_with_adr."',
							   par_1='".$follow_adr."',
							   par_2='".$months."',
							   status='ID_PENDING', 
							   tstamp='".time()."'"; 
	       $this->kern->execute($query); 
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	
	
	
	function formatTweet($mes)
	{
		
		$m="";
		$v=explode(" ", $mes);
		for ($a=0; $a<=sizeof($v)-1; $a++)
		{
			if (substr($v[$a], 0, 4)=="http")
			  $m=$m." <a href='".$v[$a]."' target='_blank' class='font_14'>".substr($v[$a], 0, 10)."...</a>";
			else if (substr($v[$a], 0, 1)=="#")
			  $m=$m." <a href='../search/index.php?term=".urlencode($v[$a])."'  class='font_14'>".$v[$a]."</a>";
			else if (substr($v[$a], 0, 1)=="$")
			  $m=$m." <a href='../../assets/user/asset.php?symbol=".substr($v[$a], 1, 100)."'  class='font_14'>".$v[$a]."</a>";
			else if (substr($v[$a], 0, 1)=="@")
			  $m=$m." <a href='../adr/index.php?adr=".urlencode($v[$a])."'  class='font_14'>".$v[$a]."</a>";
			else if (substr($v[$a], 0, 1)=="&")
			  $m=$m." <a href='../../app/directory/app.php?ID=".str_replace("&", "", $v[$a])."'  class='font_14'>applicaion</a>";
			else if (substr($v[$a], 0, 8)=="ME4wEAYH")
			  $m=$m." <a href='../adr/index.php?adr=".urlencode($v[$a])."'  class='font_14'>...".$this->template->formatAdr($v[$a])."...</a>";
			else 
			   $m=$m." ".$v[$a];
		}
		
		return $m;
	}
	
	function showTweets($adr="", $all=false, $time="24", $term="", $start=0, $end=20)
	{
		// QR modal
		$this->template->showQRModal();
		
		// Time
		switch ($time)
		{
			case "24" : $start_block=$_REQUEST['sd']['last_block']-1440; break;
			case "7" : $start_block=$_REQUEST['sd']['last_block']-10080; break;
			case "30" : $start_block=$_REQUEST['sd']['last_block']-43200; break;
		}
		
		// No address provided
		if ($adr=="")
		{
			// Load first address
			$query="SELECT ma.*
		              FROM my_adr AS ma
					  LEFT JOIN adr ON adr.adr=ma.adr 
			         WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
					  AND block>".$start_block."
			      ORDER BY adr.balance DESC 
				     LIMIT ".$start.", ".$end; 
		    $result=$this->kern->execute($query);	
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$adr=$row['adr']; 
		}
		
		if ($adr=="all")
		{
		   $query="SELECT tw.*, vs.*
		             FROM tweets AS tw 
					 LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
			        WHERE FROM_BASE64(tw.mes) LIKE '%".$term."%'
					  AND tw.block>".$start_block."
				 ORDER BY (vs.upvotes_power_24-vs.downvotes_power_24) DESC 
			        LIMIT ".$start.", ".$end; 
		}
		else
		{
			if ($all==true)
		        $query="SELECT *
		                 FROM tweets AS tw 
						 LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
					    WHERE (tw.adr='".$adr."' 
						   OR tw.adr IN (SELECT follows 
						                   FROM tweets_follow
										  WHERE adr='".$adr."')) 
						  
			         ORDER BY tw.ID DESC 
			            LIMIT ".$start.", ".$end; 
		   else
		        $query="SELECT *
		                 FROM tweets AS tw 
						 LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
				        WHERE tw.adr='".$adr."' 
						 
			         ORDER BY tw.ID DESC 
			            LIMIT ".$start.", ".$end; 
		}
	
		$result=$this->kern->execute($query); 
		 
		 // No results
		 if (mysql_num_rows($result)==0) 
		 {
			 print "<span class='font_14' style='color:#990000'>No results found</span>";
			 return false;
		 }
		 
		 ?>
         
         <table width="<? if ($adr=="all") print "100%"; else print "90%"; ?>" border="0" cellpadding="0" cellspacing="0">
         <tbody>
         
         <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				// Retweet ?
				if ($row['retweet_tweet_ID']>0)
				{
					$query="SELECT * 
					          FROM tweets AS tw 
							  LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
							 WHERE tw.tweetID='".$row['retweet_tweet_ID']."'"; 
				    $res=$this->kern->execute($query);	
	                $retweet_row = mysql_fetch_array($res, MYSQL_ASSOC); 
				}
		 ?>
         
           <tr>
             <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
               <tbody>
                 <tr>
                   <td width="17%" align="center">
                   <img src="
				   <? 
				       if ($row['retweet_tweet_ID']>0)
					   {
						   if ($retweet_row['pic']=="") 
					         print "../../template/template/GIF/mask.jpg"; 
					      else 
					         print "../../../crop.php?src=".base64_decode($retweet_row['pic'])."&w=100&h=100";
					   }
					   else
					   {
				          if ($row['pic']=="") 
					         print "../../template/template/GIF/mask.jpg"; 
					      else 
					         print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100"; 
					   }
						  
				    ?>" width="100" height="100" alt="" class="img img-responsive img-rounded"/></td>
                   <td width="3%" valign="top">&nbsp;</td>
                   <td width="80%" valign="top"><strong>
                   <a href="../tweet/index.php?ID=<? if ($row['retweet_tweet_ID']>0) print $retweet_row['tweetID']; else print $row['tweetID']; ?>" class="<? if ($adr=="all") print "font_16"; else print "font_14"; ?>">
				   <? 
				      $title=base64_decode($row['title']); 
					  
					  if ($row['retweet_tweet_ID']>0)
					  {
						   if (strlen($retweet_row['title'])>50)
					        print substr(base64_decode($retweet_row['title']), 0, 50)."...";
					     else
					        print base64_decode($retweet_row['title']);
					  }
					  else
					  {
					     if (strlen($title)>50)
					        print substr($title, 0, 50)."...";
					     else
					        print $title;
					  }
				   ?>
                   </a></strong>
                     <p class="<? if ($adr=="all") print "font_14"; else print "font_12"; ?>">
					 <? 
					    $mes=base64_decode($row['mes']); 
					  
					    if ($row['retweet_tweet_ID']>0)
					    {
							if (strlen($retweet_row['mes'])>250)
					          print substr(base64_decode($retweet_row['mes']), 0, 250)."...";
					       else
					         print base64_decode($retweet_row['mes']);
					    }
					    else
					    {
					       if (strlen($mes)>250)
					          print substr($mes, 0, 250)."...";
					       else
					          print $mes;
					    }
					 ?>
                     </p></td>
                 </tr>
                 <tr>
                   <td align="center">
                   
                   <?
				      if ($row['retweet_tweet_ID']>0)
					  {
						  // Payment
					     $pay=$retweet_row['pay']; 
					  
					     // Negative ?
					     if ($pay<0) $pay=0.00;
						 
						 // Upvotes 24
						 $upvotes_24=$retweet_row['upvotes_24'];
						 
						 // Downvotes 24
						 $downvotes_24=$retweet_row['downvotes_24'];
						 
						 // Comments
						 $comments=$retweet_row['comments'];
					  }
					  else
					  {
				         // Payment
					     $pay=$row['pay']; 
					  
					     // Negative ?
					     if ($pay<0) $pay=0.00;
						 
						 // Upvotes 24
						 $upvotes_24=$row['upvotes_24'];
						 
						 // Downvotes 24
						 $downvotes_24=$row['downvotes_24'];
						 
						 // Comments
						 $comments=$row['comments'];
					  }
				   ?>
                   
                   <span style="color:<? if ($pay==0) print "#999999"; else print "#009900"; ?>" class="<? if ($adr=="all") print "font_20"; else print "font_18"; ?>"><? print "$".$this->kern->split($pay)[0]; ?></span><span style="color:<? if ($pay==0) print "#afafaf"; else print "#61CD5F"; ?>" class="<? if ($adr=="all") print "font_12"; else print "font_10"; ?>"><? print ".".$this->kern->split($pay)[1]; ?></span>
                   
                   
                   </td>
                   <td align="right" valign="top">&nbsp;</td>
                   <td align="right" valign="top">
                   
                   <table width="100%" border="0" cellpadding="0" cellspacing="0">
                     <tbody>
                       <tr>
                         <td align="left" style="color:#999999" class="<? if ($adr=="all") print "font_12"; else print "font_10"; ?>">
						 <? 
						    print "Posted ~".$this->kern->timeFromBlock($row['block'])." ago by ".$this->template->formatAdr($row['adr']);
						 ?>
                         </td>
                        
                         <td width="50" align="center" style="color:<? if ($upvotes_24==0) print "#999999"; else print "#009900"; ?>">
                         <span class="glyphicon glyphicon-thumbs-up <? if ($adr=="all") print "font_16"; else print "font_14"; ?>"></span>&nbsp;<span class="<? if ($adr=="all") print "font_14"; else print "font_12"; ?>"><? print $upvotes_24; ?></span>
                         </td>
                         
                         <td width="50" align="center" style="color:<? if ($downvotes_24==0) print "#999999"; else print "#990000"; ?>">
                         <span class="glyphicon glyphicon-thumbs-down <? if ($adr=="all") print "font_14"; else print "font_12"; ?>"></span>&nbsp;&nbsp;<span class="<? if ($adr=="all") print "font_14"; else print "font_12"; ?>"><? print $downvotes_24; ?></span>
                         </td>
                         
                         <td width="50" align="center" class="<? if ($adr=="all") print "font_14"; else print "font_12"; ?>" style="color:<? if ($comments==0) print "#999999"; else print "#304971"; ?>">
                         <span class="glyphicon glyphicon-bullhorn <? if ($adr=="all") print "font_14"; else print "font_12"; ?>"></span>&nbsp;&nbsp;<span class="<? if ($adr=="all") print "font_14"; else print "font_12"; ?>"><? print $comments; ?></span>
                         </td>
                         </tr>
                     </tbody>
                   </table>
                   
                   </td>
                 </tr>
               </tbody>
             </table></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           
           <?
	}
		   ?>
           
         </tbody>
       </table>
         
         <?
	}
	
	function showReport()
	{
		$this->template->showModalHeader("report_modal", "Report Content", "act", "report", "tweetID", "", "../../tweets/home/index.php?act=report");
		?>
          
          <input id="remove_tweet_ID" name="remove_tweet_ID" value="" type="hidden">
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="150" align="center" valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../../template/template/GIF/report.png" width="180" height="181" alt=""/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"></td>
             </tr>
           </table></td>
           <td width="400" align="left" valign="top"><table width="300" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="left" valign="top" style="font-size:18px; color:#990000"><strong>Are you sure you want to report this content ? </strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Reason</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <textarea id="retweet_mes" name="retweet_mes" class="form-control" style="width:300px"></textarea></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
      <script>
		   $('#form_retweet_modal').submit(
		   function() 
		   {
		      $('#retweet_mes').val(btoa($('#retweet_mes').val())); 
		   });
		</script>
     
        
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showRetweetModal()
	{
		$this->template->showModalHeader("retweet_modal", "Retweet", "act", "retweet", "retweet_tweet_ID", "");
		?>
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/retweet.png" width="160" class="img-circle"/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="25" align="left" valign="top" style="font-size:14px"><strong>Retweet Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showMyAdrDD("dd_retweet_adr");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:14px"><strong>Short Message (optional)</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:14px">
               <textarea id="retweet_mes" name="retweet_mes" class="form-control" style="width:300px"></textarea></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
      
     
        
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showRemoveModal($adr)
	{
		$this->template->showModalHeader("remove_modal", "Remove Tweet", "act", "remove", "adr", $adr, "../home/index.php");
		?>
          
          <input id="remove_tweet_ID" name="remove_tweet_ID" value="" type="hidden">
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/remove.png" width="160" alt=""/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001", "trans"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_remove_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
        
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showUnfollowModal($adr, $unfollow_adr)
	{
		$this->template->showModalHeader("unfollow_modal", "Unfollow", "act", "unfollow", "adr", $adr);
		?>
          
          <input name="unfollow_adr" id="unfollow_adr" value="<? print $unfollow_adr; ?>" type="hidden">
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/down.png" width="190" alt=""/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_unfollow_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
        
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showFollowModal($adr)
	{
		$this->template->showModalHeader("follow_modal", "Follow", "act", "follow", "adr", $adr);
		?>
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/follow.png" width="180" height="180" alt=""/></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0003"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_follow_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Follow with address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showMyAdrDD("dd_follow_adr");
			   ?></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Months</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <select id="dd_months" name="dd_months" style="width:300px" class="form-control">
               <option value='3'>3 Months</option>
               <option value='6'>6 Months</option>
               <option value='9'>9 Months</option>
               <option value='12'>12 Months</option>
               <option value='24'>24 Months</option>
               <option value='36'>36 Months</option>
               </select>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             
           </table></td>
           </tr>
           </table>
           
           <script>
		   $('#dd_months').change(
		   function() 
		   { 
		      $('#ss_net_fee_panel_val').text(parseFloat($('#dd_months').val()*0.0001).toFixed(4)); 
		   });
		   </script>
        
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	
	
	
	
	
	function showNewCommentModal()
	{
		
		$this->template->showModalHeader("new_comment_modal", "New Comment", "act", "new_comment");
		?>
          
          <input type="hidden" id="com_target_type" name="com_target_type" value="ID_POST"> 
          <input type="hidden" id="com_targetID" name="com_targetID" value="0"> 
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="./GIF/comment.png" width="180" height="173" alt=""/></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001", "trans"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_comm_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Comment</strong></td>
             </tr>
             <tr>
               <td>
               <textarea name="txt_com_mes" id="txt_com_mes" rows="5"  style="width:300px" class="form-control" placeholder="Comments (10-1000 charcaters)"></textarea>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
         <script>
		   $('#form_new_comment_modal').submit(
		   function() 
		   {
		      $('#txt_com_mes').val(btoa($('#txt_com_mes').val())); 
		   });
		</script>
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	
	
	function showNewTweetPanel()
	{	
		?>
           
           <br><br>
<form id="form_new_tweet_modal" name="form_new_tweet_modal" action="new.php?act=new_tweet" method="post">
           <input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple style="display:none">
           
           <input type="hidden" id="tweet_adr" name="tweet_adr" value="">
           <input type="hidden" id="h_img_0" name="h_img_0" value="">
           <input type="hidden" id="h_img_1" name="h_img_1" value="">
           <input type="hidden" id="h_img_2" name="h_img_2" value="">
           <input type="hidden" id="h_img_3" name="h_img_3" value="">
           <input type="hidden" id="h_img_4" name="h_img_4" value="">
           
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center">
               <table width="150" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td height="150" align="center" background="../home/GIF/drop_pic.png">
                     <img id="img_0" src="" style="display:none" class="img img-responsive img-rounded">
                     </td>
                   </tr>
                   <tr>
                     <td height="50" align="center" id="row_progress">
                     <div id="progress" class="progress" style="width:150px">
                     <div class="progress-bar progress-bar-success">&nbsp;</div>
                     </div>
                     </td>
                   </tr>
                 </tbody>
               </table></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0001", "trans"); ?></td>
             </tr>
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <?
			      $this->template->showMyAdrDD("dd_tweet_net_fee", "90%");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Title</strong></td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><input type="text" class="form-control" name="txt_tweet_title" id="txt_tweet_title" value=""></td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Post</strong></td>
             </tr>
             <tr>
               <td>
               <textarea name="txt_tweet_mes" id="txt_tweet_mes" rows="20" class="form-control" placeholder="Comments (optional)" onfocus="this.placeholder=''"></textarea>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="right" valign="top">
               <a href="javascript:void" onClick="$('#form_new_tweet_modal').submit()" class="btn btn-success">
               <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Post</a>
               </td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top">&nbsp;</td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     </form>
     
         <script>
		   $('#form_new_tweet_modal').submit(
		   function() 
		   {
			  $('#txt_tweet_title').val(btoa(unescape(encodeURIComponent($('#txt_tweet_title').val())))); 
		      $('#txt_tweet_mes').val(btoa(unescape(encodeURIComponent($('#txt_tweet_mes').val())))); 
		   });
		</script>
       
        <?
		
	}
	
	function showTrending($type="ID_HASHTAG")
	{
		$query="SELECT term
		          FROM tweets_trends
		         WHERE type='".$type."'
		      ORDER BY (tweets+likes+comments+retweets)  DESC 
			     LIMIT 0,10"; 
		$result=$this->kern->execute($query);	
	    
	  ?>
        
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
           
           <tr>
           <td align="left" class="font_16">Top 24 hours</td>
           </tr>
           <tr>
           <td><hr></td>
           </tr>
                
           <?
		      $a=0;
			  
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
				  $a++;
		   ?>
           
                <tr>
                <td><a href="../search/index.php?term=<? print urlencode($row['term']); ?>" class="font_16"><? print $a.". ".$row['term']; ?></a></td>
                </tr>
                <tr>
                <td><hr></td>
                </tr>
                
           
           <?
			  }
		   ?>
           
           </table>
       
       <div id="blueimp-gallery" class="blueimp-gallery">
          <!-- The container for the modal slides -->
          <div class="slides"></div>
          <!-- Controls for the borderless lightbox -->
          <h3 class="title"></h3>
          <a class="prev">‹</a>
          <a class="next">›</a>
          <a class="close">×</a>
          <a class="play-pause"></a>
          <ol class="indicator"></ol>
          <!-- The modal dialog, which will be used to wrap the lightbox content -->
          <div class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        </div>
        </div>
        
        <?
	}
	
	function showLeftMenu($adr)
	{
		// No address provided
		if ($adr=="")
		{
			// Load first address
			$query="SELECT ma.adr
		              FROM my_adr AS ma 
				 LEFT JOIN adr ON adr.adr=ma.adr
			         WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
			      ORDER BY adr.balance DESC"; 
		    $result=$this->kern->execute($query);	
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$adr=$row['adr'];
		}
		
		// Load address data
		$query="SELECT adr.*, pr.*, ma.ID as adrID 
		          FROM adr 
			 LEFT JOIN profiles AS pr ON pr.adr=adr.adr 
			 LEFT JOIN my_adr AS ma ON ma.adr=adr.adr 
				 WHERE adr.adr='".$adr."'";
		$result=$this->kern->execute($query);	
		
		// No records
		if (mysql_num_rows($result)==0)
		{
			$tweets=0;
			$following=0;
			$followers=0;
		}
		else
		{
	       // Tweets
		   $query="SELECT COUNT(*) AS total 
		             FROM tweets 
					WHERE adr='".$adr."'";
		   $result=$this->kern->execute($query);	
		   $row = mysql_fetch_array($result, MYSQL_ASSOC); 
		   $tweets=$row['tweets'];
		   if ($tweets=="") $tweets=0;
		   
		   // Following
		   $query="SELECT COUNT(*) AS total 
		             FROM tweets_follow 
					WHERE adr='".$adr."'";
		   $result=$this->kern->execute($query);	
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $following=$row['total'];
		   if ($following=="") $following=0;
		   
		   // Followers
		   $query="SELECT COUNT(*) AS total 
		             FROM tweets 
					WHERE follow_adr='".$adr."'";
		   $result=$this->kern->execute($query);	
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $followers=$row['total'];
		   if ($followers=="") $followers=0;
		}
	
		
		if ($this->kern->isMine($adr))
		{
			$this->template->showMyAdrDD("dd_tweet_adr", "100%", $adr);
		    print "<br>";
		?>
        
            
            <?
			   if ($comments>0)
			   {
			?>
            
                <table class="table-responsive"  style="width:100%">
                <tr><td><a class="btn btn-warning"  style="width:100%; color:#000000" href="index.php?target=pending&adr=<? print $_REQUEST['adr']; ?>">
				<? print $comments." pending comments"; ?></a></td></tr>
                </table>
                <br>
            
            <?
		      }
			?>
            
            <div class="panel panel-default">
            <div class="panel-body">
            <table style="position:relative">
            <tr><td>
            
            <img src="<? if ($row['pic_back']=="") print "../../template/template/GIF/default_top_img.png"; else print "../../../crop.php?src=".base64_decode($row['pic_back'])."&w=100&h=40" ?>" width="150" class="img-responsive" >
            
            <img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=60&h=60" ?>" width="50px" style="position:absolute; left:10%; top:50%; border:solid; border-width:2px; border-color:#ffffff;" class="img-responsive img-rounded">
            
            </td></tr>
            <tr><td></td></tr>
            </table>
            <br>
            <span class="font_12"><? if ($row['name']=="") print "No name"; else print substr(base64_decode($row['name']), 0, 50); ?></span>
            <p class="font_10" style="color:#999999"><? if ($row['description']=="") print "No profile setup"; else print substr(base64_decode($row['description']), 0, 100)."..."; ?></p>
            
            <a href="../home/new.php" class="btn btn-danger" style="width:100%">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;&nbsp;New Post
            </a>
            <div style="height:10px">&nbsp;</div>
            <a href="../../adr/options/index.php?ID=<? print $row['adrID']; ?>" class="btn btn-default" style="width:100%;">
            <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;&nbsp;Update Profile
            </a>
            
            
            </div>
            </div>
       
	   <?
		}
		else if ($_REQUEST['ud']['ID']>0)
		{
			$query="SELECT * 
			          FROM tweets_follow 
					 WHERE follows='".$adr."' 
					   AND adr IN (SELECT adr 
					                 FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."')";
		    $result=$this->kern->execute($query);	
	        
			// Already following
			if (mysql_num_rows($result)>0)
			   print "<a href='javascript:void(0)' onclick=\"$('#unfollow_modal').modal();\" class='btn btn-danger' style='width:100%'><span class='glyphicon glyphicon-minus-sign'></span>&nbsp;&nbsp;Unfollow</a>";
			else
			   print "<a href='javascript:void(0)' onclick=\"$('#follow_modal').modal()\" class='btn btn-success' style='width:100%'><span class='glyphicon glyphicon-plus-sign'></span>&nbsp;&nbsp;Follow</a>"; 
		
	   ?>
            
            <div style="height:10px">&nbsp;</div>
            <a class="btn btn-default" style="width:100%;" href="javascript:void(0)" onclick="$('#compose_modal').modal(); $('#txt_rec').val('<? print $adr; ?>')">
            <span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;&nbsp;Send Message</a><br>
            
            <div style="height:10px">&nbsp;</div>
            <a class="btn btn-default" style="width:100%" href="javascript:void(0)" onclick="$('#send_coins_modal').modal(); $('#txt_to').val('<? print $adr; ?>')">
            <span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;&nbsp;Send Coins</a><br><br>
           
       <?
		}
	   ?>
       
            <div class="panel panel-default">
            <div class="panel-heading">
            <h3 class="panel-title">Tweets</h3>
            </div>
            <div class="panel-body">
            <? print $tweets; ?>
            </div>
            </div>
       
            <div class="panel panel-default">
            <div class="panel-heading">
            <h3 class="panel-title">Following</h3>
            </div>
            <div class="panel-body">
            <a href="../adr/index.php?adr=<? print urlencode($adr); ?>&target=following"><? print $following; ?></a>
            </div>
            </div>
       
            <div class="panel panel-default">
            <div class="panel-heading">
            <h3 class="panel-title">Followers</h3>
            </div>
            <div class="panel-body">
            <a href="../adr/index.php?adr=<? print urlencode($adr); ?>&target=followers"><? print $followers; ?></a>
            </div>
            </div>
            
            <?
			    if ($comments>0)
				{
			?>
            
                  <div class="panel panel-default">
                  <div class="panel-heading">
                  <h3 class="panel-title">Comments</h3>
                  </div>
                  <div class="panel-body">
                  <a href="../home/index.php?target=pending&adr=<? print urlencode($adr); ?>" class="font_18">
				  <? print $comments; ?>
                  </a><br><span class="font_12">pending approval</span>
                  </div></div>
           <?
				}
		   ?>
           
          <div id="blueimp-gallery" class="blueimp-gallery">
          <!-- The container for the modal slides -->
          <div class="slides"></div>
          <!-- Controls for the borderless lightbox -->
          <h3 class="title"></h3>
          <a class="prev">‹</a>
          <a class="next">›</a>
          <a class="close">×</a>
          <a class="play-pause"></a>
          <ol class="indicator"></ol>
          <!-- The modal dialog, which will be used to wrap the lightbox content -->
          <div class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        </div>
        </div>
        
        <script>
		   $('#dd_tweet_adr').change(
		   function () 
		   {  
		      window.location='index.php?adr='+encodeURIComponent($('#dd_tweet_adr').val()); 
	  	   });
		</script>
        
        <?
	}
	
	function showFollowers($adr)
	{
		$query="SELECT * 
		          FROM tweets_follow 
				 WHERE follows='".$adr."'";
	    $result=$this->kern->execute($query);	
	    
	  
		?>
        
        <table class="table-responsive" width="95%" align="right">
        
        <?
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		?>
        
           <tr>
           <td width="6%"><img src="../../template/template/GIF/mask.jpg" class="img-responsive img-rounded" width="50px" height="50px"></td>
           <td width="2%">&nbsp;</td>
           <td width="70%"><a href="index.php?adr=<? print urlencode($row['adr']); ?>" class="font_14"><? print $this->template->formatAdr($row['adr']); ?></a></td>
           <td width="20%" class="font_12">Expire ~<? print $this->kern->timeFromBlock($row['expire']); ?></td>
           </tr>
           <tr><td colspan="4"><hr></td></tr>
        
        <?
	    }
		?>
        
        </table>
        
        <?
	}
	
	function showFollowing($adr)
	{
		$query="SELECT * 
		          FROM tweets_follow 
				 WHERE adr='".$adr."'";
	    $result=$this->kern->execute($query);	
	    
	  
		?>
        
        <table class="table-responsive" width="95%" align="right">
        
        <?
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		?>
        
           <tr>
           <td width="6%"><img src="../../template/template/GIF/mask.jpg" class="img-responsive img-rounded" width="50px" height="50px"></td>
           <td width="2%">&nbsp;</td>
           <td width="70%"><a href="index.php?adr=<? print urlencode($row['follows']); ?>" class="font_14"><? print $this->template->formatAdr($row['follows']); ?></a></td>
           <td width="20%" class="font_12">Expire ~<? print $this->kern->timeFromBlock($row['expire']); ?></td>
           </tr>
           <tr><td colspan="4"><hr></td></tr>
        
        <?
	    }
		?>
        
        </table>
        
		<?
	}
	
	function showTrans($adr)
	{
		$query="SELECT *
		          FROM trans 
				 WHERE src='".$adr."'
			  ORDER BY trans.ID DESC 
			     LIMIT 0,50";
		$result=$this->kern->execute($query);
		
		?>
            
            <div id="div_trans" name="div_trans">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
              <tbody>
                <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                     
                          <tr>
                          <td width="55%" align="left">
                          <a href="#" class="font_14"><strong><? print $this->template->formatAdr($row['src']); ?></strong>
                          </a><p class="font_10"><? print "Received ".$this->kern->getAbsTime($row['tstamp'])." ago"; ?></p></td>
                          <td width="15%" align="center" class="font_14">
                          <?
					        if ($row['status']=="ID_CLEARED")
						    {  
						      print "<span class='label label-success'>Cleared</span>";
						    }
						    else
						    {
							   $dif=time()-$row['tstamp'];
							 
							   if ($dif<600) 
							      print "<span class='label label-warning'>Pending</span>";
							   else
							      print "<span class='label label-danger'>Pending</span>";
							}
							?>
                          
                          </td>
                          <td width="25%" align="center" class="font_14" style=" 
						  <? 
						      if ($row['amount']<0) 
							     print "color:#990000"; 
							  else 
							     print "color:#009900"; 
						  ?>"><strong><? print $row['amount']." ".strtolower($row['cur']); ?></strong></td>
                          </tr>
                          <tr>
                          <td colspan="3"><hr></td>
                          </tr>
                    
                    <?
					   }
					?>
                    
                    </tbody>
                  </table>
                  <br><br><br>
                  </div>
                  
            
            <script>
			$("span[id^='gly_']").popover();
			</script>
        
        <?
	}
	
	function showSearchPanel($target="tweets")
	{
		?>
           
           <br>
           <form action="../../tweets/search/index.php?target=<? print $target; ?>" method="post">
           <input id="term" name="term" type="text" class="form-control" placeholder="Search <? print $target; ?>" style="width:90%">
           </form>
           
        <?
	}
	
	
	function showSearchTweetPanel($pic_back, 
	                              $pic, 
								  $adr, 
								  $description, 
								  $tweets, 
								  $followers, 
								  $following)
	{
		
		?>
              <a href="../adr/index.php?adr=<? print urlencode($adr); ?>">
              <div class="panel panel-default">
              <div class="panel-body">
              
              <table style="position:relative">
              <tr>
              <td colspan="2">
              <img src="<? if ($pic_back=="") print "../../template/template/GIF/default_top_img.png"; else print "../../../crop.php?src=".$pic_back."&w=150&h=50"; ?>" class="img-responsive img-rounded" width="100%">
              
              <img src="<? if ($pic=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".$pic."&w=50&h=50"; ?>" class="img-responsive img-rounded" width="50" height="50" style="position:absolute; left:10%; top:50%; border:solid; border-color:#ffffff; border-width:2px">
              </td>
              <td>&nbsp;</td>
              </tr>
              <tr><td width="40%">&nbsp;</td><td class="font_14" height="50" valign="top"><? print $this->template->formatAdr($adr); ?></td></tr>
              </table>
              
              <p class="font_12" style="color:#999999" align="center"><? if ($description=="") print "No description provided"; else print substr($description, 0, 200)."..."; ?></p>
              
              <table width="100%" class="table-responsive">
              
              <tr class="font_10" style="color:#999999">
              <td width="33%" align="center">Posts</td>
              <td width="33%" align="center">Followers</td>
              <td width="33%" align="center">Following</td>
              </tr>
              
              <tr class="font_15">
              <td width="33%" align="center"><? print $tweets; ?></td>
              <td width="33%" align="center"><? print $followers; ?></td>
              <td width="33%" align="center"><? print $following; ?></td>
              </tr>
              
              </table>
              
              </div>     
              </div>
              </a>
        
        <?
	}
	
	function showAccounts($term)
	{
		$query="SELECT adr.*, prof.pic_back, prof.pic, dom.domain
		          FROM adr 
			 LEFT JOIN domains AS dom ON dom.adr=adr.adr 
			 LEFT JOIN profiles AS prof ON prof.adr=adr.adr 
			     WHERE adr.adr LIKE '%".$term."%' 
				    OR dom.domain LIKE '%".$term."%' 
					OR prof.name LIKE '%".$term."%' 
					OR prof.description LIKE '%".$term."%'"; 
		$result=$this->kern->execute($query);	
	    
		// Position
		$pos=0;
		
		// Number of records
		$num=mysql_num_rows($result);
		
		print "<table width='90%'>";
		
		for ($a=1; $a<=round($num/2); $a++)
		{
			print "<tr>";
			
			// Increase pos
			$pos++; 
			
			print "<td width='50%'>";
			
			if ($pos<=$num) 
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$this->showSearchTweetPanel(base64_decode($row['pic_back']), 
				                            base64_decode($row['pic']), 
											$row['adr'], 
											$row['description'], 
											$row['tweets'], 
											$row['followers'], 
											$row['following']);
			}
			
			print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			print "<td width='50%'>";
			
			// Increase pos
			$pos++;
			
			// Display panel
			if ($pos<=$num) 
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
			    $this->showSearchTweetPanel($row['pic_back'], 
				                            $row['pic'], 
											$row['adr'], 
											$row['description'], 
											$row['tweets'], 
											$row['followers'], 
											$row['following']);
			}
			
			print "</td>";
			print "</tr>";
		}
		
		print "</table>";
	  
	}
	
	
    function showPost($ID)
	{
		$query="SELECT * 
		          FROM tweets 
				  LEFT JOIN votes_stats AS vs ON vs.targetID='".$ID."'
				 WHERE tweetID='".$ID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Modals
		$this->showFollowModal($row['adr']);
		$this->showRetweetModal();
	  
		?>
        
<table width="90%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
          <tr>
          <td width="22%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
           <tr>
             <td align="center"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/mask.jpg"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100"; ?>" width="200" height="201" class="img img-responsive img-rounded" /></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
         </tbody>
       </table>
       
       <?
	       if ($_REQUEST['ud']['ID']>0)
		   {
	   ?>
       
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td width="75%" align="center"><a href="javascript:void(0)" onclick="$('#vote_modal').modal(); $('#vote_type').val('up'); $('#vote_img').attr('src', '../../tweets/GIF/like.png');" class="btn btn-success" style="width:100%"> <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Upvote </a></td>
                        <td>&nbsp;&nbsp;</td>
                        <td width="26%" align="center"><a href="javascript:void(0)" class="btn btn-danger" style="width:100%" onClick="$('#vote_modal').modal(); $('#vote_type').val('down'); $('#vote_img').attr('src', '../../tweets/GIF/down.png');"> <span class="glyphicon glyphicon-thumbs-down"></span> </a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="60" align="center">
                  <?
				     // Already following
					 $query="SELECT * 
					           FROM tweets_follow 
							  WHERE adr IN (SELECT adr 
							                  FROM my_adr 
											 WHERE userID='".$_REQUEST['ud']['ID']."') 
								AND follows='".$row['adr']."'"; 
					 $result=$this->kern->execute($query);	
	                 
					 if (mysql_num_rows($result)>0)
					 {
						 // Load data
						 $row_unfollow=mysql_fetch_array($result, MYSQL_ASSOC);
						 
						 // Unfollow modal
						 $this->showUnFollowModal($row_unfollow['adr'], $row['adr']);
				  ?>
                  
                       <a href="javascript:void(0)" onClick="$('#unfollow_modal').modal()" class="btn btn-warning" style="width:100%"> 
                       <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;&nbsp;&nbsp;Unfollow Author
                       </a>
                  
                  <?
					 }
					 else
					 {
						 ?>
                         
                          <a href="javascript:void(0)" onClick="$('#follow_modal').modal()" class="btn btn-default" style="width:100%"> 
                          <span class="glyphicon glyphicon-random"></span>&nbsp;&nbsp;&nbsp;&nbsp;Follow Author
                          </a>
                         
                         <?
					 }
				  ?>
                  
                  </td>
                </tr>
                <tr>
                  <td height="40" align="center"><a href="javascript:void(0)" onClick="$('#retweet_modal').modal(); $('#retweet_tweet_ID').val('<? print $row['tweetID']; ?>');" class="btn btn-default" style="width:100%"> <span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;&nbsp;&nbsp;Retweet Post </a></td>
                </tr>
                <tr>
                  <td height="60" align="center"><a href="javascript:void(0)" onclick="$('#compose_modal').modal(); $('#txt_rec').val('<? print $row['adr']; ?>')" class="btn btn-default" style="width:100%"> <span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;&nbsp;&nbsp;Message Author </a></td>
                </tr>
              </tbody>
            </table>
            
            <?
		   }
			?>
            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><div class="panel panel-default">
                    <div class="panel-heading font_14">Income Today</div>
                    <div class="panel-body font_20"> <strong style="color:<? if ($row['pay']==0) print "#aaaaaa"; else print "#009900"; ?>"><? print "$".$this->kern->split($row['pay'])[0]; ?></strong><strong style="color:<? if ($row['pay']==0) print "#aaaaaa"; else print "#5FBE54"; ?>" class="font_12"><? print ".".$this->kern->split($row['pay'])[1]; ?></strong></div>
                  </div></td>
                </tr>
                <tr>
                  <td align="center"><div class="panel panel-default">
                    <div class="panel-heading font_14">Upvotes Today</div>
                    <div class="panel-body"><a href="../../explorer/voters/index.php?tab=upvoters_24&target_type=ID_POST&targetID=<? print $_REQUEST['ID']; ?>" style="color:<? if ($row['upvotes_24']==0) print "#aaaaaa"; ?>"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;<strong><? if ($row['upvotes_24']=="") print "0"; else print $row['upvotes_24']; ?></strong></a></div>
                  </div></td>
                </tr>
                <tr>
                  <td align="center"><div class="panel panel-default">
                    <div class="panel-heading font_14">Downvotes Today</div>
                    <div class="panel-body"><a style="color:<? if ($row['downvotes_24']==0) print "#aaaaaa"; else print "#990000"; ?>" href="../../explorer/voters/index.php?tab=downvoters_24&target_type=ID_POST&targetID=<? print $_REQUEST['ID']; ?>"><span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;<strong><? if ($row['downvotes_24']=="") print "0"; else print $row['downvotes_24']; ?></strong></a></div>
                  </div></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table></td>
       <td width="78%" align="right" valign="top"><table width="95%" border="0" cellpadding="0" cellspacing="0">
         <tbody>
           <tr>
             <td><span class="font_22"><? print base64_decode($row['title']); ?></span><p class="font_12"><? print "Posted by ".$this->template->formatAdr($row['adr'])." ~".$this->kern->timeFromBlock($row['block'])." ago"; ?></p></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_16"><? print nl2br($this->template->makeLinks(base64_decode($row['mes']))); ?></td>
           </tr>
           <tr>
             <td class="font_14">&nbsp;</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
     <tr>
       <td colspan="2"><hr></td>
       </tr>
     <tr>
       <td colspan="2">&nbsp;</td>
     </tr>
   </tbody>
 </table>
        
        <?
	}
	
	
	
	function showComments($target_type, $targetID, $branch=0)
	{
		$query="SELECT com.*, prof.pic, vs.*
		          FROM comments AS com
			 LEFT JOIN profiles AS prof ON prof.adr=com.adr
			 LEFT JOIN votes_stats AS vs ON (vs.target_type='ID_COM' AND vs.targetID=com.comID)
				 WHERE com.parent_type='".$target_type."' 
				   AND com.parentID='".$targetID."' 
			  ORDER BY (vs.upvotes_power_total-vs.downvotes_power_total) DESC"; 
		$result=$this->kern->execute($query);	
	  
	  
		
		?>
        
        <table width="<? if ($branch==0) print "90%"; else print "100%"; ?>" border="0" cellpadding="0" cellspacing="0" align="center">
        <tbody>
        
        <?
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
               <tr>
               <td width="<? print $branch*14; ?>%">&nbsp;</td>
               <td width="7%" align="center" valign="top">
               <table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>
             <tr>
               <td align="center"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_profile.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=80&h=80"; ?>"  class="img img-responsive img-rounded"/></td>
               </tr>
             <tr>
               <td align="center" class="font_14" height="40">
               
               <table width="100%" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td><a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="$('#vote_modal').modal(); $('#vote_type').val('up'); $('#vote_img').attr('src', '../../tweets/GIF/like.png'); $('#vote_target_type').val('ID_COM'); $('#vote_targetID').val('<? print $row['comID']; ?>');"><span class="glyphicon glyphicon-thumbs-up"></span></a></td>
                     <td>&nbsp;</td>
                     <td><a href="javascript:void(0)" onclick="$('#vote_modal').modal(); $('#vote_type').val('down'); $('#vote_img').attr('src', '../../tweets/GIF/down.png'); $('#vote_target_type').val('ID_COM'); $('#vote_targetID').val('<? print $row['comID']; ?>');" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-thumbs-down"></span></a></td>
                   </tr>
                 </tbody>
               </table>
            
               </td>
               </tr>
             <tr>
              
              <td height="0" align="center" bgcolor="<? if ($row['pay']>0) print "#e7ffef"; else print "#fafafa"; ?>" class="font_14">
               <strong><span style="color:<? if ($row['pay']==0) print "#999999"; else print "#009900"; ?>"><? print "$".$this->kern->split($row['pay'])[0]; ?></span><span style="color:<? if ($row['pay']==0) print "#afafaf"; else print "#52B65D"; ?>" class="font_12"><? print ".".$this->kern->split($row['pay'])[1]; ?></span></strong></td>
             </tr>
             </tbody>
         </table></td>
       <td width="733" align="right" valign="top"><table width="95%" border="0" cellpadding="0" cellspacing="0">
         <tbody>
           <tr>
             <td align="left"><a class="font_14"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a>&nbsp;&nbsp;&nbsp;<span class="font_10" style="color:#999999"><? print "~".$this->kern->timeFromBlock($row['block'])." ago"; ?></span>
               <p class="font_14"><? print  nl2br($this->template->makeLinks(base64_decode($row['mes']))); ?></p></td>
           </tr>
           <tr>
             <td align="right">
             
             <table width="150" border="0" cellpadding="0" cellspacing="0">
               <tbody>
                 <tr>
                   <td width="25%" align="center" style="color:#999999"><a class="font_12" href="javascript:void(0)" onClick="$('#new_comment_modal').modal(); $('#com_target_type').val('ID_COM'); $('#com_targetID').val('<? print $row['comID']; ?>');"><? if ($branch<3 && $_REQUEST['ud']['ID']>0) print "reply"; ?></a></td>
                   
                   <td width="25%" align="center" style="color:<? if ($row['upvotes_24']==0) print "#999999"; else print "#009900"; ?>"><span class="font_12 glyphicon glyphicon-thumbs-up"></span>&nbsp;<span class="font_12"><? print $row['upvotes_24']; ?></span></td>
                   
                   <td width="25%" align="center" style="color:<? if ($row['downvotes_24']==0) print "#999999"; else print "#990000"; ?>"><span class="font_12 glyphicon glyphicon-thumbs-down"></span>&nbsp;<span class="font_12"><? print $row['downvotes_24']; ?></span></td>
                   </tr>
               </tbody>
             </table>
             
             </td>
           </tr>
         </tbody>
       </table>         
       
     </tr>
     <tr><td colspan="3">
	 <?
	     $this->showComments("ID_COM", $row['comID'], $branch+1);
	 ?>
     </td></tr> 
     
     <?
	    if ($branch==0)
		  print "<tr><td colspan='3'><hr></td></tr>";
		else
		  print "<tr><td colspan='3'>&nbsp;</td></tr>";  
		   }
	 ?>
   
   
   </tbody>
 </table>
 
        
        <?
	}
	
	function showNewCommentBut($ID)
	{
		if (!isset($_REQUEST['ud']['ID'])) return false;
		
		?>
        
        <table width="90%">
        <tr><td align="right"><a href="javascript:void()" onClick="$('#new_comment_modal').modal(); $('#com_target_type').val('ID_POST'); $('#com_targetID').val('<? print $ID; ?>'); " class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;New Comment</a></td></tr>
        </table>
        <br>
        
        <?
	}
}
?>