<?
class CDelegates
{
	function CDelegates($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function vote($net_fee_adr, $adr, $delegate, $type)
	{
		// Valid addresses ??
		if ($this->kern->isAdr($net_fee_adr)==false || 
		    $this->kern->isAdr($adr)==false || 
			$this->kern->isAdr($delegate)==false)
			{
				$this->template->showErr("Invalid entry data");
				return false;
			}
			
	    // Mine ?
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
	    {
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Can spend
		if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Network fee address can't spend coins");
			return false;
		}
		
		// Min balance
		if ($this->kern->getBalance($adr)<1)
		{
			$this->template->showErr("Minimum balance is 1 MSK");
			return false;
		}
		
		// Type
		if ($type!="ID_UP" && 
	        $type!="ID_DOWN")
		{
			$this->template->showErr("Invalid vote type");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Votes a delegate");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_VOTE_DELEGATE', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$adr."',
							   par_1='".$delegate."',
							   par_2='".$type."',
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
	
	function showAddBut()
	{
		if ($_REQUEST['ud']['ID']>0)
		{
			?>
        
               <table width="90%" border="0" cellpadding="0" cellspacing="0">
               <tbody>
               <tr>
               <td align="right"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/upvote.png'); $('#txt_vote_type').val('ID_UP'); $('#txt_vote_delegate').val('');" class="btn btn-success"><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Vote New Delegate</a></td>
               </tr>
               </tbody>
               </table>
               
            <?
		}
	}
	
	function showDelegates()
	{
		$query="SELECT * 
		          FROM delegates 
			  ORDER BY power DESC 
			     LIMIT 0,100";
		$result=$this->kern->execute($query);	
	    
		
		?>
        
        <br><br>
        <table style="width:90%">
        
        <?
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
              <tr>
              <td class="font_14" width="72%"><a href="#"><? print $this->template->formatAdr($row['delegate']); ?></a></td>
              <td class="font_14" style="color:#009900" width="20%"><strong><? print $row['power']." MSK"; ?></strong></td>
              <td width="6%"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/upvote.png'); $('#txt_vote_type').val('ID_UP'); $('#txt_vote_delegate').val('<? print $row['delegate']; ?>'); " class="btn btn-success btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span></a></td>
              <td width="6%"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/downvote.png'); $('#txt_vote_type').val('ID_DOWN'); $('#txt_vote_delegate').val('<? print $row['delegate']; ?>');" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-down"></span></a></td>
              </tr>
              <tr><td colspan="4"><hr></td></tr>
        
        <?
		   }
		?>
        
        </table>
        
        <?
	}
	
	function showVoteModal()
	{
		$this->template->showModalHeader("modal_vote_delegate", "Vote Delegate", "act", "vote", "vote_delegate", "");
		?>
            
            <input type="hidden" value="" id="txt_vote_type" name="txt_vote_type">
            <table width="560" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="214" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/upvote.png" width="150" height="150" id="img_delegate" name="img_delegate" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(0.0001); ?></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="396" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" height="30px" class="simple_blue_14" valign="top"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_vote_fee_adr", 340); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" height="30px"><span class="simple_blue_14"><strong>Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_vote_adr", 340); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" class="simple_blue_14" valign="top" height="30px"><strong>Delegate Address</strong></td>
              </tr>
              <tr>
                <td align="left"><input id="txt_vote_delegate" name="txt_vote_delegate" class="form-control" placeholder="Delegate Address"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
       
		
        
        <?
		$this->template->showModalFooter("Send");
	}
}
?>