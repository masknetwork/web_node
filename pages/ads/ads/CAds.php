<?
class CAds
{
	function CAds($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
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
								par_4='XX', 
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
                        <td width="68%" align="left"><span class="font_14"><strong><? print base64_decode($row['title']); ?></strong></span>
                        <p class="font_12"><? print substr(base64_decode($row['message']), 0, 50)."..."; ?></p></td>
                        <td width="17%" align="center"><strong class="font_14" style="color:#009900"><? print $row['mkt_bid']; ?></strong>
                        <p class="simple_green_10">MSK</p></td>
                        <td width="15%" align="center"><span class="font_14"><strong><? print "~".$this->kern->timeFromBlock($row['expire']); ?></strong></span><p class="font_12">expire</p></td>
                        </tr>
                        <tr>
                        <td colspan="3" background="../../template/template/GIF/lp.png">&nbsp;</td>
                        </tr>
                       
                       
                   
                   <?
					   }
				   ?>
       
            </table>
            
        <?
	}
}
?>
