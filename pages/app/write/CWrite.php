<?
class CWrite
{
	function CWrite($db, $template, $app)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->app=$app;
	}
	
	function test($appID, 
	              $target="", 
				  $trans_sender="", 
				  $trans_amount=0, 
				  $trans_cur="", 
				  $trans_mes="", 
				  $trans_escrower="", 
				  $mes_sender="", 
				  $mes_subj="", 
				  $mes_mes="", 
				  $block_hash="", 
				  $block_no=0, 
				  $block_nonce=0)
	{
		// Decode
		$trans_mes=base64_decode($trans_mes); 
		$mes_subj=base64_decode($mes_subj); 
		$mes_mes=base64_decode($mes_mes); 
		
		// Application exist ?
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'
				   AND userID='".$_REQUEST['ud']['ID']."'
				   AND compiler='SURfT0s='";
	    $result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		// Target
		if ($target!="ID_BLOCK" && 
		    $target!="ID_MES" && 
			$target!="ID_TRANS" && 
			$target!="ID_DEFAULT")
		{
			$this->template->showErr("Invalid simulation target");
			return false;
		}
		
		// Simulate transaction
		if ($target=="ID_TRANS")
		{
			// Sender
			if ($this->kern->isAdr($trans_sender)==false)
			{
				$this->template->showErr("Invalid sender");
			    return false;
			}
			
			// Amount
			if ($this->kern->isNumber($trans_amount)==false || 
			   $trans_amount<0.00000001)
			{
			   if ($this->kern->isNumber($trans_amount)==false)
			   {
				  $this->template->showErr("Invalid amount");
			      return false;
			   }
			}
			
			// Currency
			if ($this->kern->isCur($trans_cur)==false)
			{
				$this->template->showErr("Invalid currency");
			    return false;
			}
			
			// Message
			if ($trans_mes!="")
			{
				if (strlen($trans_mes)>1000)
				{
					$this->template->showErr("Invalid message");
			        return false;
				}
			}
			
			// Escrower
			if (strlen($trans_escrower)>5)
			{
				if ($this->kern->isAdr($trans_escrower)==false)
			    {
				   $this->template->showErr("Invalid escrower");
			       return false;
			    }
			}
		}
		
		// Simulate message
		if ($target=="ID_MES")
		{
			// Sender
			if ($this->kern->isAdr($mes_sender)==false)
			{
				$this->template->showErr("Invalid sender");
			    return false;
			}
			
			// Subject
			if ($mes_subj!="")
			{
				if (strlen($mes_subj)>250 || $this->kern->isString($mes_subj))
				{
					$this->template->showErr("Invalid suject");
			        return false;
				}
			}
			
			// Message
			if ($mes_mes!="")
			{
				if (strlen($mes_subj)>1000 || $this->kern->isString($mes_subj))
				{
					$this->template->showErr("Invalid message");
			        return false;
				}
			}
		}
		
		// Simulate new block
		if ($target=="ID_BLOCK")
		{
			// Block hash
			if ($block_hash!="")
			{
				if ($this->kern->isHash($block_hash)==false)
				{
					$this->template->showErr("Invalid block hash");
			        return false;
				}
			}
			
			// Block number
			if ($block_no!="")
			{
				if ($this->kern->isLong($block_no)==false)
				{
					$this->template->showErr("Invalid block number");
			        return false;
				}
			}
			
			// Block nonce
			if ($block_nonce!="")
			{
				if ($this->kern->isLong($block_nonce)==false)
				{
					$this->template->showErr("Invalid block nonce");
			        return false;
				}
			}
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Test an application");
		
		   // Insert to stack
		   $query="UPDATE agents_mine 
		              SET run='ID_PENDING', 
					      simulate_target='".$target."', 
						  trans_sender='".$trans_sender."', 
						  trans_amount='".$trans_amount."', 
						  trans_cur='".$trans_cur."', 
						  trans_mes='".base64_encode($trans_mes)."', 
						  trans_escrower='".$trans_escrower."', 
						  mes_sender='".$mes_sender."', 
						  mes_subj='".base64_encode($mes_subj)."', 
						  mes_mes='".base64_encode($mes_mes)."', 
						  block_hash='".$block_hash."', 
						  block_no='".$block_no."', 
						  block_nonce='".$block_nonce."'   
					WHERE ID='".$appID."'"; 
	        $this->kern->execute($query);
			
		   // Commit
		   $this->kern->commit();
		  
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
	
	function deployToNet($appID, 
	                     $net_fee_adr, 
						 $adr, 
						 $interval, 
						 $days)
	{
		// Application exist ?
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'
				   AND userID='".$_REQUEST['ud']['ID']."'
				   AND compiler='SURfT0s='";
	    $result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		// Already a sealed contract on this address ?
		$query="SELECT * 
		          FROM agents 
				 WHERE adr='".$adr."' 
				   AND sealed>0";
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)>0)
		{
			$this->template->showErr("There is already a sealed contract attached to this address");
			return false;
		}
		
		// Net fee adr
		if ($this->kern->adrExist($adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Address
		if ($this->kern->adrExist($adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid address");
			return false;
		}
		
		// Address balance
		if ($this->kern->getBalance($adr)<0.01)
		{
			$this->template->showErr("Minimum address balance is 0.01");
			return false;
		}
		
		// Days
		if ($days<1)
		{
			$this->template->showErr("Minimum 1 days allowed");
			return false;
		}
		
		// Fee
		$fee=$days*0.0001;
		
		// Balance
		if ($this->kern->getBalance($net_fee_adr)<$fee)
		{
			$this->template->showErr("Insufficient funds");
			return false;
		}
		
		// Interval
		if ($interval<0 || $interval=="")
		{
			$this->template->showErr("Invalid run interval");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Deploys an app to network");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_DEPLOY_APP_NET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$appID."',
								par_2='".$interval."',
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
	
	
	function save($appID, $code="")
	{
		// Application exist ?
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'
				   AND userID='".$_REQUEST['ud']['ID']."'";
	    $result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Save app changes");
		
		    // Insert to stack
		   $query="UPDATE agents_mine 
		              SET code='".base64_encode($code)."', 
					      compiler='SURfUEVORElORw==' 
					WHERE ID='".$appID."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function newDAPP($adr, $name)
	{
		// Decode
		$name=base64_decode($name);
			
		// Check network fee address
		if ($this->kern->adrValid($adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Net fee address mine ?
		if ($this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Writes a new dapp");
		
		    // Insert to stack
		   $query="INSERT INTO agents_mine 
			               SET adr='".$adr."', 
						       globals='',
						       interface='',
						       signals='',
						       storage='',
						       code='',
							   exec_log='',
							   userID='".$_REQUEST['ud']['ID']."',
							   name='".base64_encode($name)."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function deleteDAPP($ID)
	{
	    $query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$ID."'
				   AND userID='".$_REQUEST['ud']['ID']."'";
	    $result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Writes a new dapp");
		
		    // Insert to stack
		   $query="DELETE FROM agents_mine WHERE ID='".$ID."'"; 
	       $this->kern->execute($query);
		   
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function showWriteBut()
	{
		?>
        
            <table width="90%">
            <tr><td width="89%" align="right">
            
            <a class="btn btn-primary" href="javascript:void(0)" onClick="$('#write_modal').modal()">
            <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Project</a>
            
            </td>
              <td width="1%" align="right">&nbsp;</td>
            <td width="10%" align="right">
            
            <a class="btn btn-warning" href="../reference/index.php">
            <span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;&nbsp;Reference
            </a>
            
            </td>
            </tr>
            </table>
        
        <?
	}
	
	
	function showWriteModal()
	{
		$this->template->showModalHeader("write_modal", "New Decentralized Application", "act", "new");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/write.png" width="150" height="150" alt=""/></td>
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
               <td height="30" align="left" valign="top" style="font-size:14px"><strong>Test Address</strong></td>
             </tr>
             <tr>
               <td width="391" align="left">
			   <?
			      $this->template->showMyAdrDD("dd_adr");
			   ?>
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px"><strong>Application Name</strong></td>
             </tr>
             <tr>
               <td align="left">
               <input type="text" class="form-control" style="width:300px" id="txt_new_name" name="txt_new_name" placeholder="Name" />
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
      <script>
		$('#form_write_modal').submit(
		function() 
		{ 
		   $('#txt_new_name').val(btoa($('#txt_new_name').val())); 
		});
	</script>
     
    <?
		$this->template->showModalFooter("Write");
		
	}
	
	function showDeployNetModal()
	{
		$this->template->showModalHeader("deploy_modal", "Deploy Application", "act", "deploy_net", "deploy_net_appID", "");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/deploy.png" width="150" height="150" alt=""/></td>
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
               <td height="30" align="left" valign="top" style="font-size:14px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px"><? $this->template->showMyAdrDD("dd_deploy_net_fee"); ?></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:16px"><strong>Install on this Address</strong></td>
             </tr>
             <tr>
               <td width="391" align="left">
			   <?
			      $this->template->showMyAdrDD("dd_deploy_net_adr");
			   ?>
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top"><table width="350px" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td width="35%" align="left"><span style="font-size:14px"><strong>Days</strong></span></td>
                     <td width="31%" align="left"><span style="font-size:14px"><strong>Run Interval</strong></span></td>
                     <td width="34%" align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_deploy_net_days" name="txt_deploy_net_days" placeholder="100" /></td>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_deploy_net_interval" name="txt_deploy_net_interval" placeholder="0" /></td>
                     <td align="left">&nbsp;</td>
                   </tr>
                 </tbody>
               </table></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
            </table></td>
         </tr>
     </table>
     
      <script>
		$('#form_write_modal').submit(
		function() 
		{ 
		   $('#txt_name').val(btoa($('#txt_name').val())); 
		});
	</script>
     
    <?
		$this->template->showModalFooter("Deploy");
		
	}
	
	function showMyAgents()
	{
		// Deploy to network
		$this->showDeployNetModal();
		
		// Confirm modal
		$this->template->showConfirmModal();
		
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE userID='".$_REQUEST['ud']['ID']."' 
			  ORDER BY ID DESC ";
	    $result=$this->kern->execute($query);	
	  
		?>
           
           <br>
           <table class="tbale-responsive" width="90%">
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="8%"><img src="./GIF/write.png" class="img-responsive"></td>
                 <td width="2%">&nbsp;</td>
                 <td width="50%"><a class="font_16" href="#"><? print base64_decode($row['name']); ?></a>
                 <p class="font_12"><? print $this->template->formatAdr($row['adr']); ?></p></td>
                 <td width="10%">
                 <?
				    if (base64_decode($row['compiler'])=="ID_OK")
		               print "<span class='label label-success'>Ready</span>";
	                else if (base64_decode($row['compiler'])=="ID_PENDING")
		               print "<span class='label label-warning'>Compiling...</span>";
              		else
		               print "<span class='label label-danger'>Errors</span>";
                 ?>
                 </td>
                 <td width="20%">&nbsp;</td>
                 <td width="12%">
                 
                 <div class="btn-group">
                 <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">
                 <span class="glyphicon glyphicon-cog"><span class="caret"></span></button>
                 <ul role="menu" class="dropdown-menu">
                 <li><a href="edit.php?ID=<? print $row['ID']; ?>">Edit</a></li>
                 <? 
				    if (base64_decode($row['compiler'])=="ID_OK") 
				    {
						print "<li><a href=\"javascript:void(0)\" onclick=\"$('#deploy_modal').modal(); $('#deploy_net_appID').val('".$row['ID']."'); \">Deploy to Network</a></li>"; 
						
					}
				 ?>
                 <li><a href="javascript:void(0)" onClick="$('#confirm_modal').modal(); $('#par_1').val('<? print $row['ID']; ?>');">Remove</a></li>
                 </div>
                 
                 </td>
                 </tr>
                 <tr><td colspan="6"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
        
        <?
	}
	
	function showTestModal()
	{
		$this->template->showModalHeader("test_modal", "Test Application", "act", "test");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/test.png" width="150" height="150" alt=""/></td>
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
           <td width="90%" align="right" valign="top">
           
           
           
           <table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="391" height="30" align="left" valign="top" style="font-size:14px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px">
               
               <select name="dd_test_type" id="dd_test_type" class="form-control" style="width:330px" onChange="change()">
               <option value="ID_DEFAULT" selected>Simple Run</option>
               <option value="ID_TRANS">Simulate Transaction</option>
               <option value="ID_MES">Simulate Message</option>
               <option value="ID_BLOCK">Simulate New Block</option>
               </select>
               
               </td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:16px">
               
               
               
               <table width="100%" border="0" cellpadding="0" cellspacing="0" name="tab_trans" id="tab_trans" style="display:none">
                 <tbody>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Sender Address</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:330px" id="txt_test_sender" name="txt_test_sender" placeholder="sender" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td align="left"><table width="350px" border="0" cellpadding="0" cellspacing="0">
                       <tbody>
                         <tr>
                           <td width="35%" align="left" class="font_14"><strong>Amount</strong></td>
                           <td width="31%" align="left" class="font_14"><strong>Currency</strong></td>
                           <td width="34%" align="left">&nbsp;</td>
                         </tr>
                         <tr>
                           <td align="left">
                           <input type="text" class="form-control" style="width:100px" id="txt_test_amount" name="txt_test_amount" placeholder="100" /></td>
                           <td align="left">
                           <input type="text" class="form-control" style="width:100px" id="txt_test_cur" name="txt_test_cur" value="MSK" /></td>
                           <td align="left">&nbsp;</td>
                         </tr>
                       </tbody>
                     </table></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Message</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <textarea class="form-control" style="width:330px" id="txt_test_mes" name="txt_test_mes"></textarea>
                     </td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Escrower</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:330px" id="txt_test_escrower" name="txt_test_escrower" placeholder="escrower" /></td>
                   </tr>
                 </tbody>
               </table>
               
               
               <table width="100%" border="0" cellpadding="0" cellspacing="0" name="tab_mes" id="tab_mes" style="display:none">
                 <tbody>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Sender Address</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:330px" id="txt_test_mes_sender" name="txt_test_mes_sender" placeholder="sender" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Subject</strong></td>
                   </tr>
                   <tr>
                     <td align="left"><input type="text" class="form-control" style="width:330px" id="txt_test_mes_subj" name="txt_test_mes_subj" placeholder="sender" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Message</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <textarea class="form-control" style="width:330px" id="txt_test_mes_mes" name="txt_test_mes_mes"></textarea>
                     </td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                 </tbody>
               </table>
               
               
               <table width="100%" border="0" cellpadding="0" cellspacing="0" name="tab_new_block" id="tab_new_block" style="display:none">
                 <tbody>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Block Hash</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:330px" id="txt_test_block_hash" name="txt_test_block_hash" placeholder="hash" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Block Number</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_test_block_no" name="txt_test_block_no" placeholder="0" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                   <tr>
                     <td height="30" align="left" valign="top" class="font_14"><strong>Block Nonce</strong></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_test_block_nonce" name="txt_test_block_nonce" placeholder="0" /></td>
                   </tr>
                   <tr>
                     <td align="left">&nbsp;</td>
                   </tr>
                 </tbody>
               </table>
               
               
               
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
            </table></td>
         </tr>
     </table>
     
      <script>
		function change()
		{
			$('#tab_trans').css('display', 'none');
			$('#tab_mes').css('display', 'none');
			$('#tab_new_block').css('display', 'none');
			
			switch ($('#dd_test_type').val())
			{
				case "ID_TRANS" : $('#tab_trans').css('display', 'block'); break;
				case "ID_MES" : $('#tab_mes').css('display', 'block'); break;
				case "ID_BLOCK" : $('#tab_new_block').css('display', 'block'); break;
			}
		}
		
	    $('#form_test_modal').submit(
		function() 
		{ 
		   $('#txt_test_mes').val(btoa(unescape(encodeURIComponent($('#txt_test_mes').val())))); 
		   $('#txt_test_mes_subj').val(btoa(unescape(encodeURIComponent($('#txt_test_mes_subj').val())))); 
		   $('#txt_test_mes_mes').val(btoa(unescape(encodeURIComponent($('#txt_test_mes_mes').val())))); 
		});
	</script>
	 
    <?
		$this->template->showModalFooter("Start");
		
	}
	
	function showSaveBut($appID, $editor="editor_code")
	{
		// test modal
		$this->showTestModal();
		
		// Load agent data
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <table width="90%">
            <tr>
            <td align="left" width="70%">
            
            <div style="display:none" id="div_progress" name="div_progress">
            <span class="font_12">Compiling...</span><br> 
            <div class="progress">
            <div class="progress-bar" style="width:0%;  -webkit-transition: none !important; transition: none !important;" id="progress" name="progress">
            &nbsp;
            </div>
            </div>
            </div>
            
            <div id="div_result" name="div_result"></div>
            
            
            </td>
            <td align="right">
            <a class="btn btn-danger btn-sm" href="javascript:void(0)" onClick="
            $.post('write.php?act=save_code', 
                   { appID : <? print $appID; ?>, code : <? print $editor; ?>.getValue() }, 
                   function() 
                   { 
                      $('#progress').css('width', '0%');
                      $('#but_save').attr('disabled', 'disabled');
                      $('#div_progress').css('display', 'block');
                      $('#div_result').css('display', 'none');
                      
                      $('#progress').animate(
                      { width : '100%' }, 
                      2000, 
                      function() 
                      { 
                          $('#div_progress').css('display', 'none'); 
                          $('#div_result').load('write.php?act=get_status&target=code&appID=<? print $appID; ?>');
                          $('#div_result').css('display', 'block');
                      });
                   });" 
            id="but_save" name="but_save" disabled>
            <span class="glyphicon glyphicon-tasks"></span>&nbsp;&nbsp;&nbsp;Save</a></td>
            <td width="2%">&nbsp;</td>
            
            <td><a class="btn btn-success btn-sm" <? if (base64_decode($row['compiler'])!="ID_OK") print "disabled"; ?> href="javascript:void(0)" onClick="$('#test_modal').modal(); $('#txt_test_block_hash').val('<? print $row['block_hash']; ?>'); $('#txt_test_block_no').val('<? print $row['block_no']; ?>'); $('#txt_test_block_nonce').val('<? print $row['block_nonce']; ?>'); $('#txt_test_mes_sender').val('<? print $row['mes_sender']; ?>'); $('#txt_test_mes_subj').val('<? print base64_decode($row['mes_subj']); ?>'); $('#txt_test_mes_mes').val('<? print base64_decode($row['mes_mes']); ?>'); $('#txt_test_sender').val('<? print $row['trans_sender']; ?>'); $('#txt_test_amount').val('<? print $row['trans_amount']; ?>'); $('#txt_test_cur').val('<? print $row['trans_cur']; ?>'); $('#txt_test_mes').val('<? print base64_decode($row['trans_mes']); ?>'); $('#txt_test_escrower').val('<? print $row['trans_escrower']; ?>');" id="but_test" name="but_test"><span class="glyphicon glyphicon-road"></span>&nbsp;&nbsp;&nbsp;Test</a></td>
            
            </td></tr>
            </table>
            <br>
        
        <?
	}
	
	function showRunLog($appID)
	{
		// Load code
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	   
		?>
        
           <div id="exec_log" align="left"><? 
		      $json=base64_decode($row['exec_log']); 
			  $json=json_decode($json); 
			  
			  for ($a=0; $a<=sizeof($json->log)-1; $a++) print "Line ".$json->log[$a]->line." : ".$json->log[$a]->ins."\n";
			 
		   ?></div>


           <script src="./ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
           <script>
              var exec_log = ace.edit("exec_log");
              exec_log.setTheme("ace/theme/chrome");
              exec_log.setShowPrintMargin(false);
			  
			  exec_log.getSession().selection.on('changeCursor', 
			  function(e) 
			  {
				  var line=exec_log.session.getLine(exec_log.getSelectionRange().start.row);
				  var v=line.split(" ");
				  var line_no=v[1];
				  run_code.gotoLine(line_no);
			  });
		    </script>
        
        <?
	}
	
	function showRunEditor($appID)
	{
		// Load code
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
           <div id="editor_code" align="left"><? print str_replace("<", "&lt;", base64_decode($row['code'])); ?></div>


           <script src="./ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
           <script>
              var run_code = ace.edit("editor_code");
              run_code.setTheme("ace/theme/chrome");
              run_code.getSession().setMode("ace/mode/assembly_x86");
			  run_code.setShowPrintMargin(false);
			  
	 		  run_code.getSession().on('change', function(e) 
			  {
                  $('#but_save').removeAttr('disabled'); 
				  $('#but_test').attr('disabled', 'disabled');
			  });
			   
           </script>
        
        <?
	}
	
	function getRunPage($appID)
	{
		?>
        
           <table width="90%">
           <tr>
           <td width="49%"><? $this->showRunEditor($appID); ?></td>
           <td width="2%">&nbsp;</td>
           <td width="49%"><? $this->showRunLog($appID); ?></td>
           </tr>
           </table>
        
        <?
	}
	
	function showRunPage($appID)
	{
		// Save button
		$this->showSaveBut($appID, "run_code");
		
		?>
        
        <div name="div_run" id="div_run">
        <table width="90%">
        <tr><td align="center" height="50px">&nbsp;</td></tr>
        <tr><td align="center"><img src="./GIF/loader.gif"></td></tr>
        <tr><td align="center" class="font_12" height="50px">Summoning virtual machine...</td></tr>
        </table>
        </div>
        
        <script>
		function execute()
		{
			clearInterval(i);
			$('#div_run').load('write.php?act=get_run_res&appID=<? print $_REQUEST['ID']; ?>');
		}
		
		var i=setInterval(execute, 2000);
		</script>
        
        <?
	}
	
	function showEditor($appID)
	{
		// Save button
		$this->showSaveBut($appID);
	    
		// Load code
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'";
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
           <div id="editor_code" align="left"><? print str_replace("<", "&lt;", base64_decode($row['code'])); ?></div>


           <script src="./ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
           <script>
              var editor_code = ace.edit("editor_code");
              editor_code.setTheme("ace/theme/chrome");
              editor_code.getSession().setMode("ace/mode/assembly_x86");
			  editor_code.setShowPrintMargin(false);
			  
	 		  editor_code.getSession().on('change', function(e) 
			  {
                  $('#but_save').removeAttr('disabled'); 
				  $('#but_test').attr('disabled', 'disabled');
			  });
			   
           </script>
        
        <?
	}
	
	function getStatus($appID, $target)
	{
		// Load code
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Get status
		if ($target=="code") $status=$row['compiler']; 		
		else if ($target=="globals") $status=$row['compiler_globals']; 		
		else if ($target=="interface") $status=$row['compiler_interface']; 		
		else if ($target=="signals") $status=$row['compiler_signals']; 		
		
		// Decode
		$status=base64_decode($status);
	
		if ($status=="ID_OK")
		  print "<span class='label label-success'>No syntax errors<script>$('#but_test').removeAttr('disabled')</script></span>";
	    else if ($status=="ID_PENDING")
		  print "<span class='label label-warning'>Still Compiling...</span>";
		else
		  print "<span class='label label-danger'>".$status."</span>";
	}
	
}
?>