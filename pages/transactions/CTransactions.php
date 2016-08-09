<?
class CTransactions
{
	function CTransactions($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	
	function showTrans()
	{
		// Request data modal
		$this->showReqDataModal();
		
		if ($_REQUEST['act']=="clear")
		{
		  $query="UPDATE web_users 
		           SET unread_trans=0 
				 WHERE ID='".$_REQUEST['ud']['ID']."'";
		  $this->kern->execute($query);
		}
		
		$query="SELECT mt.*, blocks.confirmations
		          FROM my_trans AS mt
		     LEFT JOIN blocks ON blocks.hash=mt.block_hash
				 WHERE mt.userID='".$_REQUEST['ud']['ID']."'
				ORDER BY ID DESC 
			     LIMIT 0,20"; 
		$result=$this->kern->execute($query);
		
		?>
            
            <div id="div_trans" name="div_trans">
            <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
              <tbody>
                <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                     
                          <tr>
                          <td width="55%" align="left">
                          <a href="../../explorer/packets/packet.php?hash=<? print $row['hash']; ?>" class="font_14"><strong><? print $this->template->formatAdr($row['adr']); ?></strong>
                          </a><p class="font_10"><? print "Received ".$this->kern->getAbsTime($row['tstamp'])." ago"; ?></p></td>
                          <td width="5%" align="center" class="font_14" style="color:#999999">
                          <?
						 
						      if ($row['mes']!="") 
							  print "<span id='gly_msg_".rand(100, 10000)."' data-placement='top' class='glyphicon glyphicon-envelope' data-toggle='popover' data-trigger='hover' title='Message' data-content='".base64_decode($row['mes'])."'></span>&nbsp;&nbsp;";
							
						  ?>
                          </td>
                          <td width="15%" align="center" class="<? if ($row['confirms']<25) print "font_16"; else print "font_14"; ?>">
                          <?
						      $confirms=$row['confirmations'];
							  
						      if ($confirms==0)
					             print "<span class='label label-danger'>".$confirms."</span>";
							  if ($confirms=="")
					             print "<span class='label label-danger'>0</span>";
							  else if ($confirms<=10)
					             print "<span class='label label-info'>".$confirms."</span>";
						      else if ($confirms>10 && $confirms<25)
					             print "<span class='label label-warning'>".$confirms."</span>";
						      else
							     print "<span class='label label-success'>Confirmed</span>";
								 
							  if ($confirms<25) print "<p class=\"font_10\">confirmations</p>";
						 ?>
                         
                          </td>
                          <td width="25%" align="center" class="font_14" style=" 
						  <? 
						      if ($row['amount']<0) 
							     print "color:#990000"; 
							  else 
							     print "color:#009900"; 
						  ?>"><strong><? print $row['amount']." ".strtolower($row['cur']); ?></strong>
                          <p class="font_12">
						  <? 
						      if ($row['cur']=="MSK")
							  {
								  if ($row['amount']<0)
								    print "-$".abs(round($row['amount']*$_REQUEST['sd']['msk_price'], 4));
								  else
								     print "+$".round($row['amount']*$_REQUEST['sd']['msk_price'], 4);
							  }
					      ?>
                          </p>
                          </td>
                          </tr>
                          <tr>
                          <td colspan="4"><hr></td>
                          </tr>
                    
                    <?
					   }
					?>
                    
                    </tbody>
                  </table>
                  <br><br><br>
                  </div>
                  
            
            <script>
			$("span[id^='gly_']").popover();
			</script>
        
        <?
	}
	
	function showReqDataPanel($net_fee_adr, 
	                          $from_adr, 
					          $to_adr, 
					          $amount, 
					          $moneda, 
					          $mes, 
					          $escrower,
							  $otp_old_pass="")
	{
		$query="SELECT * 
		          FROM req_data 
				 WHERE adr='".$to_adr."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
           
           <form id="form_req_data" name="form_req_data" action="index.php?act=send_req_coins" method="post">
           
           <input type="hidden" id="h_net_fee_adr" name="h_net_fee_adr" value="<? print $net_fee_adr; ?>">
           <input type="hidden" id="h_from_adr" name="h_from_adr" value="<? print $from_adr; ?>">
           <input type="hidden" id="h_to_adr" name="h_to_adr" value="<? print $to_adr; ?>">
           <input type="hidden" id="h_amount" name="h_amount" value="<? print $amount; ?>">
           <input type="hidden" id="h_moneda" name="h_moneda" value="<? print $moneda; ?>">
           <input type="hidden" id="h_mes" name="h_mes" value="<? print $mes; ?>">
           <input type="hidden" id="h_escrower" name="h_escrower" value="<? print $escrower; ?>">
           <input type="hidden" id="h_otp_old_pass" name="h_otp_old_pass" value="<? print $otp_old_pass; ?>">
               
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="font_16">&nbsp;&nbsp;&nbsp;This address is requesting additional data</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="103" align="center"><img src="../../template/template/GIF/empty_pic.png" width="80" height="80" class="img-circle"/></td>
                        <td width="397" align="center" bgcolor="#f0f0f0" class="font_14">
                        <?
                           if ($row['mes']=="")
						     print "The owner of this address needs additional data. Please complete the following form. You need to respect the requested data length";
						   else 
							 print "&quot;".base64_decode($row['mes'])."&quot;";
                        ?>
						</td>
                      </tr>
                      
                      <tr>
                        <td colspan="2">
                        
                        
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr><td colspan="2">&nbsp;</td></tr>
                          
						  <?
						     for ($a=1; $a<=5; $a++)
							 {
								 if ($row['field_'.$a.'_name']!="")
								 {
						  ?>
                          
                                  <tr><td>&nbsp;</td></tr>
                                  <tr>
                                  <td height="30" align="left" valign="top" class="font_14">
                                  <strong><? print base64_decode($row['field_'.$a.'_name']); ?></strong></td>
                                  </tr>
                                  <tr>
                                  <td><input class="form-control" name="txt_field_<? print $a; ?>" id="txt_field_<? print $a; ?>" placeholder="<? print $row['field_'.$a.'_min']."-".$row['field_'.$a.'_max']." characters"; ?>"></td>
                                  </tr>
                          
                          
                          <?
								 }
	                         }
						  ?>
                        </table>
                        
                        
                        </td>
                        </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                      <tr>
                        <td colspan="2" align="right">
                        <a href="javascript:void(0)" onClick="$('#form_req_data').submit()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Send</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                
              </tbody>
            </table>
            </form>
            
           
        
        <?
	}
	
	function showOTP($net_fee_adr, 
	                 $from_adr, 
					 $to_adr, 
					 $amount, 
					 $moneda, 
					 $mes, 
					 $escrower)
	{
		?>
           
           <form id="form_otp" name="form_otp" action="index.php?act=send_otp_coins" method="post">
           
           <input type="hidden" id="h_net_fee_adr" name="h_net_fee_adr" value="<? print $net_fee_adr; ?>">
           <input type="hidden" id="h_from_adr" name="h_from_adr" value="<? print $from_adr; ?>">
           <input type="hidden" id="h_to_adr" name="h_to_adr" value="<? print $to_adr; ?>">
           <input type="hidden" id="h_amount" name="h_amount" value="<? print $amount; ?>">
           <input type="hidden" id="h_moneda" name="h_moneda" value="<? print $moneda; ?>">
           <input type="hidden" id="h_mes" name="h_mes" value="<? print $mes; ?>">
           <input type="hidden" id="h_escrower" name="h_escrower" value="<? print $escrower; ?>">
           
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="font_16">&nbsp;&nbsp;&nbsp;Password Required</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="15%" align="center"><img src="../../adr/options/GIF/adr_opt_froze.png" class="img-responsive"/></td>
                        <td width="85%" align="center" bgcolor="#f0f0f0" class="font_14">This address is requesting an unique 25 characters password before spending funds. A new password is generated after each transaction. Please provide the password.</td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="font_14"><strong>Password</strong></td>
                            </tr>
                            <tr>
                              <td><input class="form-control" type="password" name="txt_old_pass" id="txt_old_pass" placeholder="0-25 characters"></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="right">
                        <a href="javascript:void(0)" onClick="$('#form_otp').submit()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Send</a>
                        </td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table> 
            </form>
            
        <?
	}
	
	function showOTPConfirm($pass)
	{
		?>
           
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="font_16">&nbsp;&nbsp;Next Password</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="15%" align="center"><img src="../../adr/options/GIF/adr_opt_froze.png" class="img-responsive" /></td>
                        <td width="85%" align="center" bgcolor="#fff4eb" class="simple_red_12">Below is displayed your new password. Please keep the passoword safe because you will need it if you want to send funds from this address.</td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="60" colspan="2" align="center" class="simple_green_22"><strong><? print $pass; ?></strong></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                
              </tbody>
            </table> 
            
             
           
       
        <?
	}
	
	function getSecurePAss($length = 25, $add_dashes = false, $available_sets = 'lud')
    {
	    $sets = array();
	    
		if(strpos($available_sets, 'l') !== false)
		    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
			
	    if(strpos($available_sets, 'u') !== false)
		    $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	    
		if(strpos($available_sets, 'd') !== false)
		    $sets[] = '0123456789';
	    
		if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';
	    
		$all = '';
	    $password = '';
	    
		foreach($sets as $set)
	    {
		    $password .= $set[array_rand(str_split($set))];
		    $all .= $set;
	    }
	    
		$all = str_split($all);
	    
		for($i = 0; $i < $length - count($sets); $i++)
		    $password .= $all[array_rand($all)];
	    
		$password = str_shuffle($password);
	    
		
		if(!$add_dashes)
		   return $password;
	    
		$dash_len = floor(sqrt($length));
	    $dash_str = '';
	    
		while(strlen($password) > $dash_len)
	    {
		    $dash_str .= substr($password, 0, $dash_len) . '-';
		    $password = substr($password, $dash_len);
	    }
	    
		$dash_str .= $password;
	    return $dash_str;
    }
	
	function sendCoins($net_fee_adr, 
	                   $from_adr, 
					   $to_adr, 
					   $amount, 
					   $amount_asset, 
					   $moneda, 
					   $mes, 
					   $escrower,
					   $sign="")
	{
		// Ammount
		if ($amount_asset>0) $amount=$amount_asset;
		
		// Recipient a name ?
		if (strlen($to_adr)<31) 
		    $to_adr=$this->template->adrFromDomain($to_adr);
		
		// Escrower a name ?
		if (strlen($escrower)<31) 
		   $escrower=$this->template->adrFromDomain($escrower);
		
		// Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// From Address
	    if ($this->kern->adrExist($from_adr)==false)
		{
			$this->template->showErr("Invalid address");
			return false;
		}
		
		// To Address
		if ($this->kern->adrValid($to_adr)==false)
		{
			$this->template->showErr("Invalid recipient");
			return false;
		}
		
		// Sender and recipient the same ?
		if ($from_adr==$to_adr)
		{
			$this->template->showErr("Source and destination address is the same");
			return false;
		}
		
		// Sender and fee address can spend ?
		if ($this->kern->canSpend($from_adr)==false)
		{
			$this->template->showErr("Sender address can't spend funds");
			return false;
		}
		
		// Fee address can spend ?
		if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Net fee address can't spend funds");
			return false;
		}
		
		if ($moneda=="MSK")
		{
		   // Amount
		   if ($amount<0.0001)
		   {
			  $this->template->showErr("Minimum amount is 0.0001");
			  return false;
		   }
		
		   // Net fee balance
		   if ($this->kern->getBalance($net_fee_adr)<0.0001)
		   {
			  $this->template->showErr("Insuficient funds to execute this operation");
			  return false;
		   }
		   
		   // Sender balance
		   if ($this->kern->getBalance($from_adr)<$amount)
		   {
			  $this->template->showErr("Insuficient funds to execute this operation");
			  return false;
		   }
		}
		else
		{
			$query="SELECT * 
			          FROM assets 
					 WHERE symbol='".$moneda."'";
			$result=$this->kern->execute($query);	
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Invalid currency");
			    return false;
			}
			
			// Balance
			$query="SELECT * 
			          FROM assets_owners 
					 WHERE owner='".$from_adr."' 
					   AND symbol='".$moneda."' 
					   AND qty>".$amount;
					   
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Insuficient assets to execute this operation");
			    return false;
			}
		}
		
		// Message
		if (strlen($mes)>250)
		{
			$this->template->showErr("Invalid message length (0-100 characters)");
			return false;
		}
		
		// Escrower
		if ($escrower!="")
		{
			if ($this->kern->adrValid($escrower)==false)
			{
				$this->template->showErr("Invalid escrower");
			    return false;
			}
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Send coins / assets to an address");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_TRANSACTION', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$from_adr."',
								par_2='".$to_adr."',
								par_3='".$amount."', 
								par_4='".$moneda."', 
								par_5='".$mes."', 
								par_6='".$escrower."',
								par_7='".$sign."',
								status='ID_PENDING', 
								tstamp='".time()."'";
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   if ($otp_new_hash=="") 
		      $this->template->showOk("Your request has been succesfully recorded", 550);
		   else
		      $this->showOTPConfirm($otp_new_pass);
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
	
	
	function showReqDataModal()
	{
		$this->template->showModalHeader("modal_req_data", "Data", "act", "");
		
		?>
        
<table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/add_data.png" width="200" height="200" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             
              <tr>
                <td valign="top" class="simple_gri_14" id="field_1_name" height="30">Field 1</td>
              </tr>
              <tr>
                <td class="simple_blue_12"><textarea id="field_1_val" class="form-control" rows="3">Field 1 Value</textarea></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
             
              <tr>
                <td height="30" valign="top"><span class="simple_gri_14" height="30">Field 2</span></td>
              </tr>
              <tr>
                <td><span class="simple_blue_12">
                  <textarea name="field_2_val" rows="3" class="form-control" id="field_2_val">Field 2 Value</textarea>
                </span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
             
              <tr>
                <td height="30" valign="top"><span class="simple_gri_14" height="30">Field 3</span></td>
              </tr>
              <tr>
                <td><span class="simple_blue_12">
                  <textarea name="field_3_val" rows="3" class="form-control" id="field_3_val">Field 3 Value</textarea>
                </span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              
               <tr>
                <td height="30" valign="top"><span class="simple_gri_14" height="30">Field 4</span></td>
              </tr>
              <tr>
                <td><span class="simple_blue_12">
                  <textarea name="field_4_val" rows="3" class="form-control" id="field_4_val">Field 4 Value</textarea>
                </span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              
              <tr>
                <td height="30" valign="top"><span class="simple_gri_14" height="30">Field 5</span></td>
              </tr>
              <tr>
                <td><span class="simple_blue_12">
                  <textarea name="field_5_val" rows="3" class="form-control" id="field_5_val">Field 5 Value</textarea>
                </span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              
              
            </table></td>
          </tr>
        </table>
        
        <script>
		linkToNetFee("txt_days_re", "", "re_net_fee_panel_val");
		</script>
        
        <?
		$this->template->showModalFooter("");
	}
}
?>