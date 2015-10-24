<?
class CBranch
{
	function CBranch($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function injectData($net_fee_adr, $feed, $branch, $val)
	{
		 // Feed symbol
		 $feed=strtoupper($feed);
		 if ($this->template->symbolValid($feed)==false)
		 {
			 $this->template->showErr("Invalid feed symbol");
			 return false;
		 }
		 
		 // Branch symbol
		 $branch=strtoupper($branch);
		 if ($this->template->symbolValid($branch)==false)
		 {
			 $this->template->showErr("Invalid branch symbol");
			 return false;
		 }
		 
		 // Feed exist ?
		 $query="SELECT * 
		           FROM feeds 
				  WHERE symbol='".$feed."'"; 
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Feed symbol doesn't exist");
			 return false;
		 }
		 
		 // Feed row
		 $f_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 // Branch exist ?
		 $query="SELECT * 
		           FROM feeds_components 
				  WHERE feed_symbol='".$feed."' 
				    AND symbol='".$branch."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Branch symbol doesn't exist");
			 return false;
		 }
		 
		 // Branch row
		 $b_row = mysql_fetch_array($result, MYSQL_ASSOC);
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Injects data into feed branch (".$feed." / ".$branch." / ".$val.")");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			               SET user='".$_REQUEST['ud']['user']."', 
							   op='ID_INJECT_VALUE', 
							   fee_adr='".$net_fee_adr."', 
							   target_adr='".$f_row['adr']."',
							   par_1='".$feed."',
							   par_2='".$branch."',
							   par_3='".$val."',
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
	
	 function showBranchPanel($feed_symbol, $symbol)
	 {
		$query="SELECT *
		          FROM feeds_components 
				 WHERE feed_symbol='".$feed_symbol."' 
				   AND symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC); 
	  
		?>
           
           <br><br>
<table width="560" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="30" align="left" valign="top" class="simple_maro_deschis_18">&nbsp;&nbsp;&nbsp;&nbsp;<? print base64_decode($row['title']); ?></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">
                  <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="36%" align="left" valign="top"><img src="../../template/template/GIF/empty_pic_prod.png" width="150" height="150" class="img-circle" /></td>
                        <td width="64%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td class="simple_maro_12"><? print base64_decode($row['description']); ?></td>
                            </tr>
                            <tr>
                              <td background="../../template/template/GIF/lc.png">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td width="37%" align="right" class="simple_maro_12">Feed Symbol&nbsp;&nbsp;</td>
                                <td width="63%" height="30"><a href="#" class="red_12"><strong>
                                  <? 
								   print $feed_symbol; 
								  ?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Branch Symbol&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
                                  <? 
								   print $symbol; 
								  ?>
                                </strong></a></td>
                              </tr>
                              <tr>
                                <td align="right" class="simple_maro_12">Expire&nbsp;&nbsp;</td>
                                <td height="30"><a href="#" class="red_12"><strong>
                                  <? 
								   print "3 months";
								?>
                                </strong></a></td>
                              </tr>
                              </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
		$this->showBranchTabs($feed_symbol, $symbol);
	    $this->template->showMenu("Data", "Markets");
		$this->showData($feed_symbol, $symbol);
	}
	
	function showBranchTabs($feed_symbol, $symbol)
	{
		$query="SELECT * 
		          FROM feeds_components 
				 WHERE feed_symbol='".$feed_symbol."' 
				   AND symbol='".$symbol."'"; 
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="95" align="center" valign="top" background="../../template/template/GIF/4_panels.png"><table width="530" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr class="bold_shadow_white_12">
                        <td width="105" height="25" align="center" valign="bottom"> Fee</td>
                        <td width="39" align="center" valign="bottom">&nbsp;</td>
                        <td width="101" align="center" valign="bottom"> Ask</td>
                        <td width="35" align="center" valign="bottom">&nbsp;</td>
                        <td width="106" align="center" valign="bottom">Bid</td>
                        <td width="30" align="center" valign="bottom">&nbsp;</td>
                        <td width="114" align="center" valign="bottom">Status</td>
                      </tr>
                      <tr class="simple_red_22">
                        <td height="40" align="center" valign="bottom" class="simple_red_18"><strong><? print $row['fee']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['ask']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="simple_red_18"><strong><? print $row['bid']; ?></strong></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom" class="<? if ($row['open']=="Y") print "simple_green_18"; else print "simple_red_18"; ?>"><strong><? if ($row['open']=="Y") print "Open"; else print "Closed"; ?></strong></td>
                      </tr>
                      <tr class="simple_blue_10">
                        <td height="0" align="center" valign="bottom">MSK per day</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom"><? print $row['symbol']; ?></td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                        <td align="center" valign="bottom">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
              </tbody>
            </table>
           
        
        <?
		$this->template->showArrow();
	}
	function showInjectModal()
	{
		$this->template->showModalHeader("modal_inject", "Inject Data", "act", "inject", "feed_symbol", "");
		?>
        
        <input type="hidden" id="branch_symbol" name="branch_symbol" value="0" />
        
        <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="./GIF/inject.png"  /></td>
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
                <td height="30" align="left" valign="top" class="simple_blue_14"><strong>Value</strong></td>
              </tr>
              <tr>
                <td align="left"><input name="txt_value" class="form-control" id="txt_value" placeholder="0.0000" style="width:90px" maxlength="6"/></td>
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
	
	function showData($feed_symbol, $branch_symbol)
	{
		$query="SELECT * 
		          FROM feeds_data 
				 WHERE feed='".$feed_symbol."' 
				   AND feed_branch='".$branch_symbol."' 
			  ORDER BY ID DESC"; 
		$result=$this->kern->execute($query);
		
		?>
        
            <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="49%" align="left" class="inset_maro_14">Explanation</td>
                        <td width="1%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="15%" align="center"><span class="inset_maro_14">Ask</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="13%" align="center"><span class="inset_maro_14">Bid</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="18%" align="center"><span class="inset_maro_14">Block</span></td>
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
                           
                           <td width="49%" align="left" class="simple_maro_12">
                           <strong><? print $row['feed_branch']; ?></strong>
                           </td>
                           
                           <td width="18%" align="center" class="simple_red_12">
                           <strong><? print $row['ask']; ?></strong>
                           </td>
                           
                           <td width="14%" align="center" class="simple_green_12">
                           <strong><? print $row['bid']; ?></strong>
                           </td>
                           
                           <td width="19%" align="center" class="simple_maro_12">
                           <strong><? print $row['block']; ?></strong>
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
            <br><br>
        
        <?
	}
	
}
?>