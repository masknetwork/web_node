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
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $adr=$row['adr'];
	    
		
		// Sealed ?
		if ($this->kern->hasAttr($adr, "ID_SEALED")==true)
		   $sealed=true;
		else
		   $sealed=false;	
		?>
        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
           <tr>
                  <td width="20%" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
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
                  <td width="20" valign="top">&nbsp;</td>
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
                  <td height="40" colspan="3" align="left" ><hr></td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_profile.png"  class="img-responsive img-circle"/></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
						  <?
						     $query="SELECT * FROM profiles WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         
							 if (mysqli_num_rows($result)==0)
							 {
						  ?>
                          
                                 <a href="javascript:void()" onclick="javascript:$('#modal_profile').modal()" class="btn btn-primary <? if ($sealed==true) print "disabled"; ?>" style="width:100%">
                                 <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup
                                 </a>
                          
                          <?
							 }
							 else
							 {
								 $row = mysqli_fetch_array($result, MYSQL_ASSOC);
								 
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
                  <td height="40" colspan="3"><hr></td>
                </tr>
                <tr>
                  <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="GIF/seal.png" width="235" height="235" class="img-responsive"/></td>
                      </tr>
                      <tr>
                        <td height="50" align="center">
						<?
						     $query="SELECT * 
							           FROM adr_attr 
									  WHERE adr='".$adr."' 
									    AND attr='ID_RES_REC'"; 
							 $result=$this->kern->execute($query);	
	                         
							 if (mysqli_num_rows($result)==0) 
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_seal').modal(); \" class=\"btn btn-primary\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Restrict</a>";
						     else
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_seal').modal();\" class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Renew</a>";
								
                          ?></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%"><span class="font_16"><strong>Restrict Recipients</strong></span> <span class="font_14">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a></td>
                          <td width="32%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="font_14">If you enable this option, your address will only be able to send funds to certain predefined addresses. Any other transfer will be rejected by network. This is one of the best ways to protect your funds, especially if you have an account on a web node that you do not manage. 
Note that this option can not be canceled once it has been activated and will expire after the specified number of days.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="3" align="left"><hr></td>
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
	                         
							 $row = mysqli_fetch_array($result, MYSQL_ASSOC);
							 if (mysqli_num_rows($result)==0) 
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
                          <td width="78%" height="30"><span class="font_16"><strong>Reveal Private Key</strong></span> <span class="font_14">( free )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a><a href="javascript:$('#modal_autoresp').modal()" class="blue_16"></a></td>
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