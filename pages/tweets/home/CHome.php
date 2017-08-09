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
		          FROM comments 
				 WHERE rowID='".$comID."'
				   AND status='ID_PENDING'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load comment data
		$com_row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Load tweet data
		$query="SELECT * 
		          FROM tweets
				 WHERE tweetID='".$com_row['tweetID']."'";
		$result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load tweet data
		$tweet_row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
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
	
	
	
	function showPendingTweets($adr)
	{
		$query="SELECT tc.* 
		          FROM comments AS tc 
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
	    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
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