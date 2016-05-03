<?
class CApp
{
	function CApp($db, $template)
	{
		  $this->kern=$db;
		  $this->template=$template;
	} 
	
	function update($type, 
                    $appID, 
	                $net_fee_adr,
					$days)
	{
		// Type ok ?
		if ($type!="ID_REMOVE_STORE" && 
		    $type!="ID_REMOVE_DIR" && 
		    $type!="ID_SEAL" && 
		    $type!="ID_UNINSTALL" && 
			$type!="ID_FROZE")
	    {
			 $this->template->showErr("Invalid operation");
			 return false;
		}
		
		// Load
		$query="SELECT * 
		          FROM agents
				 WHERE aID='".$appID."'
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')"; 
				   	   
	    $result=$this->kern->execute($query);
		
		// No results
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		// Agent data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Application address
		$adr=$row['adr'];
		
		// Sealed ?
		if ($row['sealed']>0)
		{
			$this->template->showErr("No operations can be applied on a sealed application");
			return false;
		}
		
		// Remove from market ?
		if ($type=="ID_REMOVE_MARKET" && $row['price']==0)
		{
			$this->template->showErr("Invalid operation");
			return false;
		}
		
		// Remove from directory ?
		if ($type=="ID_REMOVE_DIR" && $row['dir']==0)
		{
			$this->template->showErr("Invalid operation");
			return false;
		}
		
		// Net fee adr
		if ($this->kern->adrExist($adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Valid net fee adr
		if ($this->kern->feeAdrValid($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address (has security attributes / applicaation attached)");
			return false;
		}
		
		// Balance
		if ($this->kern->getBalance($net_fee_adr)<0.0001)
		{
			$this->template->showErr("Insufficient funds");
			return false;
		}
		
		// Days
		if ($type=="ID_SEAL")
		{
			if ($days=="" || $days<1)
			{
			  $this->template->showErr("Invalid days");
			  return false;
			}
		}
		
		// Days
		if ($days=="") $days=0;
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates an application");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_UPDATE_APP', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$appID."',
								par_2='".$type."', 
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
	
	function publish($target, 
                     $appID, 
	                 $net_fee_adr, 
					 $categ,
					 $name, 
					 $desc, 
					 $pay_adr, 
					 $website, 
					 $pic, 
					 $ver, 
					 $price)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 $website=base64_decode($website); 
		 $pic=base64_decode($pic);
		 
		 // Target valid
		 if ($target!="ID_DIR" && 
		     $target!="ID_STORE")
		 {
			  $this->template->showErr("Invalid target");
			  return false;
		 }
		 
		 // Load agent 
		 $query="SELECT * 
		          FROM agents
				 WHERE aID='".$appID."'
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')";
				   	   
	    $result=$this->kern->execute($query);
		
		// No results
		if (mysql_num_rows($result)==0)
		{
		    $this->template->showErr("Invalid app ID");
			return false;
		}
		
		// Agent data
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Adr
		$adr=$row['adr'];
		
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
		if ($type=="ID_STORE")
		{
		   if ($this->kern->getBalance($adr)<0.01)
		   {
			  $this->template->showErr("Minimum address balance is 0.01");
			  return false;
		   }
		
		
		   // Payment address
		   if ($this->kern->isAdr($pay_adr)==false)
		   {
			  $this->template->showErr("Invalid payment address");
			  return false;
		   }
		}
		
		// Fee
		$fee=0.0001;
		
		// Balance
		if ($this->kern->getBalance($net_fee_adr)<$fee)
		{
			$this->template->showErr("Insufficient funds");
			return false;
		}
		
		// Price
		if ($price<0)
		{
			$this->template->showErr("Invalid price");
			return false;
		}
		
		// Version
		if (strlen($ver)>10 || strlen($ver)<1)
		{
			$this->template->showErr("Invalid version");
			return false;
		}
		
		// Name
		if ($this->kern->titleValid($name)==false)
		{
			$this->template->showErr("Invalid name");
			return false;
		}
		
		// Description
		if ($this->kern->descValid($desc)==false)
		{
			$this->template->showErr("Invalid description");
			return false;
		}
		
		// Website
		if ($website!="")
		{
			if ($this->kern->isLink($website)==false)
		    {
			   $this->template->showErr("Invalid website");
			   return false;
		    }
		}
		
		// Pic
		if ($pic!="")
		{
			if ($this->kern->isImageLink($pic)==false)
		    {
			   $this->template->showErr("Invalid pic");
			   return false;
		    }
		}
		
		// Category
		if ($categ!="ID_BUSINESS" && 
		    $categ!="ID_EDUCATION" && 
		    $categ!="ID_ENTERTAINMENT" && 
		    $categ!="ID_FINANCE" && 
		    $categ!="ID_GAMES" && 
		    $categ!="ID_GAMBLING" && 
		    $categ!="ID_PRODUCTIVITY" && 
		    $categ!="ID_SHOPPING" && 
		    $categ!="ID_TRADING" && 
		    $categ!="ID_UTILITIES")
		{
			 $this->template->showErr("Invalid category");
			 return false;
		}
		
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Deploys an app app store / directory");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_PUBLISH_APP', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$target."',
								par_2='".$appID."',
								par_3='".$categ."',
								par_4='".base64_encode($name)."',
								par_5='".base64_encode($desc)."',
								par_6='".$pay_adr."',
								par_7='".base64_encode($website)."',
								par_8='".base64_encode($pic)."',
								par_9='".base64_encode($ver)."',
								par_10='".$price."', 
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
	
	function showPublishModal()
	{
		$this->template->showModalHeader("publish_modal", "Publish Application", "act", "publish", "publish_appID", "");
		?>
        
           <input name="txt_publish_target" id="txt_publish_target" value="ID_DIR" type="hidden">
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../write/GIF/directory.png" width="150" height="150" id="img_publish" name="img_publish"/></td>
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
               <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></span></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px"><? $this->template->showMyAdrDD("dd_publish_fee", 350); ?></td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left"><span class="font_14"><strong>Category<strong><strong></strong></strong></strong></span></td>
             </tr>
             <tr>
               <td align="left">
               <select id="dd_publish_categ" name="dd_publish_categ" class="form-control" style="width:350px">
               <?
			      $query="SELECT * 
				            FROM agents_categs 
						   WHERE categID<>'ID_ALL'";
				  $result=$this->kern->execute($query);	
	              
				  while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	                print "<option value='".$row['categID']."'>".$row['name']."</option>";
			   ?>
               </select>
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Application Name<strong> <strong> (5-50 characters)</strong></strong></strong></td>
             </tr>
             <tr>
               <td align="left">
               <input type="text" class="form-control" style="width:350px" id="txt_publish_name" name="txt_publish_name" placeholder="Name" />
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Application Description <strong> (10-250 characters)</strong></strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <textarea class="form-control" id="txt_publish_desc" name="txt_publish_desc" placeholder="Description (10-500 charcaters)" rows="5" style="width:350px"></textarea></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             
             <tr id="tr_pay_adr" name="tr_pay_adr"><td>
             <table>
               <td height="0" align="left"><strong class="font_14">Payment Address<strong><span class="font_12"> (receive payments to this address)</span></strong></strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:350px" id="txt_publish_pay_adr" name="txt_publish_pay_adr" placeholder="Payment address" /></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             </table>
             </td></tr>
             
             <tr>
               <td height="0" align="left">
               <table width="350px" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td align="left"><span class="font_14"><strong>Website (optional)</strong></span></td>
                     <td align="left"><span class="font_14"><strong>Pic<strong> (optional)</strong></strong></span></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:160px" id="txt_publish_website" name="txt_publish_website" placeholder="Website" /></td>
                     <td align="left">
                     <input type="text" class="form-control" style="width:160px" id="txt_publish_pic" name="txt_publish_pic" placeholder="Pic" /></td>
                   </tr>
                 </tbody>
               </table></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="0" align="left">
               <table width="300" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td width="33%" align="left"><span class="font_14"><strong>Version</strong></span></td>
                     <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <td width="33%" align="left" id="txt_publish_price_txt" name="txt_publish_price_txt"><span class="font_14"><strong>Price</strong></span></td>
                   </tr>
                   <tr>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_publish_ver" name="txt_publish_ver" placeholder="0.0.1" /></td>
                     <td>&nbsp;</td>
                     <td align="left">
                     <input type="text" class="form-control" style="width:100px" id="txt_publish_price" name="txt_publish_price" placeholder="0.01" /></td>
                     
                   </tr>
                 </tbody>
               </table></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
      <script>
	 $('#form_publish_modal').submit(
		function() 
		{ 
		   $('#txt_publish_name').val(btoa(unescape(encodeURIComponent($('#txt_publish_name').val())))); 
		   $('#txt_publish_desc').val(btoa(unescape(encodeURIComponent($('#txt_publish_desc').val())))); 
		   $('#txt_publish_website').val(btoa(unescape(encodeURIComponent($('#txt_publish_website').val())))); 
		   $('#txt_publish_pic').val(btoa(unescape(encodeURIComponent($('#txt_publish_pic').val())))); 
		   $('#txt_publish_ver').val(btoa(unescape(encodeURIComponent($('#txt_publish_ver').val())))); 
		});
	</script>
     
    <?
		$this->template->showModalFooter("Deploy");
		
	}
	
	
	function showUpdateModal()
	{
		$this->template->showModalHeader("update_modal", "Update Application", "act", "update", "update_appID", "");
		?>
           
           <input type="hidden" id="update_act" name="update_act" val="">
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="directory/GIF/sealed.png" width="160" height="160" id="img_update" name="img_update" /></td>
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
               <td height="30" align="left" valign="top" style="font-size:14px"><? $this->template->showMyAdrDD("dd_update_fee", 350); ?></td>
             </tr>
              <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14" id="txt_update_days_txt" name="txt_update_days_txt"><strong>Days</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:100px" id="txt_update_days" name="txt_update_days" placeholder="100"/></td>
             </tr>
           </table></td>
         </tr>
     </table>
     
     
     
    <?
		$this->template->showModalFooter("Update");
		
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
}
?>