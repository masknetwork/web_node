<?
class CProfile
{
	function CProfile($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showProfilePanel()
	{
		?>
        
        <div class="panel panel-default">
        <div class="panel-heading font_14">Profile Information</div>
        <div class="panel-body">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Name</strong></td>
             </tr>
             <tr>
               <td width="391" align="left">
               <input type="text" class="form-control"  id="txt_name" name="txt_name" placeholder="Name"  />
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="30" align="left" valign="top" class="font_14"><strong>Street Address</strong></td>
             </tr>
             <tr>
               <td align="left">
               <input type="text" class="form-control" id="txt_street" name="txt_street" placeholder="Address" onfocus="this.placeholder=''"  />
               </td>
             </tr>
             <tr>
               <td height="0" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td height="50" align="left">
               
               
               <table border="0" cellspacing="0" cellpadding="0" width="100%">
                 <tr>
                   <td class="font_14" height="30px"><strong>Email</strong></td>
                   <td width="5%">&nbsp;</td>
                   <td class="font_14"><strong>Tel</strong></td>
                 </tr>
                 
                 <tr>
                   <td class="font_14"><strong>
                   <input type="text" class="form-control" id="txt_street" name="txt_street" placeholder="Address" onfocus="this.placeholder=''"  /></strong></td>
                   <td>&nbsp;</td>
                   <td class="font_14"><strong>
                   <input type="text" class="form-control" id="txt_street" name="txt_street" placeholder="Address" onfocus="this.placeholder=''"  /></strong></td>
                 </tr>
               </table>
              
               </td>
             </tr>
             <tr>
               <td><hr></td>
             </tr>
             <tr>
               <td align="right"><a href="#" class="btn btn-primary">Update</a></td>
             </tr>
           </table>
        </div>
        </div>   
        <br>
        
        <?
	}
	
	
	
	function showProfilePage()
	{
		?>
        
        <table width="90%">
        <tr>
        <td width="20%" valign="top">
        
        <table>
        <tr><td><img src="../../template/template/GIF/empty_profile.png" class="img img-responsive img-rounded"></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td height="50px" valign="top"><a href="#" class="btn btn-primary" style="width:100%">Upload Pic</a></td></tr>
        <tr><td><a href="#" class="btn btn-danger" style="width:100%">Change Password</a></td></tr>
        </table>
        
      
        <td width="5%">&nbsp;</td>
        <td width="=75%">
        
        <?
		   $this->showProfilePanel();
		  
		?>
        
       
        </td>
        </tr>
        </table>
        
        <?
	}
}
?>