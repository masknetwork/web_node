<?
class CGlobals
{
	function CGlobals($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	
	
	function update($appID, $net_fee_adr)
	{
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
		
		// Mine
		if ($this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Load global variables
		$query="SELECT * 
		          FROM agents 
				 WHERE aID='".$appID."' 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."') 
				   AND sealed=0";
	    $result=$this->kern->execute($query);	
		
		// Invalid agent
		if (mysql_num_rows($result)==0)
		{
			$this->template->showErr("Invalid application ID");
			return false;
		}
		
		// Load data
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
		
		// Address
		$adr=$row['adr'];
		
		// Decode
		$globals=json_decode(base64_decode($row['globals'])); 
		
		// Globals array
		$globals=$globals->globals; 
		
		// Json
		$json="{\"vars\" : [";
		
		for ($a=0; $a<=sizeof($globals)-1; $a++)
		{
			// Load variable
			$var=$globals[$a];
			
			if (!isset($_REQUEST['txt_'.$var->ID]))
			{
				$this->template->showErr("Missing variable (".$globals[$a]->ID.")");
				return false;
			}
			
			// Value
			$ID='txt_'.$var->ID;
			
			// Min
			$min=$var->min;
					
			// Max
			$max=$var->max;
			
			// Value 
			$val=base64_decode($_REQUEST[$ID]);
			
			// Long
			if ($var->data_type=="long" && 
			   $this->kern->isNumber($val)==false) 
			{
				$this->template->showErr("Invalid variable ".$var->name." value. Expecting a number.");
				return false;
			}
			
			// Decimal
			if ($var->data_type=="double" && 
			   $this->kern->isNumber($val)==false) 
			{
				$this->template->showErr("Invalid variable ".$var->name." value. Expecting a number.");
				return false;
			}
			
			// Long or double
			if ($var->data_type=="long" || 
			    $var->data_type=="double")
				{
					// Min and max
					if ($val<$min || $val>$max)
					{
						$this->template->showErr("Invalid value for variable ".$var->name.". Expecting a number between ".$min." and ".$max);
				        return false;
					}
				}
			  
	        // String
			if ($var->data_type=="string")
			{
			   if (strlen($val)<$min || strlen($val)>$max)
			   {
                   $this->template->showErr("Invalid length for variable ".$var->name.". Expecting beween ".$min." and ".$max);
				   return false;
			   }
			}
			
			// Add
			if ($a==0)
			  $json=$json."{\"ID\" : \"".$var->ID."\", \"value\" : \"".$val."\"}";
			else
	          $json=$json.", {\"ID\" : \"".$var->ID."\", \"value\" : \"".$val."\"}";
		}
		
		// Add
		$json=$json."]}"; 
		
	    try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Updates an application");
		
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_UPDATE_APP_SETTINGS', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$appID."',
								par_2='".base64_encode($json)."', 
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
	
	function showGlobals($appID)
	{
		// Load global variables
		$query="SELECT * FROM agents WHERE aID='".$appID."'"; 
	    $result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
		
		// Decode
		$globals=json_decode(base64_decode($row['globals']));
		
		// Globals array
		$globals=$globals->globals; 
		
		?>
         
           <br> 
           <form id="form_globals" name="form_globals" action="globals.php?act=update&ID=<? print $_REQUEST['ID']; ?>" method="post">
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="../mine/GIF/settings.png" width="150" height="150" id="img_publish" name="img_publish"/></td>
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
           <td width="90%" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></span></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" style="font-size:14px"><? $this->template->showMyAdrDD("net_fee_adr", "100%"); ?></td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">
               <br>
               <table width="100%" border="0" cellpadding="0" cellspacing="0">
                 <tbody>
                  
                  <?
				     for ($a=0; $a<=sizeof($globals)-1; $a++)
					 {
				  ?>
                    
                       <tr>
                       <td width="47%" align="left" valign="top">
                       <span class="font_14"><strong><? print $globals[$a]->name; ?></strong></span>
                       <p class="font_10"><? print $globals[$a]->expl." (Min : ".$globals[$a]->min.", Max : ".$globals[$a]->max.")"; ?></p>
                       </td>
                       <td width="2%" class="font_14" align="right">&nbsp;</td>
                       <td width="51%" class="font_14" align="right">
                       
					   <?
					       if ($globals[$a]->data_type=="long" || $globals[$a]->data_type=="double")
						   {
						      print "<input class='form-control' style='width:100px' name='txt_".$globals[$a]->ID."' id='txt_".$globals[$a]->ID."' value='".$globals[$a]->value."' required>";
						   }
						   else if ($globals[$a]->data_type=="string")
						   {
							   if ($globals[$a]->max<100)
							      print "<input maxlength='".$globals[$a]->max."' class='form-control' name='txt_".$globals[$a]->ID."' id='txt_".$globals[$a]->ID."' type='text' value='".$globals[$a]->value."' class='form-control' required>";
							   else
							      print "<textarea maxlength='".$globals[$a]->max."' class='form-control' name='txt_".$globals[$a]->ID."' id='txt_".$globals[$a]->ID."' class='form-control' rows='3'>".$globals[$a]->value."</textarea required>";
						   }
					   ?>
                       
                       </td>
                       </tr>
                       <tr>
                       <td colspan="3"><hr></td>
                       </tr>
                 
                 <?
					 }
				 ?>
                 
                 </tbody>
               </table>
               
               </td>
             </tr>
             <tr>
               <td height="0" align="right"><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Update</button></td>
             </tr>
           </table></td>
         </tr>
     </table>
     </form>
     <br><br><br>
     
      <script>
	   $('#form_globals').submit(
		function() 
		{ 
		   <?
		       for ($a=0; $a<=sizeof($globals)-1; $a++)
			       print "$('#txt_".$globals[$a]->ID."').val(btoa(unescape(encodeURIComponent($('#txt_".$globals[$a]->ID."').val())))); ";
				
			?>
			
			
		});
	</script>
        
        <?
	}
}
?>