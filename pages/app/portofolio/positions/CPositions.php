<?
  class CPositions
  {
	  function CPositions($db, $template)
	  {
		$this->kern=$db;
		$this->template=$template;
	  }
	  
	  function closeTrade($net_fee_adr, $tradeID, $percent)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Balance
		 if ($this->kern->getBalance($net_fee_adr)<0.0001)
		 {
			 $this->template->showErr("Insufficient funds");
			 return false;
		 }
		 
		 // Trade ID exist ?
		 $query="SELECT * 
		           FROM feeds_spec_mkts_pos 
				  WHERE posID='".$tradeID."' 
				    AND status<>'ID_CLOSED'";
		 $result=$this->kern->execute($query);	
	     
		 if (mysqli_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Load data
		 $pos_row = mysqli_fetch_array($result, MYSQL_ASSOC);
	     
		 // My position
		 if ($this->kern->isMine($pos_row['adr'])==false)
		 {
			 $this->template->showErr("Invalid position ID");
			 return false;
		 }
		 
		 // Percent
		 if ($percent<1 || $percent>100)
		 {
			 $this->template->showErr("Invalid percent");
			 return false; 
		 }
		 
		  try
	      {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Closes a speculative position");
			  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_CLOSE_SPEC_POS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$pos_row['adr']."',
								par_1='".$tradeID."',
								par_2='".$percent."',
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
	
	  function showSelector($type="ID_MARKET")
	  {
		  ?>
          
             <div align="right" style="width:90%">
             <div class="btn-group" align="right">
          
             <a class="btn btn<? if ($type=="ID_MARKET") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_MARKET">
             Market
             </a>
          
             <a class="btn btn<? if ($type=="ID_CLOSED") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_CLOSED">
             Closed
             </a>
          
             <a class="btn btn<? if ($type=="ID_ORDER") print "-inverse active"; else print "-default"; ?>" href="<? print $_SERVER['PHP_SELF']; ?>?status=ID_ORDER">
             Pending
             </a>
        
        </div>
        </div>
        
          
          <?
	  }
	  
	  function showChangeModal()
	  {
		$this->template->showModalHeader("change_modal", "Change Trade", "act", "change_trade", "change_posID", 0);
		?>
          
          <input id="h_change_posID" name="h_change_posID" value="" type="hidden">
          <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/change.png" width="180" height="181" alt=""/></td>
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
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Last Price</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900">
                    <strong>
					<span id="td_change_last_price" class="font_30">0.0000</span>
                  
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
				<? $this->template->showMyAdrDD("dd_change_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="left"><strong>Stop Loss</strong></td>
                      <td align="left"><strong>Take Profit</strong></td>
                    </tr>
                    <tr>
                      <td><span class="simple_gri_14">
                        <input name="txt_change_sl" class="form-control" id="txt_change_sl" placeholder="0" style="width:150px" onchange="javascript:recalculate()"/>
                      </span></td>
                      <td><span class="simple_gri_14">
                      <input name="txt_change_tp" class="form-control" id="txt_change_tp" placeholder="100" style="width:150px"/>
                      </span></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter();
	}
	
	  function showCloseModal()
	  {
		$this->template->showModalHeader("close_modal", "Close Trade", "act", "close_trade", "close_posID", 0);
		?>
            
            <input name="h_close_margin" id="h_close_margin" value="0" type="hidden">
            <input name="h_close_cur" id="h_close_cur" value="0" type="hidden">
            
            <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="31%" align="center" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/close.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" >
                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14">You will receive</td>
                  </tr>
                  <tr>
                    <td height="50" align="center" bgcolor="#fafafa" style="color:#b83c30">
                    <strong>
                    <span class="font_28" id="s_close_receive_1">0</span>
                    <span class="font_14" id="s_close_receive_2">.00</span>
                    </strong>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="69%" align="right" valign="top">
            
            
            <table width="95%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100%" height="30" align="center" valign="top" class="font_14"><table width="340" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" align="center" bgcolor="#f0f0f0" class="simple_gri_14" id="td_price_header">Last Price</td>
                  </tr>
                  <tr>
                    <td height="60" align="center" bgcolor="#fafafa"  style="#009900">
                    <strong>
					<span id="td_close_last_price" class="font_30">0.0000</span>
                  
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
				<? $this->template->showMyAdrDD("dd_close_net_fee", "350"); ?>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Percent</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">
                <select id="dd_close_percent" name="dd_close_percent" class="form-control" style="width:150px" onchange="javascript:percent_change()">
                  <option selected value="5">5%</option>
                  <option value="10">10%</option>
                  <option value="25">25%</option>
                  <option value="50">50%</option>
                  <option value="75">75%</option>
                  <option value="100">100%</option>
                </select></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table>
            
            </td>
          </tr>
        </table>
        
<script>
	 	  function percent_change()
		  {
			  var receive=(parseFloat($('#dd_close_percent').val())*parseFloat($('#h_close_margin').val()))/100; 
			  receive=Math.round(receive*10000)/10000;
			  $('#s_close_receive_1').text(receive.toString().split(".")[0]);
			  $('#s_close_receive_2').text("."+receive.toString().split(".")[1]+" "+$('#h_close_cur').val());
		  }
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
      // Poitions
	  function showMyPositions($status="ID_MARKET")
	  {
		   // Close Modal
		   $this->showCloseModal();
		
		   // Change modal
		   $this->showChangeModal();
		
			
		    $query="SELECT fsmp.*, fsm.cur, fsb.rl_symbol, fsm.last_price
		                 FROM feeds_spec_mkts_pos AS fsmp 
				         JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
						 JOIN feeds_branches AS fsb ON (fsb.feed_symbol=fsm.feed AND fsb.symbol=fsm.branch)
				        WHERE fsmp.status='".$status."' 
				          AND fsmp.adr IN (SELECT adr 
				                             FROM my_adr 
							                WHERE userID='".$_REQUEST['ud']['ID']."')
					 ORDER BY fsmp.ID DESC 
					    LIMIT 0,25";
		
		
		$result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
		   print "<br><div class='font_14' style='color:#990000'>No positions found</div>";
		   return false;
		}
		
		?>
           
           <br>
           <div align="left"><span class="font_18">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Open Positions</span></div>
           <br>
           <table class="table-responsive" width="90%">
           <tr bgcolor="#f9f9f9">
           <td></td>
           <td width="2%">&nbsp;</th>
           <td height="35px" align="left" class="font_14">&nbsp;&nbsp;Position</td>
           <td class="font_14" height="35px" align="center">Type</td>
           <td class="font_14" height="35px" align="center">Invested</td>
           <td class="font_14" height="35px" align="center">P/L</td>
           <td class="font_14" height="35px" align="center"><? if ($status=="ID_ORDER") print "Open"; else print "P/L (%)"; ?></td>
           <td class="font_14" height="35px" align="center"></td>
           <td width="0%"></tr>
           
           <?
		      while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
			  {
				  $data=$data.",'".$row['posID']."'";
		   ?>
           
                 <tr>
                 <td width="8%"><img src="../../template/template/GIF/empty_pic.png" width="100" class="img img-responsive img-circle"></td>
                 <td>&nbsp;</td>
                 <td width="27%">
                 <a href="#" class="font_14">
				 <? 
				      print $row['rl_symbol'];
				?>
                 </a>
                
                 <p class="font_10"><? print "Open : ".round($row['open'], 4).",  SL : ".round($row['sl'], 4).", TP : ".round($row['tp'], 4); ?></p>
                 </td>
                 
                 <td width="12%" align="center" class="font_14" style="color:<? if ($row['tip']=="ID_BUY") print "#009900"; else print "#990000"; ?>">
				 <? if ($row['tip']=="ID_BUY") print "Buy"; else print "Sell"; ?>
                 </td>
                 
                 <td width="13%" align="center" class="font_14">
				 <?
				     if ($row['status']=="ID_CLOSED")
					    $margin=$row['closed_margin'];
					 else 
					    $margin=$row['margin'];
						 
				     print round($margin, 4)." ".$row['cur']; 
				 ?>
                 </td>
                 
                 <td class="font_14" width="14%" id="<? 
				     if ($row['status']=="ID_MARKET") 
					    print "td_pos_".$row['posID']; 
					 else 
					    print "td_pos_closed_".$row['posID']; 
				 ?>" style="color:
				 <?
				     if ($row['status']=="ID_CLOSED")
					    $pl=$row['closed_pl'];
					 else 
					    $pl=$row['pl'];
											 
						
				     if ($pl>0) 
						print "#009900"; 
					 else 
						print "#990000";
				 ?>" align="center">
                 <strong>
				 <?
				    if ($pl>0) 
				       print "+".round($pl, 8)." ".$row['cur'];
				  	else
					   print round($pl, 8)." ".$row['cur']; 
					 
				 ?>
                 </strong>
                 </td>
                 
                  <td class="font_14" width="14%" id="
				  <?
				    if ($row['status']=="ID_MARKET") 
					       print "td_pos_proc_".$row['posID']; 
					    else 
					       print "td_pos_proc_closed_".$row['posID']; 
					 
					
				 ?>" style="color:
				 <? 
				     if ($status!="ID_ORDER")
					 {
				        if ($pl>0) 
					      print "#009900"; 
					    else 
					      print "#990000";
					 }
				 ?>" 
                 align="center">
                 <strong>
				 <?
				    if ($status!="ID_ORDER")
					{
				       if ($pl>0) 
				          print "+".round($pl*100/$margin, 2)." %";
					   else
					     print round($pl*100/$margin, 2)." %";
					}
					else print round($row['open'], 8)." ".$row['cur'];
				 ?>
                 </strong>
                 </td>
                
                 <td class="font_16" width="10%">
                 
                 <?
				    if ($status=="ID_MARKET" || $status=="ID_ORDER")
					{
				 ?>
                 
                 <div style="height:10px">&nbsp;</div>
                 <div class="btn-group">
                 <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle btn-sm" type="button">
                 <span class="glyphicon glyphicon-cog"></span>
                 <span class="caret"></span></button>
                 <ul role="menu" class="dropdown-menu">
                 <li><a href="javascript:void(0)" onClick="$('#h_close_margin').val('<? print $row['margin']+$row['pl']; ?>'); 
                                                                $('#h_close_cur').val('<? print $row['cur']; ?>'); 
                                                                $('#td_close_last_price').text('<? print $row['last_price']; ?>');
                                                                $('#close_posID').val('<? print $row['posID']; ?>');
                                                                $('#close_modal').modal(); percent_change();">Close Position</a></li>
                                                                 
                 <?
				     if ($status=="ID_MARKET") 
					    print "<li><a href=\"../../assets/margin_mkts/story.php?posID=".$row['posID']."\">Trade Story</a></li>"; 
				 ?>
                 
                 </ul>
                 </div>
                 
                 <?
					}
					
					else if ($status=="ID_CLOSED")
					{
						?>
                        
                        <a class="btn btn-success" href="../../assets/margin_mkts/story.php?posID=<? print $row['posID']; ?>">
                        Trade Story
                        </a>
                        
                        <?
					}
					
					
				 ?>
                 
                 </td>
                        
                      
                 
                 </tr>
                 <tr><td colspan="8"><hr></td></tr>
           
          <?
			  }
		  ?>
           
           </table>
           
        <?
		$this->template->showStreaming("get_pos", substr($data, 1, strlen($data)));
	}
  }
?>