<?
class CPending
{
	function CPending($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function rejectPending($ID)
	{
		// Pending address exist
		$query="SELECT * 
		          FROM pending_adr 
				 WHERE ID='".$ID."'";
				 
		$result=$this->kern->execute($query);	
	    if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$query="SELECT * 
		          FROM my_adr 
				 WHERE adr='".$row['share_adr']."' 
				   AND userID='".$_REQUEST['ud']['ID']."'";
		$result=$this->kern->execute($query);	
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Rejects a pending address");
		
		   // Insert to stack
		   $query="DELETE FROM pending_adr 
		                 WHERE ID='".$ID."'"; 
	       $this->kern->execute($query);
		   
		   // Decrease
		   $query="UPDATE web_users 
			           SET pending_adr=pending_adr-1 
					 WHERE ID='".$_REQUEST['ud']['ID']."'";
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
	}
	
	function aprovesPending($ID)
	{
		// Pending address exist
		$query="SELECT * 
		          FROM pending_adr 
				 WHERE ID='".$ID."'";
				 
		$result=$this->kern->execute($query);	
	    if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address already owned by user
		$query="SELECT * 
		          FROM my_adr 
				 WHERE adr='".$row['pub_key']."' 
				   AND userID='".$_REQUEST['ud']['ID']."'";
		$result=$this->kern->execute($query);	
		if (mysql_num_rows($result)>0)
		{
			// Remove pending address
			$query="DELETE FROM pending_adr 
			              WHERE ID='".$ID."'";
			$this->kern->execute($query);	
			
			// Decrease pending adr
			$query="UPDATE web_users 
			           SET pending_adr=pending_adr-1 
					 WHERE ID='".$_REQUEST['ud']['ID']."'";
			$this->kern->execute($query);	
			
			$this->template->showErr("You already own this address");
			return false;
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Aproves a pending address");
		
		   // Not in local wallet ?
		   $query="SELECT * 
		            FROM my_adr 
				   WHERE adr='".$row['pub_key']."'";
	 	   $result=$this->kern->execute($query);	
		   
		   if (mysql_num_rows($result)==0)
		   {
			    // Insert to stack
		        $query="INSERT INTO web_ops 
			                    SET user='".$_REQUEST['ud']['user']."', 
							        op='ID_IMPORT_ADR', 
								    par_1='".$row['pub_key']."', 
								    par_2='".$row['priv_key']."', 
								    par_3='', 
								    status='ID_PENDING', 
								    tstamp='".time()."'"; 
	            $this->kern->execute($query);
		   }
		   else
		   {
			   $query="INSERT INTO my_adr 
			                   SET userID='".$_REQUEST['ud']['ID']."', 
							       adr='".$row['pub_key']."',
								   description='".base64_encode("No Description")."'";
			   $this->kern->execute($query);
		   }
		   
		   // Remove pending address
		   $query="DELETE FROM pending_adr 
			                WHERE ID='".$ID."'";
		   $this->kern->execute($query);	
		   
		   // Decrease pending adr
		   $query="UPDATE web_users 
			           SET pending_adr=pending_adr-1 
					 WHERE ID='".$_REQUEST['ud']['ID']."'";
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
	}
	
	function showPending()
	{
		$query="SELECT pa.*, adr.balance 
		          FROM pending_adr AS pa
				  LEFT JOIN adr ON adr.adr=pa.pub_key 
				 WHERE share_adr IN (SELECT adr FROM my_adr)";
	    $result=$this->kern->execute($query);	
	  
		?>
        
          <table width="90%" border="0" cellspacing="0" cellpadding="0">
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                          <tr>
                          <td width="45%" align="left" class="font_14"><strong><? print $this->template->formatAdr($row['pub_key']); ?></strong></td>
                          <td width="22%" align="center" class="font_14" style="color:#009900"><strong>
						  <? if ($row['balance']>0) print $row['balance']." MSK"; else print "0 MSK"; ?></strong></td>
                          <td width="33%" align="right" class="font_14">
                          <table width="160" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td align="center">
                              <a href="index.php?act=add_adr&ID=<? print $row['ID']; ?>" class="btn btn-primary btn-sm" style="width:100px"> 
                              <span class="glyphicon glyphicon-ok-circle"></span>&nbsp;&nbsp;Aprove
                              </a>
                              </td>
                              <td>&nbsp;&nbsp;</td>
                              <td align="center">
                              <a href="index.php?act=reject_adr&ID=<? print $row['ID']; ?>" class="btn btn-danger btn-sm" style="width:100px"> 
                              <span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;Reject 
                              </a>
                              </td>
                            </tr>
                          </tbody>
                          </table></td>
                          </tr>
                          <tr>
                          <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                          </tr>
                      
                      <?
						 }
					  ?>
                      
                  </table>
                  
        
        <?
	}
}
?>