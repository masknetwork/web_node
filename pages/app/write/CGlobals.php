<?
class CGlobals
{
	function CGlobals($db, $template, $appID)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->appID=$appID; 
	}
	
	function removeGlobal($ID)
	{
		// Check ID
		if ($this->kern->isLong($ID)==false)
		{
			$this->template->showErr("Invalid ID");
		    return false;
		}
		
		// ID
		$query="SELECT * 
		          FROM agents_globals AS ag 
				  JOIN agents_mine AS am ON am.ID=ag.appID 
				 WHERE am.adr IN (SELECT adr 
				                    FROM my_adr 
								   WHERE userID='".$_REQUEST['ud']['ID']."')";
		$result=$this->kern->execute($query);
			
		if (mysqli_num_rows($result)==0)
		{
			$this->template->showErr("Invalid variable ID");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Removes a global variable");
		
		   // Insert to stack
		   $query="DELETE FROM agents_globals WHERE ID='".$ID."'"; 
		   $this->kern->execute($query);
		   
		   // To json
		   $this->toJSON();
			
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
	
	function addGlobal($appID,
	                   $net_fee, 
	                   $type, 
					   $varID, 
					   $name, 
					   $desc, 
					   $min, 
					   $max, 
					   $val, 
					   $val_str)
	{
		// Decode
		$varID=base64_decode($varID);
		$name=base64_decode($name);
		$desc=base64_decode($desc);
		$val=base64_decode($val);
		$val_str=base64_decode($val_str);
	    
		// Val
		if ($val_str!="") $val=$val_str;
		
		// Check ID
		if ($varID=="")
		{
		   $this->template->showErr("Required field not found (ID)");
		   return false;
		}
		
		// Var ID
		if (preg_match("/^[0-9]*[a-z]*[A-Z]*$/", $varID)!=1)
		{
			$this->template->showErr("Invalid variable ID");
			return false;
		}
		
		// Length
		if (strlen($varID)<1 || strlen($varID)>25)
		{
			$this->template->showErr("Invalid field length (ID). Allowed 1-25 characters.");
			return false;
		}
		   
		// Already exist ?
		$query="SELECT * 
		          FROM agents_globals 
				 WHERE appID='".$appID."' 
				   AND varID='".$id."'";
		$result=$this->kern->execute($query);	
	    
		// Exist ?
		if (mysqli_num_rows($result)>0)
		{
			$this->template->showErr("Variable ".$id." already exist");
			return false;
		}
	  
		// Name
		if (strlen($name)<1 || strlen($name)>50)
		{
			$this->template->showErr("Invalid field length (name). Allowed 1-50 characters.");
			return false;
		}
		
		// Expl
		if (strlen($desc)<1 || strlen($desc)>1000)
		{
			$this->template->showErr("Invalid field length (description). Allowed 1-1000 characters.");
			return false;
		}
		  
		if ($type!="string" && 
		    $type!="long" && 
			$type!="double")
	    {
			$this->template->showErr("Invalid type.");
			return false;
		}
		
		if ($type=="string" && $min<1)
		 {
			$this->template->showErr("Invalid value for 'minimum length' field");
			return false;
		}
		else if (preg_match("/^[0-9]{0,10}(\.[0-9]{0,8})?$/", $min)!=1)
		{
			$this->template->showErr("Invalid value for 'minimum value' field");
			return false;
		}
			
		if ($type=="string" && $max>1000)
		{
			$this->template->showErr("Invalid value for 'maximum length' field");
			return false;
		}
	    else if (preg_match("/^[0-9]{0,10}(\.[0-9]{0,8})?$/", $max)!=1)
		{
			$this->template->showErr("Invalid value for 'maximum length' field");
			return false;
		}
		  
		// Check value
		if ($type=="long")
		{
			if (preg_match("/^[0-9]{0,10}$/", $val)!=1)
		    {
			   $this->template->showErr("Invalid value (expected a long)");
			   return false;
		    }
			
			if (preg_match("/^[0-9]{0,10}$/", $min)!=1)
		    {
			   $this->template->showErr("Invalid min value field");
			   return false;
		    }
			
			if (preg_match("/^[0-9]{0,10}$/", $max)!=1)
		    {
			   $this->template->showErr("Invalid max value field)");
			   return false;
		    }
		}
			
	    if ($type=="double")
		{
			if (preg_match("/^[0-9]{0,10}(\.[0-9]{0,8})?$/", $val)!=1)
		    {
			   $this->template->showErr("Invalid value (expected a double)");
			   return false;
		    }
			
			if (preg_match("/^[0-9]{0,10}(\.[0-9]{0,8})?$/", $max)!=1)
		    {
			   $this->template->showErr("Invalid min value field");
			   return false;
		    }
			
			if (preg_match("/^[0-9]{0,10}(\.[0-9]{0,8})?$/", $max)!=1)
		    {
			   $this->template->showErr("Invalid max value field)");
			   return false;
		    }
		}
		
        // Number ?
		if ($type=="long" || $type=="double")
		{
			if ($val<$min || $val>$max)
			 {
			   $this->template->showErr("Invalid value (expected a number between $min and $max)");
			   return false;
		     }
		}
		else
		{
			if (strlen($val)<$min || strlen($val)>$max)
			{
			   $this->template->showErr("Invalid length for value field");
			   return false;
		     }
		}
		
		// Replace
		$val=str_replace("\"", "'", $val);
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Adds a new variable");
		
		   // Insert to stack
		   $query="INSERT INTO agents_globals 
		                   SET appID='".$appID."', 
						       varID='".$varID."', 
							   name='".base64_encode($name)."', 
							   expl='".base64_encode($desc)."', 
							   data_type='".base64_encode($type)."', 
							   min='".$min."', 
							   max='".$max."', 
							   val='".base64_encode($val)."'"; 
			$this->kern->execute($query);
			
			// To json
		    $this->toJSON();
			
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
	
	function showAddButton()
	{
		?>
        
        <table width="90%" class="tale-responsive">
        <tr>
        <td width="70%">&nbsp;</td>
        <td width="30%" align="right">
        <a href="#" onClick="$('#add_modal').modal()" class="btn btn-primary">
        <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add Variable
        </a></td>
        </tr>
        </table>
        
        <?
	}
	
	function showPage()
	{
		// Modal
		$this->showAddModal();
		
		// Buttons
		$this->showAddButton();
		
		// Vars
		$this->showVars();
	}
	
	function showAddModal()
	{
		$this->template->showModalHeader("add_modal", "Add Global Variable", "act", "add", "appID", "");
		?>
           
           <input type="hidden" id="update_act" name="update_act" val="">
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/global.png" width="170" height="170" alt=""/></td>
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
               <td width="391" height="30" align="left" valign="top" class="font_14" ><strong>Variable Type</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <select id="dd_var_type" name="dd_var_type" class="form-control" style="width:330px" onChange="change()">
               <option value="long">Long</option>
               <option value="double">Double</option>
               <option value="string">String</option>
               </select>
               </td>
             </tr>
              <tr>
               <td align="center">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14" id="txt_update_days_txt" name="txt_update_days_txt"><strong>Variable ID</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:70%" id="txt_var_id" name="txt_var_id" placeholder="ID"/></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Name</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <input type="text" class="form-control" style="width:70%" id="txt_var_name" name="txt_var_name" placeholder="Name"/></td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Description</strong></td>
             </tr>
             <tr>
               <td height="0" align="left">
               <textarea id="txt_var_desc" name="txt_var_desc" rows="3" class="form-control" style="width:70%"></textarea>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="0" align="left">
               <table width="350px" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                   <tr>
                     <td width="33%" height="30" align="left" valign="top" class="font_14"><strong id="min_val" name="min_val">Min Value</strong></td>
                     <td width="33%" height="30" align="left" valign="top" class="font_14"><strong id="max_val" name="max_val">Max Value</strong></td>
                     <td width="33%" height="30" align="left" valign="top" class="font_14"><strong id="val" name="val"> Value</strong></td>
                   </tr>
                   <tr>
                     <td><input type="text" class="form-control" style="width:100px" id="txt_min_val" name="txt_min_val" placeholder="0"/></td>
                     <td><input type="text" class="form-control" style="width:100px" id="txt_max_val" name="txt_max_val" placeholder="100"/></td>
                     <td><input type="text" class="form-control" style="width:100px" id="txt_val" name="txt_val" placeholder="100"/></td>
                   </tr>
                 </tbody>
               </table>
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14" id="td_value_str" name="td_value_str" style="display:none"><strong>Value</strong></td>
             </tr>
             <tr>
               <td height="0" align="left"  style="display:none" id="td_val_str" name="td_val_str" >
               <textarea id="txt_val_str" name="txt_val_str" rows="3" class="form-control" style="width:70%"></textarea></td>
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
	    if ($('#dd_var_type').val()=="string")
		{
	        $('#min_val').text('Min Length');
			$('#max_val').text('Max Length');
			$('#td_value_str').css('display', 'block');
			$('#td_val_str').css('display', 'block');
			$('#val').css('display', 'none');
			$('#txt_val').css('display', 'none');
		}
		else
		{
	        $('#min_val').text('Min Value');
			$('#max_val').text('Max Value');
			$('#td_value_str').css('display', 'none');
			$('#td_val_str').css('display', 'none');
			$('#val').css('display', 'block');
			$('#txt_val').css('display', 'block');
		}
	 }
	 
		$('#form_add_modal').submit(
		function() 
		{ 
		   $('#txt_var_id').val(btoa(unescape(encodeURIComponent($('#txt_var_id').val())))); 
		   $('#txt_var_name').val(btoa(unescape(encodeURIComponent($('#txt_var_name').val())))); 
		   $('#txt_var_desc').val(btoa(unescape(encodeURIComponent($('#txt_var_desc').val())))); 
		   $('#txt_val').val(btoa(unescape(encodeURIComponent($('#txt_val').val())))); 
		   $('#txt_val_str').val(btoa(unescape(encodeURIComponent($('#txt_val_str').val())))); 
		});
	 
	 </script>
     
    <?
		$this->template->showModalFooter("Add");
		
	}
	
	function toJSON()
	{
		$query="SELECT * 
		          FROM agents_globals 
				 WHERE appID='".$this->appID."'";
	    $result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0) return "";
		
		$json="{\"globals\": [{";
		
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			$json=$json."\"ID\" : \"".$row['varID']."\", ";
			$json=$json."\"name\" : \"".base64_decode($row['name'])."\", ";
			$json=$json."\"expl\" : \"".base64_decode($row['expl'])."\", ";
			$json=$json."\"data_type\" : \"".base64_decode($row['data_type'])."\", ";
			$json=$json."\"min\" : ".$row['min'].", ";
			$json=$json."\"max\" : ".$row['max'].", ";
			$json=$json."\"value\" : \"".base64_decode($row['val'])."\"}, {";
		}
	    
		$json=$json."]}";
		$json=str_replace(", {]}", "]}", $json);
		$json=preg_replace("/\r\n|\r|\n/", ' ', $json);
		
		$query="UPDATE agents_mine 
		           SET globals='".base64_encode($json)."' 
				 WHERE ID='".$this->appID."'";
		$this->kern->execute($query);
		
		//print base64_encode($json);
	}
	
	function showVars($appID)
	{
		// Confirm modal
		$this->template->showConfirmModal();
		
		$query="SELECT * 
		          FROM agents_globals 
				 WHERE appID='".$this->appID."'";
	    $result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0) return;
		
		?>
           
        <br>
        <table style="width:90%" class='table table-responsive'>
        <thead>
        <td class="font_14"><strong>Name</strong></td>
        <td>&nbsp;</td>
        <td class="font_14"><strong>ID</strong></td>
        <td class="font_14"><strong>Min</strong></td>
        <td class="font_14"><strong>Max</strong></td>
        </thead>
		<?
		
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
		{
			?>
            
            <tr>
            <td width="55%"><span class="font_14"><? print base64_decode($row['name']); ?></span><p class="font_10"><? print base64_decode($row['expl']); ?></p></td>
            <td width="5%">&nbsp;</td>
            <td width="10%" class="font_14"><? print $row['varID']; ?></td>
            <td width="10%" class="font_14"><? print round($row['min'], 8); ?></td>
            <td width="10%" class="font_14"><? print round($row['max'], 8); ?></td>
            <td width="10%"><a class="btn btn-danger btn-sm" href="javascript:void(0)" onClick="$('#confirm_modal').modal(); $('#par_1').val('<? print $row['ID']; ?>');"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
            
            <?
		}
		
		?>
        
         </table>
         <br><br><br>
        
        <?
	}
	
	function showEditor()
	{
		// Save button
		$this->showSaveBut($this->appID);
	    
		// Load code
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$this->appID."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
           <div id="globals_code" align="left"><? print base64_decode($row['globals']); ?></div>


           <script src="./ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
           <script>
              var globals_code = ace.edit("globals_code");
              globals_code.setTheme("ace/theme/chrome");
              globals_code.getSession().setMode("ace/mode/json");
			  globals_code.setShowPrintMargin(false);
			  
	 		  globals_code.getSession().on('change', function(e) 
			  {
                  $('#but_save').removeAttr('disabled'); 
			  });
			   
           </script>
        
        <?
	}
}
?>