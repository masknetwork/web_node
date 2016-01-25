<?
class CHome
{
	function CHome($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function updateCommentStatus($net_fee_adr, $comID, $status)
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
		
		// Status
		if ($status!="ID_APROVE" && $status!="ID_REJECT")
		{
			$this->template->showErr("Invalid entry data.", 550);
			return false;
		}
		
		// Comment ID
		$query="SELECT * 
		          FROM tweets_comments 
				 WHERE rowID='".$comID."'
				   AND status='ID_PENDING'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load comment data
		$com_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Load tweet data
		$query="SELECT * 
		          FROM tweets
				 WHERE tweetID='".$com_row['tweetID']."'";
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load tweet data
		$tweet_row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// My comment ?
		if ($this->kern->isMine($tweet_row['adr'])==false)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates a comment status");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_UPDATE_TWEET_COMMENT', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$tweet_row['adr']."',
							   par_1='".$comID."',
							   par_2='".$status."',
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
	
	function newTweet($net_fee_adr, 
	                  $adr,
					  $target_adr, 
					  $mes, 
					  $retweet_tweet_id=0, 
					  $pic_1="", 
					  $pic_2="", 
					  $pic_3="", 
					  $pic_4="", 
					  $pic_5="", 
					  $video="",
					  $budget=0,
					  $budget_cur="",
					  $budget_expires=0)
	{
		// Decode
		$mes=base64_decode($mes); 
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		}
		
		// Target Address
		if ($this->kern->adrValid($target_adr)==false)
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
		
		
		// Pic 1
		if ($pic_1!="")
		{
		  if (filter_var($pic_1, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 1 link", 550);
			return false;
		  }
		}
		
		// Pic 2
		if ($pic_2!="")
		{
		  if (filter_var($pic_2, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 2 link", 550);
			return false;
		  }
		}
		
		// Pic 3
		if ($pic_3!="")
		{
		  if (filter_var($pic_3, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 3 link", 550);
			return false;
		  }
		}
		
		// Pic 4
		if ($pic_4!="")
		{
		  if (filter_var($pic_4, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 4 link", 550);
			return false;
		  }
		}
		
		// Pic 5
		if ($pic_5!="")
		{
		  if (filter_var($pic_5, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid pic 5 link", 550);
			return false;
		  }
		}
		
		// Video
		if ($video!="")
		{
		  if (filter_var($video, FILTER_VALIDATE_URL) === false) 
		  {
			$this->template->showErr("Invalid video link", 550);
			return false;
		  }
		}
	    
		// Message
		if ($retweet_tweet_id==0)
		{
		   if (strlen($mes)<10 || strlen($mes)>500)
		   {
			  $this->template->showErr("Invalid message length (10-500 characters)", 550);
			  return false;
		   }
		}
		else
		{
		   if ($mes!="")
		   {
		     if (strlen($mes)<10 || strlen($mes)>500)
		     {
			    $this->template->showErr("Invalid message length (10-500 characters)", 550);
			    return false;
		     }
		   }
		}
		
		// Budget
		if ($budget>0)
		{
			// Asset exist ?
			if ($budget_cur!="MSK")
			{
				$query="SELECT * FROM assets WHERE symbol='".$budget_cur."'";
				$result=$this->kern->execute($query);	
	            if (mysql_num_rows($result)==0)
				{
					$this->template->showErr("Invalid asset symbol", 550);
			        return false;
			    }
			}
			
			// Funds
			if ($budget>$this->kern->getBalance($adr, $budget_cur))
			{
				$this->template->showErr("Innsuficient funds to execute this operation", 550);
			    return false;
			}
			
			// Maximum 10000 blocks
			if ($budget_expires>5)
			{
				$this->template->showErr("Maximum 5 days allowes for a campaign", 550);
			    return false;
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
							   par_1='".$target_adr."',
							   par_2='".base64_encode($mes)."',
							   par_3='".$retweet_tweet_id."',
							   par_4='".base64_encode($pic_1)."',
							   par_5='".base64_encode($pic_2)."',
							   par_6='".base64_encode($pic_3)."',
							   par_7='".base64_encode($pic_4)."',
							   par_8='".base64_encode($pic_5)."',
							   par_9='".base64_encode($video)."',
							   par_10='".$budget."',
							   par_11='".$budget_cur."',
							   par_12='".$budget_expires."',
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
	
	function showPendingTweets($adr)
	{
		$query="SELECT tc.* 
		          FROM tweets_comments AS tc 
				  JOIN tweets AS tw ON tw.tweetID=tc.tweetID 
				 WHERE tw.adr IN (SELECT adr 
				                    FROM my_adr 
								   WHERE userID='".$_REQUEST['ud']['ID']."') 
				   AND tc.status='ID_PENDING'";
		$result=$this->kern->execute($query);	
		
		// Modal
		$this->showUpdateTweetStatusModal();
		
		// Table
		print "<table width='95%' border='0' cellspacing='0' cellpadding='0'>";
	    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			?>
           
               <tr>
               <td width="10%" valign="top"><img src="../../template/template/GIF/empty_pic.png" class="img img-responsive img-rounded"></td>
               <td width="3%">&nbsp;</td>
               <td width="50%">
               <a href="#" class="font_16"><? print $this->template->formatAdr($row['adr']); ?></a><br>
               <span class="font_14"><? print base64_decode($row['mes']); ?></span>
               </td>
               <td width="3%">&nbsp;</td>
               
               <td width="15%" valign="top"><a href="javascript:void(0)" onclick="$('#update_comment_status').modal(); 
                                                                                 $('#status').val('ID_APROVE'); 
                                                                                 $('#img_status').attr('src', './GIF/up.png'); 
                                                                                 $('#comID').val('<? print $row['rowID']; ?>');" class="btn btn-primary">Aprove</a></td>
               
               <td width="15%" valign="top"><a href="javascript:void(0)" onclick="$('#update_comment_status').modal(); 
                                                                                 $('#status').val('ID_REJECT'); 
                                                                                 $('#img_status').attr('src', './GIF/down.png');  
                                                                                 $('#comID').val('<? print $row['rowID']; ?>');" class="btn btn-danger">Delete</a></td>
               </tr>
               <tr>
               <td colspan="6"><hr></td>
               </tr>
       
            
            <?
		}
		
		print "</table>";
	}
	
	
	
	function showUpdateTweetStatusModal()
	{
		
		$this->template->showModalHeader("update_comment_status", "Update Comment Status", "act", "update_comment", "status", "", "pending.php");
		?>
           
           
           <input id="comID" name="comID" type="hidden" value="0">
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="./GIF/up.png" width="120" id="img_status" /></td>
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
			      $this->template->showMyAdrDD("dd_tweet_status_net_fee");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             
           </table>
           </td>
         </tr>
     </table>
     
        
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
}
?>