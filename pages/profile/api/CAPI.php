<?
class CAPI
{
	function CAPI($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function generate()
	{
		$rand=hash("sha256", rand(10000, 100000000));
		return strtoupper(substr($rand, rand(1,10), 4)."-"
		                 .substr($rand, rand(10, 20), 4)."-"
						 .substr($rand, rand(30, 40), 4)."-"
						 .substr($rand, rand(40, 50), 4)."-"
						 .substr($rand, rand(50, 60), 4));
	}
	
	function showPassModal()
	{
		$this->template->showModalHeader("pass_modal", "Password", "act", "renew");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="GIF/pass.png" width="150" height="150" alt=""/></td>
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
           <td width="90%" align="left" valign="top"><table width="350" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td width="10%" align="right" valign="top" style="font-size:14px">&nbsp;</td>
               <td width="90%" height="30" align="left" valign="top" style="font-size:16px">Account Password</td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
               <td height="0" align="left"><span style="font-size:16px">
                 <input class="form-control" name="txt_pass" id="txt_pass" value="" type="password">
               </span></td>
             </tr>
           </table></td>
         </tr>
     </table>
     
    
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showAPIPanel()
	{
		 // Generate ?
		 if ($_REQUEST['act']=="renew")
		 {
			 // Check password
			 if ($this->checkPass()==false)
			 {
				 $this->template->showErr("Invalid account password");
				 return false;
			 }
			 
			 // New key
			 $new_key=$this->generate();
			 
			 // Update new key
			 $query="UPDATE web_users 
			            SET api_key='".hash("sha256", $new_key)."' 
					  WHERE ID='".$_REQUEST['ud']['ID']."'";
			 $this->kern->execute($query);	
			 
			 // Set new key
			 $_REQUEST['ud']['api_key']=$new_key;
		 }
		 
	     if ($_REQUEST['ud']['api_key']!="")
		 {	
		?>
        
           <div class="panel panel-default" style="width:90%">
           <div class="panel-heading font_14"><? if ($_REQUEST['act']=="renew") print "API Key"; else print "API Key Hash"; ?></div>
           <div class="panel-body font_22">
           <table width="100%">
           <tbody>
           <tr>
           <td width="95%" align="center" class="<? if (strlen($_REQUEST['ud']['api_key'])>40) print "font_16"; else print "font_22"; ?>"><? print $_REQUEST['ud']['api_key']; ?></td>
           <td width="5%"><a class="btn btn-success" href="javascript:void(0)" onClick="$('#pass_modal').modal()"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Renew Key</a></td>
           </tr>
           </tbody>
           </table>
            </div>
           </div>
        
        <?
		 }
		 else
		 {
			 ?>
             
             <table width="90%">
             <tbody>
             <tr>
             <td width="100%" align="right">
             <a class="btn btn-success" href="javascript:void(0)" onClick="$('#pass_modal').modal()"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Renew Key</a>
             </td>
             </tr>
             </tbody>
             </table>
             
             <?
		 }
	}
	
	function checkPass()
	{
		$query="SELECT * 
		          FROM web_users 
				 WHERE ID='".$_REQUEST['ud']['ID']."' 
				   AND pass='".hash("sha256", $_REQUEST['txt_pass'])."'";
		$result=$this->kern->execute($query);
			
		if (mysqli_num_rows($result)==0)
		   return false;
		else
		   return true;
	}
	
}
?>