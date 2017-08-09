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
	 
	 function removePeer($IP)
	 {
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_REMOVE_PEER', 
								par_1='".$IP."',
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
             
             <br><br>
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
             
                   <?
				      while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
					  {
				   ?>
                   
                        <tr>
                        <td width="20%" align="left" class="font_14"><strong><? print $row['peer']; ?></strong></td>
                        <td width="20%" align="left" class="font_14"><strong><? print $row['port']; ?></strong></td>
                        <td width="10%" align="center" class="font_14" style="color:#009900"><strong>
						<? 
						   if ($row['in_traffic']<1024000) 
						     print round($row['in_traffic']/1024)." Kb";
						   else
						     print round($row['in_traffic']/1024000, 2)." MB";
						?>
                        </strong></td>
                        <td width="10%" align="center" class="font_14" style="color:#990000"><strong>
						<? 
						   if ($row['out_traffic']<1024000) 
						     print round($row['out_traffic']/1024)." Kb";
						   else
						     print round($row['out_traffic']/1024000, 2)." MB";
						?>
                        </strong></td>
                        <td width="20%" align="center" class="font_14"><strong><? print $this->kern->getAbsTime($row['last_seen']); ?></strong></td>
                        <td width="20%" align="center" class="font_14">
                        <?
						    if ($_REQUEST['ud']['user']=="root")
						      print "<a href='index.php?act=remove&peer=".$row['peer']."' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-remove'></span>&nbsp;&nbsp;Remove</a>";
                        ?>
                        </td>
						</tr>
                        <tr>
                        <td colspan="6"><hr></td>
                        </tr>
                  
                  <?
					  }
				  ?>
                  
                  </table>
               
         
         <?
	 }
	 
	 function showAddBut()
	 {
		 if ($_REQUEST['ud']['user']!="root") return false;
		 ?>
         
             
             <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right">
                  <a href="javascript:void(0)" onClick="$('#peer_modal').modal()" class="btn btn-primary">
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