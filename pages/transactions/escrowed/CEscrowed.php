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
	    
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load transaction data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		// Signer
		$signer="";
		
		// Finds signer
		if ($this->kern->isMine($row['sender_adr'])==true) $signer=$row['sender_adr'];
		if ($this->kern->isMine($row['rec_adr'])==true) $signer=$row['rec_adr'];
		if ($this->kern->isMine($row['escrower'])==true) $signer=$row['escrower'];
		
		// Signer
		if ($signer=="")
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Type
		if ($type!="ID_RELEASE" && $type!="ID_RETURN")
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Authorize / rejects an escrowed transaction");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ESCROWED_SIGN', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$row['trans_hash']."',
								par_2='".$signer."',
								par_3='".$type."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->rollback();
		   
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
	
	function showEscrowed()
	{
		$query="SELECT * 
		          FROM escrowed 
				 WHERE (sender_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
				    OR rec_adr IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."') 
					OR escrower IN (SELECT adr FROM my_adr WHERE userID='".$_REQUEST['ud']['ID']."'))
			  ORDER BY ID DESC LIMIT 0,30";
		$result=$this->kern->execute($query);	
	 
	  
		?>
        
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="58%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="21%" align="center"><span class="inset_maro_14">Amount</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Operations</span></td>
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
                          <td width="59%" align="left" class="simple_maro_10">
                          
                          <?
						      // Escrower my address
						      if ($this->kern->isMine($row['escrower'])==true)
							     print "Address <strong>".$this->template->formatAdr($row['sender_adr'])."</strong> choosed one of your addresses <strong>(".$this->template->formatAdr($row['escrower'])."</strong>) as escrower for sending <strong>".$row['amount']." ".$row['cur']."</strong> to <strong>".$this->template->formatAdr($row['rec_adr'])."</strong>. You can <strong>relese</strong> the funds to seller or <strong>return</strong> the funds to sender address.";
							  else  if ($this->kern->isMine($row['sender_adr'])==true)
								 "You have sent <strong>".$row['amount']." ".$row['cur']."</strong> to <strong>".$this->template->formatAdr($row['rec_adr'])."</strong> using an escrower (<strong>(".$this->template->formatAdr($row['escrower'])."</strong>). You can release the funds anytime you want.";
							  
							   else  if ($this->kern->isMine($row['rec_adr'])==true)
							     "<strong>".$this->template->formatAdr($row['sender_adr'])."</strong> sent you <strong>".$row['amount']." ".$row['cur']."</strong> to address <strong>".$this->template->formatAdr($row['rec_adr'])."</strong> using an escrower (<strong>(".$this->template->formatAdr($row['escrower'])."</strong>). You can refund the sender anytime you want."
						  ?>
                          
                          </td>
                          <td width="23%" align="center" class="simple_green_14"><strong><? print round($row['amount'], 4)." ".$row['cur']; ?></strong></td>
                          <td width="18%" align="center" class="simple_maro_12">
                         
                          <?
						      if ($this->kern->isMine($row['escrower'])==true)
							  {
						  ?>
                          
                          <table width="80" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              
                              <td align="center"><a href="javascript:void(0)" onclick="$('#sign_modal').modal(); $('#act').val('ID_RELEASE'); $('#signer').val('<? print $row['escrower']; ?>')" class="btn btn-success btn-sm" title="Release Funds to Seller" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-ok"></span></a></td>
                              
                              <td align="center"><a href="javascript:void(0)" onclick="$('#sign_modal').modal(); $('#act').val('ID_RETURN'); $('#signer').val('<? print $row['escrower']; ?>')" class="btn btn-danger btn-sm" title="Return Funds to Buyer" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-remove"></span></a></td>
                              
                            </tr>
                          </tbody>
                        </table>
                        
                        <?
							  }
							  else  if ($this->kern->isMine($row['sender_adr'])==true)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#act').val('ID_RELEASE'); $('#signer').val('".$row['sender_adr']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;Release</a>";
							  
							  else  if ($this->kern->isMine($row['rec_adr'])==true)
							     print "<a href='javascript:void(0)' onclick=\"$('#sign_modal').modal(); $('#act').val('ID_RETURN'); $('#signer').val('".$row['rec_adr']."')\" class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>&nbsp;Release</a>";
						?>
                        
                        </td></tr><tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
		$this->template->showModalHeader("sign_modal", "Sign Escrowed Transaction", "act", "", "signer", "");
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