<?
class CInterest
{
	function CInterest($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function setup($adr, $net_fee_adr, $to_adr)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data", 550);
			 return false;
		}
		
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address", 550);
			return false;
		 }
		 
		 // Address 
		 if ($this->kern->adrExist($adr)==false)
		 {
			$this->template->showErr("Invalid address", 550);
			return false;
		 }
		 
		 // Address
		 $query="SELECT * 
		           FROM adr 
				  WHERE adr='".$adr."'";
		 $result=$this->kern->execute($query);	
	     $row = mysql_fetch_array($result, MYSQL_ASSOC);
	     
		 // Balance
		 if ($row['balance']<25)
		 {
			 $this->template->showErr("Only addresses holding at least 25 MSK can receive interest", 550);
			 return false;
		 }
		
		 // Address 
		 if ($this->kern->adrValid($to_adr)==false)
		 {
			$this->template->showErr("Invalid address");
			return false;
		 }
		 
		try
	    {
		   // Begin
		   $this->kern->begin();
		   
		   // Track ID
		   $tID=$this->kern->getTrackID();
           
           // Action
           $this->kern->newAct("Setup interest", $tID);
		   
		   // Insert 
		   $query="SELECT * 
		             FROM interest 
					WHERE adr='".$adr."'";
		   $result=$this->kern->execute($query);	
	       
		   if (mysql_num_rows($result)>0)
		      $query="UPDATE interest 
			             SET net_fee_adr='".$net_fee_adr."', 
						     to_adr='".$to_adr."' 
					   WHERE adr='".$adr."'";
		   else
		      $query="INSERT INTO interest 
		                   SET adr='".$adr."', 
						       net_fee_adr='".$net_fee_adr."', 
							   to_adr='".$to_adr."'";
		   $this->kern->execute($query);	
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been successfully executed.", 550);

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
		$this->template->showModalHeader("modal_interest", "Receive Interest", "act", "receive_interest", "adr", "");
		?>
           
           <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="144" align="center" valign="top"><table width="66%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td align="left"><img src="./GIF/adr_opt_interest.png"  /></td>
              </tr>
              </table></td>
            <td width="406" align="right" valign="top">
            
            <table width="90%" border="0" cellspacing="0" cellpadding="5">
              
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Pay the network fee from this address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_net_fee_adr"); ?></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Receive the interest to this address</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" name="txt_adr" id="txt_adr" style="width:300px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <?
		$this->template->showModalFooter("Activate");
	}
}
?>