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
		
		$query="SELECT * 
		          FROM my_trans AS mt 
			 LEFT JOIN trans_data AS td ON td.trans_hash=mt.hash  
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
                        <td width="10%" align="center"><img src="../GIF/esc_off.png" width="30" height="24" alt=""/></td>
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
                          <td width="15%" align="center" class="simple_green_12">&nbsp;</td>
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
        
        <?
	}
	
	function sendCoins($net_fee_adr, 
	                   $from_adr, 
					   $to_adr, 
					   $amount, 
					   $moneda, 
					   $mes, 
					   $escrower)
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
								par_8='".$otp_new_pass."', 
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
}
?>