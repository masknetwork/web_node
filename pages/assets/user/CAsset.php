<?
class CAsset
{
	function CAsset($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function trust($net_fee_adr, $trust_adr, $asset)
	{
		// Standard checks
		if ($this->kern->standardCheck($this->template, $net_fee_adr, $trust_adr)==false)
		   return false;
		
		// Network fee
		if ($this->kern->getBalance($net_fee_adr, "MSK")<0.0365)
		{
			$this->template->showErr("Insufficient funds to execute transaction");
			return false;
		}
		
		// Asset exist ?
		if ($this->kern->assetExist($asset)==false)
		{
			$this->template->showErr("Asset doesn't exist");
			return false;
		}
		
		// Already trusted ?
		$query="SELECT * 
		          FROM adr_attr 
				 WHERE adr='".$trust_adr."' 
				   AND attr='ID_TRUST_ASSET' 
				   AND s1='".$asset."'";
		$result=$this->kern->execute($query);	
		
		if (mysqli_num_rows($result)>0)
		{
			$this->template->showErr("You already trust this asset");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Trust an asset - ".$asset);
		   
		   // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_ADD_ATTR', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$trust_adr."',
								par_1='ID_TRUST_ASSET',
								par_2='".$asset."',
								par_3='',
								par_4='',
								par_5='0',
								par_6='0',
								par_7='0',
								par_8='0',
								par_9='0',
								par_10='0',
								days='365',
								status='ID_PENDING', 
								tstamp='".time()."'";  
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
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
	
	function showAssetPage($symbol)
	{
		$this->showAssetPanel($symbol);
		$this->showAssetTabs($symbol);
		$this->template->showMenu("Owners", "Transactions", "Markets", "Exchangers", "Spend It");
		
		$this->showOwners($symbol);
		$this->showTrans($symbol);
		$this->showMarkets($symbol);
	}
	
	function showTrustModal()
	{
		$this->template->showModalHeader("trust_modal", "Trust Asset", "act", "trust_asset");
		?>
        
           <table width="700" border="0" cellspacing="0" cellpadding="0">
          <tr>
           <td width="130" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td align="center"><img src="./GIF/trust.png" width="200" /></td>
             </tr>
             <tr><td>&nbsp;</td></tr>
             <tr>
               <td align="center"><? $this->template->showNetFeePanel("0.0365", "trans"); ?></td>
             </tr>
             <tr>
               <td align="center">&nbsp;</td>
             </tr>
            
           </table></td>
           <td width="400" align="center" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="5">
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Network Fee Address</strong></td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">
			   <?
			      $this->template->showMyAdrDD("dd_net_fee_trust");
			   ?>
               </td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px">&nbsp;</td>
             </tr>
             <tr>
               <td height="25" align="left" valign="top" style="font-size:16px"><strong>Trust with address</strong></td>
             </tr>
             <tr>
               <td width="391" align="left">
               
			   <?
			      $this->template->showMyAdrDD("dd_trust_adr");
			   ?>
               
               </td>
             </tr>
             <tr>
               <td align="left">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
     </table>
     
    
       
        <?
		$this->template->showModalFooter("Send");
		
	}
	
	function showPanel($symbol)
	{
		// Owners
		$query="SELECT COUNT(*) AS total
		          FROM assets_owners 
				 WHERE symbol='".$symbol."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$owners=$row['total'];
		
		$query="SELECT *
		          FROM assets 
				 WHERE symbol='".$symbol."'";
	    $result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
		
		?>
        
            <br>
            <table width="90%">
            <tr><td align="right">
            <a href="javascript:void(0)" onClick="$('#trust_modal').modal()" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Trust Asset</a></td></tr>
            </table>
            <br>
            
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
            <table width="100%">
            <tr>
            <td width="23%" valign="top"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=150&h=150"; ?>"  class="img-circle img-responsive"/></td>
            <td width="1%">&nbsp;</td>
            <td width="76%" valign="top"><span class="font_16"><strong><? print $this->kern->noescape(base64_decode($row['title'])); ?></strong></span>
            <p class="font_14"><? print $this->kern->noescape(base64_decode($row['description'])); ?></p></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr><td colspan="3">
    
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-responsive">
             <tr>
            <td width="30%" align="center"><span class="font_12">Symbol&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['symbol']; ?></strong></span></td>
            <td width="40%" class="font_12" align="center">Available&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['qty']; ?> units</strong></td>
            <td width="30%" class="font_12" align="center">Transaction Fee&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $row['trans_fee']."%"; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center"><span class="font_12">Address</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><a class="font_12" href="#"><? print $this->template->formatAdr($row['adr']); ?></a></strong></td>
            <td width="40%" class="font_12" align="center">Issued&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['block'])." (block ".$row['block'].")"; ?></strong></td>
            <td width="30%" class="font_12" align="center">Expire&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print "~ ".$this->kern->timeFromBlock($row['expire'])." (block ".$row['expire'].")"; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr>
            <td width="30%" align="center"><span class="font_12">Fee</span>&nbsp;&nbsp;&nbsp;&nbsp;<strong><a class="font_12" href="#"><? print $this->template->formatAdr($row['trans_fee_adr']); ?></a></strong></td>
            <td width="40%" class="font_12" align="center">Can Issue More&nbsp;&nbsp;&nbsp;YES<strong></strong></td>
            <td width="30%" class="font_12" align="center">Owners&nbsp;&nbsp;&nbsp;&nbsp;<strong><? print $owners; ?></strong></td>
            </tr>
            <tr><td colspan="5"><hr></td></tr>
            </table>
            
            <table>
            </table>
            
            </td></tr>
            </table>
            </div>
            </div>
            <br>
            
            <table width="90%">
            <tr>
            
            <td width="50%">
            <div class="panel panel-default">
            <div class="panel-heading">
            <h3 class="panel-title" class="font_14">How to buy <? print $row['symbol']; ?></h3>
            </div>
            <div class="panel-body font_12">
            
            <table width="90%">
            <tr>
            <td width="30%"><img src="GIF/buy.png" class="img img-responsive"></td>
            <td width="70%"><? print base64_decode($row['how_buy']); ?></td>
            </tr>
            </table>
            
            </div>
            </div>
            </td>
            
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            
            <td width="50%">
            <div class="panel panel-default">
            <div class="panel-heading">
            <h2 class="panel-title">How to sell / redeem <? print $row['symbol']; ?></h2>
            </div>
            <div class="panel-body font_14">
            
            <table width="90%">
            <tr>
            <td width="30%"><img src="GIF/redeem.png" class="img img-responsive"></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="70%" class="font_12"><? print base64_decode($row['how_sell']); ?></td>
            </tr>
            </table>
            
            </div>
            </div>
            </td>
            
            </tr>
            </table>
            
        
        <?
	}
	
	
	function showOwners($symbol)
	{
		$query="SELECT * 
		          FROM assets_owners 
				 WHERE symbol='".$symbol."'
			  ORDER BY qty DESC";
	    $result=$this->kern->execute($query);	
	    
		?>
                   <br>
                   <table width="90%" class="table-responsive">
                    
                    <?
					   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                        <td width="8%" align="left" class="font_14">
                        <img src="../../template/template/GIF/empty_pic.png"  class="img-circle img-responsive"/></td>
                        <td width="2%">&nbsp;</td>
                        <td width="90%" align="left" class="font_14">
                        <a href="#" class="font_14">
                        <strong><? print $this->template->formatAdr($row['owner']); ?></strong>
                        </a>
                        <p class="font_10"><? print $this->template->formatAdr($row['owner']); ?></p>
                        </td>
                        
                        <td width="11%" align="center" class="font_16">
                        <span<strong><? print round($row['qty'], 8); ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="4"><hr></td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        <br><br>
                       
           
        
        <?
	}
	
	function showTrans($symbol)
	{
		$query="SELECT * 
		          FROM trans 
				 WHERE cur='".$symbol."'
			  ORDER BY ID DESC
			     LIMIT 0,20";
	    $result=$this->kern->execute($query);	
		
		if (mysqli_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No transactions found</p><br><br>";
			return false;
		}
	    
		?>
           
          <br>
                   <table width="90%" class="table-responsive">
                    
                    <?
					   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                        <td width="8%" align="left" class="font_14">
                        <img src="../../template/template/GIF/empty_pic.png"  class="img-circle img-responsive"/></td>
                        <td width="2%">&nbsp;</td>
                        <td width="70%" align="left" class="font_14">
                        <a href="#" class="font_14">
                        <strong><? print $this->template->formatAdr($row['src']); ?></strong>
                        </a>
                        <p class="font_10"><? print "Received ~". $this->kern->timeFromBlock($row['block'])." ago"; ?></p>
                        </td>
                        
                        <td width="25%" align="center" class="font_14" style="color : <? if ($row['amount']<0) print "#990000"; else print "#009900"; ?>">
                        <span<strong><? print round($row['amount'], 8)." ".$symbol; ?></strong></span></td>
                        </tr>
                        <tr>
                        <td colspan="4"><hr></td>
                        </tr>
                        
                      <?
					   }
					  ?>
                        </table>
                        <br><br>
           
        
        <?
	}
	
	function showMarkets($symbol)
	{
		$query="SELECT * 
		          FROM assets_markets 
				 WHERE (asset_symbol='".$symbol."' 
				        OR cur_symbol='".$symbol."')
			  ORDER BY ID DESC
			     LIMIT 0,20";
	    $result=$this->kern->execute($query);	
	    
		if (mysqli_num_rows($result)==0)
		{
			print "<br><p class='font_14' style='color:#990000'>No markets found</p><br><br>";
			return false;
		}
		?>
           
           <div id="div_markets" name="div_markets" style="display:none">
           <table width="565" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="43" align="center" background="../../template/template/GIF/tab_top.png"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td width="64%" align="left" class="inset_maro_14">Receiver</td>
                        <td width="2%"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Asset</span></td>
                        <td width="2%" align="center"><img src="../../template/template/GIF/tab_sep.png" width="2" height="37" alt=""/></td>
                        <td width="16%" align="center"><span class="inset_maro_14">Currency</span></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td height="400" align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  
                  <table width="92%" border="0" cellspacing="0" cellpadding="0">
                    
                    <?
					   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
					   {
					?>
                    
                        <tr>
                          <td width="10%" align="left" class="simple_maro_12"><img src="../../template/template/GIF/empty_pic.png" width="40" height="40" class="img-circle"/></td>
                        <td width="68%" align="left" class="simple_maro_12">
                        <a href="#" class="maro_12">
                        <strong><? print base64_decode($row['title']); ?></strong>
                        </a><br><span class="simple_maro_10"><? print base64_decode($row['description']); ?></span>
                        </td>
                        <td width="11%" align="center"><span class="<? if ($row['asset_symbol']==$symbol) print "simple_green_12"; else print "simple_maro_12"; ?>"><strong><? print $row['asset_symbol']; ?></strong></span></td>
                        
                        <td width="11%" align="center">
                        <span class="<? if ($row['cur_symbol']==$symbol) print "simple_green_12"; else print "simple_maro_12"; ?>"><strong><? print $row['cur_symbol']; ?></strong></span></td>
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
              
                   
                    
            </table>
            <br><br><br>
            </div>
           
        
        <?
	}
}
?>