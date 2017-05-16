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
		if ($this->kern->adrValid($pub_key)==false)
		{
			$this->template->showErr("Invalid public key");
			return false;
		}
		
		// Address Exist
		if ($this->kern->isMine($pub_key)==true)
		{
			$this->template->showErr("You already own this address");
			return false;
		}
		
		// Check private key
		if ($this->kern->privKeyValid($priv_key)==false)
		{
			$this->template->showErr("Invalid private key");
			return false;
		}
		
		// Check tag
		if (strlen($tag)>50)
		{
			$this->kern->showErr("Invalid tag length (0-50 characters)");
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
		   $this->template->showOk("Your request has been succesfully recorded", 550);

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
		$query="SELECT my_adr.*, adr.balance, prof.pic
		          FROM my_adr 
				  LEFT JOIN adr ON adr.adr=my_adr.adr
				  LEFT JOIN profiles AS prof ON prof.adr=my_adr.adr
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
        
            <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
                  <?
				     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					 {
						 $balance=$this->kern->getBalance($row['adr'], "MSK");
				  ?>
                  
                        <tr>
                          <td width="9%" align="left">
                          <img src="<? if ($row['pic']!="") print base64_decode($row['pic']); else print "../../template/template/GIF/empty_pic.png"; ?>" width="50" height="50" alt="" class="img-circle"  />
                          </td>
                          <td width="40%" align="left"><a href="../options/index.php?ID=<? print $row['ID']; ?>" class="font_14"><strong><? print $this->template->formatAdr($row['adr']); ?></strong></a>&nbsp;&nbsp;<? if ($row['description']!="") print "<p class='font_12' style='color:#999999'>".base64_decode($row['description'])."</p>"; ?></td>
                         
                          <td width="10%" align="center">
                          <?
						     // Restricted recipients ?
							 if ($this->kern->hasAttr($row['adr'], "ID_RES_REC")==true)
							  print "<span class='glyphicon glyphicon-random' style='color:#999999' data-toggle='tooltip' data-placement='top' title='Restricted recipients'></span>";
							?>
                          </td>
                          
                          <td width="21%" align="center" class="font_14" style="color:<? if ($balance==0) print "#999999"; else print "#009900" ?>"><strong>
						<? 
						   
						   if ($balance=="") 
						      print "0 MSK"; 
							else
							  print round($balance, 8)." MSK"; 
						?>
                        </strong><p class="font_10"><? print "$".round($row['balance']*$_REQUEST['sd']['MSK_price'], 2); ?></p></td>
                        <td width="25%" align="center" class="simple_maro_12">
                        
                       
                        
                        <table width="110" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td>
                              
							  <?
							     print "<a class=\"btn btn-sm btn-warning\" href='../options/index.php?ID=".$row['ID']."'>Options</a></td>";
                              ?>
                              
                              <td>&nbsp;</td>
                              <td><a href="#" class="btn btn-sm btn-default" onclick="$('#qr_img').attr('src', '../../../qr/qr.php?qr=<? print $row['adr']; ?>'); $('#txt_plain').val('<? print $row['adr']; ?>'); $('#modal_qr').modal()"><span class="glyphicon glyphicon-qrcode"></span></a></td>
                            </tr>
                          </tbody>
                        </table>
                        
                       
                        
                        </td>
                        </tr>
                        <tr>
                        <td colspan="5"><hr></td>
                        </tr>
                  
                  <?
					 }
				  ?>
                
            </table>
            
            <br>            
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="10%"><a class="btn btn-primary btn-sm" onclick="$('#modal_new_adr').modal()"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Address</a></td>
             <td width="2%">&nbsp;</td>
             <td width="10%"><a class="btn btn-warning btn-sm" onclick="$('#modal_import_adr').modal()"><span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;&nbsp;Import Address</a></td>
            <td width="438">&nbsp;</td>
            </tr>
            </table>
            
            <br><br>
            
            <script>
            $(document).ready(function(){
            $('[rel="popover"]').popover(); 
            });
            </script>
        
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
                <td align="left"><img src="GIF/adr.png" width="180" height="181" alt=""/></td>
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
                <td align="left"><img src="./GIF/import_address.png" width="180"  /></td>
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
                <td align="left"><input name="txt_imp_tag" id="txt_imp_tag" placeholder="Tag (0-50 characters)" class="form-control"/></td>
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
	
	function showPending()
	{
		if ($_REQUEST['ud']['pending_adr']>0)
		{
		?>
        
            <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right">
                  <a href="index.php?act=show_pending" class="btn btn-danger btn-sm">
                  <span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;<? print $_REQUEST['ud']['pending_adr']." pending"; ?>
                  </a>
                  </td>
                </tr>
              </tbody>
            </table>
            <br>
        
        <?
		}
	}
	
	function votedAll($adr)
	{
		// Comments
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type='ID_COM' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440); 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $votes_com=$row['total'];
		if ($votes_com=="") $votes_com=0;
		
		// Img 1
		if ($votes_com>=5)
		  $com=true;
		else
		  $com=false;
		  
		// Other content
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type='ID_POST' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $votes_posts=$row['total']; 
		if ($votes_posts=="") $votes_posts=0;
		
		// Img 2
		if ($votes_posts>=3)
		  $post=true;
		else
		  $post=false;
		  
		  // Other content
		$query="SELECT COUNT(*) AS total 
	     	      FROM votes 
				 WHERE target_type<>'ID_POST' 
				   AND target_type<>'ID_COM' 
				   AND adr='".$adr."'
				   AND block>".($_REQUEST['sd']['last_block']-1440);
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    $votes_other=$row['total']; 
		if ($votes_other=="") $votes_other=0;
		
		// Img 3
		if ($votes_other>=1)
		  $other=true;
		else
		  $other=false;
		  
	    // All voted ?
		if ($com==true && $post==true && $other==true)
		   return true;
		else
		   return false;
	}
}
?>