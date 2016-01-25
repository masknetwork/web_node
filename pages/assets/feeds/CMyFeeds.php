<?
  class CMyFeeds
  {
	  function CMyFeeds($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function newFeed($net_fee_adr, 
	                   $adr, 
					   $name, 
					   $desc,
					   $source,
					   $website, 
					   $symbol, 
					   $days)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 $website=base64_decode($website);
		 $source=base64_decode($source);
		 
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round($mkt_days*0.0001, 4);
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	   
	     // Feed address
		 if ($this->kern->adrValid($adr)==false)
		 {
			$this->template->showErr("Invalid asset address");
			return false;
		 }
		 
		 // Name
		 if (strlen($name)<5 || strlen($name)>50)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Description
		 if (strlen($desc)>250)
		 {
			 $this->template->showErr("Invalid name length (5-50 characters)");
			 return false;
		 }
		 
		 // Website
		 if ($website!="")
		 {
		   if ($this->kern->isLink($website)==false)
		   {
			   $this->template->showErr("Invalid website link");
			   return false;
		   }
		 }
		 
		 // Pic
		 if ($pic!="")
		 {
		   if ($this->template->isLink($pic)==false)
		   {
			   $this->template->showErr("Invalid pic");
			   return false;
		   }
		 }
		 
		 // Market days
		 if ($days<100)
		 {
			  $this->template->showErr("Minimum market days is 100");
			  return false;
		 }
		 
		 // Symbol
		 $symbol=strtoupper($symbol);
		 if ($this->kern->symbolValid($symbol)==false)
		 {
			 $this->template->showErr("Invalid symbol");
			 return false;
		 }
		 
		 // Symbol already exist ?
		 $query="SELECT * 
		           FROM feeds 
				  WHERE symbol='".$symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)>0)
		 {
			 $this->template->showErr("Symbol already exist");
			 return false;
		 }
		 
		  try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new data feed");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_FEED', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".base64_encode($name)."',
								par_2='".base64_encode($desc)."',
								par_3='".base64_encode($source)."',
								par_4='".base64_encode($website)."',
								par_5='".$symbol."',
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
	
	
   function showNewFeedModal()
	{
		$this->template->showModalHeader("modal_new_feed", "New Feed", "act", "new_feed", "opt", "");
		?>
        
          <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../feeds/GIF/new_feed.png" width="160" height="143" class="img-circle"/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_feed_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Feed Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_feed_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Feed Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_new_feed_name" name="txt_new_feed_name" placeholder="Feed Name (5-30 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3" id="txt_new_feed_desc" name="txt_new_feed_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px"></textarea>
                </td>
              </tr>
              
               <tr>
                <td align="left">&nbsp;</td>
              </tr> 
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><table width="90%" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                      <tr>
                        <td width="49%"><strong>Datasource</strong></td>
                        <td width="51%"><strong>Official Website</strong></td>
                      </tr>
                      <tr>
                        <td><input class="form-control" id="txt_new_feed_source" name="txt_new_feed_source" placeholder="Datasource Link" style="width:150px"/></td>
                        <td><input class="form-control" id="txt_new_feed_website" name="txt_new_feed_website" placeholder="Website address" style="width:150px"/></td>
                      </tr>
                    </tbody>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="36%" height="30" align="left" valign="top"><strong>Symbol</strong></td>
                    <td width="64%" align="left" valign="top"><strong>Days</strong></td>
                    <td width="64%" align="left" valign="top"><strong>Updates</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_feed_symbol" name="txt_new_feed_symbol" placeholder="XXXXXX" style="width:90px"/></td>
                    <td><input class="form-control" id="txt_new_feed_days" name="txt_new_feed_days" placeholder="1000" style="width:90px"/></td>
                    <td><input class="form-control" id="txt_new_feed_updates" name="txt_new_feed_updates" placeholder="1" style="width:90px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#modal_new_feed').submit(
		function() 
		{ 
		   $('#txt_new_feed_source').val(btoa($('#txt_new_feed_source').val())); 
		   $('#txt_new_feed_name').val(btoa($('#txt_new_feed_name').val())); 
		   $('#txt_new_feed_desc').val(btoa($('#txt_new_feed_desc').val())); 
		   $('#txt_new_feed_website').val(btoa($('#txt_new_feed_website').val()));  
		});
		</script>
        
        <?
		$this->template->showModalFooter("Send");
	}
	
	
	
    
	function showNewFeedBut()
	{
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="javascript:void(0)" onclick="$('#modal_new_feed').modal()" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Create Data Feed
         </a>
         </td></tr>
         </table>
         <br>
         
         <?
	}
	
	
}
?>
 