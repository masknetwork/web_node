<?
class CTweet
{
	function CTweet($db, $template, $tweets)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->tweets=$tweets;
	}
	
	function reward($net_fee_adr, $resID, $reward)
	{
	    // Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 560);
			return false;
		}
		
		// Response ID
		$query="SELECT tweets.* 
		          FROM comments AS tc 
				  JOIN tweets ON tweets.tweetID=tc.tweetID 
				 WHERE tc.rowID='".$resID."'"; 
		$result=$this->kern->execute($query);	
	    if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid response ID", 570);
			return false;
		}
		
		// Load data
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    
		// Target address
		$target_adr=$row['adr'];
		
		// Mine ?
		if ($this->kern->isMine($target_adr)==false || 
		    $this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Budget
		if ($reward>$row['budget'])
		{
			$this->template->showErr("Maximum reward is ".$row['budget'], 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Post a comment");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_NEW_TWEET_REWARD', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$target_adr."',
							   par_1='".$resID."',
							   par_2='".$reward."',
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
	
	function newComment($net_fee_adr, 
	                    $adr,
					    $target_type,
						$targetID,
						$mes)
	{
		// Decode
		$mes=base64_decode($mes); 
		
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
	    if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Network fee address can't spend coins", 550);
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
		
		// Repply to tweet ?
		if ($target_type=="ID_POST")
		{
		    $query="SELECT * 
		              FROM tweets 
				     WHERE tweetID='".$targetID."'";
		    $result=$this->kern->execute($query);	
			
	        if (mysqli_num_rows($result)==0)
		    {
			    $this->template->showErr("Invalid post ID", 550);
		  	    return false;
		    }
		}
		
		
		// Reply to comment ?
		if ($target_type=="ID_COM")
		{
		    $query="SELECT * 
		              FROM comments 
				     WHERE comID='".$targetID."'";
		    $result=$this->kern->execute($query);	
			
	        if (mysqli_num_rows($result)==0)
		    {
			    $this->template->showErr("Invalid comment ID", 550);
		  	    return false;
		    }
		}
		
		// Already commented ?
		$query="SELECT * 
		          FROM comments 
				 WHERE adr='".$adr."' 
				   AND parent_type='".$target_type."' 
				   AND parentID='".$targetID."'";
	     $result=$this->kern->execute($query);	
	     
		 if (mysqli_num_rows($result)>0)
	     {
			    $this->template->showErr("You have already commented this post", 550);
		  	    return false;
		  }
			
		// Message
		if (strlen($mes)<10 || strlen($mes)>1000)
		{
			$this->template->showErr("Invalid message length (10-1000 characters)", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Post a comment");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_NEW_TWEET_COMMENT', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".$target_type."',
							   par_2='".$targetID."',
							   par_3='".base64_encode($mes)."',
							   status='ID_PENDING', 
							   tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your comment is awaiting approval from tweet owner", 550);
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
	
	function showTweet($ID)
	{
		// Like modal
		$this->tweets->showLikeModal();
		
		// Delete modal
	    $this->tweets->showRemoveModal($adr);
		
		// QR modal
		$this->tweets->template->showQRModal();
		
		// Retweet modal
		$this->tweets->showRetweet($adr);
		
		// Report
		$this->tweets->showReport();
		
		$query="SELECT * FROM tweets WHERE tweetID='".$ID."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    
		if ($row['budget']>0)
	        print "<div class='alert alert-warning font_14' role='alert' style='width:95%' align='left'><span class='glyphicon glyphicon-gift'></span>&nbsp;&nbsp;&nbsp;Incentives inside. Left budget <strong>".$row['budget']." ".$row['budget_cur']."</strong> </div>";
	   
			 
		?>
             
             
             <div class="panel panel-default" style="width:95%" align="left">
              <div class="panel-body">
              
              <?
			     if ($row['retweet_tweet_ID']>0)
				 {
					 $query="SELECT tw.*, pr.pic, pr.pic_back 
					          FROM tweets AS tw 
							  LEFT JOIN profiles AS pr ON pr.adr=tw.adr 
							 WHERE tw.tweetID='".$row['retweet_tweet_ID']."'"; 
					 $re_result=$this->kern->execute($query);	
					 $re_row = mysqli_fetch_array($re_result, MYSQL_ASSOC);
					
					 
					 ?>
                     
					 <table width="100%"><tr><td bgcolor="#f0f0f0" class="font_12" height="30">&nbsp;&nbsp;&nbsp;<? print $this->template->formatadr($adr)." retweeted ".$this->template->formatAdr($re_row['adr'])." post "; if ($row['mes']!="") print "and commented <strong>\"".base64_decode($row['mes'])."\"</strong>" ?></td></tr></table>
                     <br>
                     <?
				 }
			  ?>
              
              <table class="table-responsive" width="100%">
              <tr>
              <td width="20%" valign="top">
              
              <?
			    if ($row['retweet_tweet_ID']>0)
				{
			  ?>
                  
                  <img src="<? if ($re_row['pic']!="") print "../../../crop.php?src=".base64_decode($re_row['pic'])."&w=74&h=75"; else print "../../template/template/GIF/empty_pic.png"; ?>" width="75" height="75" class="img-rounded img-responsive">
                  
              <?
				}
				else
				{
					?>
                    
                    <img src="<? if ($row['pic']!="") print "../../../crop.php?src=".base64_decode($row['pic'])."&w=74&h=75"; else print "../../template/template/GIF/empty_pic.png"; ?>" width="75" height="75" class="img-rounded img-responsive">
                    
                    <?
				}
			  ?>
              
              </td>
              <td width="2%">&nbsp;</td>
              <td width="80%" valign="top">
              
              <?
			    if ($row['retweet_tweet_ID']>0)
				{
			  ?>
              
              <a class="font_16" href="../adr/index.php?adr=<? print urlencode($re_row['adr']); ?>"><strong><? print $this->template->formatAdr($re_row['adr']); ?></strong></a>&nbsp;&nbsp;&nbsp;<span class="font_12"><? print $this->kern->getAbsTime($re_row['received'])." ago, &nbsp;&nbsp;&nbsp;"; ?></span><a href="javascript:void(0)" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $re_row['adr']; ?>'); $('#txt_plain').val('<? print $re_row['adr']; ?>'); $('#modal_qr').modal()" class="font_12" style="color:#999999">view full address</a><br><span class="font_14"><? print nl2br($this->makeLinks(base64_decode($row['mes']))); ?></span><br>
              <br>
              
              <?
				}
				else
				{
					?>
                    
                     <a class="font_16" href="../adr/index.php?adr=<? print urlencode($row['adr']); ?>"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a>&nbsp;&nbsp;&nbsp;<span class="font_12"><? print $this->kern->getAbsTime($row['received'])." ago, &nbsp;&nbsp;&nbsp;"; ?></span><a href="javascript:void(0)" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()" class="font_12" style="color:#999999">view full address</a><br><span class="font_14"><? print nl2br($this->makeLinks(base64_decode($row['mes']))); ?></span><br>
                    
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
                  </tr></table></div></div>
                  
                  
                  <?
			  }
			  else
			  {
	   ?>
       
       <div class="panel-footer">
       <table>
       <tr><td style="width:100%">
       <a href="../tweet/index.php?ID=<? print $row['tweetID']; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;&nbsp;<? print $row['comments']; ?></a>&nbsp;
       
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
        </tr></table></div>
        
        
        <?
			  }
		  }
		  
		  ?>
          
          </div>
          
          <?
		  
	}
	
	function makeLinks($mes)
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
			else 
			   $m=$m." ".$v[$a];
		}
		
		return $m;
	}
	
	function showCommentBut($tweetID)
	{
	   if ($_REQUEST['ud']['ID']>0)
	   {
		?>
             
             <br>
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
             <tbody>
             <tr>
             <td align="right"><a htref="javascript:void(0)" onClick="$('#new_comment_modal').modal(); $('#com_tweetID').val('<? print $tweetID; ?>')" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;New Comment</a></td>
             </tr>
             </tbody>
             </table>
        
        <?
	   }
	}
	
	function showComment($ID, 
	                     $tweetID, 
						 $comID, 
						 $tweet_is_mine,
						 $pic, 
						 $tweet_budget, 
						 $tipped, 
						 $cur, 
						 $adr, 
						 $mes, 
						 $left=1)
	{
		if ($comID==0) 
		    $width=60; 
	    else 
		    $width=40;
		?>
            
            <table width='95%' border='0' cellspacing='0' cellpadding='0'>
            <tr>
               <td width="<? print $left."%"; ?>">&nbsp;</td>
               <td width="3%" valign="top">
               <img src="../../template/template/GIF/<? print $pic; ?>" class="img img-rounded" width="<? print $width; ?>">
               <div style="height:10px">&nbsp;</div>
			   
			   <?
			       if ($tweet_is_mine==true && 
				       $tweet_budget>0 && 
					   $tipped==0 &&
					   $comID==0)
				   {
			   ?>
               
               <a href="javascript:void(0)" onClick="$('#reward_modal').modal(); $('#reward_resID').val('<? print $ID; ?>');" class="btn btn-warning" style="width:100%;" title="Reward Answer" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-gift" style="color:#000000"></span></a>
               
               <?
				   }
				   else if ($tipped>0) 
				     print "<span class='label label-success' title='Rewarded Answer' data-toggle='tooltip' data-placement='top'>+ ".round($tipped, 8)." </span><p class='font_10'>".$cur."</p>";
			   ?>
               
               </td>
               <td width="5%">&nbsp;</td>
               <td width="<? print (90-$left)."%"; ?>" valign="top">
               <a href="#" class="font_14"><? print $this->template->formatAdr($adr); ?></a><br>
               <span class="font_14"><? print nl2br($this->makeLinks(base64_decode($mes))); ?></span>
               <div style="height:10px">&nbsp;</div>
               
               
               <?
			      if ($_REQUEST['ud']['ID']>0)
				  {
			   ?>
               
               <p style="background-color:#f9f9f9; padding-top:5px; padding-left:5px; padding-right:5px; padding-bottom:5px;" class="font_10" align="right">
               <a href="javascript:void(0); $('#new_comment_modal').modal(); $('#com_tweetID').val('<? print $tweetID; ?>'); $('#com_comID').val('<? print $ID; ?>');" class="font_10" style="color:#555555">Reply</a>
                </p>
                
			   <?
	             }
			   ?>
               
              
               </tr>
               <tr>
               <td colspan="4"><hr></td>
               </tr>
               </table>
        
        <?
		
		$this->showTweetComments($tweetID, $ID, $left+10);
	}
	
	function showTweetComments($tweetID, $comID=0, $left=1)
	{
		$query="SELECT * 
		          FROM tweets 
				 WHERE tweetID='".$tweetID."'"; 
		$result=$this->kern->execute($query);
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Budget
		$budget=$row['budget'];
		
		// Currency
		$budget_cur=$row['budget_cur'];
		
		// My address ?
		if ($this->kern->isMine($row['adr'])==true)
		   $mine=true;
		else
		   $mine=false;
		
		// Load comment data
		$query="SELECT *
		          FROM comments 
				 WHERE tweetID='".$tweetID."' 
				   AND comID='".$comID."'
				   AND status='ID_APROVE' 
			  ORDER BY tipped DESC 
			     LIMIT 0,25";
		$result=$this->kern->execute($query);	
		
		// No esults
		if (mysqli_num_rows($result)==0) return false;
		
		// Table
		print "<br>";
		
	    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			$this->showComment($row['rowID'], 
			                   $row['tweetID'],
							   $row['comID'],
							   $mine, 
			                   "empty_pic.png", 
							   $budget,
							   $row['tipped'], 
							   $budget_cur,
							   $row['adr'], 
							   $row['mes'],
							   $left);
	}
	
	function showRewardModal($resID)
	{
		$this->template->showModalHeader("reward_modal", "Reward Response", "act", "reward", "reward_resID", $resID);
		?>
          
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/reward.png" width="180" height="181" alt=""/></td>
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
			      $this->template->showMyAdrDD("dd_reward_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:16px"><strong>Reward</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
               <input id="txt_reward" name="txt_reward" class="form-control" style="width:100px">
               </td>
             </tr>
             
           </table></td>
         </tr>
     </table>
     
        
        <?
		$this->template->showModalFooter("Send");
		
	}
}
?>