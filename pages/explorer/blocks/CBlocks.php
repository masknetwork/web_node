<?
  class CBlocks
  {
	  function CBlocks($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function showBlocks()
	  {
		  $query="SELECT * 
		            FROM blocks 
			    ORDER BY ID DESC 
				   LIMIT 0,25";
		  $result=$this->kern->execute($query);	
	     
	  
		  ?>
          
               <table width="90%" border="0" cellspacing="0" cellpadding="0">
               <thead>
               <tr bgcolor="#fafafa" class="font_14" height="30px" style="color:#999999">
               <td>Block</td>
               <td align="center">Packets</td>
               <td align="center">Reward</td>
               <td align="center">Received</td>
               </tr>
               </thead>
                
                  <?
				      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					  {
				  ?>
                  
                        <tr>
                        <td width="60%" align="left">
                        <a href="block.php?hash=<? print $row['hash']; ?>" class="font_14"><strong>
						<? 
						    print "Block ".$row['block']; 
							if ($row['reward']==0) print "<span class='font_10' style='color:#990000'> &nbsp;&nbsp;(not on the main chain)</span>";
					    ?>
                        </strong></a><br>
                        <span class="font_10"><? print $row['hash']; ?></span>
                        </td>
                        <td width="10%" align="center"><strong  class="font_14"><? print $row['packets']; ?></strong></td>
                        <td width="10%" align="center"><strong  class="font_14" <? if ($row['reward']==0) print "style='color:#990000'"; ?>>
						<? print $row['reward']; ?></strong></td>
                        <td width="20%" align="center" class="font_14"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
                        </tr>
                        <tr>
                        <td colspan="4"><hr></td>
                        </tr>
                  
                  <?
	                  }
				  ?>
                
                  </table>
                  
                 
            
            <?
			   $query="SELECT * FROM net_stat";
			   $result=$this->kern->execute($query);	
	           $row = mysql_fetch_array($result, MYSQL_ASSOC);
	           print "<span class='font_10'>".$row['net_dif']." (".substr($row['net_dif'], 0, 3)."-".strlen($row['net_dif']).")</span><br><br>";
			
	  }
	  
	  function showBlock($hash, $search="")
 	  {
		// QR modal
		$this->template->showQRModal();
		
		if ($search=="")
		$query="SELECT * 
		          FROM blocks 
			     WHERE hash='".$_REQUEST['hash']."'"; 
		$result=$this->kern->execute($query);	
		
		// No packet found
		if (mysql_num_rows($result)==0) 
		{
			print "<span class='font_14' style='color:#990000'>No records found</span>";
		    return false;
		}
		
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
           
           <br><br>
           <table class="table-responsive" width="90%">
           <tr><td class="font_20"><strong>Block Header</strong>&nbsp;&nbsp;&nbsp;&nbsp;
           </td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   Confirmations : &nbsp;&nbsp;<span class="label label-<? if ($row['confirmations']<10) print "danger"; else if ($row['confirmations']<20 && $row['confirmations']>10) print "warning";  else if ($row['confirmations']>20) print "success"; ?> font_12"><? print $row['confirmations']; ?></span></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Block Hash : <strong>".$row['hash']."&nbsp;&nbsp;&nbsp;</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Prev hash : <strong>".$row['prev_hash']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Block Number : <strong>".$row['block']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Signer : <strong>".$this->template->formatAdr($row['signer'])."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Signer Balance: <strong>".$row['signer_balance']."</strong> MSK"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Nonce: <strong>".$row['nonce']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Size: <strong>".round($row['size']/1024, 2)." KBytes</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Difficulty: <strong>".$row['net_dif']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Packets: <strong>".$row['packets']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14" height="30px">
		   &nbsp;
           <tr><td><span class="font_18"><strong>Packets</strong></span></td></tr>
           <tr><td><hr></td></tr>
           </table>
            
           
           
        <?
		$this->showPackets($hash);
	}
	
	function showPackets($block)
	{
		$query="SELECT * 
		          FROM packets
				  WHERE block_hash='".$block."' 
		      ORDER BY ID DESC 
			     LIMIT 0,25";
		 $result=$this->kern->execute($query);	
	 
		?>
        
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                          <tr>
                          <td width="63%" align="left" class="font_14">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="12%" style="padding-right:10px"><img src="../packets/GIF/<? print $row['packet_type']; ?>.png" class="img-responsive" /></td>
                              <td width="79%"><a href="../packets/packet.php?hash=<? print $row['packet_hash']; ?>" class="font_14"><strong>
                              <?
							    print $this->getPacketName($row['packet_type']);
							  ?>
                              </strong></a><br><span class="font_10"><? print "Hash : ".substr($row['packet_hash'], 0, 25)."..."; ?></span></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="21%" align="center" class="font_14"><strong><? print $row['block']; ?></strong></td>
                        <td width="16%" align="center" class="font_14"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
                      </tr>
                      <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                    
                      <?
	                      }
					  ?>
                      
                    </tbody>
                  </table>
                  <br><br>
                  
        
        <?
	}
	
	function getPacketName($ID)
	{
		 switch ($ID)
		 {
		     case "ID_REQ_INTEREST_PACKET" : return "Request Interest Packet"; break;
			 case "ID_RENT_DOMAIN_PACKET" : return "Rent Name Packet"; break;
			 case "ID_TRANS_PACKET" : return "Simple Transaction Packet"; break;
			 case "ID_SHARE_ADR_PACKET" : return "Share Address Packet"; break;
			 case "ID_FROZE_ADR_PACKET" : return "Froze Address Packet"; break;
			 case "ID_SEAL_ADR_PACKET" : return "Seal Address Packet"; break;
			 case "ID_RESTRICT_REC_PACKET" : return "Restrict Recipients Packet"; break;
			 case "ID_MULTISIG_PACKET" : return "Setup Multisignature Packet"; break;
			 case "ID_NEW_AD_PACKET" : return "Ad Packet"; break;
			 case "ID_SEND_MES" : return "Private Message Packet"; break;
			 case "ID_ESCROWED_TRANS_SIGN" : return "Escrowed Transaction Signature"; break;
			 case "ID_TWEET_LIKE" : return "Like Tweet Packet"; break;
			 case "ID_TWEET_MES_STATUS_PACKET" : return "Aprove / Reject Tweet Message"; break;
			 case "ID_TWEET_COMMENT_PACKET" : return "Tweet Comment Packet"; break;
			 case "ID_NEW_TWEET_PACKET" : return "New Tweet Packet"; break;
			 case "ID_RESPONSE_REWARD_PACKET" : return "Reward Response Packet"; break;
			 case "ID_FEED_PACKET" : return "Data Feed Packet"; break;
			 case "ID_NEW_FEED_BET_PACKET" : return "New Bet Packet"; break;
			 case "ID_NEW_BUY_BET_PACKET" : return "Place Bet Packet"; break;
		}
	}
  }
?>