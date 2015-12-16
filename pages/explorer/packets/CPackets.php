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
		$query="SELECT * FROM footprints 
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
                              <td width="79%"><a href="show_packet.php?hash=" class="font_14"><strong>
                              <?
							     switch ($row['packet_type'])
								 {
									 case "ID_REQ_INTEREST_PACKET" : print "Request Interest Packet"; break;
									 case "ID_RENT_DOMAIN_PACKET" : print "Rent Name Packet"; break;
									 case "ID_TRANS_PACKET" : print "Simple Transaction Packet"; break;
									 case "ID_SHARE_ADR_PACKET" : print "Share Address Packet"; break;
									 case "ID_FROZE_ADR_PACKET" : print "Froze Address Packet"; break;
									 case "ID_SEAL_ADR_PACKET" : print "Seal Address Packet"; break;
									 case "ID_RESTRICT_REC_PACKET" : print "Restrict Recipients Packet"; break;
									 case "ID_MULTISIG_PACKET" : print "Setup Multisignature Packet"; break;
									 case "ID_NEW_AD_PACKET" : print "Ad Packet"; break;
									 case "ID_SEND_MES" : print "Private Message Packet"; break;
									 case "ID_ESCROWED_TRANS_SIGN" : print "Escrowed Transaction Signature"; break;
								 }
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
}
?>