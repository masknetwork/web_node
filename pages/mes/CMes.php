<?
class CMes
{
	function CMes($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showButs()
	{
		
		?>
        
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="432">&nbsp;</td>
                  <td width="118">
                  <a href="#" onClick="$('#compose_modal').modal()" class="btn btn-success" style="width:120px">
                  <span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp; Compose
                  </a>
                  </td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
	
	function sendMes($fee_adr, $from_adr, $to_adr, $subject, $mes)
	{
		// Decode
		$to_adr=base64_decode($to_adr);
		$subject=base64_decode($subject);
		$mes=base64_decode($mes);
			
		// Check network fee address
		if ($this->kern->adrExist($fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Net fee address mine ?
		if ($this->kern->isMine($fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Check from address field
		if ($this->kern->adrValid($from_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// From address is mine ?
		if ($this->kern->isMine($from_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Check to address
		if (strlen($to_adr)<=30)
		  $to_adr=$this->template->adrFromDomain($to_adr);
		
		// To address valid ?
		if ($this->kern->adrValid($to_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Subject
		if (strlen($subject)<5 || strlen($subject)>50)
		{
			$this->template->showErr("Invalid subject length");
			return false;
		}
		
		// Mes
		if (strlen($subject)<5 || strlen($subject)>250)
		{
			$this->template->showErr("Invalid message length");
			return false;
		}
		
		if ($this->template->getBalance($fee_adr)<0.0001)
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Send a message to ".$to_adr);
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_SEND_MES', 
								fee_adr='".$fee_adr."', 
								target_adr='".$from_adr."',
								par_1='".$to_adr."',
								par_2='".base64_encode($subject)."',
								par_3='".base64_encode($mes)."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		   
		   // Insert send message
		   $query="INSERT INTO mes 
		                   SET from_adr='".$from_adr."', 
						       to_adr='".$to_adr."', 
							   subject='".base64_encode($subject)."', 
							   mes='".base64_encode($mes)."', 
							   status='".time()."', 
							   tstamp='".time()."' 
							   tgt='1'";
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
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function delMes($mesID)
	{
		$query="SELECT * 
		          FROM mes 
				 WHERE ID='".$mesID."'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
		   $this->template->showErr("Invalid entry data", 550);
		   return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// My message
		if ($this->kern->isMine($row['to_adr'])==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
		     return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Deletes a message");
		
		    // Insert to stack
		   $query="DELETE FROM mes WHERE ID='".$mesID."'"; 
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
		  $this->template->showErr("Unexpected error.");

		  return false;
	   }
	}
	
	function showComposeModal()
	{
		$this->template->showModalHeader("compose_modal", "Compose Message", "act", "send");
		?>
        
          <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="192" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top"><img src="./GIF/compose.png" width="200" height="200" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001"); ?></td>
              </tr>
            </table></td>
            <td width="418" align="right" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("fee_adr"); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Sender Address</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("sender_adr"); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Recipient Address</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_rec" name="txt_rec" placeholder="Recipient" style="width:300px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="74%" align="left"><strong>Subject</strong></td>
                    <td width="26%" align="left" id="td_chars_2" class="simple_gri_10">0 characters</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_subject" name="txt_subject" placeholder="Subject (5-50 characters)" style="width:300px"/>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="74%" align="left"><strong>Message</strong></td>
                    <td width="26%" align="left" id="td_chars" class="simple_gri_10">0 characters</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left"><textarea class="form-control" name="txt_mes" rows="4" id="txt_mes" placeholder="Message (5-250 characters)" style="width:300px"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#form_compose_modal').submit(
		function() 
		{ 
		   $('#txt_subject').val(btoa($('#txt_subject').val())); 
		   $('#txt_mes').val(btoa($('#txt_mes').val())); 
		   $('#txt_rec').val(btoa($('#txt_rec').val())); 
		});
		
		
		    $('#txt_mes').keyup(
			function() 
			{ 
			   var str=String($('#txt_mes').val());
			   var length=str.length;
			   $('#td_chars').text(length+" characters");
			});
			
			 $('#txt_subject').keyup(
			function() 
			{ 
			   var str=String($('#txt_subject').val());
			   var length=str.length;
			   $('#td_chars_2').text(length+" characters");
			});
		
		
		  </script>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showMessage($mesID)
	{
		$query="SELECT * 
		          FROM mes 
				 WHERE ID='".$mesID."'"; 
		$result=$this->kern->execute($query);	
	    
		if (mysql_num_rows($result)==0)
		{
		   $this->template->showErr("Invalid entry data", 550);
		   return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// My message
		if ($this->kern->isMine($row['from_adr'])==false && 
		    $this->kern->isMine($row['to_adr'])==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
		     return false;
		}
		
		// Read
		$query="UPDATE mes 
		           SET status='".time()."' 
				 WHERE ID='".$mesID."'";
	    $this->kern->execute($query);	
		
		// Unread mes
		$query="UPDATE web_users 
		           SET unread_mes=unread_mes-1 
				 WHERE ID='".$_REQUEST['ud']['ID']."'"; 
	    $this->kern->execute($query);	
		
		?>
            
            <br>
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">
                  <table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="96" height="30" align="right" bgcolor="#FFF7EE" class="simple_maro_12">From Address</td>
                        <td width="404" align="left" bgcolor="#FFF7EE">&nbsp;&nbsp;<a href="#" class="red_12"><strong>
						<? 
						    print $this->template->formatAdr($row['from_adr']); 
					    ?>
                        </strong></a></td>
                      </tr>
                      <tr>
                        <td height="30" align="right" bgcolor="#FFF7EE" class="simple_maro_12">To address</td>
                        <td align="left" bgcolor="#FFF7EE">&nbsp;&nbsp;<a href="#" class="red_12"><strong>
                        <? 
						    print $this->template->formatAdr($row['to_adr']); 
					    ?>
                        </strong></a></td>
                      </tr>
                      <tr>
                        <td height="30" align="right" bgcolor="#FFF7EE" class="simple_maro_12">Subject</td>
                        <td align="left" bgcolor="#FFF7EE" class="simple_red_12">&nbsp;&nbsp;<strong>
                        <? 
						    print base64_decode($row['subject']); 
					    ?>
                        </strong></td>
                      </tr>
                      <tr>
                        <td height="10" colspan="2" align="left" valign="top" class="simple_maro_14">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="100" colspan="2" align="left" valign="top" class="simple_maro_14"><? print base64_decode($row['mes']); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td align="right"><a href="javascript:void(0)" onclick="$('#txt_rec').val('<? print $row['from_adr']; ?>'); $('#txt_subject').val('<? print "Re:".base64_decode($row['subject']); ?>'); $('#compose_modal').modal();" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Reply</a></td>
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
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
            <table width="201" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="../inbox/index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==1) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-envelope" style="color:<? if ($sel==1) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                        
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==1) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Inbox</td>
                        
                          <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        
                          </tr>
                        </tbody>
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                   <tr>
                    <td height="40" align="left">
                    
                    <a href="../sent/index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==2) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-send" style="color:<? if ($sel==2) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                         
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==2) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Sent</td>
                         
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                        
                          </tr>
                        </tbody>
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                  
                 
                  <tr>
                    <td height="40" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="40" align="left">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
        
        <?
	}
	
	function showMes($target=0)
	{
		$query="SELECT * 
		          FROM mes 
				 WHERE to_adr IN (SELECT adr 
				                    FROM my_adr 
								   WHERE userID='".$_REQUEST['ud']['ID']."')
				AND tgt='".$target."'
			  ORDER BY ID DESC LIMIT 0,25";
		 $result=$this->kern->execute($query);	
	  
		?>
           
           <br>
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="21%" align="left" class="inset_maro_14">Sender</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="47%" align="left"><span class="inset_maro_14">Subject</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="14%" align="center"><span class="inset_maro_14">Received</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="12%" align="center">&nbsp;</td>
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
                               <td width="4%" align="left"><span class="glyphicon glyphicon-envelope" style="color:#<? if ($row['status']==0) print "990000"; else print "b5ae80"; ?>"></span></td>
                             <td width="18%" align="left">
                            
                             <a href="index.php?act=show_mes&mesID=<? print $row['ID']; ?>" class="<? if ($row['status']>0) print "maro_12"; else print "red_12"; ?>">
							 <? 
							    if ($row['status']==0) 
								   print "<strong>".$this->template->formatAdr($row['from_adr'])."</strong>"; 
							    else
								   print $this->template->formatAdr($row['from_adr']); 
						     ?>
                             </a>
                            
                             </td>
                             
                             
                             <td width="51%" align="left" >
                             <a href="index.php?act=show_mes&mesID=<? print $row['ID']; ?>" class="<? if ($row['status']>0) print "maro_12"; else print "red_12"; ?>">
							 <? 
							    if ($row['status']==0) 
								   print "<strong>".base64_decode($row['subject'])."</strong>"; 
							    else
								   print base64_decode($row['subject']); 
						     ?>
                             </a>
                             
                             </td>
                             <td width="13%" align="center">
							 
                             <a href="index.php?act=show_mes&mesID=<? print $row['ID']; ?>" class="<? if ($row['status']>0) print "maro_12"; else print "red_12"; ?>">
                             <? 
							    if ($row['status']==0) 
								   print "<strong>".$this->kern->getAbsTime($row['tstamp'])."</strong>"; 
							    else
								   print $this->kern->getAbsTime($row['tstamp']); 
						     ?>
                              </a>
                             </td>
                            
                             
                             <td width="14%" align="center" class="simple_maro_12">
                  
                             <div class="dropdown" align="right">
                             <button class="btn btn-danger dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>&nbsp;</button>
                             <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                             <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#confirm_modal').modal(); $('#par_1').val('<? print $row['ID']; ?>');">Delete</a></li>
                             </ul>
                             </div>
                  
                            </td></tr>
                            <tr>
                            <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
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