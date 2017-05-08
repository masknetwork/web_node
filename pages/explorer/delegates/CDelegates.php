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
		// Delegate from domain 
		$delegate=$this->kern->adrFromDomain($delegate);
		
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
		if ($this->kern->getBalance($adr)<10)
		{
			$this->template->showErr("Minimum balance is 10 MSK");
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
               <td width="20%" align="left">
               
               <div class="btn-group">
               <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span class="glyphicon glyphicon-refresh"></span>&nbsp;<span class="caret"></span>
               </button>
               <ul class="dropdown-menu">
               <li><a href="index.php?type=real_time">Real time delegates table</a></li>
               <li><a href="index.php?type=active">Active delegates table</a></li>
               </ul>
               </div>
               
               </td>
               <td width="70%" align="right"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/upvote.png'); $('#txt_vote_type').val('ID_UP'); $('#txt_vote_delegate').val('');" class="btn btn-success"><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Vote New Delegate</a></td>
               <td width="1%">&nbsp;</td>
               <td width="1%"><a href="last_votes.php" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Last votes">
               <span class="glyphicon glyphicon-list"></span></a></td>
               </tr>
               </tbody>
               </table>
               
            <?
		}
	}
	
	function getLogBlock($type="ID_BEHIND")
	{
		if ($type=="ID_BEHIND")
		{
		   $block=$_REQUEST['sd']['last_block'];
		   while ($block%100!=0) $block--;
		   $block=$block-100;
		}
		else
		{
		   $block=$_REQUEST['sd']['last_block'];
		   while ($block%100!=0) $block++;
		   $block=$block+100;
		}
		
		return $block;
	}
	
	function showDelegates($type="real_time")
	{
		// Find block
		$block=$this->getLogBlock();
		
		if ($type=="real_time")
		    $query="SELECT * 
		              FROM delegates 
			      ORDER BY power DESC 
			         LIMIT 0,100";
		else
		   $query="SELECT * 
		             FROM delegates_log 
					WHERE block='".$block."'
			     ORDER BY power DESC 
			        LIMIT 0,100";
		
		// Execute		 
		$result=$this->kern->execute($query);	
	    
		
		?>
        
        
        <br><br>
        <table style="width:90%">
        <tr><td height="40px" class="font_18" align="left" style="color:#aaaaaa">
        <? 
		    if ($type=="real_time") 
			    print "Real Time Delegates Table"; 
		    else 
			    print "Active Delegates Table"; ?>
        
        </td></tr>
        
		<?
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
              <tr>
              <td class="font_14" width="85%"><a href="delegate.php?ID=<? print $row['ID']; ?>"><? print $this->template->formatAdr($row['delegate']); ?></a></td>
              <td class="font_14" style="color:#009900" width="18%"><strong><? print $row['power']." MSK"; ?></strong></td>
              
              <td width="5%"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/upvote.png'); $('#txt_vote_type').val('ID_UP'); $('#txt_vote_delegate').val('<? print $row['delegate']; ?>'); " class="btn btn-success btn-sm"><span class="glyphicon glyphicon-thumbs-up"></span></a></td>
              
              <td>&nbsp;</td>
              <td width="5%"><a href="javascript:void(0)" onClick="$('#modal_vote_delegate').modal(); $('#img_delegate').attr('src', 'GIF/downvote.png'); $('#txt_vote_type').val('ID_DOWN'); $('#txt_vote_delegate').val('<? print $row['delegate']; ?>');" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-down"></span></a></td>
              
              <td>&nbsp;</td>
              <td width="5%"><a href="delegate.php?ID=<? print $row['ID']; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span></a></td>
              </tr>
              <tr><td colspan="4"><hr></td></tr>
        
        <?
		   }
		?>
        
        </table>
        
        <?
	}
	
	function showLastVotes()
	{
		$query="SELECT * 
		          FROM del_votes 
			  ORDER BY block DESC 
			     LIMIT 0,100";
		$result=$this->kern->execute($query);	
	    
		
		?>
        
        <br><br>
        <table style="width:90%" class="table table-responsive">
        <thead style="font-size:14px">
        <td width="35%">Address</td>
        <td width="35%">Delegate</td>
        <td width="10%">Type</td>
        <td width="10%">Power</td>
        <td width="10%">Received</td>
        </thead>
        
        <?
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		   {
		?>
        
              <tr>
              <td class="font_14" width="35%" height="50px"><a href="#"><? print $this->template->formatAdr($row['adr']); ?></a></td>
              <td class="font_14" width="35%"><a href="#"><? print $this->template->formatAdr($row['delegate']); ?></a></td>
              <td class="font_14" width="10%" style="color:<? if ($row['type']=="ID_UP") print "#009900"; else print "#990000"; ?>"><? if ($row['type']=="ID_UP") print "Upvote"; else print "Downvote"; ?></td>
              <td class="font_14" width="10%"><? print $row['power']; ?></td>
              <td class="font_14" width="10%"><? print $row['block']; ?></td>
              </tr>
              
        
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
	
	function showDelegate($ID)
	{
		$query="SELECT * 
		          FROM delegates
				 WHERE ID='".$ID."'"; 
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			// Search in delegates log
			$query="SELECT * 
		              FROM delegates_log
				     WHERE ID='".$ID."'"; 
		    $result=$this->kern->execute($query);	
		    
			// No records
			if (mysql_num_rows($result)==0)
		    {
		       print "<span class='font_14' style='color:#990000'>No records found</span>";
		       return false;
			}
			else
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$ID=$row['ID'];
			}
		}
		else $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$delegate=$row['delegate'];
		
		// Difficulty
		$dif=$row['dif'];
		
		// Upvotes number
		$query="SELECT COUNT(*) AS total_no, 
		               SUM(power) AS total_power
		          FROM del_votes 
				 WHERE type='ID_UP' 
				   AND delegate='".$delegate."'"; 
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$upvotes_no=$row['total_no'];
		$upvotes=$row['total_power'];
		
		// Downvotes
		$query="SELECT COUNT(*) AS total_no,
		                SUM(power) AS total_power
		          FROM del_votes 
				 WHERE type='ID_DOWN' 
				   AND delegate='".$delegate."'";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$downvotes_no=$row['total_no'];
		$downvotes=$row['total_power'];
		
		// Net
		$net=$upvotes-$downvotes;
		
		// Blocks mined
		$query="SELECT COUNT(*) AS blocks_no, 
		               SUM(reward) AS reward
		          FROM blocks 
				 WHERE signer='".$delegate."' 
				   AND block>".($_REQUEST['sd']['last_block']-1440); 
	    $result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$blocks_no=$row['blocks_no'];
		$reward=$row['reward'];
		
		if ($blocks_no=="")
		{
			$blocks_no=0;
			$reward=0;
		}
		
		// Network dif
		$query="SELECT * FROM net_stat";
		$result=$this->kern->execute($query);	
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$net_dif=$row['net_dif'];
		
		?>
        
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="16%" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                  <tr>
                    <td><img src="../../template/template/GIF/empty_pic.png" width="100%" class="img img-circle img-responsive"/></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="50px" align="center">
                    <a href="#" class="btn btn-success btn-sm" style="width:100px"> <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Upvote </a></td>
                  </tr>
                  <tr>
                    <td align="center">
                    <a height="50px" href="#" class="btn btn-danger btn-sm"  style="width:100px"> <span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;&nbsp;Downvote </a></td>
                  </tr>
                </tbody>
            </table>
             
             </td>
            <td width="84%" align="right" valign="top"><table width="95%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="74%" height="40" align="left" class="font_14">Delegate : <strong><? print $this->template->formatAdr($row['delegate']); ?></strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Upvotes : <strong style="color:#009900"><? print $upvotes_no." (".$upvotes." MSK)"; ?></strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Downvotes : <strong style="color:#990000"><? print $downvotes_no." (".$downvotes." MSK)"; ?></strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Net Votes Power : <strong><? print $net; ?> MSK</strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Default  difficulty : <strong><? print $net_dif; ?></strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Miner  difficulty : <strong><? print $dif; ?></strong></td>
                  </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Blocks mined 24H : <strong><? print $blocks_no; ?> blocks</strong></td>
                </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Miner revenue 24H : <strong><? print round($reward-$reward/4, 8); ?> MSK</strong></td>
                </tr>
                <tr>
                  <td height="40" align="left" class="font_14">Voters revenue 24H : <strong><? print round($reward/4, 8); ?> MSK</strong></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
        </table>
        
        <?
	}
}
?>