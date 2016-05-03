<?
class CTweets
{
	function CTweets($db, $template, $mes)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->mes=$mes;
	}
	
	
	function remove($net_fee_adr, $tweetID)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// My address
	    if ($this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
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
		
		// Tweet ID valid ?
		$query="SELECT * 
		          FROM tweets 
				 WHERE tweetID='".$tweetID."'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load follow data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		if ($this->kern->isMine($row['adr'])==false)
		{
			$this->template->showErr("Invalid entry data", 550);
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
							   op='ID_REMOVE_TWEET', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$row['adr']."',
							   par_1='".$tweetID."',
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
	
	function unfollow($net_fee_adr, $adr)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Address valid
		if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid target address", 550);
			return false;
		}
		
		// My address
	    if ($this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
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
		         WHERE follows='".$adr."' 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("You don't follow this address", 550);
			return false;
		}
		
		// Load follow data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$following_adr=$row['adr'];
		
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
							   target_adr='".$following_adr."',
							   par_1='".$adr."',
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
	
	function follow($net_fee_adr, $adr, $follow_adr)
	{
	    // Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Address valid
		if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid target address", 550);
			return false;
		}
		
		// Follow address valid
		if ($this->kern->adrValid($follow_adr)==false)
		{
			$this->template->showErr("Invalid follow address", 550);
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
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
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
							   target_adr='".$adr."',
							   par_1='".$follow_adr."',
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
	
	
	function like($net_fee_adr, $adr, $tweetID)
	{
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Address valid
		if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid target address", 550);
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
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.", 550);
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
		
		// Tweet exist ?
		$query="SELECT * 
		          FROM tweets 
				 WHERE tweetID='".$tweetID."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid tweetID", 550);
			return false;
		}
		
		// Already liked ?
		$query="SELECT * 
		          FROM tweets_likes 
				 WHERE adr='".$adr."' 
				   AND tweetID='".$tweetID."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("Already liked this post", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Like a tweet");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_LIKE_TWEET', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".$tweetID."',
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
	
	function showTweets($adr="", $all=false, $term="", $start=0, $end=20, $budget=0)
	{
		// Like modal
		$this->showLikeModal();
		
		// Delete modal
	    $this->showRemoveModal($adr);
		
		// QR modal
		$this->template->showQRModal();
		
		// Retweet modal
		$this->showRetweet($adr);
		
		// Report
		$this->showReport();
		
		// No address provided
		if ($adr=="")
		{
			// Load first address
			$query="SELECT ma.*
		              FROM my_adr AS ma
					  LEFT JOIN adr ON adr.adr=ma.adr 
			         WHERE ma.userID='".$_REQUEST['ud']['ID']."' 
			      ORDER BY adr.tweets DESC"; 
		    $result=$this->kern->execute($query);	
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$adr=$row['adr']; 
		}
		
		if ($adr=="all")
		{
		   $query="SELECT tw.*, pr.pic
		             FROM tweets AS tw 
				LEFT JOIN profiles AS pr ON pr.adr=tw.adr 
					 WHERE FROM_BASE64(tw.mes) LIKE '%".$term."%'
					 AND tw.budget>=".$budget."
			     ORDER BY tw.ID DESC 
			        LIMIT 0,20"; 
		}
		else
		{
			if ($all==true)
		        $query="SELECT tw.*, pr.pic
		                 FROM tweets AS tw 
					LEFT JOIN profiles AS pr ON pr.adr=tw.adr
			            WHERE tw.adr='".$adr."' 
						   OR tw.adr IN (SELECT follows 
						                   FROM tweets_follow
										  WHERE adr='".$adr."') 
			         ORDER BY tw.ID DESC 
			            LIMIT 0,20"; 
		   else
		        $query="SELECT tw.*, pr.pic
		                 FROM tweets AS tw 
					LEFT JOIN profiles AS pr ON pr.adr=tw.adr
			            WHERE tw.adr='".$adr."' 
			         ORDER BY tw.ID DESC 
			            LIMIT 0,20"; 
		    
		}
		
		$result=$this->kern->execute($query);	
		 
		 // No results
		 if (mysql_num_rows($result)==0) 
		 {
			 print "<span class='font_14' style='color:#990000'>No results found</span>";
			 return false;
		 }
		 
		 while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	     {
			 if ($row['budget']>0)
			 {
				 print "<div class='alert alert-warning font_14' role='alert' style='width:95%' align='left'><span class='glyphicon glyphicon-gift'></span>&nbsp;&nbsp;&nbsp;Incentives inside. Left budget <strong>".$row['budget']." ".$row['budget_cur']."</strong> </div>";
			 }
		?>
              
              <a href="../tweet/index.php?ID=<? print $row['tweetID']; ?>">
              <div class="panel panel-default" style="width:95%; " align="left">
              <div class="panel-body">
              
              <?
			     if ($row['retweet_tweet_ID']>0)
				 {
					 $query="SELECT tw.*, pr.pic, pr.pic_back 
					          FROM tweets AS tw 
							  LEFT JOIN profiles AS pr ON pr.adr=tw.adr 
							 WHERE tw.tweetID='".$row['retweet_tweet_ID']."'"; 
					 $re_result=$this->kern->execute($query);	
					 $re_row = mysql_fetch_array($re_result, MYSQL_ASSOC);
					
					 
					 ?>
                     
					 <table width="100%"><tr><td bgcolor="#f0f0f0" class="font_12" height="30">&nbsp;&nbsp;&nbsp;<? print $this->template->formatadr($adr)." retweeted ".$this->template->formatAdr($re_row['adr'])." post "; if ($row['mes']!="") print "and commented <strong>\"".base64_decode($row['mes'])."\"</strong>" ?></td></tr></table>
                     <br>
                     <?
				 }
			  ?>
              
              <table>
              <tr>
              <td width="20%" valign="top">
              
              <?
			    if ($row['retweet_tweet_ID']>0)
				{
			  ?>
                  
                  <img src="<? if ($re_row['pic']!="") print "../../../crop.php?src=".base64_decode($re_row['pic'])."&w=74&h=75"; else print "../../template/template/GIF/empty_pic.png"; ?>" width="75" height="75" class="img-responsive img-rounded">
                  
              <?
				}
				else
				{
					?>
                    
                    <img src="<? if ($row['pic']!="") print "../../../crop.php?src=".base64_decode($row['pic'])."&w=74&h=75"; else print "../../template/template/GIF/empty_pic.png"; ?>" width="75" height="75" class="img-responsive img-rounded">
                    
                    <?
				}
			  ?>
              
              </td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td width="80%" valign="top">
              
              <?
			    if ($row['retweet_tweet_ID']>0)
				{
			  ?>
              
              <a class="font_16" href="../adr/index.php?adr=<? print urlencode($re_row['adr']); ?>"><strong><? print $this->template->formatAdr($re_row['adr']); ?></strong></a>&nbsp;&nbsp;&nbsp;<span class="font_12"><? print "@".$this->template->formatAdr($re_row['adr']).", &nbsp;&nbsp;&nbsp;".$this->kern->getAbsTime($re_row['received'])." ago, &nbsp;&nbsp;&nbsp;"; ?></span><a href="javascript:void(0)" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $re_row['adr']; ?>'); $('#txt_plain').val('<? print $re_row['adr']; ?>'); $('#modal_qr').modal()" class="font_12" style="color:#999999">view full address</a><br><span class="font_14"><? print $this->formatTweet(base64_decode($re_row['mes'])); ?></span><br>
              <br>
              
              <?
				}
				else
				{
					?>
                    
                     <a class="font_16" href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>">
                     <strong><? print $this->template->formatAdr($row['adr']); ?></strong>
                     </a>&nbsp;&nbsp;&nbsp;
                     <span class="font_12"><? print "@".$this->template->formatAdr($row['adr']).", &nbsp;&nbsp;&nbsp;".$this->kern->getAbsTime($row['received'])." ago, &nbsp;&nbsp;&nbsp;"; ?></span><a href="javascript:void(0)" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()" class="font_12" style="color:#999999">view full address</a><br><span class="font_14" style="color:#555555"><? print nl2br($this->formatTweet(base64_decode($row['mes']))); ?></span><br>
                    
                    <?
				}
			  ?>
              
              
       
       <?
	       if (strlen($row['pic_1'])>10 && $row['retweet_tweet_ID']==0)
		   {
	   ?>
       
            <a href="<? print base64_decode($row['pic_1']); ?>" data-gallery>
            <img src="../../../crop.php?src=<? print base64_decode($row['pic_1']); ?>&w=400&h=300" class="img-responsive img-rounded" style="padding-bottom:10px">
            </a>
       
       <?
		   }
		   else if ($row['retweet_tweet_ID']>0 && strlen($re_row['pic_1'])>10)
		   {
			   ?>
               
                <a href="<? print base64_decode($re_row['pic_1']); ?>" data-gallery>
                <img src="../../../crop.php?src=<? print base64_decode($re_row['pic_1']); ?>&w=400&h=300" class="img-responsive img-rounded" style="padding-bottom:10px">
                </a>
               
               <?
		   }
	   ?>
       
       <table width="100%">
       <td width="25%">
       
       <?
	       if (strlen($row['pic_2'])>10 && $row['retweet_tweet_ID']==0)
		   {
		?>
       
             <a href="<? print base64_decode($row['pic_2']); ?>" data-gallery>
             <img src="../../../crop.php?src=<? print base64_decode($row['pic_2']); ?>&w=100&h=100" class="img-responsive img-rounded">
             </a>
       
       <?
		   }
		   else if ($row['retweet_tweet_ID']>0 && strlen($re_row['pic_2'])>10)
		   {
			   ?>
               
                <a href="<? print base64_decode($re_row['pic_2']); ?>" data-gallery>
                <img src="../../../crop.php?src=<? print base64_decode($re_row['pic_2']); ?>&w=100&h=100" class="img-responsive img-rounded">
                </a>
               
               <?
		   }
	   ?>
       
       </td>
       <td>&nbsp;&nbsp;&nbsp;</td>
     
       <td width="25%">
       
       <?
	       if (strlen($row['pic_3'])>10 && $row['retweet_tweet_ID']==0)
		   {
	   ?>
       
             <a href="<? print base64_decode($row['pic_3']); ?>" data-gallery>
             <img src="../../../crop.php?src=<? print base64_decode($row['pic_3']); ?>&w=100&h=100" class="img-responsive img-rounded">
             </a>
       
       <?
		   }
		   else if ($row['retweet_tweet_ID']>0 && strlen($re_row['pic_3'])>10)
		   {
			   ?>
               
                <a href="<? print base64_decode($re_row['pic_3']); ?>" data-gallery>
                <img src="../../../crop.php?src=<? print base64_decode($re_row['pic_3']); ?>&w=100&h=100" class="img-responsive img-rounded">
                </a>
               
               <?
		   }
	   ?>
       
       </td>
       
       <td>&nbsp;&nbsp;&nbsp;</td>
       
       <td width="25%">
       
       <?
	       if (strlen($row['pic_4'])>10 && $row['retweet_tweet_ID']==0)
		   {
	   ?>
       
             <a href="<? print base64_decode($row['pic_4']); ?>" data-gallery>
             <img src="../../../crop.php?src=<? print base64_decode($row['pic_4']); ?>&w=100&h=100" class="img-responsive img-rounded">
             </a>
       
       <?
		   }
		    else if ($row['retweet_tweet_ID']>0 && strlen($re_row['pic_4'])>10)
		    {
			   ?>
               
                <a href="<? print base64_decode($re_row['pic_4']); ?>" data-gallery>
                <img src="../../../crop.php?src=<? print base64_decode($re_row['pic_4']); ?>&w=100&h=100" class="img-responsive img-rounded">
                </a>
               
               <?
		   }
	   ?>
       
       </td>
       
       <td>&nbsp;&nbsp;&nbsp;</td>
       
       <td width="25%">
       
       <?
	       if (strlen($row['pic_5'])>10 && $row['retweet_tweet_ID']==0)
		   {
	   ?>
       
              <a href="<? print base64_decode($row['pic_5']); ?>" data-gallery>
              <img src="../../../crop.php?src=<? print base64_decode($row['pic_5']); ?>&w=100&h=100" class="img-responsive img-rounded">
              </a>
       
       <?
		    }
		    else if ($row['retweet_tweet_ID']>0 && strlen($re_row['pic_5'])>10)
		    {
			   ?>
               
                <a href="<? print base64_decode($re_row['pic_5']); ?>" data-gallery>
                <img src="../../../crop.php?src=<? print base64_decode($re_row['pic_5']); ?>&w=100&h=100" class="img-responsive img-rounded">
                </a>
               
               <?
		   }
	   ?>
       
       </td>
       
       </tr>
       </table>
       
       </td>
       </tr>
       </table>
       
       </div>
       
       <?
	      if ($_REQUEST['ud']['ID']>0)
		  {
			  if ($row['retweet_tweet_ID']>0)
			  {
				  ?>
                  
                   <div class="panel-footer">
                   <table>
                   <tr><td style="width:100%">
                    <a href="../tweet/index.php?ID=<? print $re_row['tweetID']; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;&nbsp;<? print $re_row['comments']; ?></a>&nbsp;
       
                   <a href="javascript:void(0)" onclick="$('#retweet_modal').modal(); $('#retweet_tweet_ID').val('<? print $re_ow['tweetID']; ?>')" class="btn btn-default btn-sm">
                   <span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;&nbsp;<? print $re_row['retweets']; ?></a>&nbsp;
       
                   <a href="javascript:void(0)" onclick="$('#like_modal').modal(); $('#like_tweetID').val('<? print $re_row['tweetID']; ?>');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;&nbsp;<? print $re_row['likes']; ?></a>&nbsp;
       
                   </td>
                   <td style="width:10%">
                   <div class="btn-group" role="group">
                   <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="glyphicon glyphicon-cog"></span>
                   <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li><a href="../adr/index.php?adr=<? print urlencode($re_row['adr']); ?>">Message Author</a></li>
                   <li><a href="../adr/index.php?adr=<? print urlencode($re_row['adr']); ?>">Tip Author</a></li>
                   <li><a href="../adr/index.php?adr=<? print urlencode($re_row['adr']); ?>">Follow / Unfollow Author</a></li>
                   <li><a href="javascript:void(0); $('#report_modal').modal(); $('#report_tweetID').val('<? print $re_row['tweetID']; ?>');">Report Tweet</a></li>
       
                  <? 
	                 if ($this->kern->isMine($re_row['adr'])==true) 
		               print "<li><a href='javascript:void(0)' onClick=\"$('#remove_modal').modal(); $('#remove_tweet_ID').val('".$re_row['tweetID']."'); \">Delete Tweet</a></li>";
	              ?>
      
       
                  </ul></div></td>
                  </tr></table>
                  </div></div>
                  
                  
                  <?
			  }
			  else
			  {
	   ?>
       
       <div class="panel-footer">
       <table>
       <tr><td style="width:100%">
       <a href="../tweet/index.php?ID=<? print $row['tweetID']; ?>" class="btn <? if ($row['comments']>0) print "btn-info"; else print "btn-default"; ?> btn-sm"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;&nbsp;<? print $row['comments']; ?></a>&nbsp;
       
       <a href="javascript:void(0)" onclick="$('#retweet_modal').modal(); $('#retweet_tweet_ID').val('<? print $row['tweetID']; ?>')" class="btn btn-default btn-sm">
       <span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;&nbsp;<? print $row['retweets']; ?></a>&nbsp;
       
       <a href="javascript:void(0)" onclick="$('#like_modal').modal(); $('#like_tweetID').val('<? print $row['tweetID']; ?>');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;&nbsp;<? print $row['likes']; ?></a>&nbsp;
       
       </td>
       <td style="width:10%">
       <div class="btn-group" role="group">
       <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       <span class="glyphicon glyphicon-cog"></span>
       <span class="caret"></span>
       </button>
       <ul class="dropdown-menu">
       <li><a href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>">Message Author</a></li>
       <li><a href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>">Tip Author</a></li>
       <li><a href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>">Follow / Unfollow Author</a></li>
       <li><a href="javascript:void(0); $('#report_modal').modal(); $('#report_tweetID').val('<? print $row['tweetID']; ?>');">Report Tweet</a></li>
       
       <? 
	      if ($this->kern->isMine($row['adr'])==true) 
		     print "<li><a href='javascript:void(0)' onClick=\"$('#remove_modal').modal(); $('#remove_tweet_ID').val('".$row['tweetID']."'); \">Delete Tweet</a></li>";
	   ?>
      
       
       </ul></div></td>
       </tr></table>
       </div></div>
       </a><br>
        
        <?
			  }
		   }
		   else print "</div></div></a><br>";
		   
		 }
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
	
	function showRetweet($adr)
	{
		$this->template->showModalHeader("retweet_modal", "Retweet", "act", "retweet", "retweet_tweet_ID", "", "../home/index.php?act=retweet");
		?>
          
          <input id="remove_tweet_ID" name="remove_tweet_ID" value="" type="hidden">
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/retweet.png" width="160" class="img-circle"/></td>
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
			      $this->template->showMyAdrDD("dd_retweet_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Retweet Address</strong></td>
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
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Short Message</strong></td>
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
	
	function showUnfollowModal($adr)
	{
		$this->template->showModalHeader("unfollow_modal", "Unfollow", "act", "unfollow", "adr", $adr);
		?>
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/down.png" width="190" alt=""/></td>
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
               <td align="center"><img src="../GIF/follow.png" width="150" alt=""/></td>
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
			      $this->template->showMyAdrDD("dd_follow_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Following Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showMyAdrDD("dd_follow_adr");
			   ?></td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
        
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showLikeModal()
	{
		$this->template->showModalHeader("like_modal", "Like", "act", "like", "like_tweetID", "");
		?>
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../GIF/like.png" width="180" alt=""/></td>
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
			      $this->template->showMyAdrDD("dd_like_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showMyAdrDD("dd_like_adr");
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
	
	function showNewCommentModal()
	{
		
		$this->template->showModalHeader("new_comment_modal", "New Comment", "act", "new_comment");
		?>
          
          <input type="hidden" id="com_tweetID" name="com_tweetID" value="0"> 
          <input type="hidden" id="com_comID" name="com_comID" value="0"> 
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="./GIF/comment.png" width="180" height="173" alt=""/></td>
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
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><?
			      $this->template->showMyAdrDD("dd_comm_adr");
			   ?></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Comment</strong></td>
             </tr>
             <tr>
               <td>
               <textarea name="txt_com_mes" id="txt_com_mes" rows="3"  style="width:300px" class="form-control" placeholder="Comments (optional)" onfocus="this.placeholder=''"></textarea>
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
	
	
	
	function showNewTweetModal()
	{
		
		$this->template->showModalHeader("new_tweet_modal", "New Tweet", "act", "new_tweet", "adr", $_REQUEST['adr'], "../../tweets/home/index.php");
		?>
           
           <input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple style="display:none">
           
           <input type="hidden" id="tweet_adr" name="tweet_adr" value="">
           <input type="hidden" id="h_img_0" name="h_img_0" value="">
           <input type="hidden" id="h_img_1" name="h_img_1" value="">
           <input type="hidden" id="h_img_2" name="h_img_2" value="">
           <input type="hidden" id="h_img_3" name="h_img_3" value="">
           <input type="hidden" id="h_img_4" name="h_img_4" value="">
           
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/chat.png" width="180" height="173" alt=""/></td>
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
			      $this->template->showMyAdrDD("dd_tweet_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" valign="top" style="font-size:16px"><strong>Tweet</strong></td>
             </tr>
             <tr>
               <td>
               <textarea name="txt_tweet_mes" id="txt_tweet_mes" rows="3"  style="width:300px" class="form-control" placeholder="Comments (optional)" onfocus="this.placeholder=''"></textarea>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top"><strong>Pics</strong></td>
             </tr>
             <tr id="row_drop">
               <td height="50" align="left"><img src="GIF/drop.png"/></td>
             </tr>
             <tr id="row_progress">
               <td height="0" align="left">
               <div id="progress" class="progress" style="width:300px">
               <div class="progress-bar progress-bar-success">&nbsp;</div>
               </div>
               </td>
             </tr>
             
             <tr>
               <td height="0" align="left">
              
               <table width="300" border="0" cellspacing="0" cellpadding="0">
               <tr>
               <td align="center" width="60"><img id="img_0" src="" style="display:none"></td>
               <td align="center">&nbsp;</td>
               <td align="center" width="60"><img id="img_1" src="" style="display:none"></td>
               <td align="center">&nbsp;</td>
               <td align="center" width="60"><img id="img_2" src="" style="display:none"></td>
               <td align="center">&nbsp;</td>
               <td align="center" width="60"><img id="img_3" src="" style="display:none"></td>
               <td align="center">&nbsp;</td>
               <td align="center" width="60"><img id="img_4" src="" style="display:none"></td>
              </tr>
              </table>
              
              </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top"><table width="320px" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td height="100" valign="top"><input type="checkbox" name="chk_incentive" id="chk_incentive" onChange="if ($('#tab_budget').css('display')=='none') $('#tab_budget').css('display', 'block'); else $('#tab_budget').css('display', 'display'); ">
                     &nbsp;&nbsp;&nbsp; <span class="font_14">Offer Incentive</span>&nbsp;&nbsp;&nbsp;<span class="font_10">( You can offer a reward to incentivize people to engage with your post or execute other actions like social media posts / likes and so on.)</span></td>
                   </tr>
                   <tr>
                     <td>
                     
                     <table width="290" border="0" cellpadding="0" cellspacing="0" id="tab_budget" style="display:none">
                       <tbody>
                         <tr>
                           <td width="122" align="left" class="font_14" height="35" valign="top"><strong>Budget</strong></td>
                           <td width="128" align="left" class="font_14" height="35" ><strong>Currency</strong></td>
                           <td width="128" align="left" class="font_14" height="35" ><strong>Period</strong></td>
                         </tr>
                         <tr>
                           <td align="left"><input name="txt_budget" id="txt_budget" class="form-control" style="width:80px" placeholder="0"></td>
                           <td align="left"><input name="txt_cur" id="txt_cur" class="form-control" style="width:80px" placeholder="MSK"></td>
                           <td align="left">
                           <select id="dd_days" name="dd_days" class="form-control" style="width:80px">
                           <option value="1">1 Day</option>
                           <option value="2">2 Day</option>
                           <option value="3">3 Day</option>
                           <option value="4">4 Day</option>
                           <option value="5">5 Day</option>
                           </select>
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
               <td height="30" align="left" valign="top">&nbsp;</td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
         <script>
		   $('#form_new_tweet_modal').submit(
		   function() 
		   {
		      $('#txt_tweet_mes').val(btoa(unescape(encodeURIComponent($('#txt_tweet_mes').val())))); 
		   });
		</script>
       
        <?
		$this->template->showModalFooter("Send");
		
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
		
		// Load unmoderated commnets
		$query="SELECT COUNT(*) AS total 
		          FROM tweets_comments 
		         WHERE tweetID IN (SELECT tweetID 
				                     FROM tweets 
								    WHERE adr IN (SELECT adr 
									                FROM my_adr 
												   WHERE userID='".$_REQUEST['ud']['ID']."')) 
			       AND status='ID_PENDING'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$comments=$row['total'];
		
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
	       $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $tweets=$row['tweets'];
		   $following=$row['following'];
		   $followers=$row['followers'];
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
            
            <a href="javascript:void(0)" onClick="$('#new_tweet_modal').modal(); $('#tweet_adr').val('<? print $adr; ?>');" class="btn btn-danger" style="width:100%">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;&nbsp;Tweet
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
            <a href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>&target=following"><? print $following; ?></a>
            </div>
            </div>
       
            <div class="panel panel-default">
            <div class="panel-heading">
            <h3 class="panel-title">Followers</h3>
            </div>
            <div class="panel-body">
            <a href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>&target=followers"><? print $followers; ?></a>
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
           <td width="6%"><img src="../../../crop.php?src=http://galleries.payserve.com/1/32188/43817/images/11.jpg&w=30&h=30" class="img-responsive img-rounded"></td>
           <td width="2%">&nbsp;</td>
           <td width="90%"><a href="index.php?adr=<? print urlencode($row['adr']); ?>"><? print $this->template->formatAdr($row['adr']); ?></a></td>
           </tr>
           <tr><td colspan="3"><hr></td></tr>
        
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
           <td width="6%"><img src="../../../crop.php?src=http://galleries.payserve.com/1/32188/43817/images/11.jpg&w=30&h=30" class="img-responsive img-rounded"></td>
           <td width="2%">&nbsp;</td>
           <td width="90%"><a href="index.php?adr=<? print urlencode($row['follows']); ?>"><? print $this->template->formatAdr($row['follows']); ?></a></td>
           </tr>
           <tr><td colspan="3"><hr></td></tr>
        
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
              <td width="33%" align="center">Tweets</td>
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

}
?>