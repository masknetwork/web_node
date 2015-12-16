<?
class CBrowseFeeds
{
   function CBrowseFeeds($db, $template)
   {
	   $this->kern=$db;
	   $this->template=$template;
   }
   

   function showFeeds()
   {
	   $query="SELECT *, 
	                  (SELECT COUNT(*) FROM feeds_components WHERE feed_symbol=symbol) AS branches 
	             FROM feeds 
			 ORDER BY mkt_bid DESC 
			    LIMIT 0,10";
	   $result=$this->kern->execute($query);	
	   
	   
	   ?>
       
          <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="57%" align="left" class="inset_maro_14">Feed</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="17%" align="center"><span class="inset_maro_14">Branches</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="22%" align="center"><span class="inset_maro_14">Details</span></td>
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
                        <td width="47%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12"><strong><? print base64_decode($row['title']); ?></strong></a><br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span></td>
                        <td width="20%" align="center" class="simple_green_12"><strong><? print $row['branches']; ?></strong></td>
                        <td width="23%" align="right" class="simple_maro_12">
                        
                        <?
						      if ($this->kern->isMine($row['adr'])==false)
							  {
						   ?>
                           
                              <a href="branch.php?feed_symbol=<? print $row['feed_symbol']; ?>&symbol=<? print $row['symbol']; ?>" 
                                 class="btn btn-warning btn-sm">
                              <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Details
                              </a>
                           
                           <?
							  }
							  else
							  {
						   ?>
                           
                                  <div class="dropdown" align="right">
                                  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>&nbsp; Settings&nbsp; &nbsp; <span class="caret"></span></button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="feed.php?symbol=<? print $row['symbol']; ?>">Details</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_new_feed_branch').modal(); $('#feed_symbol').val('<? print $row['symbol']; ?>');">New Branch</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_increase_bid').modal()">Increase Bid</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#modal_renew').modal()">Renew</a></li>
                    <li class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#confirm_modal').modal()">Remove Feed</a></li>
                    </ul>
                  </div>  
                           
						   <?
							  }
						   ?>
                       
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
		$this->template->showModalHeader("modal_new_feed", "New Feed", "act", "new_feed");
		?>
        
        <input type="hidden" id="branch_symbol" name="branch_symbol" value="0" />
        
        <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="../../GIF/inject.png"  /></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(); ?></td>
              </tr>
            </table></td>
            <td width="450" height="300" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><? $this->template->showMyAdrDD("dd_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="simple_blue_14">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Title</strong></td>
              </tr>
              <tr>
                <td align="left"><input name="txt_value" class="form-control" id="txt_value" placeholder="0.0000" style="width:90px" maxlength="6"/></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
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
        
        <?
		$this->template->showModalFooter(true, "Send");
	}
	
	
	
	
	
	
}
?>