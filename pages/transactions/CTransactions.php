<?
class CTransactions
{
	function CTransactions($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
            <table width="201" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  
                  <tr>
                    <td height="40" align="left">
                    
                    <a href="../all/index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="4" <? if ($sel==1) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-th-list" style="color:<? if ($sel==1) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          
                           <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==1) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">All Transactions</td>
                           
                           <td <? if ($sel==1) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;"><? if ($_REQUEST['ud']['unread_trans']>0) $this->template->showBubble($_REQUEST['ud']['unread_trans'], "ID_ROSU"); ?></td>
                          </tr>
                       
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                   <tr>
                    <td height="40" align="left">
                    
                     <a href="../escrowed/index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==2) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-lock" style="color:<? if ($sel==2) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==2) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Escrowed</td>
                          <td <? if ($sel==2) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;"><? if ($_REQUEST['ud']['unread_esc']>0) $this->template->showBubble($_REQUEST['ud']['unread_esc'], "ID_PORTO"); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      </a>
                      
                      </td>
                  </tr>
                  
                  
                 <tr>
                    <td height="40" align="left">
                    
                    <a href="../multisig/index.php">
                    <table width="200" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td width="4" <? if ($sel==3) print "bgcolor='#B20002'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">&nbsp;</td>
                          <td width="40" <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;" height="40" align="center">
                          <span class="glyphicon glyphicon-link" style="color:<? if ($sel==3) print "#990000"; else print "#bcac8e"; ?>"></span></td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="110" class="<? if ($sel==3) print "inset_red_14"; else print "inset_maro_14"; ?>" style="border-bottom-width:1px; border-bottom-style:solid; border-bottom-color:#ffffff;">Multisig</td>
                          <td <? if ($sel==3) print "bgcolor='#f7f5e8'"; ?> width="36" align="center" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;"><? if ($_REQUEST['ud']['unread_multisig']>0) $this->template->showBubble($_REQUEST['ud']['unread_multisig'], "ID_VERDE"); ?></td>
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
	
	function showTrans()
	{
		$query="UPDATE web_users 
		           SET unread_trans=0 
				 WHERE ID='".$_REQUEST['ud']['ID']."'";
		$this->kern->execute($query);
		
		$query="SELECT mt.*, rd.field_1_name  
		          FROM my_trans AS mt 
				  LEFT JOIN req_data AS rd ON rd.adr=mt.adr
			     WHERE mt.userID='".$_REQUEST['ud']['ID']."'
			  ORDER BY mt.ID DESC 
			     LIMIT 0,20";
		$result=$this->kern->execute($query);
		
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="47%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="3%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="10%" align="center" class="inset_maro_14">Data</td>
                        <td width="4%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="10%" align="center"><span class="inset_maro_14">Status</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="24%" align="center"><span class="inset_maro_14">Amount</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                     
                          <tr>
                          <td width="48%" align="left">
                          <a href="#" class="maro_12"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a><br><span class="simple_maro_10"><? print "Received ".$this->kern->getAbsTime($row['tstamp'])." ago"; ?></span></td>
                          <td width="15%" align="center" class="simple_maro_14">
                          <?
						 
						      if ($row['mes']!="") 
							  print "<span id='gly_msg_".rand(100, 10000)."' data-placement='top' class='glyphicon glyphicon-envelope' data-toggle='popover' data-trigger='hover' title='Message' data-content='".base64_decode($row['mes'])."'></span>&nbsp;&nbsp;";
							
							 if ($row['field_1']!="") print "<a href='javascript:void(0)' onclick=\"
							 $('#modal_req_data').modal(); 
							 
							 $('#field_1_name').text('".base64_decode($row['field_1_name'])."'); 
							 $('#field_1_val').text('".base64_decode($row['field_1'])."');
							 
							 $('#field_2_name').text('".base64_decode($row['field_2_name'])."'); 
							 $('#field_2_val').text('".base64_decode($row['field_2'])."');
							 
							 $('#field_3_name').text('".base64_decode($row['field_3_name'])."'); 
							 $('#field_3_val').text('".base64_decode($row['field_3'])."');
							 
							 $('#field_4_name').text('".base64_decode($row['field_4_name'])."'); 
							 $('#field_4_val').text('".base64_decode($row['field_4'])."');
							 
							 $('#field_5_name').text('".base64_decode($row['field_5_name'])."'); 
							 $('#field_5_val').text('".base64_decode($row['field_5'])."');
							 
							 \" class='maro_14'><span class='glyphicon glyphicon-list'></span></a>";
						  ?>
                          </td>
                          <td width="12%" align="center" class="simple_green_12">
                          <?
					        if ($row['status']=="ID_CLEARED")
						    {  
						      print "<span class='simple_green_12'><strong>cleared</strong></span>";
						    }
						    else
						    {
							   $dif=time()-$row['tstamp'];
							 
							   if ($dif<40) $img="p1.png";
							   if ($dif>=40 && $dif<80) $img="p2.png";
							   if ($dif>=80 && $dif<120) $img="p3.png";
							   if ($dif>=120 && $dif<160) $img="p4.png";
							   if ($dif>=160 && $dif<200) $img="p5.png";
							   if ($dif>200 && $dif<300) $img="p6.png";
							   if ($dif>300) $img="small_warning.png";
							   
							   print "<img src='../../template/template/GIF/".$img."'>";
							}
							?>
                          
                          </td>
                          <td width="25%" align="center" class="
						  <? 
						      if ($row['amount']<0) 
							     print "simple_red_12"; 
							  else 
							     print "simple_green_12"; 
						  ?>"><strong><? print $row['amount']." ".strtolower($row['cur']); ?></strong></td>
                          </tr>
                          <tr>
                          <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                          </tr>
                    
                    <?
					   }
					?>
                    
                    </tbody>
                  </table>
                  
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
            
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
               
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="simple_maro_16">&nbsp;&nbsp;&nbsp;This address is requesting additional data</td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="103" align="center"><img src="../../template/template/GIF/empty_pic.png" width="80" height="80" class="img-circle"/></td>
                        <td width="397" align="center" bgcolor="#fff4eb" class="simple_red_12">
                        <?
                           if ($row['mes']=="")
						     print "The owner of this address needs additional data. Please complete the following form. You need to respect the requested data length";
						   else 
							 print "&quot;".base64_decode($row['mes'])."&quot;";
                        ?>
						</td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
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
                                  <td height="30" align="left" valign="top" class="simple_maro_14">
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
                        <a href="javascript:void(0)" onClick="$('#form_req_data').submit()" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Send</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
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
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="simple_maro_16">&nbsp;&nbsp;&nbsp;Password Required</td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="103" align="center"><img src="../../adr/options/GIF/adr_opt_froze.png" width="80" height="74" alt=""/></td>
                        <td width="397" align="center" bgcolor="#fff4eb" class="simple_red_12">This address is requesting an unique 25 characters password before spending funds. A new password is generated after each transaction. Please provide the password.</td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td height="30" align="left" valign="top" class="simple_maro_14"><strong>Password</strong></td>
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
                        <a href="javascript:void(0)" onClick="$('#form_otp').submit()" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Send</a>
                        </td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table> 
            </form>
       
        <?
	}
	
	function showOTPConfirm($pass)
	{
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td class="simple_maro_16">&nbsp;&nbsp;Next Password</td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="103" align="center"><img src="../../adr/options/GIF/adr_opt_froze.png" width="80" height="74" alt=""/></td>
                        <td width="397" align="center" bgcolor="#fff4eb" class="simple_red_12">Below is displayed your new password. Please keep the passoword safe because you will need it if you want to send funds from this address.</td>
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
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
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
					   $moneda, 
					   $mes, 
					   $escrower,
					   $otp_old_pass="")
	{
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
		
		// Fee address is security options free
	    if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.");
			return false;
		}
		
		// From Address
	    if ($this->kern->adrExist($from_adr)==false)
		{
			$this->template->showErr("Invalid address");
			return false;
		}
		
		// Net Fee Address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// To Address
		if ($this->kern->adrValid($to_adr)==false)
		{
			$this->template->showErr("Invalid recipient");
			return false;
		}
		
		// Amount
		if ($amount<0.0001)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// Net fee balance
		if (($amount/1000)>$this->kern->getBalance($net_fee_adr))
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Sender balance
		if ($amount>$this->kern->getBalance($from_adr))
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Moneda
		if ($moneda!="MSK")
		{
			$query="SELECT * FROM assets WHERE symbol='".$moneda."'";
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
		
		// Mesaage
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
		
		// Otp
		if ($this->kern->hasAttr($from_adr, "ID_OTP")==true)
		{
			if ($otp_old_pass=="")
			{
				$this->showOTP($net_fee_adr, 
				               $from_adr, 
							   $to_adr, 
							   $amount, 
							   $amount, 
							   $mes, 
							   $escrower);
				return false;
			}
			else
			{
			   	// Load datas
				$query="SELECT * 
				          FROM adr_options 
						 WHERE adr='".$from_adr."' 
						   AND op_type='ID_OTP'";
				$result=$this->kern->execute($query);	
	            $row = mysql_fetch_array($result, MYSQL_ASSOC);
	            
				// Check pas
				if (hash("sha256", $otp_old_pass)!=$row['par_1'])
				{
				   $this->template->showErr("Invalid password", 550);
				   return false;	
				}
				
				// New pass
				$otp_new_pass=$this->getSecurePass();
				$otp_new_hash=hash("sha256", $otp_new_pass);
			}
		}
		
		// Additional data
		if ($this->kern->hasAttr($to_adr, "ID_REQ_DATA")==true)
		{
			if ($_REQUEST['act']=="send_req_coins")
			{
			   // Load data
			   $query="SELECT * 
			          FROM req_data 
					 WHERE adr='".$to_adr."'";
			   $result=$this->kern->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
			   // Check field 1
		       if ($row['field_1_name']!="")
			   {
				  if (strlen($_REQUEST['txt_field_1'])<$row['field_1_min'] || 
					   strlen($_REQUEST['txt_field_1'])>$row['field_1_max'])
				   {
					   $this->template->showErr("Invalid field 1 length", 550);
					   return false;	
				   }
			   }
				
			   // Check field 2
			   if ($row['field_2_name']!="")
		 	   {
			      if (strlen($_REQUEST['txt_field_2'])<$row['field_2_min'] || 
				      strlen($_REQUEST['txt_field_2'])>$row['field_2_max'])
				   {
					   $this->template->showErr("Invalid field 2 length", 550);
					   return false;	
				   }
			   }
				
			   // Check field 3
			   if ($row['field_3_name']!="")
			   {
			      if (strlen($_REQUEST['txt_field_3'])<$row['field_3_min'] || 
				      strlen($_REQUEST['txt_field_3'])>$row['field_3_max'])
			      {
				      $this->template->showErr("Invalid field 3 length", 550);
				      return false;	
			      }
			   }
				
			   // Check field 4
			   if ($row['field_4_name']!="")
			   {
				   if (strlen($_REQUEST['txt_field_4'])<$row['field_4_min'] || 
				       strlen($_REQUEST['txt_field_4'])>$row['field_4_max'])
				   {
					   $this->template->showErr("Invalid field 4 length", 550);
					   return false;	
				   }
			    }
				
			   // Check field 5
			   if ($row['field_5_name']!="")
			   {
				   if (strlen($_REQUEST['txt_field_5'])<$row['field_5_min'] || 
					   strlen($_REQUEST['txt_field_5'])>$row['field_5_max'])
				   {
					   $this->template->showErr("Invalid field 5 length", 550);
					   return false;	
				   }
			   }
		}
		else
	    {
				// Show panel
				$this->showReqDataPanel($net_fee_adr, 
				                        $from_adr, 
										$to_adr, 
										$amount, 
										$moneda, 
										$mes, 
										$escrower, 
										$otp_old_pass);
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
								par_7='".$otp_old_pass."',
								par_8='".$otp_new_hash."',
								par_9='".base64_encode($_REQUEST['txt_field_1'])."',
								par_10='".base64_encode($_REQUEST['txt_field_2'])."',
								par_11='".base64_encode($_REQUEST['txt_field_3'])."',
								par_12='".base64_encode($_REQUEST['txt_field_4'])."',
								par_13='".base64_encode($_REQUEST['txt_field_5'])."', 
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
	
	function showMyTrans($type="ID_REGULAR")
	{
		$query="SELECT * 
		          FROM my_trans AS mt 
				  LEFT JOIN trans_data AS td ON td.trans_hash=mt.hash  
				  WHERE mt.userID='".$_REQUEST['ud']['ID']."'
			  ORDER BY mt.ID DESC 
			     LIMIT 0,20";
		$result=$this->kern->execute($query);	
	   
	  
		?>
        
            <table width="93%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <table width="550" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
              <thead>
                <th width="55%">From / To</th>
                <th width="14%" align="center">&nbsp;&nbsp;&nbsp;Data</th>
                <th width="14%" align="center">&nbsp;&nbsp;&nbsp;Time</th>
                <th width="13%" align="center">&nbsp;&nbsp;&nbsp;Status</th>
                <th width="15%" align="center">&nbsp;&nbsp;&nbsp;Amount</th>
                <td width="3%"></thead>
                <tbody>
               
               <?
			       while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				   {
					   $time=$this->kern->getAbsTime($row['tstamp']);
			   ?>
               
                     <tr>
                     <td class="simple_blue_14"><? print $this->template->formatAdr($row['adr']); ?><br />
                     <span class="<? if ($row['mes']=="") print "simple_gri_10"; else print "simple_porto_10"; ?>"><? if ($row['mes']=="") print "No Message"; else print "Mesaage : ".base64_decode($row['mes']); ?></span></td>
                     <td class="simple_blue_14">&nbsp;</td>
                     <td align="center">
                     <span class="simple_blue_14"><strong><? $e=explode(" ", $time); print $e[0]; ?></strong></span><br /><span class="simple_gri_10"><? $e=explode(" ", $time); print $e[1]; ?></span></td>
                     <td align="center">
                    
                      <?
					     if ($row['status']=="ID_CLEARED")
						 {  
						   print "<span class='simple_green_14'>cleared</span>";
						 }
						 else
						 {
							 $dif=time()-$row['tstamp'];
							 
							 if ($dif<40) $img="p1.png";
							 if ($dif>=40 && $dif<80) $img="p2.png";
							 if ($dif>=80 && $dif<120) $img="p3.png";
							 if ($dif>=120 && $dif<160) $img="p4.png";
							 if ($dif>=160 && $dif<200) $img="p5.png";
							 if ($dif>200) $img="p6.png";
					  ?>
                      
                     <table width="60" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                      <td align="center"><img src="../../GIF/<? print $img; ?>"  /></td>
                      </tr>
                     <tr>
                      <td align="center" class="simple_porto_12">pending</td>
                      </tr>
                    </table>
                    
                    <?
						 }
					?>
                    
                    </td>
                    <td align="center"><span class="<? if ($row['amount']>0) print "inset_green_14"; else print "inset_red_14"; ?>"><strong><? print $row['amount']; ?></strong></span><br /><strong class="<? if ($row['amount']>0) print "inset_green_10"; else print "inset_red_10"; ?>"><? print $row['cur']; ?></strong></td>
                    </tr>
                
                <?
				   }
				?>
                
                 </tbody>
            </table></td>
          </tr>
        </table>
        
        <?
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
		$this->template->showModalFooter();
	}
}
?>