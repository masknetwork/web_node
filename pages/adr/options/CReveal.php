<?
class CReveal
{
	function CReveal($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function reveal($adr, $pass)
	{
		// Addresses valid
		if ($this->kern->adrValid($adr)==false ||
			$this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		// Check password
		$query="SELECT * 
		          FROM web_users 
				 WHERE user='".$_REQUEST['ud']['user']."' 
				   AND pass='".hash("sha256", $pass)."'";
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			 $this->template->showErr("Invalid password", 550);
			 return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Requests private key reveal for an address");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_REVEAL_PK', 
								target_adr='".$adr."',
								status='ID_PENDING', 
								tstamp='".time()."'";
	       $result=$this->kern->execute($query);
		   $ID=mysql_insert_id();
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		  $this->showLoader($ID);

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
	
	function showLoader($ID)
	{
		?>
            
            <div id="div_pk" name="div_pk">
           
                  <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  <tr>
                  <td height="100" align="center" bgcolor="#ffffff"><img src="./GIF/loader.gif" width="64" height="64" alt=""/></td>
                  </tr>
                  <tr>
                  <td align="center" bgcolor="#ffffff" class="simple_red_10">Loading data...</td>
                  </tr>
                  </tbody>
                  </table>
                  
                
            </div>
            
            <script>
			 function show()
			 {
				 $('#div_pk').load('get-page.php?act=show_pk&ID=<? print $ID; ?>');
				 clearTimeout(tID);
			 }
			 
			 var tID=window.setTimeout(show, 2000);
			</script>
        
        <?
	}
		
	function show($ID)
	{
		$query="SELECT * 
		          FROM web_ops 
				 WHERE ID='".$ID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	    
		?>
           
           
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_reveal.png" width="150px" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="1051" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Public Key</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <textarea id="txt_mes" name="txt_mes" rows="4" class="form-control"><? print $row['resp_1']; ?></textarea></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Private Key</strong></td>
              </tr>
              <tr>
                <td height="50" align="left" valign="middle" class="simple_blue_14">
                <textarea id="txt_mes2" name="txt_mes2" rows="4" class="form-control"><? print $row['resp_2']; ?></textarea></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
      
        
        <?
	}
	
	function showModal()
	{
		$this->template->showModalHeader("modal_reveal", "Reveal Private Key", "act", "reveal", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="182" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="center"><img src="./GIF/adr_opt_reveal.png" width="150" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="368" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Account Password</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">
                <input class="form-control" name="txt_pass" id="txt_pass" style="width:350px" placeholder="" type="password" />
                </td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Reveal");
	}
}
?>