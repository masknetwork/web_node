<?
class CAds
{
	function CAds($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function increaseBid($net_fee_adr, $rowhash, $bid)
	{
		// Check network fee address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Rowhash valid
		if ($this->kern->isSHA256($rowhash)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Net fee address mine ?
		if ($this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Bid
		if ($this->kern->isNumber($bid, "decimal")==false)
	    {
		    $this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Round
		$bid=round($bid, 4);
		
		// Rowhash valid
		$query="SELECT * 
		          FROM ads 
				 WHERE rowhash='".$rowhash."'";
		$result=$this->kern->execute($query);	
		if (mysql_num_rows($result)==false)
		{
			$this->template->showErr("Invalid entry data.");
			return false;
		}
		
		// Load data
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// My address
		if ($this->kern->isMine($row['adr'])==false)
		{
			$this->template->showErr("Invalid entry data.");
			return false;
		}
		
		// Increase value
		if ($row['mkt_bid']>=$bid)
		{
			$this->template->showErr("New bid is lower or equal than the old bid");
			return false;
		}
		
		// Funds
		$fee=(($row['expires']-(time()/100))/36)*($bid-$row['mkt_bid']); 
		$fee=round($fee, 4);
		
		if ($this->kern->getBalance($net_fee_adr)<$fee)
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Increases ad bid");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_INCREASE_BID', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$row['adr']."',
								par_1='ads',
								par_2='".$rowhash."',
								par_3='".$bid."',
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
	
	function delAd($net_fee_adr, $rowhash)
	{
		// Check network fee address
		if ($this->template->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Rowhash valid
		if ($this->template->isSHA256($rowhash)==false)
		{
			$this->template->showErr("Invalid entry data");
			return false;
		}
		
		// Funds
		if ($this->template->getBalance($net_fee_adr)<0.0001)
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Net fee address mine ?
		if ($this->template->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Rowhash valid
		$query="SELECT * 
		          FROM ads 
				 WHERE rowhash='".$rowhash."'";
		$result=$this->kern->execute($query);	
		if (mysql_num_rows($result)==false)
		{
			$this->template->showErr("Invalid entry data.");
			return false;
		}
		
		// Load data
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// My address
		if ($this->template->isMine($row['adr'])==false)
		{
			$this->template->showErr("Invalid entry data..");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Deletes an ad message");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_DEL_ITEM', 
								fee_adr='".$net_fee_adr."', 
								target_adr='".$row['adr']."',
								par_1='ads',
								par_2='".$rowhash."',
								status='ID_PENDING', 
								tstamp='".time()."'"; print $query;
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
	
	function newAd($net_fee_adr, $title, $mes, $link, $country, $hours, $bid)
	{
		// Decode
		$title=base64_decode($title);
		$mes=base64_decode($mes);
		$link=base64_decode($link);
	    	
		// Check network fee address
		if ($this->kern->adrExist($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Net fee address mine ?
		if ($this->kern->isMine($net_fee_adr)==false)
		{
			$this->template->showErr("Invalid net fee address");
			return false;
		}
		
		// Funds
		$price=$hours*$bid;
		if ($this->kern->getBalance($net_fee_adr)<$price)
		{
			$this->template->showErr("Insuficient funds to execute this operation");
			return false;
		}
		
		// Check title
		if (strlen($title)<5 || strlen($title)>30)
		{
			$this->template->showErr("Invalid title length (5-30 characters)");
			return false;
		}
		
		// Check message
		if (strlen($mes)<50 || strlen($mes)>70)
		{
			$this->template->showErr("Invalid message length (50-70 characters)");
			return false;
		}
		
		// Check link
		if (strlen($link)<10 || strlen($link)>100)
		{
			$this->template->showErr("Invalid link length (10-100 characters)");
			return false;
		}
		
		// Check hours
		if ($this->kern->isNumber($hours)==false || $hours<1)
		{
			$this->template->showErr("Invalid hours");
			return false;
		}
		
		// Check bid
		if ($this->kern->isNumber($bid, "decimal")==false || $bid<0.0001)
		{
			$this->template->showErr("Invalid hours");
			return false;
		}
		
		// Country
		if (strlen($country)!=2)
		{
			$this->template->showErr("Invalid country");
			return false;
		}
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Post a new ad message");
		
		    // Insert to stack
		   $query="INSERT INTO web_ops 
			                SET user='".$_REQUEST['ud']['user']."', 
							    op='ID_NEW_AD', 
								fee_adr='".$net_fee_adr."', 
								par_1='".base64_encode($title)."',
								par_2='".base64_encode($mes)."',
								par_3='".base64_encode($link)."', 
								par_4='".$country."', 
								days='".$hours."', 
								bid='".$bid."', 
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
	
	function showMyAds()
	{
		$query="SELECT * 
		          FROM ads 
				 WHERE adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')"; 
	    $result=$this->kern->execute($query); 
		
			
		?>
          
            <table width="90%" border="0" cellspacing="0" cellpadding="0">
            
                      <?
				       while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				       {
				      ?>
                       
                        <tr>
                        <td width="51%" align="left"><span class="font_14"><strong><? print base64_decode($row['title']); ?></strong></span>
                        <p class="font_12"><? print substr(base64_decode($row['message']), 0, 50)."..."; ?></p></td>
                        <td width="17%" align="center"><strong class="font_14" style="color:#009900"><? print $row['mkt_bid']; ?></strong>
                        <p class="simple_green_10">MSK</p></td>
                        <td width="15%" align="center"><span class="font_14"><strong><? print $row['expires']-$this->kern->block(); ?></strong></span><p class="font_12">hours</p></td>
                        <td width="17%" align="center" class="font_14">
                        
                        <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:$('#txt_val').val('<? print $row['mkt_bid']; ?>'); $('#rowhash').val('<? print $row['rowhash']; ?>'); $('#modal_increase_bid').modal()">Increase Bid</a></li>
                       
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
            
        <?
	}
}
?>
