<?
class CStats
{
	function CStats($kernel, $template)
	{
		$this->kern=$kernel;
		$this->template=$template;
	}
	
	function showStats()
	{
		if ($_REQUEST['ud']['ref_adr']==0)
		{
		  $this->showJoin();
		}
		else
		{
		  $this->showLink();
		  $this->showDailyStats(date("Y"), date("m"));
		}
	}
	
	function hit($refID)
	{
	}
	
	function signup($refID)
	{
	}
	
	function getMonth($month)
	{
		switch ($month)
		{
			case 1 : return "January"; break;
			case 2 : return "February"; break;
			case 3 : return "Mars"; break;
			case 4 : return "April"; break;
			case 5 : return "May"; break;
			case 6 : return "June"; break;
			case 7 : return "July"; break;
			case 8 : return "August"; break;
			case 9 : return "Septmeber"; break;
			case 10 : return "October"; break;
			case 11 : return "November"; break;
			case 12 : return "December"; break;
		}
	}
	
	function showDailyStats($year, $month)
	{
		// Format month
		$month=round($month);
		?>
        
         <br>
         <table class="table table-condensed table-hover table-responsive" style="width:90%">
         <thead>
         <th class="font_14" width="70%">Day</th>
         <th class="font_14" width="10%">Hits</th>
         <th class="font_14" width="10%">Signups</th>
         <th class="font_14" width="10%">Rate</th>
         </thead>
         <tbody>
         
         <?
		     for ($a=1; $a<=31; $a++)
			 {
		        $query="SELECT * 
				          FROM ref_stats 
						 WHERE userID='".$_REQUEST['ud']['ID']."' 
						   AND year='".$year."' 
						   AND month='".$month."' 
						   AND day='".$a."'";
						   
			    $result=$this->kern->execute($query);	
	           
				if (mysql_num_rows($result)>0)
				{
				   // Signups
				   $signups=$row['signups'];
				
			   	   // Hits
				   $hits=$row['hits'];
				}
				else
				{
					// Hits
					$hits=0;
					
					// Signups
					$signups=0;
				}
         ?>
         
               <tr height="45px">
               <td class="font_14"><? print $this->getMonth($month).", ".$a.", ".$year; ?></td>     
               <td class="font_14"><? print $hits; ?></td>
               <td class="font_14"><? print $signups; ?></td>
               <td class="font_14" style="color:<? if ($signups>0) print "#009900"; else print "#aaaaaa"; ?>"><strong><? if ($signups>0) print round($signups*100/$hits, 2)."%"; else print "0.00%"; ?></strong></td>
               </tr>
         
         <?
	        }
		 ?>
         
         </tbody>
         </table>
        
        <?
	}
	
	function joinNow($adr)
	{
		// My address ?
		$query="SELECT * 
		          FROM my_adr 
				 WHERE adr='".$adr."' 
				   AND userID='".$_REQUEST['ud']['ID']."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
	  
		// Adr ID
		$adrID=$row['ID'];
		
		try
	    {
		   // Begin
		   $this->kern->begin();

           // Action
           $this->kern->newAct("Joins affiliate program");
		   
		   // Insert to stack
		   $query="UPDATE web_users 
		              SET ref_adr='".$adrID."' 
					WHERE ID='".$_REQUEST['ud']['ID']."'"; 
	       $this->kern->execute($query);
		
		   // Commit
		   $this->kern->commit();
		   
		   // Ref adr
		   $_REQUEST['ud']['ref_adr']=$adr;
		   
		   // Confirm
		   $this->template->showOk("Your request has been succesfully recorded", 550);
	   }
	   catch (Exception $ex)
	   {
	      // Rollback
		  $this->kern->rollback();

		  // Mesaj
		  $this->template->showErr("Unexpected error.", 550);

		  return false;
	   } 
		
		
	}
	
	function showJoin()
	{
		?>
        
        <br>
        <form id="form_join" name="form_join" action="index.php?act=join" method="post">
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td height="100" align="center" bgcolor="#fafafa"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                    <td width="14%" rowspan="2" align="left" bgcolor="#FAFAFA" class="font_16"><img src="GIF/join.png" width="90" height="89" alt=""/></td>
                    <td width="78%" height="35" align="left" bgcolor="#FAFAFA" class="font_16"><strong>&nbsp;&nbsp;Join using this address</strong>&nbsp;&nbsp;<span class="font_12">(this is the address where you will receive the rewards)</span></td>
                    <td width="3%" bgcolor="#FAFAFA" class="font_14">&nbsp;</td>
                    <td width="5%" align="left" bgcolor="#FAFAFA" class="font_14">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#fafafa"><? $this->template->showMyAdrDD("dd_adr", "60%"); ?></td>
                    <td bgcolor="#FAFAFA" class="font_14">&nbsp;</td>
                    <td bgcolor="#fafafa"><a href="javascript:void(0)" onClick="$('#form_join').submit()" class="btn btn-danger">Join</a></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
          </tbody>
        </table>
        </form>
        
        <?
	}
	
	function showLink()
	{
		?>
         
          <br>
          <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td height="50" align="center" bgcolor="#fafafa" class="font_20"><strong>http://<? print $_SERVER['HTTP_HOST']; ?>/?i=43</strong></td>
          </tr>
          <tr>
          <td height="50" align="center" bgcolor="#fafafa" class="font_12">Copy the code above and send to your friends or post it in public places using our banners. Whom register will become your affiliates. For each 1 MSK owned by your affiliates the network will reward you up to 0.0005 MSK every single day. Do not spam other forums with your affiliate link.</td>
          </tr>
          </tbody>
          </table>
<?
	}
}
?>
