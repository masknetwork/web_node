<?
class CPackets
{
	function CPackets($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLastPackets()
	{
		$query="SELECT * FROM packets 
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
                              <td width="12%" style="padding-right:10px"><img src="GIF/<? print $row['packet_type']; ?>.png" class="img-responsive" /></td>
                              <td width="79%"><a href="packet.php?hash=<? print $row['packet_hash']; ?>" class="font_14"><strong>
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
			 case "ID_SEAL_ADR_PACKET" : return "Seal Address Packet"; break;
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
			 case "ID_RENEW_PACKET" : return "Renew Packet"; break;
			 case "ID_TRANSFER_DOMAIN_PACKET" : return "Transfer Domain Packet"; break;
		}
	}
	
	function showPacket($hash)
	{
		// QR modal
		$this->template->showQRModal();
		
		$query="SELECT * 
		             FROM packets 
				    WHERE packet_hash='".$hash."' 
					   OR payload_hash='".$hash."' 
					   OR fee_hash='".$hash."'"; 
		
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
           <tr><td class="font_20"><strong>Packet Header</strong>&nbsp;&nbsp;&nbsp;&nbsp;
           </td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   Confirmations : &nbsp;&nbsp;<span class="label label-<? if ($row['confirms']<10) print "danger"; else if ($row['confirms']<20 && $row['confirms']>10) print "warning";  else if ($row['confirms']<30 && $row['confirms']>20) print "success"; ?> font_12"><? print $row['confirms']; ?></span></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Packet Type : <strong>".$row['packet_type']."&nbsp;&nbsp;&nbsp;( ".$this->getPacketName($row['packet_type'])." )</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Packet Hash : <strong>".$row['packet_hash']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Block : <strong>".$row['block']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>

           
           <tr><td>
		   <? print "Block Hash : <strong><a class='font_14' href='../blocks/block.php?hash=".$row['block_hash']."'>".$row['block_hash']."</a></strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           </table>
            
           <br>
           <table class="table-responsive" width="90%">
           <tr><td class="font_20"><strong>Network Fee Packet Data</strong></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Fee Address : <strong>".$this->formatStr($row['fee_src'])."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Fee Amount : <strong>".$row['fee_amount']." MSK</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Fee Packet Hash : <strong>".$row['fee_hash']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           </table>
           
           <br>
           <table class="table-responsive" width="90%">
           <tr><td class="font_20"><strong>Payload Data</strong></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Payload Hash : <strong>".$row['payload_hash']."</strong>"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <tr><td class="font_14">
		   <? print "Payload Size : <strong>".round($row['payload_size']/1024, 2)."</strong> Kbytes"; ?></td></tr>
           <tr><td><hr></td></tr>
           
           <?
		     for ($a=1; $a<=10; $a++)
			 {
				 $n="par_".$a."_name";
				 $v="par_".$a."_val";
				 
				 if ($row[$n]!="")
				 {
		   ?>
           
                  <tr><td class="font_14">
		          <? print $row[$n]." : <strong>".$this->formatStr(base64_decode($row[$v]))."</strong>"; ?></td></tr>
                  <tr><td><hr></td></tr>
          
          <?
				 }
			 }
		  ?> 
          
           </table>
           <br><br><br>
        
        <?
	}
	
	
	function formatStr($str)
	{
		if ($str=="") return "<span >none</span>";
		
		$str=str_replace("<", "", $str);
		$str=str_replace(">", "", $str);
		
		if (strlen($str)>50 && strpos($str, " ")===false) 
		   $str="<a href='../../tweets/adr/index.php?adr=".urlencode($str)."'>".$this->template->formatAdr($str)."</a>&nbsp;&nbsp;<a href=\"javascript:void(0)\" onclick=\"$('#qr_img').attr('src', '../../../qr/qr.php?qr=".$str."'); $('#txt_plain').val('".$str."'); $('#modal_qr').modal();\" class=\"font_10\" style=\"color:#999999\">full address</a>";
		
		return $str;
	}
}
?>