<?
class CDirectory
{
	function CDirectory($db, $template, $mes)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->mes=$mes;
	}
	
	function showButs($appID)
	{
		// Mes modal
		$this->mes->showComposeModal();
		
		// Load data
		$query="SELECT * FROM agents WHERE aID='".$appID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		if ($_REQUEST['ud']['ID']>0)
		{
		?>
           
         <br>
           <table width="90%">
           <tr><td width="80%">&nbsp;</td>
           <td width="10%"><a href="#" onclick="$('#send_coins_modal').modal(); $('#txt_to').val('<? print $row['adr']; ?>');" class="btn btn-primary" style="width:140px"><span class="glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;Send Coins</a></td>
           <td>&nbsp;&nbsp;&nbsp;</td>
           <td width="10%"><a href="#" onclick="$('#compose_modal').modal(); $('#txt_rec').val('<? print $row['adr']; ?>');" class="btn btn-warning"><span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;Send Message</a></td></tr>
           </table>
         
        
        <?
		}
	}
	
	function showPanel($appID)
	{
		$query="SELECT ag.*, adr.balance
		          FROM agents AS ag
				  JOIN adr ON adr.adr=ag.adr
				 WHERE ag.aID='".$appID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br><br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td width="16%" valign="top"><img src="<? if ($row['pic']!="") print "../../../crop.php?src=".base64_decode($row['pic']); else print "../../template/template/GIF/empty_pic.png\""; ?>" class="img-responsive img-rounded"></td>
            <td width="3%">&nbsp;</td>
            <td width="71%" valign="top"><span class="font_20"><strong><? print base64_decode($row['name']); ?></strong></span><p class="font_14"><? print base64_decode($row['description']); ?></p></td>
             <td width="3%">&nbsp;</td>
            <td width="11%" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center" class="font_12">
                  
                  <?
				     if ($row['sealed']==0)
					 {
				  ?>
                  
                  <img src="GIF/not_sealed.png" width="100" height="101" data-toggle="popover" data-trigger="hover" title="Unsealed Application" data-content="This application is not sealed. This means the owner has full control over the application and funds. He / she can modify the source code or shut the app down, without requiring user approval." data-placement="top"/>
                  
                  <?
					 }
					 else
					 {
						 ?>
                         
                          <img src="GIF/guarantee.png" width="100" height="101" data-toggle="popover" data-trigger="hover" title="Sealed Application" data-content="This application is sealed. This means the owner has no control over the application or funds. He / she can not modify the source code or shut the app down. This is a 100% autonomus app that will run as long as it has funds." data-placement="top"/>
                         
                         <?
						 
					 }
				  ?>
                  
                  </td>
                </tr>
                <tr>
                  <td height="30" align="center" class="font_14" style="color:#<? if ($row['sealed']>0) print "009900"; else print "990000"; ?>"><? if ($row['sealed']>0) print "sealed"; else print "not_sealed"; ?></td>
                </tr>
              </tbody>
            </table></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr><td colspan="5">
    
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-responsive">
            <tr>
            <td width="25%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#"><? print $this->template->formatAdr($row['adr']); ?></a></td>
            <td width="35%" class="font_12" align="center">Installed&nbsp;&nbsp;&nbsp;&nbsp;<? print "~".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></td>
            <td width="40%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<? print "~".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></td>
            </tr>
            <tr>
              <td colspan="3" align="center"><hr></td>
              </tr>
            <tr>
              <td align="center" class="font_12">Status<span class="font_12" style="color : <? if ($row['sealed']>0) print "#009900"; else print "#990000"; ?>">&nbsp;&nbsp;&nbsp;&nbsp;
			  
			  <? 
			      if ($row['sealed']>0) 
				     print "sealed"; 
				  else 
				     print "not sealed"; 
			  ?>
              
              </span></td>
              
              <td class="font_12" align="center">Website&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#">
			  
			  <? 
			      if ($row['website']=="") 
				     print "not provided"; 
				  else 
				     print base64_decode($row['website']); 
			  ?>
              
              </a></td>
              
              <td class="font_12" align="center">Run Interval&nbsp;&nbsp;&nbsp;&nbsp;
			  
			  <? 
			      if ($row['run_interval']>0) 
				      print $row['run_interval']." blocks"; 
				  else 
				      print "not schedulled"; 
			  ?>
              
              </td>
            </tr>
            <tr>
              <td colspan="3" align="center" class="font_12"><hr></td>
              </tr>
            <tr>
              <td align="center" class="font_12">Balance&nbsp;&nbsp;&nbsp;&nbsp;<? print $row['balance']." MSK"; ?></td>
              <td class="font_12" align="center">&nbsp;</td>
              <td class="font_12" align="center">&nbsp;</td>
            </tr>
            </table>
    
            </td></tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
	function showTrans($appID)
	{
		$query="SELECT * 
		          FROM agents 
				 WHERE aID='".$appID."'"; 
		$result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No transactions found</p><br><br>";
			return false;
		}
		
		// Load data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$adr=$row['adr'];
		
	    // Load transactions	 
		$query="SELECT * 
		          FROM trans 
				 WHERE src='".$adr."'
			  ORDER BY ID DESC
			     LIMIT 0,20"; 
	    $result=$this->kern->execute($query);	
		
		if (mysql_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No transactions found</p><br><br>";
			return false;
		}
	    
		?>
           
          <br>
                   <table width="90%" class="table-responsive">
                    
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                        <td width="8%" align="left" class="font_14">
                        <img src="../../template/template/GIF/empty_pic.png"  class="img-circle img-responsive"/></td>
                        <td width="2%">&nbsp;</td>
                        <td width="70%" align="left" class="font_14">
                        <a href="#" class="font_14">
                        <strong><? print $this->template->formatAdr($row['src']); ?></strong>
                        </a>
                        <p class="font_10"><? print "Received ~". $this->kern->timeFromBlock($row['block'])." ago"; ?></p>
                        </td>
                        
                        <td width="25%" align="center" class="font_14" style="color : <? if ($row['amount']<0) print "#990000"; else print "#009900"; ?>">
                        <span<strong><? print round($row['amount'], 8)." ".$row['cur']; ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="4"><hr></td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        <br><br>
           
        
        <?
	}
	
	function showSource($appID)
	{
		// Load code
		$query="SELECT * 
		          FROM agents 
				 WHERE aID='".$appID."'"; 
	    $result=$this->kern->execute($query); 
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
           
           <br>
           <div id="editor_code" align="left"><? print str_replace("<", "&lt;", base64_decode($row['code'])); ?></div>
           <br><br><br>

           <script src="../write/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
           <script>
              var editor_code = ace.edit("editor_code");
              editor_code.setTheme("ace/theme/chrome");
              editor_code.getSession().setMode("ace/mode/assembly_x86");
			  editor_code.setShowPrintMargin(false);
			  editor_code.setReadOnly(true);
			</script>
        
        <?
	}
	
	function showCategs($sel="ID_ALL")
	{
		$query="SELECT * 
		          FROM agents_categs 
			  ORDER BY name ASC";
		$result=$this->kern->execute($query); 
	    
		print "<table width=\"95%\">";
        
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
		   if ($row['categID']!="ID_ALL") print "<tr><td colspan='2'><hr></td></tr>";
		   
		   if ($sel==$row['categID'])
              print "<tr><td><a href=\"index.php?categ=".$row['categID']."\"><strong>".$row['name']." (".$row['dir_no'].")</strong></a></td><td><span class='glyphicon glyphicon-play'></span></td></tr>";
		   else
		      print "<tr><td><a href=\"index.php?categ=".$row['categID']."\">".$row['name']." (".$row['dir_no'].")</a></td><td>&nbsp;</td></tr>";
		}
		
		print "</table>";
	}
	
	function showAppPanel($appID, $name, $desc, $pic)
	{
		?>
        
           <div class="panel panel-default">
           <div class="panel-body font_14" align="center">
           <table>
           <tr><td align="center" valign="top" height="160px">
           <img src="<? if ($pic!="") print "../../../crop.php?src=".base64_decode($pic)."&w=150"; else print "../../template/template/GIF/empty_pic.png\" width='150px'"; ?>"  class="img-responsive img-rounded">
           </td></tr>
           
           <tr><td align="left" class="font_14"><strong>
           <? print ucfirst(base64_decode($name)); ?>
           </strong></td></tr>
           
           <tr><td align="left" class="font_12" height="80px" valign="top">
           <? print substr(base64_decode($desc), 0, 75)."..."; ?>
           </td></tr>
           
           <tr><td align="left" class="font_12" height="50px" valign="bottom">
           <a href="app.php?ID=<? print $appID; ?>" class="btn btn-sm btn-primary" style="width:100%">Use</a>
           </td></tr>
           
           </table>
           </div>
           </div>
        
        <?
	}
	
	function showApps($categ="ID_ALL", $search="", $target="ID_SEALED")
	{
		if ($categ!="ID_ALL")
		$query="SELECT * 
		          FROM agents 
				 WHERE status='ID_ONLINE' 
				   AND (name LIKE '".$search."' OR description LIKE '%".$search."%') 
				   AND categ='".$categ."' 
				   AND dir>0"; 
		else
		$query="SELECT * 
		          FROM agents 
				 WHERE status='ID_ONLINE' 
				   AND (name LIKE '".$search."' OR description LIKE '%".$search."%') 
				   AND dir>0";
				   
		$result=$this->kern->execute($query);	
	    
		// No results
		if (mysql_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No results found</p><br><br>";
			return false;
		}
		
		// App no
		$no=mysql_num_rows($result);
		
		// Lines
		$lines=floor($no/3)+1;
		
		?>
        
           <table width="95%" class="table-responsive">
           
           <?
		       $a=0;
		       for ($l=1; $l<=$lines; $l++)
			   {
				  // Row
				  print "<tr>";
				  
				  // Increase pos
				  $a++;
				  
				  // Display
				  if ($a<=$no)
				  { 
				     $row = mysql_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='30%' align=center'>";
					 $this->showAppPanel($row['aID'], $row['name'], $row['description'], $row['pic']);
					 print "</td>";
				  }
				  else
				  {
				     print "<td width='25%' align=center'>&nbsp;</td>";
				  }
				  
				  // Space
				  print "<td width='3%' align=center'>&nbsp;</td>";
				  
			      // Increase pos
				  $a++;
				  
				  // Display
				   if ($a<=$no)
				   { 
				     $row = mysql_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='30%' align=center'>";
					 $this->showAppPanel($row['aID'], $row['name'], $row['description'], $row['pic']);
					 print "</td>";
				  }
				  else
				  {
				     print "<td width='30%' align=center'>&nbsp;</td>";
				  }
				  
				  // Space
				  print "<td width='3%' align=center'>&nbsp;</td>";
					 
				  // Increase pos
				  $a++;
				  
				  // Display
				   if ($a<=$no)
				   { 
				     $row = mysql_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='30%' align=center'>";
					 $this->showAppPanel($row['aID'], $row['name'], $row['description'], $row['pic']);
					 print "</td>";
				  }
				  else
				  {
				     print "<td width='30%' align=center'>&nbsp;</td>";
				  }
					 
			      // Row
				  print "</tr><tr><td colspan='6'>&nbsp;</td></tr>";
			   }
           ?>
           </table>
        
        <?
	}
}
?>