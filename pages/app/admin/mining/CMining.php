<?
class CMining
{
	function CMining($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showPanels()
	{
		// Cores
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $cores=$row['mining_threads'];
		$hash_power=$row['cpu_1_power']+$row['cpu_2_power']+$row['cpu_3_power']+$row['cpu_4_power']+$row['cpu_5_power']+$row['cpu_6_power']+$row['cpu_7_power']+$row['cpu_8_power']+$row['cpu_9_power']+$row['cpu_11_power']+$row['cpu_11_power']+$row['cpu_12_power']+$row['cpu_13_power']+$row['cpu_14_power']+$row['cpu_15_power']+$row['cpu_16_power']+$row['cpu_17_power']+$row['cpu_18_power']+$row['cpu_19_power']+$row['cpu_20_power'];
		
		// Blocks 24 hours
		$query="SELECT COUNT(*) AS total 
	    	      FROM blocks 
				 WHERE signer='".$_REQUEST['sd']['delegate']."' 
				   AND tstamp>".(time()-86400); 
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $blocks=$row['total'];
		
		// Mining reward
		$reward=$this->kern->getReward("ID_MINER");
		?>
        
        <br><br>
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
        <td align="center" width="25%">
       
        <div class="panel panel-default" style="width:90%">
        <div class="panel-heading">
        <h3 class="panel-title font_14">Mining Cores</h3>
        </div>
        <div class="panel-body font_22">
        <? print $cores; ?><p class="font_10">cores</p>
        </div>
        </div>
       
       </td>
       <td align="center" width="25%">
       
       <div class="panel panel-default" style="width:90%">
       <div class="panel-heading">
       <h3 class="panel-title font_14">Hashing Power</h3>
       </div>
       <div class="panel-body font_22">
       <? print round($hash_power/1000, 2)." Khs"; ?><p class="font_10">hashes per second</p>
       </div>
       </div>
       
       </td>
       <td align="center" width="25%">
       
       <div class="panel panel-default" style="width:90%">
       <div class="panel-heading">
       <h3 class="panel-title font_14">Blocks 24 Hours</h3>
       </div>
       <div class="panel-body font_22">
       <? print $blocks; ?><p class="font_10">mined in 24 hours</p>
       </div>
       </div>
       
       </td>
       <td align="center" width="25%">
       
       <div class="panel panel-default" style="width:90%">
       <div class="panel-heading">
       <h3 class="panel-title font_14">Revenue 24 Hours</h3>
       </div>
       <div class="panel-body font_22" style="color:#009900">
       <? print "$".round($blocks*$reward*$_REQUEST['sd']['MSK_price']); ?><p class="font_10"><? print $reward." MSK / block"; ?></p>
       </div>
       </div>
       
       </td>
     </tr>
   </tbody>
 </table>
        
        <?
	}
	
	function getImg($core)
	{
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		if ($row['cpu_'.$core.'_power']>0)
		  return "<div class='loader'></div>";
		else
		  return "<img src=\"GIF/cpu.png\" width=\"77\" height=\"77\" />";
	}
	
	function getPower($core)
	{
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		return round($row['cpu_'.$core.'_power']/1000, 2);
	}
	
	function showCores()
	{
		// Cores
		$query="SELECT * FROM web_sys_data";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    $cores=$row['mining_threads'];
		
		?>
         
         <br>
         <div class="panel panel-default" style="width:87%">
         <div class="panel-heading">
         <h3 class="panel-title font_14">CPU Cores</h3>
         </div>
         <div class="panel-body font_22">
         <table width="100%">
         
         <tr>
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(1); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(1)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(1)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(2); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(2)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(2)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(3); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(3)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(3)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(4); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(4)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(4)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(5); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(5)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(5)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(6); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(6)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(6)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(7); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(7)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(7)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(7); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(7)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(7)." Khs"; ?></strong></td></tr></table></td>
         </tr>
         
         <?
		     if ($cores>8)
			 {
				 ?>
                 
                 <tr><td colspan="8"><hr></td></tr>
                 <tr>
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(9); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(9)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(9)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(10); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(10)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(10)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(11); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(11)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(11)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(12); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(12)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(12)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(13); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(13)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(13)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(14); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(14)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(14)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(15); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(15)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(15)." Khs"; ?></strong></td></tr></table></td>
         
         <td width="12%" align="center"><table><tr>
         <td align="center"><? print $this->getImg(16); ?></td></tr><tr><td align="center" height="40px" class="font_16" <? if ($this->getPower(16)==0) print "style=\"color:#bbbbbb\""; ?>><strong><? print $this->getPower(16)." Khs"; ?></strong></td></tr></table></td>
         </tr>
                 
                 <?
			 }
		 ?>
        
         
         
         </table>
         </div>
         </div>
        
        <?
	}
	
	function showTopBar()
	{
		?>
            
            <br><br>
            <form method="post" name="form_miner" id="form_miner" action="index.php?act=<? if ($_REQUEST['sd']['mining_threads']) print "stop"; else print "start"; ?>">
            <table width="87%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
            <td height="100" align="center" bgcolor="#fafafa"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
            <tbody>
            <tr>
             <td bgcolor="#fafafa" class="font_14" width="60%" height="40px"><strong>Delegate <? if ($_REQUEST['sd']['delegate']!="") print "(delegate power ".$this->getDelegatePower($_REQUEST['sd']['delegate'])." MSK)"; ?></strong></td>
             <td width="20%" align="left" bgcolor="#fafafa" class="font_14"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cores</strong></td>
             <td width="20%" align="left" bgcolor="#fafafa" class="font_14"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Actions</strong></td>
            </tr>
            <tr>
             <td bgcolor="#fafafa"><input class="form-control" name="txt_delegate" id="txt_delegate" value="<? print $_REQUEST['sd']['delegate']; ?>"></td>
             <td align="center" bgcolor="#fafafa">
             
             <select id="dd_cores" name="dd_cores" class="form-control" style="width:80%" <? if ($_REQUEST['sd']['mining_threads']>0) print "disabled"; ?>>
             <option value="1" <? if ($_REQUEST['sd']['mining_threads']==1) print "selected"; ?>>1 Core</option>
             <option value="2" <? if ($_REQUEST['sd']['mining_threads']==2) print "selected"; ?>>2 Cores</option>
             <option value="3" <? if ($_REQUEST['sd']['mining_threads']==3) print "selected"; ?>>3 Cores</option>
             <option value="4" <? if ($_REQUEST['sd']['mining_threads']==4) print "selected"; ?>>4 Cores</option>
             <option value="5" <? if ($_REQUEST['sd']['mining_threads']==5) print "selected"; ?>>5 Cores</option>
             <option value="6" <? if ($_REQUEST['sd']['mining_threads']==6) print "selected"; ?>>6 Cores</option>
             <option value="7" <? if ($_REQUEST['sd']['mining_threads']==7) print "selected"; ?>>7 Cores</option>
             <option value="8" <? if ($_REQUEST['sd']['mining_threads']==8) print "selected"; ?>>8 Cores</option>
             <option value="9" <? if ($_REQUEST['sd']['mining_threads']==9) print "selected"; ?>>9 Cores</option>
             <option value="10" <? if ($_REQUEST['sd']['mining_threads']==10) print "selected"; ?>>10 Cores</option>
             </select>
             
             </td>
             <td align="center" bgcolor="#fafafa"><a href="javascript:void(0)" onClick="$('#form_miner').submit()" class="btn <? if ($_REQUEST['sd']['mining_threads']) print "btn-danger"; else print "btn-success"; ?>"><span class="glyphicon <? if ($_REQUEST['sd']['mining_threads']==0) print "glyphicon-dashboard"; else print "glyphicon-ban-circle"; ?>"></span>&nbsp;&nbsp;<? if ($_REQUEST['sd']['mining_threads']==0) print "Start Miners"; else print "Stop Miners"; ?></a></td>
             </tr>
             </tbody>
             </table></td>
             </tr>
             </tbody>
             </table>
             </form>
        
        <?
	}
	
	function getDelegatePower($adr)
	{
		$query="SELECT * 
		          FROM delegates 
				 WHERE delegate='".$adr."'";
	    $result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	    return $row['power'];
	}
	
	function startMiners($delegate, $cores)
	{
		// Cores
		if ($cores>24 || $cores<1)
		{
			$this->template->showErr("Invalid cores number");
			return false;
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Start mining");
		   
		   /// Update delegate
		   $query="UPDATE net_stat SET delegate='".$delegate."'";
		   $this->kern->execute($query);
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_START_MINING', 
								par_1='".$cores."',
								status='ID_PENDING', 
								tstamp='".time()."'";
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Miners number
		   $_REQUEST['sd']['mining_threads']=$cores;
		   
		   // Delay
		   sleep(2);
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
		   
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   } 
	   
	   
	}
	
	function stopMiners()
	{
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Stop mining");
		   
		   /// Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_STOP_MINING', 
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Miners number
		   $_REQUEST['sd']['mining_threads']=0;
		   
		   // Delay
		   sleep(2);
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   } 
	   
	  
	}
	
	function showLastBlocks()
	{
		$query="SELECT * 
		          FROM blocks 
				 WHERE signer='".$_REQUEST['sd']['delegate']."' 
			  ORDER BY ID DESC LIMIT 0,20";
		$result=$this->kern->execute($query);	
	    
		?>
        
        <br><br>
       <table width="87%">
        
        <?
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		   { 
		?>
        
              <tr>
              <td width="80%" class="font_14"><a hre="#"><strong><? print "Block ".$row['block']; ?></strong></a><p class="font_10"><? print $row['hash']; ?></p></td>
              <td width="20%" class="font_14"><? print $this->kern->getAbsTime($row['tstamp']); ?></td>
              </tr>
              <tr><td colspan="2"><hr></td></tr>
        
        <?
		   }
		?>
        
        </table>
        <br><br>
        
        <?
	}
	
	function showNetDif()
	{
		?>
        
        <br>
        <div class="panel panel-default" style="width:90%">
       <div class="panel-heading">
       <h3 class="panel-title font_14">Default Network Difficulty</h3>
       </div>
       <div class="panel-body font_18">
       <? print $_REQUEST['sd']['net_dif']; ?><p class="font_10">Default network difficulty applies only to delegates voted by a total of 1 MSK. Network difficulty decreases depending on how many votes a delegates has received. Delegates having a bigger number of votes will be able to mine using a smaller network difficulty.</p>
       </div>
       </div>
        
        <?
	}
}
?>