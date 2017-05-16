<?
class CFeed
{
	function CFeed($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	 function newBranch($net_fee_adr, 
	                    $feed_symbol, 
					    $name, 
					    $desc,
						$type,
					    $real_asset,
					    $symbol, 
					    $days)
	{
		 // Decode
		 $name=base64_decode($name);
		 $desc=base64_decode($desc);
		 
		 // Symbols
		  $symbol=strtoupper($symbol);
		  $feed_symbol=strtoupper($feed_symbol);
		 
		 // Net Fee Address 
		 if ($this->kern->adrExist($net_fee_adr)==false)
		 {
			$this->template->showErr("Invalid network fee address");
			return false;
		 }
		 
		 // Net fee
		 $fee=round($days*0.0001, 4);
		 
		 // Funds
		 if ($this->kern->getBalance($net_fee_adr)<$fee)
	     {
		    $this->template->showErr("Insufficient funds to execute the transaction");
		    return false;
	     }
	     
		 // Feed symbol
		 if ($this->kern->symbolValid($symbol)==false)
		 {
			 $this->template->showErr("Invalid feed symbol");
		     return false;
		 }
		 
		 // Feed symbol exist ?
		 $query="SELECT * 
		           FROM feeds 
				  WHERE symbol='".$feed_symbol."' 
				    AND adr IN (SELECT adr 
					              FROM my_adr 
								 WHERE userID='".$_REQUEST['ud']['ID']."')";  
		 $result=$this->kern->execute($query);
		 
		 if (mysql_num_rows($result)==0)
		 {
			 $this->template->showErr("Invalid feed symbol");
		     return false;
		 }
		 
		 // Load address
		 $row = mysql_fetch_array($result, MYSQL_ASSOC);
		 $adr=$row['adr'];
		 
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
		 
		 // Type
		 if ($type!="ID_CRYPTO" && 
		    $type!="ID_FX" && 
			$type!="ID_COMM" && 
			$type!="ID_IND" && 
			$type!="ID_STOCKS" &&
			$type!="ID_OTHER")
		 {
			 $this->template->showErr("Invalid category");
			 return false;
		 }
		 
		 // Symbol
		 if ($this->kern->symbolValid($symbol)==false)
		 {
			 $this->template->showErr("Invalid symbol");
			 return false;
		 }
		 
		 // Symbol already exist ?
		 $query="SELECT * 
		           FROM feeds_branches 
				  WHERE feed_symbol='".$feed_symbol."' 
				    AND symbol='".$symbol."'";
		 $result=$this->kern->execute($query);	
	     if (mysql_num_rows($result)>0)
		 {
			 $this->template->showErr("Symbol already exist");
			 return false;
		 }
		 
		 // Market days
		 if ($days<100)
		 {
			  $this->template->showErr("Minimum market days is 100");
			  return false;
		 }
		 
		 
		 try
	     {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Creates a new feed branch");
		  	  
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_FEED_BRANCH', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$adr."',
								par_1='".$feed_symbol."',
								par_2='".$symbol."',
								par_3='".base64_encode($name)."',
								par_4='".base64_encode($desc)."',
								par_5='".$type."',
								par_6='".$real_asset."',
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
	
	function showPanel($symbol)
	{
		$query="SELECT * FROM feeds WHERE symbol='".$symbol."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
            <br>
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="19%"><img src="../../template/template/GIF/empty_pic.png" class="img-responsive img-circle"></td>
            <td width="3%">&nbsp;</td>
            <td width="61%" valign="top"><span class="font_16"><strong><? print base64_decode($row['name']); ?></strong></span><p class="font_14"><? print base64_decode($row['description']); ?></p></td>
            <td width="17%" valign="top">&nbsp;</td>
            </tr>
            <tr><td colspan="4"><hr></td></tr>
            <tr><td colspan="4">
    
            <table class="table-responsive" width="100%">
            <tr>
            <td width="20%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="font_12" href="#"><? print $this->template->formatAdr($row['adr']); ?></a></td>
            <td width="40%" class="font_12" align="center">Creation&nbsp;&nbsp;&nbsp;&nbsp;<? print "~".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></td>
            <td width="40%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<? print "~".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></td>
            </tr>
            </table>
    
            </td></tr>
            </table>
            </div>
            </div>
        
        <?
	}
	
	function showNewFeedBranchModal($feed_symbol)
	{
		$this->template->showModalHeader("modal_new_feed_branch", "Add Feed Branch", "act", "new_branch", "feed_symbol", $feed_symbol);
		?>
        <table width="610" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="172" align="center" valign="top"><table width="180" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center"><img src="GIF/new_branch.png" width="180" alt=""/></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><? $this->template->showNetFeePanel(0.0001, "branch"); ?></td>
              </tr>
            </table></td>
            <td width="450" align="right" valign="top"><table width="400" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Network Fee Address</strong></td>
              </tr>
              <tr>
                <td align="left"><? $this->template->showMyAdrDD("dd_branch_fee_adr", "350"); ?></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
             
              <tr>
                <td align="left"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                      <td width="62%" height="30" align="left" valign="top" class="font_14"><strong>Name</strong></td>
                      <td width="38%" align="left" valign="top" class="font_14"><strong>Real Asset Symbol</strong></td>
                  </tr>
                    <tr>
                      <td><input class="form-control" id="txt_branch_name" name="txt_branch_name" placeholder="Name" style="width:200px"/></td>
                      <td><input class="form-control" id="txt_branch_rl" name="txt_branch_rl" placeholder="AAPL" style="width:120px"/></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Short Description</strong></td>
              </tr>
              <tr>
                <td align="left">
                <textarea rows="3" id="txt_branch_desc" name="txt_branch_desc" class="form-control" placeholder="Short Description ( 0-250 characters )" style="width:350px"></textarea>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><strong>Category</strong></td>
              </tr>
              <tr>
                <td align="left">
                <select name="dd_branch_type" id="dd_branch_type" class="form-control" style="width:340px">
                <option value="ID_CRYPTO" selected>Cryptocurrencies</option>
                <option value="ID_FX">Forex</option>
                <option value="ID_COMM">Commodities</option>
                <option value="ID_IND">Indices</option>
                <option value="ID_STOCKS">Stocks</option>
                <option value="ID_OTHER">Other</option>
                </select>
                </td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" class="font_14"><table width="60%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="33%" height="30" align="left" valign="top"><strong>Symbol</strong></td>
                    <td width="33%" align="left" valign="top"><strong>Days</strong></td>
                  </tr>
                  <tr>
                    <td><input name="txt_branch_symbol" class="form-control" id="txt_branch_symbol" placeholder="XXXXXX" style="width:100px" maxlength="6"/></td>
                    <td><input class="form-control" id="txt_branch_days" name="txt_branch_days" placeholder="1000" style="width:100px"/></td>
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
		$('#form_modal_new_feed_branch').submit(
		function() 
		{ 
		   $('#txt_branch_name').val(btoa($('#txt_branch_name').val())); 
		   $('#txt_branch_desc').val(btoa($('#txt_branch_desc').val())); 
		});
		
		linkToNetFee("txt_branch_days", "branch_net_fee_panel_val", "0.0001");
		</script>
        
        <?
		$this->template->showModalFooter("Send");
	}
	
	function showBranches($feed_symbol)
    {
	   $query="SELECT *
	             FROM feeds_branches
				WHERE feed_symbol='".$feed_symbol."'"; 
	   $result=$this->kern->execute($query);	
	   
	   ?>
          
             <br><br>
             
             <table width="90%" border="0" cellspacing="0" cellpadding="0" class="table-responsive">
             <thead bgcolor="#f9f9f9">
             <th class="font_14" height="35px">&nbsp;&nbsp;</th>
             <th class="font_14" height="35px" align="center">Branch Name</th>
             <th class="font_14" height="35px" align="center">Real Symbol</th>
             <th class="font_14" height="35px" align="center">Last Value</th>
              <th class="font_14" height="35px" align="center" colspan="2">&nbsp;</th>
             </thead>       
                    <?
					   while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                     
                        <tr>
                        <td width="8%" align="left">
                        <img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle" /></td>
                        <td width="40%" align="left" class="font_14">
                        <a href="#" class="font_14"><strong><? print base64_decode($row['name']); ?>&nbsp;&nbsp;(<? print $row['symbol']; ?>)</strong></a><p class="font_10"><? print base64_decode($row['description']); ?></p></td>
                        <td width="20%" align="left" class="font_14"><strong><a href="chart.php?symbol=<? print $row['rl_symbol']; ?>" target="_blank" class="font_14"><? print $row['rl_symbol']; ?></a></strong></td>
                        <td width="20%" align="left" class="font_14"><strong><? print round($row['val'], 8); ?></strong></td>
                        <td width="20%" align="right" class="font_14">
                        <a href="branch.php?feed=<? print $row['feed_symbol']; ?>&symbol=<? print $row['symbol']; ?>" class="btn btn-warning btn-sm" style="color:#000000">Details</a>             
                        </td>
                        </tr>
                        <tr>
                        <td colspan="5" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                        
                     <?
                         }
					 ?>
                     
                     </table>
                     <br><br><br>
                 
       
       <?
   }
    
	function showNewBranchBut($symbol)
	{
	   // My feed ?
	   $query="SELECT * 
	             FROM feeds 
				WHERE symbol='".$symbol."' 
				  AND adr IN (SELECT adr 
				                FROM my_adr 
							   WHERE userID='".$_REQUEST['ud']['ID']."')"; 
	   $result=$this->kern->execute($query);	
	   if (mysql_num_rows($result)==0) return false;
	  	
		?>
         
         <br>
		 <table width="90%">
         <tr><td align="right">
         <a href="javascript:void(0)" onclick="$('#modal_new_feed_branch').modal()" class="btn btn-primary">
         <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;New Feed Branch
         </a>
         </td></tr>
         </table>
         
         
         <?
	}
}
?>
