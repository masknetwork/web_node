<?
class CEscrowers
{
	function CEscrowers($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function addEscrower($net_fee_adr, $adr, $title, $desc, $web_page, $fee, $days)
	{
		// Address owner
		if ($this->kern->isMine($net_fee_adr)==false || 
		    $this->kern->isMine($adr)==false)
		{
			 $this->template->showErr("Invalid entry data");
			 return false;
		}
		
		// Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Market address
		 if ($this->kern->adrValid($adr)==false)
		 {
			$this->template->showErr("Invalid market address");
			return false;
		 }
		 
		// Title
		$title=base64_decode($title);
		if ($this->kern->titleValid($title)==false)
		{
			$this->template->showErr("Invalid title");
			return false;
		}
		
		// Description
		$desc=base64_decode($desc);
		if ($this->kern->descValid($desc)==false)
		{
			$this->template->showErr("Invalid description");
			return false;
		}
		
		// Web page
		$web_page=base64_decode($web_page); 
		if ($this->kern->isLink($web_page)==false)
		{
			$this->template->showErr("Invalid web page");
			return false;
		}
		
		// Fee
		if ($fee<0.01)
		{
			$this->template->showErr("Minimum fee is 0.01");
			return false;
		}
		
		// Days
		if ($days<100)
		{
			$this->template->showErr("Minimum days is 100");
			return false;
		}
		
		// An offer already exist ?
		$query="SELECT * 
	     	      FROM escrowers 
				 WHERE adr='".$adr."'";
		$result=$this->kern->execute($query);
		
		if (mysql_num_rows($result)>0) 
		{
			$this->template->showErr("An escrower offer already exist");
			return false;
		}
		
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new escrower offer");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_ESCROWER', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".base64_encode($title)."',
								par_2='".base64_encode($desc)."',
								par_3='".base64_encode($web_page)."',
								par_4='".$fee."',
								days='".$days."',
								status='ID_PENDING', 
								tstamp='".time()."'"; 
	       $this->kern->execute($query);
		 
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded");
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
	
	function showAddBut()
	{
		// Modal
		$this->showAddEscrowerModal();
		
		?>
        
           <table width="90%">
           <tr><td align="right">
           <a href="javascript:void(0)" onclick="$('#modal_escrower').modal()" class="btn btn-primary">
           <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add Offer</a>
           </td></tr>
           </table>
        
        <?
	}
	
	function showAddEscrowerModal()
	{
		$this->template->showModalHeader("modal_escrower", "Add New Offer", "act", "add");
		?>
        
           <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../markets/escrowers/GIF/escrowers.png" width="180" height="181" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="438" align="center" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_esc_net_fee_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_esc_adr", "330"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Title</strong></span></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_esc_title" name="txt_esc_title" placeholder="Title" style="width:330px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Description</strong></span></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="4" style="width:330px" placeholder="Description" id="txt_esc_desc" name="txt_esc_desc" class="form-control"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Web Page</strong></span></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_esc_web_page" name="txt_esc_web_page" placeholder="Web Page" style="width:330px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td width="29%" height="30" valign="top"><span class="simple_blue_14"><strong><strong>Fee (%)</strong></strong></span></td>
                      <td width="71%" align="left"><span class="simple_blue_14"><strong>Days</strong></span></td>
                    </tr>
                    <tr>
                      <td><input class="form-control" id="txt_esc_fee" name="txt_esc_fee" placeholder="0" style="width:100px"/></td>
                      <td align="left"><input class="form-control" id="txt_esc_days" name="txt_esc_days" placeholder="0" style="width:100px" /></td>
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
         <script>
		   $('#form_modal_escrower').submit(
		   function() 
		   {
		      $('#txt_esc_title').val(btoa($('#txt_esc_title').val())); 
			  $('#txt_esc_desc').val(btoa($('#txt_esc_desc').val())); 
			  $('#txt_esc_web_page').val(btoa($('#txt_esc_web_page').val())); 
		   });
		</script>
        
        <?
		$this->template->showModalFooter();
	}
	
	function showEscrowers($mine=false, $search="")
	{
		if ($mine==false)
		$query="SELECT esc.*, prof.pic 
		          FROM escrowers AS esc 
			 LEFT JOIN profiles AS prof ON prof.adr=esc.adr
				 LIMIT 0,25";
		else
		$query="SELECT esc.*, prof.pic 
		          FROM escrowers AS esc 
			 LEFT JOIN profiles AS prof ON prof.adr=esc.adr 
			     WHERE esc.adr IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."')
				 LIMIT 0,25";
				 
		$result=$this->kern->execute($query);	
	 
	  
		?>
           
           <br>
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Fee</th>
           <th class="font_14" height=\"35px\" align=\"center\">Details</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%">
                 <img class="img img-responsive img-circle" src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic']); ?>">
                 </td>
                 <td>&nbsp;</td>
                 <td width="70%" class="font_16">
                 <? print base64_decode($row['title']); ?>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print $row['fee']."%"; 
			     ?>
                 </td>
                 
                
                 
                <td class="font_16" width="15%">
                <a href="asset.php?symbol=<? print $row['symbol']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Details</a>
                </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
}
?>