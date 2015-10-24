<?
class CTrans
{
	function CTrans($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function sendCoins($net_fee_adr, $from_adr, $to_adr, $amount, $moneda, $mes, $escrower)
	{
		// Fee Address
		if ($this->template->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// Fee address is security options free
	    if ($this->template->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Only addresses that have no security options applied can be used to pay the network fee.");
			return false;
		}
		
		// From Address
	    if ($this->template->adrExist($from_adr)==false)
		{
			$this->template->showErr("Invalid address");
			return false;
		}
		
		// Net Fee Address
		if ($this->template->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// To Address
		if ($this->template->adrValid($to_adr)==false)
		{
			$this->template->showErr("Invalid recipient");
			return false;
		}
		
		// Recipient a name ?
		if (strlen($to_adr)<31) $to_adr=$this->template->adrFromDomain($to_adr);
		
		// Amount
		if ($amount<0.0001)
		{
			$this->template->showErr("Invalid network fee address");
			return false;
		}
		
		// Net fee balance
		if (($amount/1000)>$this->template->getBalance($net_fee_adr))
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Sender balance
		if ($amount>$this->template->getBalance($from_adr))
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Moneda
		if ($moneda!="MSK")
		{
			$query="SELECT * FROM assets WHERE symbol='".$moneda."'";
			$result=$this->kern->execute($query);	
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Invalid currency");
			    return false;
			}
			
			// Balance
			$query="SELECT * 
			          FROM assets_owners 
					 WHERE owner='".$from_adr."' 
					   AND symbol='".$moneda."' 
					   AND qty>".$amount;
					   
			if (mysql_num_rows($result)==0)
			{
				$this->template->showErr("Insuficient assets to execute this operation");
			    return false;
			}
		}
		
		// Mesaage
		if (strlen($mes)>250)
		{
			$this->template->showErr("Invalid message length (0-100 characters)");
			return false;
		}
		
		// Escrower
		if ($escrower!="")
		{
			if ($this->template->adrValid($escrower)==false)
			{
				$this->template->showErr("Invalid escrower");
			    return false;
			}
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Send coins / assets to an address");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_TRANSACTION', 
								fee_adr='".$net_fee_adr."', 
								par_1='".$from_adr."',
								par_2='".$to_adr."',
								par_3='".$amount."', 
								par_4='".$moneda."', 
								par_5='".$escrower."', 
								par_6='".$mes."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
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
	
	function showMyTrans($type="ID_REGULAR")
	{
		$query="SELECT * 
		          FROM my_trans AS mt 
				  LEFT JOIN trans_data AS td ON td.trans_hash=mt.hash  
			  ORDER BY mt.ID DESC 
			     LIMIT 0,20";
		$result=$this->kern->execute($query);	
	   
	  
		?>
        
            <table width="93%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <table width="550" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
              <thead>
                <th width="55%">From / To</th>
                <th width="14%" align="center">&nbsp;&nbsp;&nbsp;Data</th>
                <th width="14%" align="center">&nbsp;&nbsp;&nbsp;Time</th>
                <th width="13%" align="center">&nbsp;&nbsp;&nbsp;Status</th>
                <th width="15%" align="center">&nbsp;&nbsp;&nbsp;Amount</th>
                <td width="3%"></thead>
                <tbody>
               
               <?
			       while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				   {
					   $time=$this->kern->getAbsTime($row['tstamp']);
			   ?>
               
                     <tr>
                     <td class="simple_blue_14"><? print $this->template->formatAdr($row['adr']); ?><br />
                     <span class="<? if ($row['mes']=="") print "simple_gri_10"; else print "simple_porto_10"; ?>"><? if ($row['mes']=="") print "No Message"; else print "Mesaage : ".base64_decode($row['mes']); ?></span></td>
                     <td class="simple_blue_14">&nbsp;</td>
                     <td align="center">
                     <span class="simple_blue_14"><strong><? $e=explode(" ", $time); print $e[0]; ?></strong></span><br /><span class="simple_gri_10"><? $e=explode(" ", $time); print $e[1]; ?></span></td>
                     <td align="center">
                    
                      <?
					     if ($row['status']=="ID_CLEARED")
						 {  
						   print "<span class='simple_green_14'>cleared</span>";
						 }
						 else
						 {
							 $dif=time()-$row['tstamp'];
							 
							 if ($dif<40) $img="p1.png";
							 if ($dif>=40 && $dif<80) $img="p2.png";
							 if ($dif>=80 && $dif<120) $img="p3.png";
							 if ($dif>=120 && $dif<160) $img="p4.png";
							 if ($dif>=160 && $dif<200) $img="p5.png";
							 if ($dif>200) $img="p6.png";
					  ?>
                      
                     <table width="60" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                      <td align="center"><img src="../../GIF/<? print $img; ?>"  /></td>
                      </tr>
                     <tr>
                      <td align="center" class="simple_porto_12">pending</td>
                      </tr>
                    </table>
                    
                    <?
						 }
					?>
                    
                    </td>
                    <td align="center"><span class="<? if ($row['amount']>0) print "inset_green_14"; else print "inset_red_14"; ?>"><strong><? print $row['amount']; ?></strong></span><br /><strong class="<? if ($row['amount']>0) print "inset_green_10"; else print "inset_red_10"; ?>"><? print $row['cur']; ?></strong></td>
                    </tr>
                
                <?
				   }
				?>
                
                 </tbody>
            </table></td>
          </tr>
        </table>
        
        <?
	}
}
?>