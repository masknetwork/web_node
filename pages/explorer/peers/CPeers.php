<?
  class CPeers
  {
	 function CPeers($db, $template)
	 {
		 $this->kern=$db;
		 $this->template=$template;
	 }
	 
	 function addPeer($IP, $port)
	 {
		  // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ADD_PEER', 
								par_1='".$IP."',
								par_2='".$port."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
	 }
	 
	 function addPeerModal()
	 {
		 $this->template->showModalHeader("peer_modal", "Add Peer Message", "act", "add_peer");
		?>
        
          <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="192" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top"><img src="./GIF/chain.png" width="180" height="136" /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="418" align="right" valign="top">
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>IP</strong></td>
              </tr>
              <tr>
                <td align="left">
                <input class="form-control" id="txt_ip" name="txt_ip" placeholder="0.0.0.0" style="width:300px"/>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Port</strong></span></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_port" name="txt_port" placeholder="10000" style="width:300px"/></td>
              </tr>
            </table></td>
          </tr>
        </table>
      
        
        <?
		$this->template->showModalFooter();
	 }
	 
	 function showPeers()
	 {
		 $query="SELECT * FROM peers";
		 $result=$this->kern->execute($query);	
	
	  
		 ?>
             
             <br>
             <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="42%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="11%" align="center"><span class="inset_maro_14">In</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="10%" align="center"><span class="inset_maro_14">Out</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="14%" align="center"><span class="inset_maro_14">Seen</span></td>
                        <td width="1%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Remove</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                   
                   <?
				      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					  {
				   ?>
                   
                        <tr>
                        <td width="42%" align="left" class="simple_maro_12"><strong><? print $row['peer'].":".$row['port']; ?></strong></td>
                        <td width="12%" align="center" class="simple_green_12"><strong>
						<? 
						   if ($row['in_traffic']<1024000) 
						     print round($row['in_traffic']/1024)." Kb";
						   else
						     print round($row['in_traffic']/1024000, 2)." MB";
						?>
                        </strong></td>
                        <td width="13%" align="center" class="simple_red_12"><strong>
						<? 
						   if ($row['out_traffic']<1024000) 
						     print round($row['out_traffic']/1024)." Kb";
						   else
						     print round($row['out_traffic']/1024000, 2)." MB";
						?>
                        </strong></td>
                        <td width="17%" align="center" class="simple_maro_12"><strong><? print $this->kern->getAbsTime($row['last_seen']); ?></strong></td>
                        <td width="16%" align="center" class="simple_maro_12">
                        <a href="index.php?act=remove&peer=<? print $row['ID']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                        </tr>
                        <tr>
                        <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                  
                  <?
					  }
				  ?>
                  
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
	 
	 function showAddBut()
	 {
		 ?>
         
             <br><br>
             <table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right">
                  <a href="javascript:void(0)" onClick="$('#peer_modal').modal()" class="btn btn-success">
                  <span class="glyphicon glyphicon-plus"></span>&nbsp;Add Peer
                  </a>
                  </td>
                </tr>
              </tbody>
            </table>
         
         <?
	 }
  }
?>