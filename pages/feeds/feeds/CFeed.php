<?
class CFeed
{
	function CFeed($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function newBranch($net_fee_adr, 
	                   $branch_adr, 
					   $name, 
					   $desc, 
					   $feed_symbol, 
					   $symbol, 
					   $follow_fee, 
					   $days)
	{
		// Decode
		$branch_name=base64_decode($branch_name);
		$branch_name=base64_decode($branch_desc);
		
		// Net Fee Address 
		 if ($this->template->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round($mkt_days*0.0001, 4);
		 
		 // Funds
		 if ($this->template->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	   
	     // Feed address
		 if ($this->template->adrValid($branch_adr)==false)
		 {
			$this->template->showErr("Invalid asset address");
			return false;
		 }
		 
		 // Name
		 if (strlen($name)<5 || strlen($name)>50)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 if (strlen($desc)>250)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Market days
		 if ($days<100)
		 {
			  $this->template->showErr("Minimum market days is 100");
			  return false;
		 }
		 
		 
		 // Symbol
		 $symbol=strtoupper($symbol);
		 if ($this->template->symbolValid($symbol)==false)
		 {
			 $this->template->showErr("Invalid symbol");
			 return false;
		 }
		 
		 // Symbol already exist ?
		 $query="SELECT * 
		           FROM feeds 
				  WHERE symbol='".$feed_symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Feed symbol doesn't exist");
			 return false;
		 }
		 
		 // Feed symbol already exist ?
		 $query="SELECT * 
		           FROM feeds_components 
				  WHERE feed_symbol='".$feed_symbol."'
				    AND symbol='".$symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)>0)
		 {
			 $this->template->showErr("Feed symbol already exist");
			 return false;
		 }
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new data feed branch");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_FEED_BRANCH', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$branch_adr."',
								par_1='".base64_encode($name)."',
								par_2='".base64_encode($desc)."',
								par_3='".$feed_symbol."',
								par_4='".$symbol."',
								par_5='".$follow_fee."',
								days='".$days."',
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
	
	 function showFeedPanel($symbol)
	{
		$query="SELECT *, 
		               (SELECT COUNT(*) FROM feeds_components WHERE symbol=feed_symbol) AS branches 
		          FROM feeds 
				 WHERE symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
	  
		?>
           
           <br><br>
<table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" align="left" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;&nbsp;<? print base64_decode($row['title']); ?></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">
                  <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="36%" align="left" valign="top"><img src="../../template/template/GIF/empty_pic_prod.png" width="150" height="150" class="img-circle" /></td>
                        <td width="64%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td class="simple_maro_12"><? print base64_decode($row['description']); ?></td>
                            </tr>
                            <tr>
                              <td background="../../template/template/GIF/lc.png">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td align="right" class="simple_maro_12">Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
                                  <? 
								   print $row['symbol']; 
								  ?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td width="37%" align="right" class="simple_maro_12">Issuer&nbsp;&nbsp;</td>
                                <td width="63%" height="30"><a href="#" class="red_12"><strong>
								<? 
								   print $this->template->formatAdr($row['adr']); 
								?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Branches&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print $row['branches']; ?></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Website&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong></strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Expire&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong><? print "3 months"; ?></strong></a></td>
                              </tr>
                              </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	
	function showBranches($symbol)
	{
		// Load feed data
		$query="SELECT * 
		          FROM feeds 
				 WHERE symbol='".$symbol."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $adr=$row['adr'];
		
		$query="SELECT * 
		          FROM feeds_components 
				 WHERE feed_symbol='".$symbol."' 
			  ORDER BY ID DESC";
		$result=$this->kern->execute($query);
		
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="52%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="18%" align="center"><span class="inset_maro_14">Fee</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="25%" align="center"><span class="inset_maro_14">Details</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                           <tr>
                           <td width="55%" align="left" class="simple_maro_12"><strong><? print base64_decode($row['title']); ?></strong> (ESDSSS)<br>
                           <span class="simple_maro_10"><? print base64_decode($row['description']); ?></span></td>
                           <td width="19%" align="center" class="simple_green_12"><strong><? print $row['fee']." MSK"; ?></strong><br><span class="simple_maro_10">per day</span></td></td>
                           <td width="25%" align="right" class="simple_maro_12">
                           
						   <?
						      if ($this->kern->isMine($adr)==false)
							  {
						   ?>
                           
                              <a href="branch.php?feed_symbol=<? print $row['feed_symbol']; ?>&symbol=<? print $row['symbol']; ?>" 
                                 class="btn btn-warning btn-sm">
                              <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Details
                              </a>
                           
                           <?
							  }
							  else
							  {
						   ?>
                           
                                  <div class="dropdown" align="right">
                                  <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp; Settings&nbsp; &nbsp; <span class="caret"></span></button>
                                   <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                   <li role="presentation"><a role="menuitem" tabindex="-1" href="branch.php?feed_symbol=<? print $symbol; ?>&symbol=<? print $row['symbol']; ?>">Details</a></li>
                                   <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_inject').modal(); 
                                                                                          $('#feed_symbol').val('<? print $row['feed_symbol']; ?>');
                                                                                          $('#branch_symbol').val('<? print $row['symbol']; ?>');
                                                                                          ">Inject Data</a></li>
               <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_renew').modal()">Renew</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#confirm_modal').modal()">Remove Branch</a></li>
              </ul>
            </div>
                           
						   <?
							  }
						   ?>
                           
                           </td>
                           </tr>
                           <tr>
                           <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                           </tr>
                        
                        <?
						 }
						?>
                        
                    </table>
                  
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
}
?>