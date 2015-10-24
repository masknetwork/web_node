<?
class CAditionalData
{
	function CAditionalData($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function checkField($field_name, $min, $max)
	{
		// Field name
		if ($field_name!="")
		{
			// Decode
			$field_name=base64_decode($field_name);
			
			// Feild name length
			if (strlen($field_name)<3 || strlen($field_name)>50) return false;
			
			// Field min
			if ($min<1 || $min>250) return false;
			
			// Field max
			if ($max<1 || $max>250) return false;
		}
		
		// Return 
		return true;
	}
	
	function setup($net_fee_adr, 
	               $adr,
	               $mes, 
				   $field_1_name, $field_1_min, $field_1_max,
				   $field_2_name, $field_2_min, $field_2_max,
				   $field_3_name, $field_3_min, $field_3_max,
				   $field_4_name, $field_4_min, $field_4_max,
				   $field_5_name, $field_5_min, $field_5_max,
				   $field_6_name, $field_6_min, $field_6_max,
				   $days)
	{
		// Addresses valid
		if ($this->kern->adrExist($net_fee_adr)==false || 
		    $this->kern->adrValid($adr)==false ||
			$this->kern->isMine($net_fee_adr)==false || 
			$this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Funds
		if ($this->kern->getBalance($net_fee_adr)<0.0001*$days)
		{
			 $this->template->showErr("Insufficient funds", 550);
			 return false;
		}
		
		// Message
		if ($mes!="")
		{
			$mes=base64_decode($mes);
			
		    if (strlen($mes)<5 || strlen($mes)>250)
			{
				 $this->template->showErr("Invalid message length", 550);
			     return false;
			}
		}
		
		// Missing fields
		if ($field_2_name!="")
		{  
		   if ($field_1_name=="")
		     {
				 $this->template->showErr("Missing middle fields", 550);
			     return false;
			 }
		}
		
		if ($field_3_name!="")
		{  
		   if ($field_1_name=="" || 
		       $field_2_name=="")
		     {
				 $this->template->showErr("Missing middle fields", 550);
			     return false;
			 }
		}
		
		if ($field_4_name!="")
		{  
		   if ($field_1_name=="" || 
		       $field_2_name=="" || 
			   $field_3_name=="")
		     {
				 $this->template->showErr("Missing middle fields", 550);
			     return false;
			 }
		}
		
		if ($field_5_name!="")
		{  
		   if ($field_1_name=="" || 
		       $field_2_name=="" || 
			   $field_3_name=="" || 
			   $field_4_name=="")
		     {
				 $this->template->showErr("Missing middle fields", 550);
			     return false;
			 }
		}
		
		if ($field_6_name!="")
		{  
		   if ($field_1_name=="" || 
		       $field_2_name=="" || 
			   $field_3_name=="" || 
			   $field_4_name=="" || 
			   $field_4_name=="")
		     {
				 $this->template->showErr("Missing middle fields", 550);
			     return false;
			 }
		}
	
		
		// Field 1
		if ($this->checkField($field_1_name, $field_1_min, $field_1_max)==false)
		{
		     $this->template->showErr("Invalid field 1", 550);
			 return false;
	    }
		
		// Field 2
		if ($this->checkField($field_2_name, $field_2_min, $field_2_max)==false)
		{
		     $this->template->showErr("Invalid field 2", 550);
			 return false;
	    }
		
		// Field 3
		if ($this->checkField($field_3_name, $field_3_min, $field_3_max)==false)
		{
		     $this->template->showErr("Invalid field 3", 550);
			 return false;
	    }
		
		// Field 4
		if ($this->checkField($field_4_name, $field_4_min, $field_4_max)==false)
		{
		     $this->template->showErr("Invalid field 4", 550);
			 return false;
	    }
		
		// Field 5
		if ($this->checkField($field_5_name, $field_5_min, $field_5_max)==false)
		{
		     $this->template->showErr("Invalid field 5", 550);
			 return false;
	    }
		
		// Field 6
		if ($this->checkField($field_6_name, $field_6_min, $field_6_max)==false)
		{
		     $this->template->showErr("Invalid field 6", 550);
			 return false;
	    }
		
		// Days
		if ($days<1)
		{
			 $this->template->showErr("Minimum days is 1", 550);
			 return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Shares an address");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_REQ_DATA', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".base64_encode($mes)."',
								par_2='".$field_1_name."',
								par_3='".$field_1_min."',
								par_4='".$field_1_max."',
								par_5='".$field_2_name."',
								par_6='".$field_2_min."',
								par_7='".$field_2_max."',
								par_8='".$field_3_name."',
								par_9='".$field_3_min."',
								par_10='".$field_3_max."',
								par_11='".$field_4_name."',
								par_12='".$field_4_min."',
								par_13='".$field_4_max."',
								par_14='".$field_5_name."',
								par_15='".$field_5_min."',
								par_16='".$field_5_max."',
								days='".$days."',
								status='ID_PENDING', 
								tstamp='".time()."'";
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
	
	function showModal()
	{
		$this->template->showModalHeader("modal_aditional", "Request Aditional Data", "act", "aditional", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_additional_data.png" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_req_net_fee", 340); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Message</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <textarea id="txt_req_mes" name="txt_req_mes" placeholder="Short message describing the data you request (25-250 characters)" rows="4" class="form-control"></textarea>
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="52%" height="30" valign="top"><strong>Fields Names</strong></td>
                      <td width="22%" valign="top"><strong>Length </strong></td>
                      <td width="26%" valign="top"><strong>Length</strong></td>
                    </tr>
                   
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_1_name" id="txt_field_1_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_1_min" id="txt_field_1_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_1_max" id="txt_field_1_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                   
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_2_name" id="txt_field_2_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_2_min" id="txt_field_2_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_2_max" id="txt_field_2_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                   
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_3_name" id="txt_field_3_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_3_min" id="txt_field_3_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_3_max" id="txt_field_3_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                    
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_4_name" id="txt_field_4_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_4_min" id="txt_field_4_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_4_max" id="txt_field_4_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                    
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_5_name" id="txt_field_5_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_5_min" id="txt_field_5_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_5_max" id="txt_field_5_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                    
                    <tr>
                      <td height="40">
                      <input class="form-control" name="txt_field_6_name" id="txt_field_6_name" style="width:150px" placeholder="Name"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_6_min" id="txt_field_6_min" style="width:60px" placeholder="Min"/></td>
                      <td height="30">
                      <input class="form-control" name="txt_field_6_max" id="txt_field_6_max" style="width:60px" placeholder="Max"/></td>
                    </tr>
                    
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Days</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_req_days" id="txt_req_days" style="width:60px" placeholder="5"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
         <script>
		   $('#form_modal_aditional').submit(
		   function() 
		   {   
		      $('#txt_req_mes').val(btoa($('#txt_req_mes').val())); 
			  
			  // Field 1
			  if ($('#txt_field_1_name').val()!="") 
			      $('#txt_field_1_name').val(btoa($('#txt_field_1_name').val()));
				  
			  // Field 2 
			  if ($('#txt_field_2_name').val()!="") 
			      $('#txt_field_2_name').val(btoa($('#txt_field_2_name').val())); 
				  
			  // Field 3
			  if ($('#txt_field_3_name').val()!="") 
			      $('#txt_field_3_name').val(btoa($('#txt_field_3_name').val())); 
		      
			  // Field 4		  
			  if ($('#txt_field_4_name').val()!="") 
			      $('#txt_field_4_name').val(btoa($('#txt_field_4_name').val())); 
				  
			  // Field 5
			  if ($('#txt_field_5_name').val()!="") 
			      $('#txt_field_5_name').val(btoa($('#txt_field_5_name').val())); 
				  
			  // Field 6
			  if ($('#txt_field_6_name').val()!="") 
			      $('#txt_field_6_name').val(btoa($('#txt_field_6_name').val())); 
		   });
		</script>
        
        <?
		$this->template->showModalFooter("Cance", "Activate");
	}
}
?>