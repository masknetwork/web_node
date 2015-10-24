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
		
		?>
             
             <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">&nbsp;
                  
                
             <table width="500" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="30" colspan="2" bgcolor="#fff6e5" class="simple_red_16">&nbsp;&nbsp;General Options</td>
                  </tr>
                <tr>
                  <td width="26%">&nbsp;</td>
                  <td width="74%">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td align="center"><img src="./GIF/adr_opt_interest.png" width="80" /></td>
                      </tr>
                      <tr>
                        <td height="40" align="center"><a href="#" onclick="javascript:$('#modal_interest').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Setup</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td height="50" align="left" class="bold_maro_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="73%" height="30" bgcolor="#fff6e3">
                          <a href="javascript:$('#modal_interest').modal()" class="red_14">&nbsp;&nbsp;Receive Daily Interest</a>
                          <span class="simple_red_12">( not receiving )</span></td>
                          <td width="27%" bgcolor="#fff6e3">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="0" align="left" class="simple_gri_12">MaskCoin este distribuit in mare parte prin dobanda. Dobanda este stabilita in functie de cate monezi sunt in circulatie si scade in timp. Dobanda actuala este de 55%. pe an. Orice adresa care detine minim 5 MSK poate primii zilnic aceasta dobanda. Aici poti seta aceasta adresa sa primeasca dobanda.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr align="left">
                  <td height="40" colspan="2" background="../../template/template/GIF/lc.png">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_names.png" width="100" /></td>
                        </tr>
                        <tr>
                          <td height="40" align="center"><a href="#" onclick="javascript:$('#modal_new_domain').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Rent</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="75%" height="30">
                          <a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Rent Adress Name</a> <span class="simple_red_12">( 0.0001 MSK / day )</span></td>
                          <td width="25%">&nbsp;</td>
                        </tr>
                      </table></td>
                      </tr>
                    <tr>
                      <td height="0" align="left" class="simple_gri_12">Inchirierea unui nume pentru adresa ta este un mod excelent de a simplifica primirea de bani. In loc sa fii platit la o adresa greu de inteles formata din zeci de cifre / litere, poti primii bani intr-o adresa de genul &quot;maria&quot;. Poti inchiria un numar nelimitat de nume. Poti deasemenea dispune de multiple nume pentru o singura adresa.</td>
                      </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_share.png" width="100"  /></td>
                        </tr>
                        <tr>
                          <td height="40" align="center"><a href="#" onclick="javascript:$('#modal_share').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;Share</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Share This  Adress </a> <span class="simple_red_12">( 0.0001 MSK  )</span></td>
                          <td width="32%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">O adresa este formata dintr-o cheie publica si una privata. Daca detii cheia privata a unei adrese, poti cheltui fondurile asociate cu adresa. Folosind aceasta optiune poti trimite cheia privata a acestei adrese unei alte persoane (adresa). Dupa ce primeste cheia privata, destinatarul va putea folosit aceasta adresa exact ca tine.</td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_profile.png" width="90"  /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_profile').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Profile Setup</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a></td>
                          <td width="32%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Poti asocia acestei adrese un profilunde sa furnizezi mai multe informatii cum ar fi o adresa de email, un website, un avatar, etc. Toate profilele sunt publice. Costul uni profil este de 0.0001 MSK / zi.</td>
                    </tr>
                  </table></td>
                  </tr>
                <tr align="left">
                  <td height="40" colspan="2">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td height="30" colspan="2" bgcolor="#fff6e5"><span class="simple_red_16">&nbsp;&nbsp;Security Options</span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_froze.png" width="91" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_froze').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Froze Address</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Poti ingheta aceasta adresa pentru o perioada. Dintr-o adresa inghetata nu se pot cheltui fonduri. Aceasta optiune nu poate fi suspendata in perioada cat este activa. Recomandam ca aceasta optiune sa fie utilizata prident. Daca un atacator preia controlul adresei, nu va putea cheltui fonduri pe perioada cat optiunea este activa.</td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                  </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_seal.png" width="80" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_seal').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Seal Address</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Dintr-o adresa sigilata se pot cheltui fonduri dar nu poti sa-i atasezi nici un fel de optiune. Deasemenea nu i se pot atasa nume si nici nu poate fi impartita cu o alta adresa. Daca un atacator preia controlul adresei, va putea cheltui fonduri dar nu va putea executa alte operatii. </td>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_restrict.png" height="90" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_restrict').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Restrict Recipients</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_restrict').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Prin activarea acestei optiuni vei putea trimite fonduri din adresa doar catre un grup de maxim patru alte adrese. Daca un atacator preia controlul adresei, nu va putea trimite fonduri decat catre un grup sepcificat de adrese. Nu poti atasa destinatari noi sau sterge actuali destinatari pe perioada cat optiunea este activa.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td><img src="./GIF/adr_opt_multisig.png" width="90" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						    if ($this->kern->hasAttr($adr, "ID_MULTISIG")==false)
							{
						  ?>
                          
                          <a href="javascript:void(0)" onclick="javascript:$('#modal_multisig').modal()" class="btn btn-success" style="width:100px">                          <span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup
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
                                                                
                                                                " class="btn btn-warning" style="width:100px">                           
                           <span class="glyphicon glyphicon-refresh" ></span>&nbsp;&nbsp;Renew
                           </a>   
                          
                          <?
							 }
						  ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Multiple Signatures</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_multisig').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">O adresa care are atasata aceasta optiune va avea nevoie de multiple semnaturi pentru a putea cheltui fonduri. Orice transfer din aceasta adresa, va trebui sa fie semnata de adresele autorizate. Daca un atacator preia controlul acestei adrese, va avea nevoie de semnatura celor autorizati pentru a putea  cheltui fonduri.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_otp.png" height="90" /></td>
                      </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_otp').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Setup</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="68%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;One Time Password</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a></td>
                          <td width="32%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Daca activezi aceasta optiune, inainte de a trimite fonduri va trebui sa introduci o parola noua. Dupa ce trimiti bani dintr-o adresa protejata cu aceasta optiune, o noua parola va fi generata. In cazul in care un atacator preia controlul acestei adrese, va trebui sa furnizeze si o parola pentru a utea cheltui fonduri.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td height="40">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td height="30" colspan="2" bgcolor="#fff6e5"><span class="inset_maro_16">&nbsp;&nbsp;Merchant Tools</span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_web_ipn.png" width="90" /></td>
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
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_web_ipn').modal(); \" class=\"btn btn-success\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
						     else
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_web_ipn').modal(); $('#txt_ipn_web_adr').val('".$row['web_link']."'); $('#txt_ipn_pass').val('".$row['web_pass']."');\" class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="75%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Web Instant Payment Notification</a> <span class="simple_red_12">( free )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_web_ipn').modal()" class="blue_16"></a></td>
                          <td width="25%" height="30">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Prin aceasta optiune poti fi notificat prin internet de fiecare data cand sunt primite sau trimise fonduri din aceasta adresa. De fiecare data cand se primesc / trimit bani, o adresa web specificata de tine va primii toate datele tranzactiei.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_additional_data.png" width="90" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     $query="SELECT * 
							           FROM req_data 
									  WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         $row = mysql_fetch_array($result, MYSQL_ASSOC);
							 
							 if (mysql_num_rows($result)==0) 
							    print "<a href=\"javascript:void(0)\" onclick=\"javascript:$('#modal_aditional').modal(); \" class=\"btn btn-success\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
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
																			   $('#txt_field_5_max').val('".$row['field_5_max']."');\" 
																			   
																			   class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="76%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Request Aditional Data</a> <span class="simple_red_12">( 0.0001 MSK / day )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a></td>
                          <td width="24%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Prin aceasta optiune poti cere date suplimentare de la cei care doresc sa-ti trimita fonduri. De exemplu daca setezi aceasta adresa sa ceara adresa de email, atunci toti cei care doresc sa trimita fonduri vor trebui sa-si introduca si adresa de email.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">
                  
                  <table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_autoresp.png" width="90"/></td>
                        </tr>
                        <tr>
                          <td height="50" align="center">
                          
                          <?
						     $query="SELECT * 
							           FROM autoresp 
									  WHERE adr='".$adr."'";
							 $result=$this->kern->execute($query);	
	                         
							 if (mysql_num_rows($result)==0) 
							    print "<a href=\"autoresp.php?adrID=".$_REQUEST['ID']."\" class=\"btn btn-success\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Setup</a>";
						     else
							    print "<a href=\"autoresp.php?adrID=".$_REQUEST['ID']."\" class=\"btn btn-warning\" style=\"width:100px\"><span class=\"glyphicon glyphicon-cog\" ></span>&nbsp;&nbsp;Manage</a>";
								
                          ?>
                          
                          </td>
                        </tr>
                    </tbody>
                  </table>
                  
                  </td>
                  <td height="40" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="77%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Autoresponders</a> <span class="simple_red_12">( 0.0001 MSK / message )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a><a href="javascript:$('#modal_autoresp').modal()" class="blue_16"></a></td>
                          <td width="23%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">Prin activarea acestei optiuni, poti trimite automat mesaje celor care au trimis fonduri catre adresa ta. Daca de exemplu doresti ca toti cei care au trimis fonduri catre aceasta adresa, sa primeasca imediat un mesaj inapoi, atunci poti folosi aceasta optiune pentru a seta mesajul.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td height="40">&nbsp;</td>
                </tr>
                <tr align="left">
                  <td height="30" colspan="2" bgcolor="#fff6e5"><span class="inset_maro_16">&nbsp;&nbsp;Advanced Tools</span></td>
                  </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="left"><table width="90" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                          <td align="center"><img src="./GIF/adr_opt_reveal.png" width="74" height="80" /></td>
                        </tr>
                        <tr>
                          <td height="50" align="center"><a href="#" onclick="javascript:$('#modal_reveal').modal()" class="btn btn-success" style="width:100px"><span class="glyphicon glyphicon-cog" ></span>&nbsp;&nbsp;Reveal</a></td>
                        </tr>
                    </tbody>
                  </table></td>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="left" bgcolor="#fff6e3" class="inset_gri_16"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="78%" height="30"><a href="javascript:$('#modal_new_domain').modal()" class="red_14">&nbsp;&nbsp;Reveal Private Key</a> <span class="simple_red_12">( 0.0001 MSK / message )</span><a href="javascript:$('#modal_profile').modal()" class="blue_16"></a><a href="javascript:$('#modal_froze').modal()" class="blue_16"></a><a href="javascript:$('#modal_seal').modal()" class="blue_16"></a><a href="javascript:$('#modal_otp').modal()" class="blue_16"></a><a href="javascript:$('#modal_aditional').modal()" class="blue_16"></a><a href="javascript:$('#modal_autoresp').modal()" class="blue_16"></a></td>
                          <td width="22%">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" class="simple_gri_12">O adresa este formata dintr-o cheie publica vizibila de toata lumea si o cheie privata (parola) care este stiuta doar de proprietar. Pentru a putea controla o adresa ai nevoie de chaia privata. Apasa butonul verde daca doresti sa vizualizezi cheia privata a acestei adrese. Niciodata sa nu predai cheia privata unei persoane straine.</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="40" colspan="2" align="left" background="../../template/template/GIF/lc.png">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;</td>
                  <td height="30">&nbsp;</td>
                </tr>
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