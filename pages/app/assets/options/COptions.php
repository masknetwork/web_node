<?
class COptions
  {
	function COptions($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function buyOption($net_fee_adr, 
	                   $bet_adr,
					   $betID, 
					   $amount)
	{
		// Net Fee Address 
		if ($this->kern->adrValid($net_fee_adr)==false || 
		    $this->kern->adrExist($net_fee_adr)==false || 
			$this->kern->canSpend($net_fee_adr)==false || 
			$this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address or network fee address can't spend funds");
			return false;
		}
		
		// Net Fee Address 
		if ($this->kern->adrValid($bet_adr)==false || 
		    $this->kern->adrExist($bet_adr)==false || 
			$this->kern->canSpend($bet_adr)==false ||
			$this->kern->isMine($bet_adr)==false)
		{
			$this->template->showErr("Invalid bet address or network fee address can't spend funds");
			return false;
		}
	   
	     // Network fee
		 if ($this->kern->getBalance($net_fee_adr)<0.0001)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		 // Bet ID valid
		 $query="SELECT * 
		           FROM feeds_bets 
				  WHERE betID='".$betID."' 
				    AND end_block>".$_REQUEST['sd']['last_block']." 
					AND accept_block>".$_REQUEST['sd']['last_block']; 
		 $result=$this->kern->execute($query);	
		 
		 // Bet exist ?
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid entry data");
		     return false;
		 }
		 
		 // Load data
	     $bet_row = mysql_fetch_array($result, MYSQL_ASSOC);
	     
		 // Currency balance
		 if ($this->kern->getBalance($bet_adr, $bet_row['cur'])<$invest)
		 {
			 $this->template->showErr("Insufficient funds to execute the transaction");
		     return false;
		 }
		 
		 // Enough bet budget
		 $left_budget=round($bet_row['budget']/(1+$bet_row['win_multiplier']/100), 4); 
		 
		 // Check
		 if ($left_budget<$amount)
		 {
			 $this->template->showErr("Maximum accepted investment is ".$left_budget." ".$bet_row['cur']);
		     return false;
		 }
		 
		 // Amount
		 if ($amount<0.0001)
		 {
			 $this->template->showErr("Minimum accepted investment is 0.0001 ".$bet_row['cur']);
		     return false;
		 }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Buys a binary option");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_BUY_BET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$bet_adr."',
								par_1='".$betID."',
								par_2='".$amount."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function newOption($net_fee_adr, 
	                   $bet_adr, 
					   $type, 
					   $lev_1, 
					   $lev_2, 
					   $budget, 
					   $cur, 
					   $profit, 
					   $name, 
					   $desc, 
					   $bid, 
					   $expire,
					   $expire_per, 
					   $accept,
					   $accept_per, 
					   $feed, 
					   $feed_branch)
	{
		// Decode
		$name=base64_decode($name);
		$desc=base64_decode($desc);
		 
		// Net Fee Address 
		if ($this->kern->adrValid($net_fee_adr)==false || 
		    $this->kern->adrValid($bet_adr)==false || 
			$this->kern->adrExist($net_fee_adr)==false || 
			$this->kern->adrExist($bet_adr)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
	    
		// Can spend
		if ($this->kern->canSpend($net_fee_adr)==false || 
			$this->kern->canSpend($bet_adr)==false)
		{
			$this->template->showErr("Address can't spend funds");
			return false;
		}
	     
		 // Name
		 if ($this->kern->isTitle($name)==false)
		 {
			 $this->template->showErr("Invalid name");
			 return false;
		 }
		 
		 // Description
		 if ($this->kern->isDesc($desc)==false)
		 {
			 $this->template->showErr("Invalid description");
			 return false;
		 }
		
		 // Type 
		 if ($type!="ID_TOUCH" && 
		     $type!="ID_NOT_TOUCH" && 
		     $type!="ID_CLOSE_ABOVE" && 
		     $type!="ID_CLOSE_BELOW" && 
		     $type!="ID_CLOSE_BETWEEN" && 
			 $type!="ID_NOT_CLOSE_BETWEEN" && 
			 $type!="ID_CLOSE_EXACT_VALUE")
		 {
			  $this->template->showErr("Invalid type");
			  return false;
		 }
		 
		// Level 1
		if ($this->kern->isNumber($lev_1)==false)
		{
			$this->template->showErr("Invalid level 1");
			return false;
		}
		
		// Level 2
		if ($type=="ID_BETWEEN")
		{
		  if ($this->kern->isNumber($lev_2)==false)
		  {
			  $this->template->showErr("Invalid level 2");
			  return false;
		  }
		}
		
		// Touch option ?
		if ($type=="ID_TOUCH" || $type=="ID_NOT_TOUCH")
		{
			$query="SELECT * 
			         FROM feeds_branches 
					WHERE feed_symbol='".$feed."' 
					  AND symbol='".$feed_branch."'";
		    $result=$this->kern->execute($query);	
	        $row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			if ($row['val']<$lev_1) 
			   $type=$type."_UP";
			else
			   $type=$type."_DOWN";
		}
		
		// Budget 
		if ($this->kern->isNumber($budget, "decimal", 4)==false)
		{
			$this->template->showErr("Invalid budget");
			return false;
		}
		
		// Currency 
		if ($cur!="MSK")
		{
			$symbol=strtoupper($cur);
			
		    if ($this->kern->symbolValid($symbol)==false)
		    {
			  $this->template->showErr("Invalid currency");
			  return false;
		    }
			
			$query="SELECT * FROM assets WHERE symbol='".$symbol."'";
			$result=$this->kern->execute($query);	
	        
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Invalid currency");
			    return false;
			}
		}
		
		// Profit 
		if ($this->kern->isNumber($profit)==false || $profit<1)
		{
			$this->template->showErr("Invalid profit level");
			return false;
		}
		
		// Expire
		if ($this->kern->isNumber($expire)==false || $expire<0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Accept
		if ($this->kern->isNumber($accept)==false || $accept<0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Expire 
		switch ($expire_per)
		{
			case "ID_MINUTES" : $expire_block=$_REQUEST['sd']['last_block']+$expire; break;
			case "ID_HOURS" : $expire_block=$_REQUEST['sd']['last_block']+$expire*60; break;
			case "ID_DAYS" : $expire_block=$_REQUEST['sd']['last_block']+$expire*1440; break;
			case "ID_MONTHS" : $expire_block=$_REQUEST['sd']['last_block']+$expire*43200; break;
			case "ID_YEARS" : $expire_block=$_REQUEST['sd']['last_block']+$expire*525600; break;
		}
		
		// Accept bets 
		switch ($accept_per)
		{
			case "ID_MINUTES" : $accept_block=$_REQUEST['sd']['last_block']+$accept; break;
			case "ID_HOURS" : $accept_block=$_REQUEST['sd']['last_block']+$accept*60; break;
			case "ID_DAYS" : $accept_block=$_REQUEST['sd']['last_block']+$accept*1440; break;
			case "ID_MONTHS" : $accept_block=$_REQUEST['sd']['last_block']+$accept*43200; break;
			case "ID_YEARS" : $accept_block=$_REQUEST['sd']['last_block']+$accept*525600; break;
		}
	    
		
		// Check
		if ($accept_block>=$expire_block)
		{
			$this->template->showErr("Accepting bets is a date before expiration");
			return false;
		}
		
		 // Feed
		 $feed=strtoupper($feed);
		 $feed_branch=strtoupper($feed_branch);
		
		 // Feed
		 if ($this->kern->branchExist($feed, $feed_branch)==false) 
		 {
			 $this->template->showErr("Invalid feed branch symbol");
		     return false;
		 }
		
		 // Net fee
		 if ($cur!="MSK")
		    $fee=round($mkt_days*0.0001, 4);
		 else
		    $fee=round($mkt_days*0.0001*$budget/400, 4);
			
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Launches a new bet");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_BET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$bet_adr."',
								par_1='".$type."',
								par_2='".$lev_1."',
								par_3='".$lev_2."',
								par_4='".$budget."',
								par_5='".$cur."',
								par_6='".$profit."',
								par_7='".base64_encode($name)."',
								par_8='".base64_encode($desc)."',
								par_9='".$expire_block."',
								par_10='".$accept_block."',
								par_11='".$feed."',
								par_12='".$feed_branch."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function showNewBetModal()
	{
		$this->template->showModalHeader("modal_new_bet", "New Bet", "act", "new_bet");
		?>
        
          <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/new.png" width="140"  alt="" class="img-circle"/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001", "feeds"); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"><strong class="font_14">Bet  Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_bet_adr", "200px"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" colspan="3" align="left" valign="top" class="font_14"><strong>Data Feed  / Branch</strong></td>
                    <td width="68%" align="left" valign="top" class="font_14">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="20%"><input class="form-control" id="txt_bet_feed" name="txt_bet_feed" placeholder="XXXXXX" style="width:100px"/></td>
                    <td width="1%">&nbsp;-&nbsp;</td>
                    <td width="11%"><input class="form-control" id="txt_bet_branch" name="txt_bet_branch" placeholder="XXXXXX" style="width:100px"/></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" height="30" align="left" valign="top" class="font_14"><strong>Type</strong></td>
                    <td width="5%">&nbsp;</td>
                    <td width="30%" align="left" valign="top" class="font_14"><strong>Level 1</strong></td>
                    <td width="5%">&nbsp;</td>
                    <td width="30%" align="left" valign="top" class="font_14"><strong>Level 2</strong></td>
                  </tr>
                  <tr>
                    <td>
                    
                    <select id="dd_bet_type" name="dd_bet_type" class="form-control" onchange="type_changed()">
                      <option value="ID_TOUCH">Touch Level</option>
                      <option value="ID_NOT_TOUCH">Don't Touch Level</option>
                      <option value="ID_CLOSE_BELOW">Close Below Level</option>
                      <option value="ID_CLOSE_ABOVE">Close Above Level</option>
                      <option value="ID_CLOSE_BETWEEN">Between Levels</option>
                      <option value="ID_NOT_CLOSE_BETWEEN">Not Between Levels</option>
                      <option value="ID_CLOSE_EXACT_VALUE">Exact Value</option>
                    </select>
                    
                    </td>
                    <td width="5%">&nbsp;</td>
                    <td><input class="form-control" id="txt_bet_lev_1" name="txt_bet_lev_1" placeholder="0" /></td>
                    <td width="5%">&nbsp;</td>
                    <td><input class="form-control" id="txt_bet_lev_2" name="txt_bet_lev_2" placeholder="0" disabled="disabled"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="28%" height="30" align="left" valign="top" class="font_14"><strong>Budget</strong></td>
                    <td width="4%">&nbsp;</td>
                    <td width="32%" align="left" valign="top" class="font_14"><strong>Currency</strong></td>
                    <td width="3%">&nbsp;</td>
                    <td width="33%" align="left" valign="top" class="font_14"><strong>Profit Ratio (%)</strong></td>
                  </tr>
                  <tr>
                    <td><input name="txt_bet_budget" class="form-control" id="txt_bet_budget" placeholder="0"  /></td>
                    <td width="4%">&nbsp;</td>
                    <td><input name="txt_bet_cur" class="form-control" id="txt_bet_cur" placeholder="XXXXXX"  value="MSK"/></td>
                    <td width="3%">&nbsp;</td>
                    <td><input class="form-control" id="txt_bet_profit" name="txt_bet_profit" placeholder="10" type="number" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Bet Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_bet_name" name="txt_bet_name" placeholder="Bet Name" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left"><textarea rows="3" id="txt_bet_desc" name="txt_bet_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:100%"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" height="30" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td><strong class="font_14">Bet Expire </strong></td>
                        </tr>
                        <tr>
                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="10%"><input class="form-control" id="txt_bet_expire" name="txt_bet_expire" placeholder="1" style="width:90px" type="number" /></td>
                              <td width="3%">&nbsp;-&nbsp;</td>
                              <td width="87%">
                              
                              <select id="dd_bet_expire_per" name="dd_bet_expire_per" class="form-control">
                                <option value="ID_MINUTES">Minutes</option>
                                <option value="ID_HOURS">Hours</option>
                                <option value="ID_DAYS">Days</option>
                                <option value="ID_MONTHS">Months</option>
                                <option value="ID_YEARS">Years</option>
                              </select>
                              
                              </td>
                            </tr>
                          </table></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td><strong class="font_14">Positions can be placed in the next</strong></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="10%"><input class="form-control" id="txt_bet_accept" name="txt_bet_accept" placeholder="1" style="width:90px" type="number" /></td>
                          <td width="3%">&nbsp;-&nbsp;</td>
                          <td width="87%">
                          
                          <select id="dd_bet_accept_per" name="dd_bet_accept_per" class="form-control">
                            <option value="ID_MINUTES">Minutes</option>
                            <option value="ID_HOURS">Hours</option>
                            <option value="ID_DAYS">Days</option>
                            <option value="ID_MONTHS">Months</option>
                            <option value="ID_YEARS">Years</option>
                          </select>
                          
                          </td>
                        </tr>
                      </table></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		function type_changed()
		{
		   if ($('#dd_bet_type').val()=="ID_CLOSE_BETWEEN")
		   {
			  $('#txt_bet_lev_2').prop('disabled', false);
			  event.preventDefault();
		   }
		   else
		   {
			    $('#txt_bet_lev_2').prop('disabled', true);
			    event.preventDefault();
		   }
		}
	
		$('#form_modal_new_bet').submit(
		function() 
		{ 
		   $('#txt_bet_name').val(btoa($('#txt_bet_name').val())); 
		   $('#txt_bet_desc').val(btoa($('#txt_bet_desc').val())); 
		});
		</script>
        
        <?
		$this->template->showModalFooter("Send");
	}
	
	
	function showBuyBetModal($betID)
	{
		$query="SELECT * FROM feeds_bets WHERE betID='".$betID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $cur=$row['cur']; 
		 
		$this->template->showModalHeader("modal_buy_bet", "Invest", "act", "buy_bet", "betID", $betID);
		?>
        
          <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/buy.png" width="150" class="img-circle"/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="410" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"><strong class="font_14">Bet  Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_buy_bet_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="31%" height="30" align="left" valign="top" class="font_14"><strong>Amount (<? print $cur; ?>)</strong></td>
                  </tr>
                  <tr>
                    <td><input name="txt_buy_bet_amount" class="form-control" id="txt_buy_bet_amount" placeholder="0" style="width:100px" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="0" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
       
        
        <?
		$this->template->showModalFooter("Invest");
	}
	
	function showOptions($target, $search="", $status='ID_PENDING')
	{
		if ($status=="ID_PENDING")
		$query="SELECT fb.*, fbr.type 
		          FROM feeds_bets AS fb
		          JOIN feeds_branches AS fbr ON (fbr.feed_symbol=fb.feed 
				                                 AND fbr.symbol=fb.branch)
		         WHERE (title LIKE '%".$search."%'
				    OR fb.description LIKE '%".$search."%') 
				   AND status='ID_PENDING'
				   AND fbr.type='".$target."'
			  ORDER BY accept_block ASC 
			     LIMIT 0,25"; 
	    else
		$query="SELECT fb.*, fbr.type 
		          FROM feeds_bets AS fb
		          JOIN feeds_branches AS fbr ON (fbr.feed_symbol=fb.feed 
				                                 AND fbr.symbol=fb.branch)
		         WHERE (title LIKE '%".$search."%'
				    OR fb.description LIKE '%".$search."%') 
				   AND (status='ID_WIN' OR status='ID_LOST')
				   AND fbr.type='".$target."'
			  ORDER BY accept_block ASC 
			     LIMIT 0,25"; 
				
		 $result=$this->kern->execute($query);	
	 
	    
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Ends</th>
           <th class="font_14" height="35px" align="center">Profit</th>
           <th class="font_14" height="35px" align="center">Bets</th>
           <th class="font_14" height="35px" align="center">Left Budget</th>
           <th class="font_14" height=\"35px\" align=\"center\">Status</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="40%">
                 <a href="bet.php?betID=<? print $row['betID']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%" <? if ($row['accept_block']-$_REQUEST['sd']['last_block']<0) print "style=\"color:#990000\""; ?>>
				 <? 
				      if ($row['accept_block']-$_REQUEST['sd']['last_block']>0)
				         print "~".$this->getTime($row['accept_block']-$_REQUEST['sd']['last_block']); 
					  else
					    print "bets closed"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%"><? print $row['win_multiplier']; ?>%</td>
                 <td class="font_14" width="10%"><? print $row['bets']; ?></td>
                 <td class="font_14" width="30%" style="color:#009900"><strong>
				 <? print round($row['budget']/(1+$row['win_multiplier']/100)-$row['invested'], 4)." ".$row['cur']; ?>
                 </strong></td>
                 
                <td class="font_16" width="10%">
                      <?  
				          switch ($row['status'])
					      {
						      case "ID_PENDING" : print "<span class=\"label label-warning\">Pending</span>"; break;
							  case "ID_WIN" : print "<span class=\"label label-success\">Winner</span>"; break;
							  case "ID_LOST" : print "<span class=\"label label-danger\">Lost</span>"; break;
					      }
				      ?>
                 </td>
                
                 
                 </tr>
                 <tr><td colspan="6"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showIssuedOptions($search="")
	{
		$query="SELECT * 
		          FROM feeds_bets AS bets 
				 WHERE adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."') 
				ORDER BY bets.accept_block ASC 
			     LIMIT 0,25"; 
				
		 $result=$this->kern->execute($query);	
	 
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Ends</th>
           <th class="font_14" height="35px" align="center">Profit</th>
           <th class="font_14" height="35px" align="center">Bets</th>
           <th class="font_14" height="35px" align="center">Profit / Loss</th>
           <th class="font_14" height=\"35px\" align=\"center\">Status</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="40%">
                 <a href="bet.php?betID=<? print $row['betID']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%" <? if ($row['accept_block']-$_REQUEST['sd']['last_block']<0) print "style=\"color:#990000\""; ?>>
				 <? 
				      if ($row['accept_block']-$_REQUEST['sd']['last_block']>0 || $row['status']=="ID_PENDING")
				         print "~".$this->getTime($row['accept_block']-$_REQUEST['sd']['last_block']); 
					  else
					    print "closed"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%"><? print $row['win_multiplier']; ?>%</td>
                 <td class="font_14" width="10%"><? print $row['bets']; ?></td>
                 <td class="font_14" width="30%" style="color:<? if ($row['status']=="ID_WIN") print "#009900"; else print "#990000"; ?>"><strong>
				 <? 
				    if ($row['status']=="ID_WIN")
					   print "+".$row['invested']." ".$row['cur'];
					else
					    print "-".($row['invested']+$row['invested']*$row['win_multiplier']/100)." ".$row['cur'];
				  ?>
                 </strong></td>
                 
                <td class="font_16" width="10%" align="center">
                      <?  
				          switch ($row['status'])
					      {
						      case "ID_PENDING" : print "<span class=\"label label-warning\">Pending</span>"; break;
							  case "ID_WIN" : print "<span class=\"label label-success\">Winner</span>"; break;
							  case "ID_LOST" : print "<span class=\"label label-danger\">Lost</span>"; break;
					      }
				      ?>
                 </td>
                
                 
                 </tr>
                 <tr><td colspan="6"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showMyOptionsSel($status="ID_PENDING")
	{
		// Bet modal
		$this->showNewBetModal();
		
		?>
          
          <table width="90%">
          <tr>
          
          <td>
          <a href="javascript:void(0)" onClick="$('#modal_new_bet').modal()" class="btn btn-danger">
          <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Option
          </a>
          </td>
          
          <td align="right">
          <div class="btn-group" role="group" align="right">
          <a href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_PENDING" type="button" class="btn <? if ($status=="ID_PENDING") print "btn-primary"; else print "btn-default"; ?>">Pending</a>
          <a href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_CLOSED" type="button" class="btn <? if ($status=="ID_PENDING") print "btn-default"; else print "btn-primary"; ?>">Closed</a> 
          </div>
          </td></tr>
          </table>
          <br>
        
        <?
	}
	
	
	
	function getTime($time)
	{
		if ($time<60) return $time." minutes";
		else if ($time>=60 && $time<2880) return round($time/60)." hours";
		else if ($time>=2880) return round($time/1440)." days";
	}
	
	
	
	function showPanel($betID)
	{
		$query="SELECT fb.*
		          FROM feeds_bets AS fb 
				 WHERE betID='".$betID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="15%"><img src="../../template/template/GIF/empty_pic.png" class="img-responsive img-circle"></td>
            <td width="3%">&nbsp;</td>
            <td width="83%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="77%"><span class="font_16"><strong><? print base64_decode($row['title']); ?></strong></span>
                  <p class="font_14">
				  <? 
				      print base64_decode($row['description']); 
				  ?>
                  </p>
                  <?
				      if ($row['accept_block']<$_REQUEST['sd']['last_block']) print "<span class=\"label label-danger\" class=\"font_14\">Closed Bet</span><br>";
				  ?>
                  </td>
                  <td width="23%" align="center">&nbsp;</td>
                </tr>
              </tbody>
            </table>              <p class="font_14">&nbsp;</p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table class="table-responsive" width="95%">
            
            <tr>
            <td width="30%" class="font_12" align="center">Bet ID&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><? print $row['betID']; ?></strong></td>
            <td width="40%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			<strong><? print $this->template->formatAdr($row['adr']); ?></strong></a></td>
             <td width="30%" align="center"><span class="font_12">Accepts Bets</span>&nbsp;&nbsp;&nbsp;&nbsp;<font class="font_12" style="color:<? if ($row['accept_block']-$_REQUEST['sd']['last_block']<0) print "#990000"; else print "#009900"; ?>">
             <strong>
			 <? 
			     if ($row['accept_block']-$_REQUEST['sd']['last_block']<0) 
				    print "closed"; 
			     else 
				    print "~".$this->kern->timeFromBlock($row['accept_block']); 
			?>
            </strong></font></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" class="font_12" align="center">Expires&nbsp;&nbsp;&nbsp;&nbsp;
			<strong>~ <? print $this->kern->timeFromBlock($row['end_block']); ?></strong>
            </td>
             <td width="33%" align="center"><span class="font_12">Total Budget&nbsp;&nbsp;&nbsp;&nbsp;
			 <strong><? print round($row['budget']/(1+$row['win_multiplier']/100), 4); ?></strong></span>&nbsp;&nbsp;<a href="#" class="font_12"><? print $row['cur']; ?></a></td>
            <td width="33%" class="font_12" align="center">Left Budget&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['budget']/(1+$row['win_multiplier']/100)-$row['invested'], 4); ?></strong>&nbsp;&nbsp;<a href="#" class="font_12"><? print $row['cur']; ?></a></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            
            <tr>
            <td width="33%" align="center"><span class="font_12">Type&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>
            <?
			   switch ($row['tip'])
			   {
				   case "ID_TOUCH_UP" : print "Touch Level"; break;
				   case "ID_TOUCH_DOWN" : print "Touch Specified Level"; break;
				   case "ID_NOT_TOUCH_UP" : print "Don't Touch Specified Level"; break;
				   case "ID_NOT_TOUCH_DOWN" : print "Don't Touch Level"; break;
				   case "ID_BELOW" : print "Below Level"; break;
				   case "ID_ABOVE" : print "Above Level"; break;
				   case "ID_BETWEEN" : print "Betwee Levels"; break;
				   case "ID_NOT_BETWEEN" : print "Not Between Levels"; break;
				   case "ID_EXACT" : print "Exact Value"; break;
			   }
			?>
            </strong>
            </span></td>
            <td width="33%" class="font_12" align="center">Level 1&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['val_1'], 8); ?></strong></td>
            <td width="33%" class="font_12" align="center">Level 2&nbsp;&nbsp;&nbsp;&nbsp; 
			<strong><? print round($row['val_2'], 8); ?></strong></td>
            </tr>
            
            <tr><td colspan="3"><hr></td></tr>
            <tr>
            <td width="33%" class="font_12" align="center">Feed  1&nbsp;&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_1']; ?>&symbol=<? print $row['branch']; ?>" class="font_12"><strong><? print $row['feed']." / ".$row['branch']; ?></strong></a></td>
            <td width="33%" class="font_12" align="center">Feed 2&nbsp;&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_2']; ?>&symbol=<? print $row['branch_2']; ?>" class="font_12"><strong><? print $row['feed_2']." / ".$row['branch_2']; ?></strong></a></td></td>
            <td width="33%" class="font_12" align="center">Feed 3&nbsp;&nbsp;&nbsp; 
			<a href="../../assets/feeds/branch.php?feed=<? print $row['feed_3']; ?>&symbol=<? print $row['branch_3']; ?>" class="font_12"><strong><? print $row['feed_3']." / ".$row['branch_3']; ?></strong></a></td></td>
            </tr>
            
           
           
            </table>
            <br>
            
            <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="table-responsive">
            <tr>
            <td width="15%" height="100" align="center" bgcolor="#f9f9f9">
            <img src="GIF/confirm.png"  alt="" class="img-circle img-responsive" style="padding-left:5px; padding-top:5px; padding-right:5px; padding-bottom:5px"/></td>
            <td  bgcolor="#f9f9f9">&nbsp;&nbsp;&nbsp;</td>
            <td width="70%" bgcolor="#f9f9f9" class="font_10">Address <a href="#"><? print $this->template->formatAdr($row['adr']); ?></a> is proposing a bet based on a data feed (<a href="../feeds/branch.php?feed=<? print $row['feed_symbol']; ?>&branch=<? print $row['branch_symbol']; ?>" class="font_12">feed</a>). In case the price provided by this data feed  
			<? 
			   // Touch
			   if ($row['tip']=="ID_TOUCH_UP" || $row['tip']=="ID_TOUCH_DOWN")
			   print "<strong>TOUCH ".round($row['val_1'], 8)."</strong> between this moment and block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If the specified level is not touched in time, you will <strong>loose your investment</strong>."; 
			   
			   // Don't touch
			   if ($row['tip']=="ID_NOT_TOUCH_UP" || $row['tip']=="ID_NOT_TOUCH_DOWN")
			   print "<strong>DON'T TOUCH ".round($row['val_1'], 8)."</strong> between this moment and block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If the specified level is not touched in time, you will <strong>loose your investment</strong>."; 
			   
			   // Close Below
			   if ($row['tip']=="ID_CLOSE_BELOW")
			   print "<strong>IS BELOW ".round($row['val_1'], 8)."</strong> at block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If this condition is not met in time, you will <strong>loose your investment</strong>."; 
			   
			   // Close Above
			   if ($row['tip']=="ID_CLOSE_ABOVE")
			   print "<strong>IS ABOVE ".round($row['val_1'], 8)."</strong> at block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If this condition is not met in time, you will <strong>loose your investment</strong>."; 
			   
			   // Close Between
			   if ($row['tip']=="ID_CLOSE_BETWEEN")
			   print "<strong>IS BETWEEN ".round($row['val_1'], 8)." AND ".round($row['val_2'], 8)."</strong> at block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If this condition is not met in time, you will <strong>loose your investment</strong>."; 
			   
			   // No Close Between
			   if ($row['tip']=="ID_CLOSE_BETWEEN")
			   print "<strong>IS NOT BETWEEN ".round($row['val_1'], 8)." AND ".round($row['val_2'], 8)."</strong> at block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If this condition is not met in time, you will <strong>loose your investment</strong>."; 
			   
			   // Close Between
			   if ($row['tip']=="ID_CLOSE_BETWEEN")
			   print "<strong>IS ".round($row['val_1'], 8)." </strong> at block <strong>".$row['end_block']." ( ~".$this->getTime($row['end_block']-$_REQUEST['sd']['last_block'])." from now )</strong> you will win your invested amount plus <strong>".$row['win_multiplier']."%</strong>. If this condition is not met in time, you will <strong>loose your investment</strong>."; 
			   
			   
			?>
            </td>
            <td width="3%">&nbsp;&nbsp;</td>
            <td align="center" width="20%">
     
             <table width="120px" border="0" cellpadding="0" cellspacing="0" >
                    <tbody>
                      <tr>
                        <td height="30" align="center" class="font_12" style="color:#1B4B39"  bgcolor="#d4f4e4">Profit Ratio</td>
                      </tr>
                      <tr>
                        <td height="65" align="center" class="font_24" style="color:#1B4B39"  bgcolor="#E7FFF3"><strong><? print "+".$row['win_multiplier']."%"; ?></strong></td>
                      </tr>
                    </tbody>
                  </table>
            
            </td>
            </tr>
            </table>
    
            </td></tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
	function showBuyOptionBut($betID)
	{
		$query="SELECT * FROM feeds_bets WHERE betID='".$betID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// Expired option ?
		if ($row['accept_block']>$_REQUEST['sd']['last_block']) 
		{
		?>
         
            <br>
		    <table width="90%">
            <tr><td align="right">
            <a href="javascript:void(0)" onclick="$('#modal_buy_bet').modal()" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus">&nbsp;</span>&nbsp;&nbsp;&nbsp;Invest
            </a>
	        </td></tr>
            </table>
            <br>
         
         <?
		}
	}
	
	function showBuyers($betID)
	{
		$query="SELECT fbs.*, fb.cur, fb.status 
		          FROM feeds_bets_pos AS fbs 
				  JOIN feeds_bets AS fb ON fb.betID=fbs.betID
				 WHERE fbs.betID='".$betID."' 
			  ORDER BY ID DESC 
			     LIMIT 0,25"; 
	    $result=$this->kern->execute($query);	
	    ?>
        
		<br><table width='90%' class='table-responsive'>
        <thead bgcolor="#f9f9f9">
        <th class="font_14" height="30px">&nbsp;&nbsp;Buyer</th>
        <th class="font_14">Invested</th>
        <th class="font_14">Status</th>
        </thead>
		
		<?
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		?>
           
            
            <tr>
            <td width="70%"><a href="#" class="font_14"><? print $this->template->formatAdr($row['adr']); ?></a></td>
            <td class="font_14" width="20%"><? print $row['amount']." ".$row['cur']; ?></td>
            <td class="font_16" width="10%">
			<?
			   switch ($row['status'])
			   {
				   case "ID_PENDING" : print "<span class=\"label label-warning\" class=\"font_16\">Pending</span><br>"; break;
				   case "ID_WIN" : print "<span class=\"label label-danger\" class=\"font_16\">Looser</span><br>"; break;
				   case "ID_LOST" : print "<span class=\"label label-success\" class=\"font_16\">Winner</span><br>"; break;
			   }
			?>
            </td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
        
        <?	
		}
	    
		print "</table><br><br>";
	}
	
	function showReport($ID)
	{
		// Load bet data
		$query="SELECT * 
		          FROM feeds_bets 
				 WHERE betID='".$ID."'";
		$result=$this->kern->execute($query);	
		$bet_row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $end_block=$row['end_block'];
		
		// Min, max, count
		$query="SELECT MIN(val) AS minimum, 
		               MAX(val) AS maximum
			      FROM feeds_data
				 WHERE feed='".$bet_row['feed']."' 
				   AND feed_branch='".$bet_row['branch']."' 
				   AND block>=".$bet_row['block']." 
				   AND block<=".$bet_row['end_block']; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		?>
            
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table>
            <tr>
            <td width="25%" valign="top" align="center"><span class="font_10">Last Price</span><br><span class="font_20">
			<? print round($bet_row['last_price'], 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Min Price</span><br><span class="font_20">
			<? print round($row['minimum'], 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Max Price</span><br><span class="font_20">
			<? print round($row['maximum'], 8); ?></span></td>
            <td style="border-left: solid 1px #aaaaaa;">&nbsp;</td>
            <td width="25%" valign="top" align="center"><span class="font_10">Accept Bets</span><br><span class="font_20">
			<?
			    if ($_REQUEST['sd']['last_block']<$bet_row['accept_block']) 
			       print "~".$this->kern->timeFromBlock($bet_row['accept_block']); 
				else 
				   print "closed";
			?>
            </span></td>
            </tr>
            </table>
            </div>
            </div>
        
        <?
	} 
	
	function showChart($betID)
	{
		// Load branch
		$query="SELECT * 
		          FROM feeds_bets 
				 WHERE betID='".$betID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$feed=$row['feed'];
		$branch=$row['branch'];
		$start_block=$row['block'];
		$end_block=$row['end_block'];
		
		// Load branch
		 $query="SELECT COUNT(*) AS no
		          FROM feeds_data 
				  WHERE feed='".$feed."' 
				    AND feed_branch='".$branch."' 
				    AND block>=".$start_block." 
				    AND block<=".$end_block; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		$total=$row['no'];
		
	   $query="SELECT AVG(val) AS val
		          FROM feeds_data 
				 WHERE feed='".$feed."' 
				   AND feed_branch='".$branch."'
				   AND block>=".$start_block." 
				   AND block<=".$end_block." 
			  GROUP BY round(block/".($total/25).")"; 
		$result=$this->kern->execute($query);	
	   
		?>
           
           <script type="text/javascript">
	       google.load('visualization', '1', {packages: ['corechart', 'line']});
           google.setOnLoadCallback(drawChart);

      function drawChart() 
	  {
         
		 var data = new google.visualization.DataTable();
         data.addColumn('string', 'Date');
		 data.addColumn('number', 'Price');
		 
         data.addRows([
		 <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  print "['', ".$row['val']."],";
		 ?>
		 ]);

        var options = {
          title: '<? print $symbol; ?> Chart',
          curveType: 'function',
		  legend:'none',
	      tooltip: { isHtml: true },
	      chartArea: {'width': '80%', 'height': '85%'},
	      backgroundColor : '#ffffff'
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
    
           <br>
           <div id="curve_chart" style="width: 100%; height: 400px"></div>
           <br><br>
        
        <?
	}
  }
?>