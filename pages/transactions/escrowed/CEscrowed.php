<?
  class CEscrowed
  {
	function CEscrowed($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function signTransaction($net_fee_adr, $transID, $type="ID_RELEASE")
	{
		// Transaction ID exist
		$query="SELECT * 
		          FROM escrowed 
				 WHERE ID='".$transID."'";
		$result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Load transaction data
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    
		// Signer
		$signer="";
		
		// Finds signer
		if ($this->kern->isMine($row['sender_adr'])==true) $signer=$row['sender_adr'];
		if ($this->kern->isMine($row['rec_adr'])==true) $signer=$row['rec_adr'];
		if ($this->kern->isMine($row['escrower'])==true) $signer=$row['escrower'];
		
		// Signer
		if ($signer=="")
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		// Type
		if ($type!="ID_RELEASE" && $type!="ID_RETURN")
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Authorize / rejects an escrowed transaction", 550);
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ESCROWED_SIGN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$row['trans_hash']."',
								par_2='".$type."',
								target_adr='".$signer."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
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
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   }
	}
	
	function showEscrowed()
	{
		$query="SELECT * 
		          FROM escrowed 
				 WHERE (sender_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
				    OR rec_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					OR escrower IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."'))
			  ORDER BY ID DESC LIMIT 0,30";
			  
		$result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
			print "<span class='font_12' style='color:#990000'>No escrowed transactions found</span>";
			return false;
		}
	  
		?>
        
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
             
                      <?
					     while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                        
                          <tr>
                          <td width="59%" align="left" class="font_12">
                          
                          <?
						      // Escrower my address
						      if ($this->kern->isMine($row['escrower'])==true)
							     print "Address <strong>".$this->template->formatAdr($row['sender_adr'])."</strong> choosed one of your addresses <strong>(".$this->template->formatAdr($row['escrower'])."</strong>) as escrower for sending <strong>".$row['amount']." ".$row['cur']."</strong> to <strong>".$this->template->formatAdr($row['rec_adr'])."</strong>. You can <strong>relese</strong> the funds to seller or <strong>return</strong> the funds to sender address.";
							 
							  else  if ($this->kern->isMine($row['sender_adr'])==true)
								 print "You have sent <strong>".$row['amount']." ".$row['cur']."</strong> to <strong>".$this->template->formatAdr($row['rec_adr'])."</strong> using an escrower (<strong>".$this->template->formatAdr($row['escrower'])."</strong>). You can release the funds anytime you want.";
							  
							   else  if ($this->kern->isMine($row['rec_adr'])==true)
							     print "<strong>".$this->template->formatAdr($row['sender_adr'])."</strong> sent you <strong>".$row['amount']." ".$row['cur']."</strong> to address <strong>".$this->template->formatAdr($row['rec_adr'])."</strong> using an escrower (<strong>(".$this->template->formatAdr($row['escrower'])."</strong>). You can refund the sender anytime you want."
						  ?>
                          
                          </td>
                          <td width="23%" align="center" class="simple_green_14"><strong><? print round($row['amount'], 4)." ".$row['cur']; ?></strong></td>
                          <td width="18%" align="center" class="simple_maro_12">
                         
                          <?
						      if ($this->kern->isMine($row['escrower'])==true)
							  {
						  ?>
                          
                          <table width="80" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              
                              <td align="center"><a href="javascript:void(0)" onclick="$('#sign_modal').modal(); $('#esc_act').val('ID_RELEASE'); $('#transID').val('<? print $row['ID']; ?>')" class="btn btn-primary btn-sm" title="Release Funds to Seller" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-ok"></span></a></td>
                              <td>&nbsp;&nbsp;</td>
                              <td align="center"><a href="javascript:void(0)" onclick="$('#sign_modal').modal(); $('#esc_act').val('ID_RETURN'); $('#transID').val('<? print $row['ID']; ?>')" class="btn btn-danger btn-sm" title="Return Funds to Buyer" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-remove"></span></a></td>
                              
                            </tr>
                        </table>
                        
                        <?
							  }
							  else  if ($this->kern->isMine($row['sender_adr'])==true)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#esc_act').val('ID_RELEASE'); $('#transID').val('".$row['ID']."')\" class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;Release</a>";
							  
							  else  if ($this->kern->isMine($row['rec_adr'])==true)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#esc_act').val('ID_RETURN'); $('#transID').val('".$row['ID']."')\" class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;Release</a>";
						?>
                        
                        </td></tr><tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                    <?
						 }
					?>
                            
                    
                  </table>
                  
           
        <?
	}
	
	function showSignModal()
	{
		$this->template->showModalHeader("sign_modal", "Sign Escrowed Transaction", "esc_act", "", "transID", "");
		?>
        
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
                <td align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("esc_fee_adr"); ?></td>
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