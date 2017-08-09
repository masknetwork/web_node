<?
  class CAppStore
  {
	  function CAppStore($db, $template, $app)
	  {
		  $this->kern=$db;
		  $this->template=$template;
		  $this->app=$app;
	  }
	  
	  function rent($net_fee_adr, $adr, $appID, $days)
	  {
		  // Load
		  $query="SELECT * 
		          FROM agents
				 WHERE aID='".$appID."' 
				   AND app_store>0"; 
				   	   
	      $result=$this->kern->execute($query);
		
		  // No results
		  if (mysqli_num_rows($result)==0)
		  {
		    $this->template->showErr("Invalid app ID");
			return false;
		  }
		
		  // Agent data
		  $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		  // Net fee adr
		  if ($this->kern->adrExist($net_fee_adr)==false || 
		      $this->kern->isMine($net_fee_adr)==false)
		  {
			$this->template->showErr("Invalid net fee address");
			return false;
		  }
		
		  // Buy adr
		  if ($this->kern->adrExist($adr)==false || 
		     $this->kern->isMine($adr)==false)
		  {
			$this->template->showErr("Invalid net fee address..");
			return false;
		  }
		
		  // Valid net fee adr
		  if ($this->kern->feeAdrValid($net_fee_adr)==false)
		  {
			$this->template->showErr("Invalid net fee address (has security attributes / applicaation attached)");
			return false;
		  }
		
		  // Price
		  $price=$row['price']*$days;
		
		  // Adr Balance
		  if ($this->kern->getBalance($adr)<$price)
		  {
			$this->template->showErr("Insufficient funds");
			return false;
		  }
		
		  // Net Fee Adr Balance
		  if ($this->kern->getBalance($net_fee_adr)<(0.0001*$days))
		  {
			$this->template->showErr("Insufficient funds");
			return false;
		  }
		
		  // Days
		  if ($days=="" || $days<1)
	      {
			  $this->template->showErr("Invalid days");
			  return false;
		  }
		  
		  // Another contract installed on this address ?
		  $query="SELECT * FROM agents WHERE adr='".$adr."'";
		  $result=$this->kern->execute($query);
		  
		  if (mysqli_num_rows($result)>0)
		  {
			  $this->template->showErr("An application is already installed on this address");
			  return false;
		  }
		  
		  // Can spend
		  if ($this->kern->voted($adr)==true)
		  {
			  $this->template->showErr("Your address voted in the last 24 hours.");
			  return false;
		  }
		  
		   if ($this->kern->canSpend($adr)==false)
		  {
			  $this->template->showErr("This address can't spend funds");
			  return false;
		  }
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Buys an application");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_RENT_APP', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$appID."',
								days='".$days."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	        $this->kern->execute($query);
			
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been recorded");

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
	  
	  function showPanel($appID)
	  {
		  // Rent modal
		  $this->showRentModal();
		  
		  $query="SELECT ag.*, adr.balance
		          FROM agents AS ag
				  JOIN adr ON adr.adr=ag.adr
				 WHERE ag.aID='".$appID."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br><br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td width="16%" valign="top"><img src="<? if ($row['pic']!="") print "../../../crop.php?src=".base64_decode($row['pic']); else print "../../template/template/GIF/empty_pic.png\""; ?>" class="img-responsive img-rounded"></td>
            <td width="3%">&nbsp;</td>
            <td width="71%" valign="top"><span class="font_20"><strong><? print $this->kern->noescape(base64_decode($row['name'])); ?></strong></span><p class="font_14"><? print $this->kern->noescape(base64_decode($row['description'])); ?></p></td>
             <td width="3%">&nbsp;</td>
            <td width="11%" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="center" class="font_12">
                  
                 <table cellspacing="2px" width="100px">
                 <tr><td bgcolor="#ECFFEE" height="50px" align="center"><span class="font_16" style="color:#005500">
                 <?
				     if ($row['sale_price']>0)
					    print "<strong>".$row['sale_price']."</strong></span><span class=\"font_12\"> MSK</span><p class=\"font_10\">daily</p>";
				     else
					    print "free";
				 ?>
                 </td></tr>
                 <tr><td><a class="btn btn-danger btn-sm" style="width:100px" href="javascript:void(0)" onClick="$('#rent_modal').modal(); $('#rent_appID').val('<? print $appID; ?>')"><? if ($row['sale_price']>0) print "Rent"; else print "Install"; ?></a></td></tr>
                 </table>
                  
                  </td>
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
	
	  function showCategs($sel="ID_ALL")
	  {
		$query="SELECT * 
		          FROM agents_categs 
			  ORDER BY name ASC";
		$result=$this->kern->execute($query); 
	    
		print "<table width=\"95%\">";
        
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
		   if ($row['categID']!="ID_ALL") print "<tr><td colspan='2'><hr></td></tr>";
		   
		   if ($sel==$row['categID'])
              print "<tr><td><a href=\"index.php?categ=".$row['categID']."\"><strong>".$row['name']." (".$row['mkt_no'].")</strong></a></td><td><span class='glyphicon glyphicon-play'></span></td></tr>";
		   else
		      print "<tr><td><a href=\"index.php?categ=".$row['categID']."\">".$row['name']." (".$row['mkt_no'].")</a></td><td>&nbsp;</td></tr>";
		}
		
		print "</table>";
	}
	
	function showAppPanel($appID, $name, $desc, $pic, $price)
	{
		?>
        
           <div class="panel panel-default">
           <div class="panel-body font_14" align="center">
           <table border="0" cellpadding="0" cellspacing="0">
           <tr><td align="center" valign="top" height="110px">
           <img src="<? if ($pic!="") print "../../../crop.php?src=".base64_decode($pic)."&w=150"; else print "../../template/template/GIF/empty_pic.png"; ?>"  class="img-responsive img-rounded">
           </td></tr>
           
           <tr><td align="left" class="font_14" height="30px"><strong>
           <? print $this->kern->noescape(ucfirst(base64_decode($name))); ?>
           </strong></td></tr>
           
           <tr><td align="left" class="font_12" height="100px" valign="top">
           <? print $this->kern->noescape(substr(base64_decode($desc), 0, 75))."..."; ?>
           </td></tr>
           
           <tr><td align="center" class="font_12" height="30px" bgcolor="#e1ffe7" style="color:#005500">
           <? if ($price>0) print "<strong>".$price."</strong> MSK / day"; else print "free"; ?>
           </td></tr>
           
           <tr><td align="left" class="font_12" height="50px" valign="bottom">
           <a class="btn btn-sm btn-primary" style="width:100%" href="app.php?ID=<? print $appID; ?>"><? if ($price>0) print "Rent"; else print "Install"; ?></a>
           </td></tr>
           
           </table>
           </div>
           </div>
        
        <?
	}
	
	function showApps($categ="ID_ALL", $search="")
	{
		// Rent modal
		$this->showRentModal();
		
		if ($categ!="ID_ALL")
		$query="SELECT * 
		          FROM agents 
				 WHERE status='ID_ONLINE' 
				   AND (name LIKE '".$search."' OR description LIKE '%".$search."%') 
				   AND categ='".$categ."' 
				   AND app_store>0"; 
		else
		$query="SELECT * 
		          FROM agents 
				 WHERE status='ID_ONLINE' 
				   AND (name LIKE '".$search."' OR description LIKE '%".$search."%') 
				   AND app_store>0";
				   
		$result=$this->kern->execute($query);	
	    
		// No results
		if (mysqli_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No results found</p><br><br>";
			return false;
		}
		
		// App no
		$no=mysqli_num_rows($result);
		
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
				     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='33%' align=center'>";
					 $this->showAppPanel($row['aID'], 
					                    $row['name'], 
										$row['description'], 
										$row['pic'], 
										$row['price']);
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
				     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='33%' align=center'>";
					 $this->showAppPanel($row['aID'], 
					                     $row['name'], 
										 $row['description'], 
										 $row['pic'], 
										 $row['price']);
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
				     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				     print "<td width='33%' align=center'>";
					 $this->showAppPanel($row['aID'], $row['name'], $row['description'], $row['pic'], $row['price']);
					 print "</td>";
				  }
				  else
				  {
				     print "<td width='30%' align=center'>&nbsp;</td>";
				  }
					 
			      // Row
				  print "</tr>";
			   }
           ?>
           </table>
        
        <?
	}
	
	
	function showRentModal()
	{
		$this->template->showModalHeader("rent_modal", "Rent Application", "act", "rent", "rent_appID", "");
		?>
           
          <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="./GIF/cart.jpg" width="160" height="160" id="img_update" name="img_update" /></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
           </table></td>
           <td width="90%" align="right" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></span></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px"><? $this->template->showMyAdrDD("dd_rent_net_fee_adr", 350); ?></td>
             </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><span class="font_14"><strong> Address</strong></span></span></td>
             </tr>
              <tr>
                <td align="left"><span style="font-size:14px">
                  <? $this->template->showMyAdrDD("dd_rent_adr", 350); ?>
                </span></td>
              </tr>
              <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14" id="txt_update_days_txt" name="txt_update_days_txt"><strong>Days</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:100px" id="txt_rent_days" name="txt_rent_days" placeholder="100"/></td>
             </tr>
           </table></td>
         </tr>
         </table>
     
     
     
    <?
		$this->template->showModalFooter("Rent");
		
	}
	
	function showInstalls($appID)
	{
		$query="SELECT * 
		          FROM agents 
				 WHERE aID='".$appID."'"; 
		$result=$this->kern->execute($query);	
		
		if (mysqli_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No transactions found</p><br><br>";
			return false;
		}
		
		// Load data
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		// Address
		$owner=$row['adr'];
		
	    // Load transactions	 
		$query="SELECT * 
		          FROM agents 
				 WHERE owner='".$adr."' and owner<>adr
			  ORDER BY ID DESC
			     LIMIT 0,20"; 
	    $result=$this->kern->execute($query);	
		
		if (mysqli_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No installs found</p><br><br>";
			return false;
		}
	    
		?>
           
          <br>
                   <table width="90%" class="table-responsive">
                    
                    <?
					   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                        <td width="8%" align="left" class="font_14">
                        <img src="../../template/template/GIF/empty_pic.png"  class="img-circle img-responsive"/></td>
                        <td width="2%">&nbsp;</td>
                        <td width="70%" align="left" class="font_14">
                        <a href="#" class="font_14">
                        <strong><? print $this->template->formatAdr($row['adr']); ?></strong>
                        </a>
                        <p class="font_10"><? print "Received ~". $this->kern->timeFromBlock($row['block'])." ago"; ?></p>
                        </td>
                        
                        <td width="25%" align="center" class="font_14" style="color : <? if ($row['amount']<0) print "#990000"; else print "#009900"; ?>">
                        <a href="../directory/app.php?ID=<? print $row['aID']; ?>"></a></td>
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
	
	function showWonPanel($appID)
	{
		// Load app data
		$query="SELECT * 
		          FROM agents 
				 WHERE aID='".$appID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$owner=$row['adr']; 
		
		// Total sum
		$query="SELECT SUM(adr.balance) AS total
		          FROM agents 
				  JOIN adr ON adr.adr=agents.adr 
				 WHERE agents.owner<>agents.adr";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$total=$row['total']; 
		
		// Balance on installed addresses
		$query="SELECT SUM(adr.balance) AS total
		          FROM agents 
				  JOIN adr ON adr.adr=agents.adr 
				 WHERE agents.owner='".$owner."' 
				   AND agents.owner<>agents.adr";
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$used=$row['total'];
		
		// Percent
		$p=$used*100/$total; 
		
		// Amount
		$pay=round($p/100*$this->kern->getReward("ID_APP")*$_REQUEST['sd']['MSK_price'], 2); 
		
		// Number of installs
		$query="SELECT COUNT(*) AS total 
		          FROM agents 
				 WHERE owner<>adr 
				   AND owner='".$owner."'";
		$result=$this->kern->execute($query);	
		$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$no=$row['total'];
		
		?>
        
            <div class="panel panel-default" style="width:90%">
            <div class="panel-heading">
            <h3 class="panel-title font_14">Panel title</h3>
            </div>
            <div class="panel-body">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td width="15%"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
            <td align="center" class="font_12">Won Today</td>
            </tr>
            <tr>
            <td align="center" style="color:#<? if ($pay>0) print "009900"; else print "999999"; ?>"><strong><? print "$".$pay; ?></strong></td>
            </tr>
            <tr>
            <td align="center" class="font_12"><? print $no." installs"; ?></td>
            </tr>
            </tbody>
            </table></td>
            <td width="2%">&nbsp;</td>
            <td width="83%" class="font_14">Publishing your application to decentralized app store is one of the easiest way to make money with MaskNetwork. Everyday, 10% of distributed MaskCoins goes to applications owners. The payment depends on the number of coins held by addresses on which the application is installed.</td>
            </tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
  }
?>