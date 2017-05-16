<?
class CRegMkts
{
	function CRegMkts($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function newMarket($net_fee_adr, 
	                   $mkt_adr, 
					   $asset_symbol, 
					   $cur_symbol, 
					   $decimals,
					   $name, 
					   $desc, 
					   $days)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 
		 if ($this->kern->standardCheck($this->template, $net_fee_adr, $mkt_adr, 0.0001*$days)==false)
		    return false;
		 
		 // Name
		 if ($this->kern->isTitle($name)==false)
		 {
			 $this->template->showErr("Invalid name");
			 return false;
		 }
		 
		 // Description
		 if ($this->kern->isDesc($desc)==false)
		 {
			 $this->template->showErr("Invalid description");
			 return false;
		 }
		 
		 // Asset symbol valid
		 if ($this->kern->assetExist($asset_symbol)==false)
		 {
			 $this->template->showErr("Invalid asset");
			 return false;
		 }
		 
		 // Currency valid ?
		 if ($cur_symbol!="MSK")
		    if ($this->kern->assetExist($cur_symbol)==false)
		    {
				$this->template->showErr("Invalid currency");
			    return false;
		    }
		
		// Days
		if ($days<100)
		{
			 $this->template->showErr("Minimum period is 10 days");
			 return false;
		}
		
		try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Launch a new regular asset market");
					   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_REGULAR_ASSET_MARKET', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$mkt_adr."',
								par_1='".$asset_symbol."',
								par_2='".$cur_symbol."',
								par_3='".base64_encode($name)."',
								par_4='".base64_encode($desc)."',
								par_5='".$decimals."',
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
	
	function showNewMktBut()
	{
		?>
        
		 <table width="90%">
         <tr><td align="right">
         <a href="javascript:void(0)" onClick="$('#modal_new_regular_market').modal();" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Market
         </a>
         </td></tr>
</table>
         <br>
         
         <?
	}
	
	function showMarkets($search="", $cur="MSK")
	{
		$query="SELECT * FROM assets_mkts LIMIT 0,25";
		$result=$this->kern->execute($query);	
	 
	  
		?>
           
           <table class="table-responsive" width="90%">
           <thead bgcolor="#f9f9f9">
           <th></th>
           <th width="1%">&nbsp;</th>
           <th class="font_14" height="35px">&nbsp;&nbsp;Description</th>
           <th class="font_14" height="35px" align="center">Ask</th>
           <th class="font_14" height="35px" align="center">Bid</th>
           <th class="font_14" height="35px" align="center">Last Price</th>
           <th class="font_14" height=\"35px\" align=\"center\">Trade</th>
           </thead>
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                 <tr>
                 <td width="7%"><img class="img img-responsive img-circle" src="../../template/template/GIF/empty_pic.png"></td>
                 <td>&nbsp;</td>
                 <td width="40%">
                 <a href="bet.php?uid=<? print $row['uid']; ?>" class="font_14"><? print base64_decode($row['name'])."<br>"; ?></a>
                 <p class="font_10"><? print substr(base64_decode($row['description']), 0, 40)."..."; ?></p>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print round($row['ask'], 8)." ".$row['cur']; 
			     ?>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				      print round($row['bid'], 8)." ".$row['cur']; 
			     ?>
                 </td>
                 <td class="font_14" width="15%">
				 <? 
				    print round($row['last_price'], 8)." ".$row['cur']; ; 
				 ?>
                 </td>
                 
                 <td class="font_16" width="5%">
                 <a href="market.php?ID=<? print $row['mktID']; ?>" class='btn btn-warning btn-sm' style="color:#000000">Trade</a>
                 </td>
                
                 
                 </tr>
                 <tr><td colspan="7"><hr></td></tr>
           
           <?
			  }
		   ?>
           
           </table>
           
        <?
	}
	
	
	function showNewRegularMarketModal()
	{
		$this->template->showModalHeader("modal_new_regular_market", "New Regular Assets Market", "act", "new_market", "edit_symbol", "");
		?>
        
            <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/new.png" class="img-responsive"/></td>
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
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
            </table></td>
            <td width="438" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Market Address</strong></span></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_new_mkt_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Asset Symbol</strong></td>
                    <td width="33%" height="30" align="left" valign="top"><strong>Currency</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Decimals</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_new_asset_symbol" name="txt_new_asset_symbol" placeholder="XXXXXX" style="width:90%"/></td>
                    <td><input class="form-control" id="txt_new_cur" name="txt_new_cur" placeholder="XXXXXX" style="width:90%"/></td>
                    <td align="left">
                    <select id="dd_decimals" name="dd_decimals" class="form-control" style="width:90%">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                    </select></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
               <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Title</strong></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">
                <input class="form-control" id="txt_new_name" name="txt_new_name" placeholder="Title (5-50 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3" id="txt_new_desc" name="txt_new_desc" class="form-control" style="width:350px" placeholder="Short Description (optional, 0-250 characters)"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><span class="simple_blue_14"><strong>Days</strong></span></td>
                  </tr>
                  <tr>
                    <td align="left"><input class="form-control" id="txt_new_days" name="txt_new_days" placeholder="100" style="width:80px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              </table></td>
              </tr>
</table>
              
              <script>
		        $('#form_modal_new_regular_market').submit(
		          function() 
		          {   
		            $('#txt_new_name').val(btoa($('#txt_new_name').val())); 
		            $('#txt_new_desc').val(btoa($('#txt_new_desc').val()));  
		          });
		      </script>
        
        <?
		$this->template->showModalFooter();
	}
	
	
	
	
}
?>