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
					 $pic, 
					 $website, 
					 $symbol, 
					 $bid, 
					 $days)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 $website=base64_decode($website);
		 $source=base64_decode($source);
		 $pic=base64_decode($pic);
		 
		 // Net Fee Address 
		 if ($this->template->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round($mkt_bid*$mkt_days, 4);
		 
		 // Funds
		 if ($this->template->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	   
	     // Feed address
		 if ($this->template->adrValid($adr)==false)
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
		   if ($this->template->isLink($website)==false)
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
		 
		 // Market bid
		 if ($bid<0.0001)
		 {
			 $this->template->showErr("Minimum bid value is 0.0001");
			 return false;
		 }
		 
		 // Symbol
		 $symbol=strtoupper($symbol);
		 if ($this->template->symbolValid($symbol)==false)
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
								bid='".$bid."', 
								status='ID_PENDING', 
								tstamp='".time()."'"; print $query;
	       $this->kern->execute($query);
		 
		   // Commit
		   $this->kern->rollback();
		   
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
	
	function showMyFeeds()
    {
	   $query="SELECT *, (SELECT COUNT(*) 
	                        FROM feeds_components 
						   WHERE feed_symbol=symbol) AS branches
	             FROM feeds
				WHERE adr IN (SELECT adr 
				                FROM my_adr 
							   WHERE userID='".$_REQUEST['ud']['ID']."')
			 ORDER BY mkt_bid DESC 
			    LIMIT 0,10"; 
	   $result=$this->kern->execute($query);	
	   
	   
	   ?>
          
             <br>
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td align="right"><a href="javascript:void(0)" onclick="$('#modal_new_feed').modal()" class="btn btn-primary">
                  <span class="glyphicon glyphicon-plus-sign"></span>&nbsp;&nbsp;New Data Feed</a></td>
                </tr>
              </tbody>
            </table>
            <br>
            
          <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="59%" align="left" class="inset_maro_14">Feed</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Branches</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="21%" align="center"><span class="inset_maro_14">Details</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                    <table width="92%" border="0" cellspacing="0" cellpadding="0">
                    
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                     
                        <tr>
                        <td width="10%" align="left" class="simple_maro_12">
                        <img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle" /></td>
                        <td width="49%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12"><strong><? print base64_decode($row['title']); ?></strong></a><br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span></td>
                        <td width="20%" align="center" class="simple_green_12"><strong><? print $row['branches']; ?></strong></td>
                        <td width="21%" align="right" class="simple_maro_12">
                       
                     
                                  <div class="dropdown" align="right">
                                  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp; Settings&nbsp; &nbsp; <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="../feeds/feed.php?symbol=<? print $row['symbol']; ?>">Details</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_new_feed_branch').modal(); $('#feed_symbol').val('<? print $row['symbol']; ?>');">New Branch</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_increase_bid').modal()">Increase Bid</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_renew').modal()">Renew</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#confirm_modal').modal()">Remove Feed</a></li>
                    </ul>
                  </div>  
                           
						  
                           
                        </td>
                        </tr>
                        <tr>
                        <td colspan="4" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                     <?
                         }
					 ?>
                     </table>
                  
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
       
       <?
   }
   
   function showNewFeedModal()
	{
		$this->template->showModalHeader("modal_new_feed", "New Feed", "act", "new_feed", "opt", "");
		?>
        
          <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/new_feed.png" width="160" height="143" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Feed Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Feed Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name" name="txt_name" placeholder="Feed Name (5-30 characters)" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="5" id="txt_desc" name="txt_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px"></textarea>
                </td>
              </tr>
              
               <tr>
                <td align="left">&nbsp;</td>
              </tr> 
              <tr>
                <td height="30" align="left" valign="top"><strong>Datasource</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_source" name="txt_source" placeholder="Datasource Link" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              
              <tr>
                <td height="30" align="left" valign="top"><strong>Official Website</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_website" name="txt_website" placeholder="Website address" style="width:350px"/></td>
              </tr>
              
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Symbol</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Bid</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Days</strong></td>
                  </tr>
                  <tr>
                    <td><input class="form-control" id="txt_symbol" name="txt_symbol" placeholder="XXXXXX" style="width:90px"/></td>
                    <td><input class="form-control" id="txt_bid" name="txt_bid" placeholder="0.0001" style="width:90px"/></td>
                    <td><input class="form-control" id="txt_days" name="txt_days" placeholder="1000" style="width:90px"/></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#modal_new_feed').submit(
		function() 
		{ 
		   $('#txt_source').val(btoa($('#txt_source').val())); 
		   $('#txt_name').val(btoa($('#txt_name').val())); 
		   $('#txt_desc').val(btoa($('#txt_desc').val())); 
		   $('#txt_website').val(btoa($('#txt_website').val())); 
		   $('#txt_pic').val(btoa($('#txt_pic').val())); 
		});
		</script>
        
        <?
		$this->template->showModalFooter("Send");
	}
	
	function showNewFeedBranchModal()
	{
		$this->template->showModalHeader("modal_new_feed_branch", "Add Feed Branch", "act", "new_branch", "feed_symbol", "");
		?>
        <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/feed_component.png" width="179" height="123" /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"><strong>Bet Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Branch Name</strong></td>
              </tr>
              <tr>
                <td align="left"><input class="form-control" id="txt_name" name="txt_name" placeholder="Address or address name" style="width:350px"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="5" id="txt_desc" name="txt_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Symbol</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Follow Fee</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Days</strong></td>
                  </tr>
                  <tr>
                    <td><input name="txt_symbol" class="form-control" id="txt_symbol" placeholder="XXXXXX" style="width:90px" maxlength="6"/></td>
                    <td><input class="form-control" id="txt_fee" name="txt_fee" placeholder="0.0001" style="width:90px"/></td>
                    <td><input class="form-control" id="txt_days" name="txt_days" placeholder="1000" style="width:90px"/></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table>
        
        <script>
		$('#modal_new_feed').submit(
		function() 
		{ 
		   $('#txt_name').val(btoa($('#txt_name').val())); 
		   $('#txt_desc').val(btoa($('#txt_desc').val())); 
		});
		</script>
        
        <?
		$this->template->showModalFooter(true, "Send");
	}
	
	
}
?>