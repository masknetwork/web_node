<?
class CMyAdr
{
	function CMyAdr($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function newAdr($curve, $tag)
	{		
		// Check strength
		if ($curve!="secp224r1" && 
		    $curve!="secp256r1" && 
			$curve!="secp384r1" && 
			$curve!="secp521r1")
		{
			$this->template->showErr("Invalid address strength");
			return false;
		}
		
		// Check tag
		if (strlen($tag)>50)
		{
			$this->template->showErr("Invalid tag length (0-50 characters)");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new address");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_ADR', 
								par_1='".$curve."', 
								par_2='".base64_encode($tag)."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	        $this->kern->execute($query);
			
		   // Commit
		   $this->kern->commit();

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
		
		// Confirm
		$this->template->showOk("Your request has been succesfully recorded");
	}
	
	function importAdr($pub_key, $private_key, $tag)
	{
		// Check public key
		if ($this->template->adrValid($pub_key)==false)
		{
			$this->template->showErr("Invalid public key");
			return false;
		}
		
		// Address Exist
		if ($this->template->isMine($pub_key)==true)
		{
			$this->template->showErr("You already own this address");
			return false;
		}
		
		// Check private key
		if ($this->template->privKeyValid($priv_key)==false)
		{
			$this->template->showErr("Invalid private key");
			return false;
		}
		
		// Check tag
		if (strlen($tag)>50)
		{
			$this->template->showErr("Invalid tag length (0-50 characters)");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Imports an address");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_IMPORT_ADR', 
								par_1='".$pub_key."', 
								par_2='".$private_key."', 
								par_3='".base64_encode($tag)."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");

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
    
	function showMyAdr()
	{
		$query="SELECT my_adr.*, adr.balance, dom.domain
		          FROM my_adr 
				  LEFT JOIN adr ON adr.adr=my_adr.adr
				  LEFT JOIN domains AS dom ON dom.adr=my_adr.adr
				 WHERE userID='".$_REQUEST['ud']['ID']."' 
			  ORDER BY balance DESC"; 
	    $result=$this->kern->execute($query);
		
		// QR modal
		$this->template->showQRModal();
		
		// New address
		$this->showNewAdrModal();
		
		// Import modal
		$this->showImportAdrModal();
		
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png">
                  <table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="43%" align="left" class="inset_maro_14">Address</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="9%" align="center"><span class="glyphicon glyphicon-list" style="color:#9c906f"></span></td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Balance</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="25%" align="center"><span class="inset_maro_14">Options</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                  <?
				     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					 {
				  ?>
                  
                        <tr>
                          <td width="8%" align="left"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" alt="" class="img-circle"  /></td>
                          <td width="36%" align="left"><a href="#" class="maro_12"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a><br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span></td>
                          <td width="10%" align="center" class="<? if ($row['balance']==0 || $row['balance']=="") print "simple_maro_12"; else print "simple_green_12"; ?>">
                          <?
						     $attr="";
							 
							 // Receive interest
							 $query="SELECT * 
							           FROM interest 
									  WHERE adr='".$row['adr']."'"; 
							 $r=$this->kern->execute($query);	
							 if (mysql_num_rows($r)>0)
							 print "<span class='glyphicon glyphicon-gift' style='color:#b5943c;' title='Receive Interest' data-toggle='tooltip' data-placement='top'></span>";
								 
							 // Frozen
                             if ($this->kern->hasAttr($row['adr'], "ID_FROZEN")==true) 
							 print "<span class='glyphicon glyphicon-remove-circle' style='color:#b5943c;' title='Frozen Address' data-toggle='tooltip' data-placement='top'></span>";
							 
							 // Sealed
							 if ($this->kern->hasAttr($row['adr'], "ID_SEALED")==true) 
							 print "&nbsp;<span class='glyphicon glyphicon-record' style='color:#b5943c;' title='Sealed Address' data-toggle='tooltip' data-placement='top'></span>";
							 
							 // Restricted recipients
							 if ($this->kern->hasAttr($row['adr'], "ID_RESTRICT_REC")==true) 
							  print "&nbsp;<span class='glyphicon glyphicon-random' style='color:#b5943c;' title='Restricted Recipients' data-toggle='tooltip' data-placement='top'></span>";
							 
							 // Multisignatures
							 if ($this->kern->hasAttr($row['adr'], "ID_MULTISIG")==true)
							  print "&nbsp;<span class='glyphicon glyphicon-pencil' style='color:#b5943c;' title='Multisig Address' data-toggle='tooltip' data-placement='top'></span>";
							 
							 // OTP 
							 if ($this->kern->hasAttr($row['adr'], "ID_OTP")==true)
							  print "&nbsp;<span class='glyphicon glyphicon-file' style='color:#b5943c;' title='One Time Password Enabled' data-toggle='tooltip' data-placement='top'></span>";
							 
							 // Request Data 
							 if ($this->kern->hasAttr($row['adr'], "ID_REQ_DATA")==true)
							  print "&nbsp;<span class='glyphicon glyphicon-list' style='color:#b5943c;' title='Request Additional Data' data-toggle='tooltip' data-placement='top'></span>";
							    
								 
						  ?>
                          
						  </td>
                        <td width="21%" align="center" class="<? if ($row['balance']==0 || $row['balance']=="") print "simple_maro_12"; else print "simple_green_12"; ?>"><strong>
						<? 
						   if ($row['balance']=="") 
						      print "0 MSK"; 
							else
							  print $row['balance']." MSK"; 
						?>
                        </strong></td>
                        <td width="25%" align="center" class="simple_maro_12">
                        
                        <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Options
                        <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        
                        <li><a href="#" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()">Get QR Code</a></li>
                        
                        <li><a href="../options/index.php?ID=<? print $row['ID']; ?>">Adress Options</a></li>
                        
                        </ul>
                        </div>
                        
                        </td>
                        </tr>
                        <tr>
                        <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
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
            <br>            
            <table width="540" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="75"><a class="btn btn-success btn-sm" onclick="$('#modal_new_adr').modal()"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Address</a></td>
             <td width="20">&nbsp;</td>
             <td width="75"><a class="btn btn-warning btn-sm" onclick="$('#modal_import_adr').modal()"><span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;&nbsp;Import Address</a></td>
            <td width="438">&nbsp;</td>
            </tr>
            </table>
        
        <?
	}
	
	
	
	function showNewAdrModal()
	{
		$this->template->showModalHeader("modal_new_adr", "New Address", "act", "new_adr");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="left"><img src="./GIF/new_adr.png" width="162" height="176" /></td>
              </tr>
              </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Encryption Type Type</strong></td>
              </tr>
              <tr>
                <td align="left">
                <select class="form-control" name="dd_curve" id="dd_curve">
                <option value="secp224r1">Default (strength 224 bits)</option>
                <option value="secp256r1">Medium (strength 256 bits)</option>
                <option value="secp384r1">Strong (strength 384 bits)</option>
                <option value="secp521r1">Very Strong (strength 521 bits)</option>
                </select>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Address Tag</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" name="txt_tag" id="txt_tag"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showImportAdrModal()
	{
		$this->template->showModalHeader("modal_import_adr", "Import Address", "act", "import_adr");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="left"><img src="./GIF/import_address.png"  /></td>
              </tr>
              </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Public Key</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea name="txt_pub_key" id="txt_pub_key" rows="3"  style="width:330px" class="form-control" placeholder="Public Key" onfocus="this.placeholder=''"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Private Key</strong></td>
              </tr>
              <tr>
                <td align="left"><textarea name="txt_priv_key" id="txt_pub_key" rows="3"  style="width:330px" class="form-control" placeholder="Private Key" onfocus="this.placeholder=''"></textarea></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" height="30px" valign="top"><strong>Tag</strong></td>
              </tr>
              <tr>
                <td align="left"><input name="txt_tag" id="txt_tag" placeholder="Tag (0-50 characters)" class="form-control"/></td>
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
	

	
	
	
	function showQRModal()
	{
		$this->template->showModalHeader("modal_qr", "Address QR Code");
		?>
        
           <table width="550" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td align="center">
            <img id="qr_img" name="qr_img"/>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><textarea class="form-control" name="txt_plain" id="txt_plain" rows="4" style="width:550px"></textarea></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter(false);
	}
}
?>