<?
class CAdrOptions
{
	function CAdrOptions($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showOptions($adrID)
	{
		$query="SELECT * 
		          FROM my_adr 
				 WHERE ID='".$adrID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $adr=$row['adr'];
	    
		
		// Sealed ?
		if ($this->kern->hasAttr($adr, "ID_SEALED")==true)
		   $sealed=true;
		else
		   $sealed=false;	
		?>
             
            
                
             <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
                <tr>
                  <td align="left" valign="top" width="20%">
                  
                  <!-- ----------------------------- Interest image ------------------------------------------------------ -->
                  <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center" width="100"><img src="./GIF/adr_opt_interest.png" class="img-responsive" /></td>
                      </tr>
                      <tr>
                        <td height="40" align="center"><a href="javascript:void()" onclick="javascript:$('#modal_interest').modal()" class="btn btn-primary" style="width:100%"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Setup&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                      </tr>
                    </tbody>
                  </table>
                  
                  </td>
                  <td valign="top" width="20px">&nbsp;</td>
                 
                  <td valign="top">
                  
                  <!-- -----------------------------  Interest text  ------------------------------------------------------ -->
                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td height="0" align="left">
                       <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="73%" height="30">
                          <span class="font_16"><strong>Receive Daily Interest</strong></span>
                          <span class="font_14">( not receiving )</span></td>
                          <td width="27%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="0" align="left" class="font_14">MaskCoin is distributed largely through interest. The interest rate is determined depending on how many coins are in circulation and decreases in time. The current interest rate is 55%. per year. Any address that holds at least 5 MSK can receive this interest daily. You can set this address to receive interest.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr align="left">
                  <td height="40" colspan="3"><hr></td>
                </tr>
                
                
                
                <tr>
                  <td align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><img src="./GIF/adr_opt_names.png" class="img-responsive" /></td>
                      </tr>
                      <tr>
                        <td height="40" align="center"><a href="javascript:void()" onclick="javascript:$('#modal_new_domain').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Rent&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                      </tr>
                    </tbody>
                  </table>
                  
                  <!-- ----------------------------- Rent image ------------------------------------------------------ --></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">
                  
                   <!-- -----------------------------  Rent text  ------------------------------------------------------ -->
                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="75%" height="30">
                          <span class="font_16"><strong>Rent Adress Name</strong></span> 
                          <span class="font_14">( 0.0001 MSK / day )</span></td>
                          <td width="25%">&nbsp;</td>
                        </tr>
                      </table></td>
                      </tr>
                    <tr>
                      <td height="0" align="left" class="font_14">Renting a name for your address is a great way to simplify the receipt of money. Instead of paying at an address difficult to understand consisting of dozens of numbers / letters, you can receive money in an address like "Maria". You can rent an unlimited number of names. You can also have multiple names for a single address.</td>
                      </tr>
                  </table>
                  
                  
                  </td>
               </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                  </tr>
                <tr>
                  <td align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_share.png"  class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="40" align="center">
                          <a href="javascript:void()" onclick="javascript:$('#modal_share').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;&nbsp;&nbsp;Share&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%"><span class="font_16"><strong>Share This  Adress </strong></span> <span class="font_14">( 0.0001 MSK  )</span></td>
                          <td width="32%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">An address consists a public and a private key. If you own the private key of an address, you can spend the funds associated with the address. By using this option you can send the private key of this address to another person (address). After receiving the private key, the recipient will be able to use this address exactly like you.</td>
                    </tr>
                  </table></td>
               </tr>
                <tr>
                  <td height="40" colspan="3" align="left" ><hr></td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_profile.png"  class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
						  <?
						     $query="SELECT * FROM profiles WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         
							 if (mysql_num_rows($result)==0)
							 {
						  ?>
                          
                                 <a href="javascript:void()" onclick="javascript:$('#modal_profile').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%">
                                 <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup
                                 </a>
                          
                          <?
							 }
							 else
							 {
								 $row = mysql_fetch_array($result, MYSQL_ASSOC);
								 
								 ?>
                                 
                                  <a href="javascript:void()" onclick="javascript:$('#modal_profile').modal(); 
                                                                  $('#txt_prof_name').val('<? print base64_decode($row['name']); ?>');
                                                                  $('#txt_desc').val('<? print base64_decode($row['description']); ?>');
                                                                  $('#txt_tel').val('<? print base64_decode($row['tel']); ?>');
                                                                  $('#txt_email').val('<? print base64_decode($row['email']); ?>');
                                                                  $('#txt_web').val('<? print base64_decode($row['website']); ?>');
                                                                  $('#txt_fb').val('<? print base64_decode($row['facebook']); ?>');
                                                                  $('#txt_pic').val('<? print base64_decode($row['avatar']); ?>');" 
                                  class="btn btn-warning <? if ($sealed==true) print "disabled"; ?>" style="width:100%">
                                  <span class="glyphicon glyphicon-cog" ></span>Update
                                  </a>
                                 
                                 <?
							 }
						  ?>
                          
                          </td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td  valign="top">&nbsp;</td>
                  <td height="30"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%"><span class="font_16"><strong>Profile Setup</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a></td>
                          <td width="32%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">You can associate to this address a profile where to provide more information such as an email address, a website, an avatar, etc. All profiles are public. The cost of a profile is 0.0001 MSK / day.</td>
                    </tr>
                  </table></td>
                  </tr>
                <tr align="left">
                  <td height="40" colspan="3">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td height="30" colspan="3" bgcolor="#f0f0f0">
                  <span class="font_18"><strong>&nbsp;&nbsp;Security Options</strong></span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_froze.png" class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     if ($this->kern->hasAttr($adr, "ID_FROZEN")==true)
							 {
						  ?>
                          
                                   <a href="javascript:void()" onclick="javascript:$('#modal_froze').modal()" class="btn btn-warning <? if ($sealed==true) print "disabled"; ?>" style="width:100%">
                                   <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Renew
                                   </a>
                          
                          <?
							 }
							 else
							 {
								 ?>
                                 
                                   <a href="javascript:void()" onclick="javascript:$('#modal_froze').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%">
                                   <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup
                                   </a>
                                 
                                 <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><span class="font_16"><strong>Froze Address</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">You can freeze this address for a period of time. One cannot spend money from a frozen address. This option cannot be suspended while it is active. We recommend that this option be used with prudence. If an attacker takes control of the address, he/she will not be able to spend funds for as long as the option is active.
                      <?
					     if ($this->kern->hasAttr($adr, "ID_FROZEN")==true)
						 {
							 $query="SELECT * 
								           FROM adr_options 
										  WHERE adr='".$adr."' 
										    AND op_type='ID_FROZEN'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
								 
						          print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";  
						 }
					  ?>
                      </td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td height="40" colspan="3" align="left" ><hr></td>
                  </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_seal.png" class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     if ($sealed==true)
							 {
						  ?>
                          
                          <a href="javascript:void()" onclick="javascript:$('#modal_seal').modal()" class="btn btn-warning" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Renew</a>
                          
                          <?
							 }
							 else
							 {
								 ?>
                                 
                                  <a href="javascript:void()" onclick="javascript:$('#modal_seal').modal()" class="btn btn-primary" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a>
                                 
                                 <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><span class="font_16"><strong>Seal Address</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">From a sealed address you can spend money but you cannot attach any option. Also there can be no names attached to it and cannot be shared with another address. If an attacker takes control of the address, he/she will be able to spend funds but cannot perform other operations.
                      <?
					     if ($this->kern->hasAttr($adr, "ID_SEALED")==true)
						 {
							 $query="SELECT * 
								           FROM adr_options 
										  WHERE adr='".$adr."' 
										    AND op_type='ID_SEALED'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
								 
						          print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";  
						 }
					  ?>
                      </td>
                    </tr>
                  </table></td>
               </tr>
                <tr>
                  <td height="40" colspan="3" align="left" ><hr></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_restrict.png" class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     if ($this->kern->hasAttr($adr, "ID_RESTRICT_REC")==true)
							 {
								 $query="SELECT * 
								           FROM adr_options 
										  WHERE adr='".$adr."' 
										    AND op_type='ID_RESTRICT_REC'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
                          ?>
                          
                          <a href="javascript:void()" onclick="javascript:$('#modal_restrict').modal(); 
                                                          $('#txt_target_1').val('<? print $this->template->formatAdr($row['par_1']); ?>');
                                                          $('#txt_target_2').val('<? print $this->template->formatAdr($row['par_2']); ?>');
                                                          $('#txt_target_3').val('<? print $this->template->formatAdr($row['par_3']); ?>');
                                                          $('#txt_target_4').val('<? print $this->template->formatAdr($row['par_4']); ?>');
                                                          $('#txt_target_5').val('<? print $this->template->formatAdr($row['par_5']); ?>');
                                                          $('#txt_target_1').attr('disabled', 'true');
                                                          $('#txt_target_2').attr('disabled', 'true');
                                                          $('#txt_target_3').attr('disabled', 'true');
                                                          $('#txt_target_4').attr('disabled', 'true');
                                                          $('#txt_target_5').attr('disabled', 'true');" class="btn btn-warning <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Renew</a>
                          
                          <?
							 }
							 else
							 {
								 ?>
                                 
                                 <a href="javascript:void()" onclick="javascript:$('#modal_restrict').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a>
                                 
                                 <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><span class="font_16"><strong>Restrict Recipients</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_restrict').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">By activating this option you will be able to send funds from the address only to a group of up to 5 other addresses. If an attacker takes control of the address, he/she can only send funds to a specified group of addresses. You cannot attach new recipients or remove current recipients as long as the option is active.
                      
                      <?
					  if ($this->kern->hasAttr($adr, "ID_RESTRICT_REC")==true)
						print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";                   
					  ?>
                      
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_multisig.png" width="90%" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						    if ($this->kern->hasAttr($adr, "ID_MULTISIG")==false)
							{
						  ?>
                          
                          <a href="javascript:void(0)" onclick="javascript:$('#modal_multisig').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%">                          <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup
                          </a>
                          
                          <?
	                         }
							 else
							 {
								 $query="SELECT * 
								          FROM adr_options 
										 WHERE adr='".$adr."' 
										   AND op_type='ID_MULTISIG'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
						  ?>
                          
                           <a href="javascript:void(0)" onclick="javascript:$('#modal_multisig').modal(); 
                                                                $('#txt_signer_1').val('<? print $this->kern->domainFromAdr($row['par_1']); ?>');
                                                                $('#txt_signer_1').attr('disabled', 'disabled');
                                                                
                                                                $('#txt_signer_2').val('<? print $this->kern->domainFromAdr($row['par_2']); ?>');
                                                                $('#txt_signer_2').attr('disabled', 'disabled');
                                                                
                                                                $('#txt_signer_3').val('<? print $this->kern->domainFromAdr($row['par_3']); ?>');
                                                                $('#txt_signer_3').attr('disabled', 'disabled');
                                                                
                                                                $('#txt_signer_4').val('<? print $this->kern->domainFromAdr($row['par_4']); ?>');
                                                                $('#txt_signer_4').attr('disabled', 'disabled');
                                                                
                                                                $('#txt_signer_5').val('<? print $this->kern->domainFromAdr($row['par_5']); ?>');
                                                                $('#txt_signer_5').attr('disabled', 'disabled');
                                                                
                                                                $('#txt_sig_min').val('<? print $row['par_6']; ?>');
                                                                $('#txt_sig_min').attr('disabled', 'disabled');
                                                                
                                                                " class="btn btn-warning <? if ($sealed==true) print "disabled"; ?>" style="width:100%">                           
                           <span class="glyphicon glyphicon-refresh" ></span>&nbsp;&nbsp;Renew
                           </a>   
                          
                          <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><span class="font_16"><strong>Multiple Signatures</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_multisig').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">An address that has this option attached will require multiple signatures to be able to spend funds. Any transfer of this address will be signed by the authorized addresses. If an attacker takes control of this address, he/she will require the signature of the authorized persons to be able to spend funds.
                      
                       <?
					      if ($this->kern->hasAttr($adr, "ID_MULTISIG")==true)
						     print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";                   
					  ?>
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_otp.png" class="img-responsive" /></td>
                      </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     if ($this->kern->hasAttr($adr, "ID_OTP")==true)
							 {
								 $query="SELECT * 
								           FROM adr_options 
										  WHERE adr='".$adr."' 
										    AND op_type='ID_OTP'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
						  ?>
                          
                                <a href="javascript:void()" onclick="javascript:$('#modal_otp').modal(); $('#txt_otp_adr').attr('disabled', true); $('#txt_otp_adr').val('<? print $row['par_2']; ?>'); $('#tab_otp').css('display', 'none');" class="btn btn-warning <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Renew</a>
                          
                          <?
							 }
							 else
							 {
								 ?>
                                 
                                  <a href="javascript:void()" onclick="javascript:$('#modal_otp').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a>
                                 
                                 <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><span class="font_16"><strong>One Time Password</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">If you activate this option, before sending the funds you will need to enter a new password. After sending the money from a protected address with this option, a new password will be generated. If an attacker takes control of this address, he/she will need to provide a password to be able to spend the funds.
                      
					  <?
					     if ($this->kern->hasAttr($adr, "ID_OTP")==true)
						 {	 
						          print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";  
						 }
					  ?>
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td height="40">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td colspan="3" bgcolor="#f0f0f0" height="40"><span class="font_18"><strong>&nbsp;&nbsp;Merchant Tools</strong></span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_web_ipn.png" class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                           <?
						     $query="SELECT * 
							           FROM ipn 
									  WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         
							 $row = mysql_fetch_array($result, MYSQL_ASSOC);
							 if (mysql_num_rows($result)==0) 
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_web_ipn').modal(); \" class=\"btn btn-primary\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
						     else
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_web_ipn').modal(); $('#txt_ipn_web_adr').val('".$row['web_link']."'); $('#txt_ipn_pass').val('".$row['web_pass']."');\" class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="75%" height="30"><span class="font_16"><strong>Web Instant Payment Notification</strong></span> <span class="font_14">( free )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_web_ipn').modal()" class="blue_16"></a></td>
                          <td width="25%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">Through this option you can be notified via internet whenever funds are received or sent from this address. Every time money is received / sent, a web address specified by you will receive all transaction data.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_additional_data.png" class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     $query="SELECT * 
							           FROM req_data 
									  WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         $row = mysql_fetch_array($result, MYSQL_ASSOC);
							 
							 if ($sealed==true) 
							    $disabled="disabled";
						     else
							    $disabled="";
							 
							 if (mysql_num_rows($result)==0) 
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_aditional').modal(); \" class=\"btn btn-primary ".$disabled."\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
						     else
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_aditional').modal(); 
								                                               $('#txt_req_mes').val('".base64_decode($row['mes'])."');                                                                               
																			   $('#txt_field_1_name').val('".base64_decode($row['field_1_name'])."');
																			   $('#txt_field_1_min').val('".$row['field_1_min']."');
																			   $('#txt_field_1_max').val('".$row['field_1_max']."');
																						   
																			   $('#txt_field_2_name').val('".$row['field_2_name']."');
																			   $('#txt_field_2_min').val('".$row['field_2_min']."');
																			   $('#txt_field_2_max').val('".$row['field_2_max']."'); 
																			   
																			   $('#txt_field_3_name').val('".$row['field_3_name']."');
																			   $('#txt_field_3_min').val('".$row['field_3_min']."');
																			   $('#txt_field_3_max').val('".$row['field_3_max']."'); 
																			   
																			   $('#txt_field_4_name').val('".$row['field_4_name']."');
																			   $('#txt_field_4_min').val('".$row['field_4_min']."');
																			   $('#txt_field_4_max').val('".$row['field_4_max']."'); 
																			   
																			   $('#txt_field_5_name').val('".$row['field_5_name']."');
																			   $('#txt_field_5_min').val('".$row['field_5_min']."');
																			   $('#txt_field_5_max').val('".$row['field_5_max']."');
																			   
																			   $('#txt_field_1_name').attr('disabled', true);
																			   $('#txt_field_1_min').attr('disabled', true);
																			   $('#txt_field_1_max').attr('disabled', true);
																			   
																			   $('#txt_field_2_name').attr('disabled', true);
																			   $('#txt_field_2_min').attr('disabled', true);
																			   $('#txt_field_2_max').attr('disabled', true);
																			   
																			   $('#txt_field_3_name').attr('disabled', true);
																			   $('#txt_field_3_min').attr('disabled', true);
																			   $('#txt_field_3_max').attr('disabled', true);
																			   
																			   $('#txt_field_4_name').attr('disabled', true);
																			   $('#txt_field_4_min').attr('disabled', true);
																			   $('#txt_field_4_max').attr('disabled', true);
																			   
																			   $('#txt_field_5_name').attr('disabled', true);
																			   $('#txt_field_5_min').attr('disabled', true);
																			   $('#txt_field_5_max').attr('disabled', true);
																			   
																			   $('#txt_field_6_name').attr('disabled', true);
																			   $('#txt_field_6_min').attr('disabled', true);
																			   $('#txt_field_6_max').attr('disabled', true);
																			   
																			   $('#txt_req_mes').attr('disabled', true);\" 
																			   
																			   class=\"btn btn-warning ".$disabled."\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="76%" height="30"><span class="font_16"><strong>Request Aditional Data</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a></td>
                          <td width="24%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">By using this option you can request additional information from those who want to send you funds. For example if you set this address to request your email address, then all those wishing to send you funds will need to insert and email address.
                      
                       <?
					       $query="SELECT * 
								     FROM adr_options 
								    WHERE adr='".$adr."' 
									  AND op_type='ID_REQ_DATA'";
								 $result=$this->kern->execute($query);	
	                             $row = mysql_fetch_array($result, MYSQL_ASSOC);
					         
							 if ($this->kern->hasAttr($adr, "ID_REQ_DATA")==true)
						        print "<p><span class='label label-success' style='background-color:#1abc9c'>Expire ~ ".$this->kern->daysFromBlock($row['expires'])." days</span></p>";  
						 
					  ?>
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td align="left">
                  
                  <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_autoresp.png"  class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     $query="SELECT * 
							           FROM autoresp 
									  WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         
							 if (mysql_num_rows($result)==0) 
							    print "<a href=\"autoresp.php?adrID=".$_REQUEST['ID']."\" class=\"btn btn-primary\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
						     else
							    print "<a href=\"autoresp.php?adrID=".$_REQUEST['ID']."\" class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table>
                  
                  </td>
                  <td valign="top">&nbsp;</td>
                  <td height="40" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="77%" height="30"><span class="font_16"><strong>Autoresponders</strong></span> <span class="font_14">( 0.0001 MSK / message )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a><a href="javascript:$('#modal_autoresp').modal()" class="blue_16"></a></td>
                          <td width="23%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">By enabling this option, you can automatically send messages to those who have sent funds to your address. If for example you want those who sent funds to this address to immediately receive a message back, then you can use this option to set the message.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td height="40">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td height="40" colspan="3" bgcolor="#f0f0f0"><span class="font_18"><strong>&nbsp;&nbsp;Advanced Tools</strong></span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_reveal.png"  class="img-responsive" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="javascript:void()" onclick="javascript:$('#modal_reveal').modal()" class="btn btn-primary" style="width:100%"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Reveal</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="78%" height="30"><span class="font_16"><strong>Reveal Private Key</strong></span> <span class="font_14">( 0.0001 MSK / message )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a><a href="javascript:$('#modal_autoresp').modal()" class="blue_16"></a></td>
                          <td width="22%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">An address consists of a visible public key and a private key (password) that is known only by the owner. To control the address you need the private key (the password). Press the green button if you want to visualize the private key of address. Never divulge the private key.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td height="30">&nbsp;</td>
                </tr>
              </table>
              
        
        <?
	}
}
?>