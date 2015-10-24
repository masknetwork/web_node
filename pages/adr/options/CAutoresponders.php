<?
class CAutoresponders
{
	function CAutoresponders($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function delete($ID)
	{
		// Check ID
		$query="SELECT * 
		          FROM autoresp 
				 WHERE userID='".$_REQUEST['ud']['ID']."' 
				   AND ID='".$ID."'";
	     $result=$this->kern->execute($query);	
	     
		 if (mysql_num_rows($result)==0)
	     {
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		 }
		 
		 try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Removes an autoresponder ");
		
		   // Insert to stack
		   $query="DELETE FROM autoresp 
		                 WHERE ID='".$ID."'";
		   $this->kern->execute($query);	 
		 
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been successfully executed", 550);

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
	
	function updateSettings($net_fee_adr, $adr, $subj, $mes, $when)
	{
		// Address
		if ($this->kern->adrValid($net_fee_adr)==false ||
			$this->kern->isMine($net_fee_adr)==false ||
			$this->kern->adrValid($adr)==false ||
			$this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
	
		// Subject
		$subj=base64_decode($subj);
		if (strlen($subj)>100)
		{
			 $this->template->showErr("Invalid subject length", 550);
			 return false;
		}
		
		// Message
		$mes=base64_decode($mes);
		if (strlen($mes)>250)
		{
			 $this->template->showErr("Invalid mesasage length", 550);
			 return false;
		}
		
		// When
		if ($when!="ID_TRANS" && $when!="ID_MES")
		{
			$this->template->showErr("Invalid entry data", 550);
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Set / Update an autoresponder ");
		
		   // Insert to stack
		   $query="SELECT * 
		             FROM autoresp 
					WHERE adr='".$adr."' 
					  AND send_when='".$when."'"; 
		   $result=$this->kern->execute($query);	
		   
		   if (mysql_num_rows($result)==0)
		     $query="INSERT INTO autoresp 
			                 SET userID='".$_REQUEST['ud']['ID']."', 
							     net_fee_adr='".$adr."',
								 adr='".$adr."', 
								 subject='".base64_encode($subj)."', 
								 mes='".base64_encode($mes)."', 
								 send_when='".$when."', 
								 tstamp='".time()."'";
		   else
		    $query="UPDATE autoresp 
			           SET net_fee_adr='".$net_fee_adr."',
					       subject='".base64_encode($subj)."', 
					       mes='".base64_encode($mes)."' 
					 WHERE adr='".$adr."' 
					   AND send_when='".$when."'";
		
		   $this->kern->execute($query);	
		 
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been successfully executed", 550);

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
	
	function showAddButton()
	{
		?>
        
              <br>
              <table width="550" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                    <td width="437">&nbsp;</td>
                    <td width="113"><a href="javascript:void(0)" onclick="javascript:$('#modal_autoresp').modal(); 
                                                                                    $('#tab_when').css('display', 'block');
                                                                                    $('#txt_subj').val('');                                                                                    $('#txt_mes').val('')" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;Add Autoresponder</a></td>
                  </tr>
                </tbody>
              </table>
              <br>
        
        <?
	}
	
	function showAutoresponders($adrID)
	{
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="64%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Edit</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Delete</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     $query="SELECT * 
						          FROM my_adr 
								 WHERE ID='".$adrID."'";
						 $result=$this->kern->execute($query);	
	                     $row = mysql_fetch_array($result, MYSQL_ASSOC);
	                     $adr=$row['adr'];
						 
					     $query="SELECT * 
						           FROM autoresp 
								  WHERE adr='".$adr."' 
							   ORDER BY ID DESC 
							      LIMIT 0,20";
						  $result=$this->kern->execute($query);	
	                      
						  while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						  {
					  ?>
                      
                            <tr>
                            <td width="65%" align="left">
                            <span class="simple_maro_12"><strong><? print base64_decode($row['subject']); ?></strong></span>
                            &nbsp;&nbsp;<span class="simple_maro_10">(<? if ($row['send_when']=="ID_TRANS") print "on new transaction"; else print "on received message"; ?>)</span>
                            <br><span class="simple_maro_10"><? print base64_decode($row['mes']); ?></span></td>
                            <td width="17%" align="center" class="simple_green_12">
                            <a href="javascript:void(0);" onclick="$('#modal_autoresp').modal(); 
                                                                   $('#tab_when').css('display', 'none'); 
                                                                   $('#txt_subj').val('<? print base64_decode($row['subject']); ?>');                
                                                                   $('#txt_mes').val('<? print base64_decode($row['mes']); ?>');" 
                            class="btn btn-warning btn-sm" style="width:80px">
                            <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Edit</a></td>
                            <td width="18%" align="center" class="simple_maro_12">
                            <a href="autoresp.php?act=delete&adrID=<? print $adrID; ?>&ID=<? print $row['ID']; ?>" class="btn btn-danger btn-sm" style="width:80px">
                            <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete</a></td>
                            </tr>
                            <tr>
                            <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
	
	function showModal()
	{
		$this->template->showModalHeader("modal_autoresp", "Autoresponders", "act", "autoresp", "ID", $_REQUEST['ID']);
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_autoresp.png" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel("0.0001", "froze"); ?></td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_net_fee", 340); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Subject</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" id="txt_subj" name="txt_subj" style="width:350px"></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong> Message</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <textarea id="txt_mes" name="txt_mes" placeholder="Short message that will be relayed to sender (25-250 characters)" rows="4" class="form-control"></textarea>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab_when" name="tab_when">
                  <tr>
                    <td height="30" colspan="2" align="left" valign="top"><strong>Data Format</strong></td>
                    <td height="20" align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="10%" height="20" align="center"><input name="when" type="radio" id="when" value="ID_TRANS" checked="checked" /></td>
                    <td width="78%" height="30" align="left" class="simple_blue_14">When i receive a new transaction</td>
                    <td width="12%" height="20" align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" align="center"><input type="radio" name="when" id="when" value="ID_MES" /></td>
                    <td height="30" align="left" class="simple_blue_14">When i receive a message</td>
                    <td height="20" align="center">&nbsp;</td>
                  </tr>
                </table>
                
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
         <script>
		   $('#form_modal_autoresp').submit(
		   function() 
		   { 
		     $('#txt_subj').val(btoa($('#txt_subj').val())); 
		     $('#txt_mes').val(btoa($('#txt_mes').val())); 
		   });
		</script>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>