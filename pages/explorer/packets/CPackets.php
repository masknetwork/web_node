<?
class CPackets
{
	function CPackets($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function hsowLastPackets()
	{
		$query="SELECT * FROM footprints 
		      ORDER BY ID DESC 
			     LIMIT 0,25";
		 $result=$this->kern->execute($query);	
	 
		?>
        
             <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="62%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="19%" align="center"><span class="inset_maro_14">Block</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Received</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      
                      <?
					     while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                          <tr>
                          <td width="63%" align="left" class="simple_maro_12">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="21%"><img src="GIF/<? print $row['packet_type']; ?>.png" width="55" height="55" alt="" /></td>
                              <td width="79%"><a href="show_packet.php?hash=" class="maro_14"><strong>
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
								 }
							  ?>
                              </strong></a><br><span class="simple_maro_10"><? print substr($row['packet_hash'], 0, 25)."..."; ?></span></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td width="21%" align="center" class="simple_maro_12"><strong><? print $row['block']; ?></strong></td>
                        <td width="16%" align="center" class="simple_maro_12"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
                      </tr>
                      <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                    
                      <?
	                      }
					  ?>
                      
                    </tbody>
                  </table>
                  
                  
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
        
        <?
	}
}
?>