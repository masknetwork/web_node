<?
class CIssued
{
	function CIssued($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function withdraw($mktID, 
	                 $net_fee_adr, 
					 $rec, 
					 $amount)
	{
		// Net fee address
		if ($this->kern->isAdr($net_fee_adr)==false ||
		    $this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->adrExist($net_fee_adr)==false ||
			$this->kern->canSpend($net_fee_adr)== false ||
			$this->kern->getBalance($net_fee_adr)<0.0001)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Valid recipient ?
		if ($this->kern->isAdr($rec)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
	     // Market address
		 $query="SELECT * 
		          FROM feeds_spec_mkts 
		         WHERE mktID='".$mktID."'"; 
		$result=$this->kern->execute($query);
		
		// No results
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load data
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$adr=$row['adr'];
		
		// My address ?
		if ($this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Balance
		$balance=$this->kern->getBalance($adr, $row['cur']); 
		
		// Calculate max withdraw
		$query="SELECT SUM(margin+pl) AS total
		          FROM feeds_spec_mkts_pos 
				 WHERE mktID='".$mktID."' 
				   AND (status='ID_MARKET' || status='ID_PENDING')";
				   
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Max allowed
		$used_margin=$row['total'];
		
		// Used margin in percent
		$used_margin=$used_margin*100/($balance-$amount);
		
		// Remaining margin
		if ($used_margin>25)
		{
			$this->template->showErr("You can't withdraw that much");
			return false;
		}
		
		// Amount
		if ($amount<0.00000001)
		{
			$this->template->showErr("Invalid amount");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Modify a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SPEC_MKT_WTH', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."', 
								par_1='".$mktID."',
								par_2='".$amount."',
								par_3='".$rec."',
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
	
	function closeMkt($mktID, 
	                 $net_fee_adr,
	                 $pass)
	{
		// Net fee address
		if ($this->kern->isAdr($net_fee_adr)==false ||
		    $this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->adrExist($net_fee_adr)==false ||
			$this->kern->canSpend($net_fee_adr)== false ||
			$this->kern->getBalance($net_fee_adr)<0.0001)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Account password
		$query="SELECT * 
		          FROM web_users 
				 WHERE pass='".hash("sha256", $pass)."' 
				   AND ID='".$_REQUEST['ud']['ID']."'"; 
		$result=$this->kern->execute($query);
		
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid password");
			return false;
		}
		
		// Market ID
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."' 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')";
		$result=$this->kern->execute($query);
		
		// No results
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid market ID");
			return false;
		}
		
		// Load mkt data
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Market address
		$adr=$row['adr'];
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Modify a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SPEC_MKT_CLOSE', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."', 
								par_1='".$mktID."',
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
	
	
	function showSelector($target="ID_ASSETS")
	{
		?>
        
        <div class="btn-group" style="width:90%" align="left">
        
        <a class="btn btn<? if ($target=="ID_ASSETS") print "-inverse active"; else print "-default"; ?>" href="index.php" style="width:18%">Assets</a>
        
        <a class="btn btn<? if ($target=="ID_OPTIONS") print "-inverse active"; else print "-default"; ?>" href="options.php" style="width:14%">Binary Options</a>
        
        <a class="btn btn<? if ($target=="ID_SPEC_MKTS") print "-inverse active"; else print "-default"; ?>" href="mkts.php" style="width:16%">Margin Markets</a>
        
        </div>
        
<br><br><br>
        
        <?
	}
	
	function showCloseMktModal()
	{
		$this->template->showModalHeader("close_mkt_modal", "Close Market", "act", "close_mkt", "close_mkt_ID", 0);
		?>
            
          <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/close_mkt.png" width="180" height="176" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >&nbsp;</td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100%" height="30" align="center" valign="top" class="font_14">Warning. You are going to close the market. All positions will be also closed using the last price. This actions can't be undone. Are you sure you want to close this market ?</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_close_mkt_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Account Password</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><span class="simple_gri_14">
                  <input name="txt_close_mkt_pass" class="form-control" id="txt_close_mkt_pass" placeholder="" style="width:250px" type="password"/>
                </span></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Close");
	}
	
	function showWthModal()
	{
		$this->template->showModalHeader("wth_modal", "Withdraw", "act", "wth", "wth_mkt_ID", 0);
		
		?>
            
           <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/wth.png" width="180" height="180" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >&nbsp;</td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100%" height="30" align="center" valign="top" class="font_14"><table width="340" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Max Amount</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900">
                    <strong>
					<span id="td_wth_max" class="font_30">0</span>
                  
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
				<? $this->template->showMyAdrDD("dd_wth_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Recipient Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><span class="simple_gri_14">
                  <input name="txt_wth_rec" class="form-control" id="txt_wth_rec" placeholder="0" style="width:350px"/>
                </span></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Amount</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><span class="simple_gri_14">
                  <input name="txt_wth_amount" class="form-control" id="txt_wth_amount" placeholder="0" style="width:100px"/>
                </span></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
        
        <?
		$this->template->showModalFooter("Withdraw");
	}
	
	function showMyMarkets()
	{
		// Modal
		$this->showWthModal();
		
		// Close Modal
		$this->showCloseMktModal();
		
		// Query
	    $query="SELECT fsm.*, adr.balance AS mkt_adr_balance
		          FROM feeds_spec_mkts AS fsm 
				  JOIN adr ON adr.adr=fsm.adr
				 WHERE fsm.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."')"; 
		$result=$this->kern->execute($query);	
	
	 
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Colaterall</th>
           <th class="font_14" height="35px" align="center">Leverage</th>
           <th class="font_14" height="35px" align="center">Currency</th>
           <th class="font_14" height=\"35px\" align=\"center\">Admin</th>
           </thead>
           
           <?
		      while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['title'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print "<span class='font_14'>".round($row['mkt_adr_balance'],2)."</span> <span class='font_10'>".$row['cur']."</span>"; 
			     ?>
                 </td>
                 <td class="font_14" width="10%"><? print "x ".$row['max_leverage']; ?></td>
                 <td class="font_14" width="10%"><? print $row['cur']; ?></td>
                
                 
                <td class="font_16" width="10%">
                
                 <div style="height:10px">&nbsp;</div>
                       <div class="btn-group">
                       <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle btn-sm" type="button">
                       <span class="glyphicon glyphicon-cog"></span>
                       <span class="caret"></span></button>
                       <ul role="menu" class="dropdown-menu">
                       
                       <?
					       // Address
		                   $adr=$row['adr'];
		
		                  // Calculate max withdraw
		                   $query="SELECT SUM(margin+pl) AS total
		                             FROM feeds_spec_mkts_pos 
			                   	    WHERE mktID='".$mktID."' 
				                      AND (status='ID_MARKET' || status='ID_PENDING')";
		                   $result2=$this->kern->execute($query);
		                   $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
		
		                   // Max allowed
						    $max=($row['mkt_adr_balance']-$row2['total'])/2; 
					   ?>
                       
                       <li><a href="#" onClick="$('#wth_modal').modal(); 
                                               $('#wth_mkt_ID').val('<? print $row['mktID']; ?>'); 
                                               $('#td_wth_max').text('<? print $max; ?>');">
                       <span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Withdraw
                       </a></li>
                       
                       <li>
                       <a href="mkt_report.php?mktID=<? print $row['mktID']; ?>&dd_interval=24"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;Report</a>
                       </li>
                       
                       <li>
                       <a href="rewards.php?type=margin_mkts&wth_mkt_ID=<? print $row['ID']; ?>"><span class="glyphicon glyphicon-gift"></span>&nbsp;&nbsp;Rewards</a>
                       </li>
                                 
                       <li role="separator" class="divider"></li>                              
                       
                       <li><a href="#" onClick="$('#close_mkt_modal').modal(); 
                                               $('#close_mkt_ID').val('<? print $row['mktID']; ?>');"> 
                       <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Close Market
                       </a></li>
                       </ul>
                       </div>
                </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	function showMktReport($mktID, $interval)
	{
		// Load market data
		$query="SELECT * 
		          FROM feeds_spec_mkts 
				 WHERE mktID='".$mktID."'";
		$result=$this->kern->execute($query);	
		$mkt_row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Market positions
		$query="SELECT COUNT(*) AS total 
		          FROM feeds_spec_mkts_pos 
				 WHERE status='ID_MARKET' 
				   AND mktID='".$mktID."'
				   AND block_start>".($_REQUEST['sd']['last_block']-$interval*60);
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$market_pos=$row['total'];
		
		// Closed positions
		$query="SELECT COUNT(*) AS total 
		          FROM feeds_spec_mkts_pos 
				 WHERE status='ID_CLOSED' 
				   AND mktID='".$mktID."'
				   AND block_start>".($_REQUEST['sd']['last_block']-$interval*60);
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$closed_pos=$row['total'];
		
		// Pending positions
		$query="SELECT COUNT(*) AS total 
		          FROM feeds_spec_mkts_pos 
				 WHERE status='ID_ORDER' 
				   AND mktID='".$mktID."'
				   AND block_start>".($_REQUEST['sd']['last_block']-$interval*60);
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$pending_pos=$row['total'];
		
		// Total positions
		$total_pos=$market_pos+$closed_pos+$pending_pos;
		
		// Looses
		$query="SELECT SUM(pl) AS total 
		          FROM feeds_spec_mkts_pos 
				 WHERE mktID='".$mktID."' 
				   AND pl<0
				   AND status='ID_CLOSED'
				   AND block_start>".($_REQUEST['sd']['last_block']-$interval*60); 
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$losses=round(abs($row['total']), 2);
		
		// Profit
		$query="SELECT SUM(pl) AS total 
		          FROM feeds_spec_mkts_pos 
				 WHERE mktID='".$mktID."' 
				   AND pl>=0
				   AND status='ID_CLOSED'
				   AND block_start>".($_REQUEST['sd']['last_block']-$interval*60); 
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$profit=round(abs($row['total']), 2);
		
		
		// Market net
		$net=$losses-$profit;
		?>
        
        <br>
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
        <td width="16%">
        
        <form method="post" name="form_interval" id="form_interval" action="mkt_report.php?mktID=<? print $_REQUEST['mktID']; ?>">
        <select class="form-control" id="dd_interval" name="dd_interval" onChange="$('#form_interval').submit()" style="width:200px">
        <option value="24" <? if ($interval==24) print "selected"; ?>>24 Hours</option>
        <option value="48" <? if ($interval==48) print "selected"; ?>>48 Hours</option>
        <option value="168" <? if ($interval==168) print "selected"; ?>>7 Days</option>
        <option value="720" <? if ($interval==720) print "selected"; ?>>30 Days</option>
        <option value="2160" <? if ($interval==2160) print "selected"; ?>>3 Months</option>
        </select>
        </form>
        
        </td>
        </tr>
        <tr>
        <td colspan="6"><hr></td>
        </tr>
        </tbody>
        </table>
 
        <br>
<table width="90%">
        <tr>

        <td width="30%">
        <div class="panel panel-success" style="width:100%">
        <div class="panel-heading">
        <h3 class="panel-title">Traders Loss</h3>
        </div>
        <div class="panel-body" align="center">
        <span class="font_20" style="color:#009900"><? print $losses; ?></span>
        <span class="font_12" style="color:#009900"><? print $mkt_row['cur']; ?></span>
        </div>
        </div>
        </td>

        <td width="5%" align="center"><img src="./GIF/minus.png" width="20px"></td>
        
        <td width="30%">
        <div class="panel panel-danger" style="width:100%">
        <div class="panel-heading">
        <h3 class="panel-title">Traders Profit</h3>
        </div>
        <div class="panel-body" align="center">
        <span class="font_20" style="color:#990000"><? print $profit; ?></span>
        <span class="font_12" style="color:#990000"><? print $mkt_row['cur']; ?></span>
        </div>
        </div>
        </td>
        
        <td width="5%" align="center"><img src="./GIF/equal.png" width="20px"></td>
        
        <td width="30%">
        <div class="panel panel-<? if ($net<0) print "danger"; if ($net>0) print "success"; if ($net==0) print "default"; ?>" style="width:100%">
        <div class="panel-heading">
        <h3 class="panel-title">Market P / L</h3>
        </div>
        <div class="panel-body" align="center">
        <span class="font_20" style="color:<? if ($net<0) print "#990000"; if ($net>0) print "#009900"; if ($net==0) print "#999999"; ?>"><? print $net; ?></span>
        <span class="font_12" style="color:<? if ($net<0) print "#990000"; if ($net>0) print "#009900"; if ($net==0) print "#999999"; ?>"><? print $mkt_row['cur']; ?></span>
        </div>
        </div>
        </td>
        
        </tr>
        </table>
        <br>
        
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
          <td align="left" class="font_14">Total Positions</td>
          <td align="right" class="font_14"><strong><? print $total_pos; ?></strong></td>
        </tr>
        <tr>
        <td colspan="2" align="left"><hr></td>
        </tr>
        <tr>
        <td width="20%" align="left" class="font_14">Market Positions</td>
        <td width="80%" align="right" class="font_14"><strong><? print $market_pos; ?></strong></td>
        </tr>
        <tr>
        <td colspan="2" align="left"><hr></td>
        </tr>
        <tr>
        <td align="left" class="font_14">Pending Orders</td>
        <td align="right" class="font_14"><strong><? print $pending_pos; ?></strong></td>
        </tr>
        <tr>
        <td colspan="2" align="left"><hr></td>
        </tr>
        <tr>
        <td align="left" class="font_14">Closed Positions</td>
        <td align="right" class="font_14"><strong><? print $closed_pos; ?></strong></td>
     </tr>
     <tr>
       <td colspan="2" align="left" class="font_14"><hr></td>
       </tr>
   </tbody>
 </table>
<br><br><br>
        
<?
	}
	
	
	function showIssuedAssets()
	{
		$query="SELECT * 
		          FROM assets 
				 WHERE adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')
			  ORDER BY ID ASC
			     LIMIT 0,20"; 
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="95%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="8%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100"; ?>"  class="img-circle img-responsive"/></td>
                            <td width="1%">&nbsp;</td>
                            <td width="95%">
                            <span class="font_16"><a href="../../assets/user/asset.php?symbol=<? print $row['symbol']; ?>">
							<? print $this->kern->noescape(base64_decode($row['title']))." (".$row['symbol'].")"; ?></a>
                            <p class="font_12"><? print $this->kern->noescape(substr(base64_decode($row['description']), 0, 250))."..."; ?></p></td>
                            </tr>
                            <tr>
                            <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                            </tr>
                      
                      <?
	                      }
					  ?>
                        
                  </table>
                  
                 
        
        <?
	}
}
?>