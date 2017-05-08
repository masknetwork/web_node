<?
class CSeal
{
   function CSeal($db, $template)
   {
	   $this->kern=$db;
	   $this->template=$template;
   }
   
   function sealAdr($net_fee_adr, $adr, $rec_1, $rec_2, $rec_3, $days)
	{
		// Fee Address
		if ($this->kern->adrValid($net_fee_adr)==false || 
	       $this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid network fee address", "90%");
			return false;
		}
		
		// My address
	    if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid entry data", "90%");
			return false;
		}
		
		// Fee address is security options free
	    if ($this->kern->canSpend($net_fee_adr)==false)
		{
			$this->template->showErr("Network fee address can't spend funds. Choose another address", "90%");
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Invalid days (minimum 1 day)", "90%");
			return false;
		}
	   
	   // Funds
	   if ($this->kern->getBalance($net_fee_adr)<$days*0.0001)
	   {
		   $this->template->showErr("Insufficient funds to execute the transaction", "90%");
		   return false;
	   }
	   
	   // From domains
	   $rec_1=$this->kern->adrFromDomain($rec_1);
	   $rec_2=$this->kern->adrFromDomain($rec_2);
	   $rec_3=$this->kern->adrFromDomain($rec_3);
	   
	   // All blank
	   if ($rec_1=="" && 
	       $rec_2=="" && 
		   $rec_3=="")
	   {
		   $this->template->showErr("All recipients are blank", "90%");
		   return false;
	   }
	   
	   // Recipient 1
	   if ($rec_1!="")
	     if (!$this->kern->adrValid($rec_1))
	     {
			$this->template->showErr("Invalid recipient 1", "90%");
			return false;
		}
		
	   // Recipient 2
	   if ($rec_2!="")
	     if (!$this->kern->adrValid($rec_2))
	     {
			$this->template->showErr("Invalid recipient 2", "90%");
			return false;
		}
		
	   // Recipient 3
	   if ($rec_3!="")
	     if (!$this->kern->adrValid($rec_3))
	     {
			$this->template->showErr("Invalid recipient 3", "90%");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Seal an address");
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ADD_ATTR', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='ID_RES_REC',
								par_2='".$rec_1."',
								par_3='".$rec_2."',
								par_4='".$rec_3."',
								par_5='0',
								par_6='0',
								par_7='0',
								par_8='0',
								par_9='0',
								par_10='0',
								days='".$days."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", "90%");
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
	
   function showModal($attach_to="")
	{
		$this->template->showModalHeader("modal_seal", "Seal Address", "act", "seal_adr", "adr", "");
		?>
        
         <table width=""90%"" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="240" align="left" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="GIF/seal.png" width="150" height="150" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                <? $this->template->shownetFeePanel(0.036, "seal"); ?>
                </td>
              </tr>
            </table></td>
            <td width="290" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td><? $this->template->showMyAdrDD("dd_net_fee_adr_seal"); ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><strong class="font_14">Recipient 1</strong></td>
              </tr>
              <tr>
                <td><input class="form-control" name="txt_rec_1" id="txt_rec_1" style="width:100%" placeholder="Recipient 1"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><strong class="font_14">Recipient 2</strong></td>
              </tr>
              <tr>
                <td><input class="form-control" name="txt_rec_2" id="txt_rec_2" style="width:100%" placeholder="Recipient 2"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><strong class="font_14">Recipient 3</strong></td>
              </tr>
              <tr>
                <td><input class="form-control" name="txt_rec_3" id="txt_rec_3" style="width:100%" placeholder="Recipient 3"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" valign="top"><span class="font_14"><strong>Days</strong></span></td>
              </tr>
              <tr>
                <td>
                <input type="number" min="10" step="1" name="txt_days" class="form-control" id="txt_days" style="width:100px" value="365" maxlength="6"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		   linkToNetFee("txt_days_seal", "seal_net_fee_panel_val", 0.0365);
		</script>
        
        <?
		$this->template->showModalFooter();
	}
}
?>