<?
  class CMultisig
  {
	function CMultisig($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function sign($net_fee_adr, $trans_hash, $signer)
	{
		// Type
		if ($this->kern->adrValid($signer)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// My address
		if ($this->kern->isMine($signer)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load transaction
		$query="SELECT * 
		          FROM multisig 
				 WHERE trans_hash='".$trans_hash."'";
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load data
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Authorized signer ?
		if ($row['signer_1']!=$signer && 
		    $row['signer_2']!=$signer && 
			$row['signer_3']!=$signer && 
			$row['signer_4']!=$signer && 
			$row['signer_5']!=$signer)
	    {
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Already signed ?
		if ($row['signer_1']==$signer)
		  if (strlen($row['sign_1'])>10)
		{
			$this->template->showErr("You have already signed this transaction");
			return false;
		}  
		
		// Already signed ?
		if ($row['signer_2']==$signer)
		  if (strlen($row['sign_2'])>10)
		{
			$this->template->showErr("You have already signed this transaction");
			return false;
		}
		
		// Already signed ?
		if ($row['signer_3']==$signer)
		  if (strlen($row['sign_3'])>10)
		{
			$this->template->showErr("You have already signed this transaction");
			return false;
		}    
		
		// Already signed ?
		if ($row['signer_4']==$signer)
		  if (strlen($row['sign_4'])>10)
		{
			$this->template->showErr("You have already signed this transaction");
			return false;
		}
		
		// Already signed ?
		if ($row['signer_5']==$signer)
		  if (strlen($row['sign_5'])>10)
		{
			$this->template->showErr("You have already signed this transaction");
			return false;
		}      
		
		try
	    {
		   // Begin
		   $this->kern->begin();
		   
		   // Track ID
		   $tID=$this->kern->getTrackID();

           // Action
           $this->kern->newAct("Sign a multisig transaction", $tID);
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_MULTISIG_SIGN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$row['trans_hash']."',
								par_2='".$signer."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query); 
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Signed.", 550);

		   return true;
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
	
	
	function showMultisig()
	{
		$query="SELECT * 
		          FROM multisig 
				 WHERE (sender_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
				    OR rec_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					OR signer_1 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					OR signer_2 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					OR signer_3 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					OR signer_4 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."')
					OR signer_5 IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."'))
			  ORDER BY ID DESC LIMIT 0,30";
		$result=$this->kern->execute($query);	
	 
	  
		?>
        
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="52%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="12%" align="center"><span class="inset_maro_14">Signed</span></td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Sign</span></td>
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
							 // Number of signers
							 $signed=0;
							 if (strlen($row['sign_1'])>10) $signed++; 
							 if (strlen($row['sign_2'])>10) $signed++; 
							 if (strlen($row['sign_3'])>10) $signed++; 
							 if (strlen($row['sign_4'])>10) $signed++; 
							 if (strlen($row['sign_5'])>10) $signed++; 
					  ?>
                        
                          <tr>
                          <td width="52%" align="left" class="simple_maro_10">
                          
                          <?
						      // Escrower my address
						      if ($this->kern->isMine($row['sender_adr'])==true)
							     print "You have sent <strong>(".$row['amount']." ".$row['cur']."</strong>) to <strong>".$this->template->formatAdr($row['rec_adr'])."</strong> from a multisignature protected address. The signers were notified.";
							 
							  else  if ($this->kern->isMine($row['rec_adr'])==true)
								 "Multisignature protected address <strong>".$this->template->formatAdr($row['sender_adr'])."</strong> has sent you ".$row['amount']." ".$row['cur'].". The network is waiting for requested signatures. A minimum of ".$row['min']." signatures are required.";
							  
							   else  "<strong>".$this->template->formatAdr($row['sender_adr'])."</strong> wants to send <strong>".$row['amount']." ".$row['cur']."</strong> to address <strong>".$this->template->formatAdr($row['rec_adr'])."</strong>. The sender is a multisig address and one of your addresses is an authorized signer."
						  ?>
                          
                          </td>
                          <td width="14%" align="center" class="simple_red_14"><strong><? print $signed." / ".$row['required']; ?></strong></td>
                          <td width="18%" align="center" class="simple_green_14"><strong><? print round($row['amount'], 4)." ".$row['cur']; ?></strong></td>
                          <td width="16%" align="center" class="simple_maro_12">
                         
                          <?
						      if ($this->kern->isMine($row['signer_1'])==true && strlen($row['sign_1'])<10)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#trans_hash').val('".$row['trans_hash']."'); $('#signer').val('".$row['signer_1']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Sign</a>";
								 
						      else if ($this->kern->isMine($row['signer_2'])==true && strlen($row['sign_2'])<10)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#trans_hash').val('".$row['trans_hash']."'); $('#signer').val('".$row['signer_2']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Sign</a>";
								 
							  else if ($this->kern->isMine($row['signer_3'])==true && strlen($row['sign_3'])<10)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#trans_hash').val('".$row['trans_hash']."'); $('#signer').val('".$row['signer_3']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Sign</a>";
								 
							  else if ($this->kern->isMine($row['signer_4'])==true && strlen($row['sign_4'])<10)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#trans_hash').val('".$row['trans_hash']."'); $('#signer').val('".$row['signer_4']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Sign</a>";
								 
							  else if ($this->kern->isMine($row['signer_5'])==true && strlen($row['sign_5'])<10)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#trans_hash').val('".$row['trans_hash']."'); $('#signer').val('".$row['signer_5']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;&nbsp;Sign</a>";
						?>
                        
                        </td></tr><tr>
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
	
	function showSignModal()
	{
		$this->template->showModalHeader("sign_modal", "Sign Transaction", "act", "sign", "signer", "");
		?>
          
          <input id="trans_hash" name="trans_hash" value="" type="hidden">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="192" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top"><img src="../GIF/sign.png" width="176" height="235" alt=""/></td>
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
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
      
        <?
		$this->template->showModalFooter();
	}
  }
?>