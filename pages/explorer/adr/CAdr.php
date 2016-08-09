<?
class CAdr
{
	function CAdr($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showAdr($search="")
	{
		$query="SELECT adr.*
		          FROM adr
		     LEFT JOIN profiles AS prof ON prof.adr=adr.adr
				  WHERE adr.adr LIKE '%".$search."%'
		      ORDER BY adr.balance DESC 
			     LIMIT 0,25";
		 $result=$this->kern->execute($query);	
	 
		?>
        
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                          <tr>
                          <td width="50%" align="left" class="font_14">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="12%" style="padding-right:10px">
                              <img src="../../template/template/GIF/empty_profile.png" class="img-responsive img-circle" />
                              </td>
                              <td width="79%"><a href="#>" class="font_14"><strong>
                              <?
							    print $this->template->formatAdr($row['adr']); 
							  ?>
                              </strong></a><br><span class="font_10"><? print "Created ~".$this->kern->timeFromBlock($row['created'])." ago"; ?></span></td>
                            </tr>
                          </tbody>
                        </table></td>
                        
                        <td width="25%" align="center" class="font_14"><strong><? print $row['balance']; ?> </strong>MSK</td>
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