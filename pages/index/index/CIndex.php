<?
  class CIndex
  {
	  function CIndex($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function showLoginModal()
	  {
		  $this->template->showModalHeader("modal_login", "Login", "act", "login");
		?>
           
           <div style="padding-left:30px; padding-right:30px;">
           <div class="row"><span style="padding-left:12px">Username</span></div>
           <input class="form-control" id="txt_user" value=""></input>
           <br>
           <div class="row"><span style="padding-left:12px">Password</span></div>
           <input class="form-control" id="txt_pass" type="password" value=""></input>
           </div>
           <br>
           
        <?
		$this->template->showModalFooter("Login");
	}
	
	function showSignupModal()
	  {
		  $this->template->showModalHeader("modal_signup", "New Account", "act", "signup");
		?>
           
           <div style="padding-left:30px; padding-right:30px;">
          
           <div class="row"><span style="padding-left:12px">Username</span></div>
           <input class="form-control" id="txt_user" value=""></input>
           <br>
          
           <div class="row"><span style="padding-left:12px">Email</span></div>
           <input class="form-control" id="txt_email" type="password" value=""></input>
           <br>
           
           <div class="row"><span style="padding-left:12px">Password</span></div>
           <input class="form-control" id="txt_pass_1" type="password" value=""></input>
           <br>
           
           <div class="row"><span style="padding-left:12px">Retype Password</span></div>
           <input class="form-control" id="txt_pass_2" type="password" value=""></input>
           
           </div>
           <br>
           
        <?
		$this->template->showModalFooter("Signup");
	}
	
	function showTopTrades()
	{
		$query="SELECT fsmp.*, fsm.real_symbol  
		          FROM feeds_spec_mkts_pos AS fsmp
				  JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID
				 WHERE fsmp.status='ID_MARKET' 
				ORDER BY ROUND(pl*100/margin) DESC 
			     LIMIT 0,5"; 
	    $result=$this->kern->execute($query);	
	 
	  
		?>
        
           <table class="table-responsive" style="width:95%">
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                <tr height="50px">
                <td width="60%"><span class="font_16" style="color:<? if ($row['tip']=="ID_BUY") print "#009900"; else print "#990000"; ?>"><strong><? if ($row['tip']=="ID_BUY") print "BUY "; else print "SELL "; ?></strong></span><span class="font_16"><? print $row['real_symbol']; ?></span><p class="font_10">Trader : <? print $this->template->formatAdr($row['adr']); ?></p></td>
                <td class="font_16"  width="20%" style="color:<? if ($row['pl']<0) print "#990000"; else print "#009900"; ?>"><strong>
				<?
				   print round($row['pl']*100/$row['margin'], 2)."%";   
				?>
                </strong></td>
                <td><a class="btn btn-sm btn-info" width="20%" href="./pages/assets/margin_mkts/story.php?posID=<? print $row['posID']; ?>">Story</a></td>
                </tr>
               
        
           <?
			  }
		   ?>
           
           </table>
        
        <?
	}
	
	function showTopBets()
	{
		$query="SELECT * 
		          FROM feeds_bets 
				 WHERE block>".($_REQUEST['sd']['last_block']-5000)." 
				   AND status='ID_PENDING'
			  ORDER BY budget DESC 
			     LIMIT 0,5"; 
	    $result=$this->kern->execute($query);	
	 
	  
		?>
        
           <table class="table-responsive" style="width:95%">
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                <tr height="50px">
                <td width="80%"><span class="font_14"><? print base64_decode($row['title']); ?></span><p class="font_10">
				<? print substr(base64_decode($row['description']), 0, 70)."..."; ?></p>
                </td>
                <td><a class="btn btn-sm btn-info" width="20%" href="./pages/assets/margin_mkts/story.php?posID=<? print $row['posID']; ?>">Bet Now</a></td>
                </tr>
               
        
           <?
			  }
		   ?>
           
           </table>
        
        <?
	}
	
	function showTopAssets()
	{
		$query="SELECT fam.*, 
		               adr.balance, 
					   assets.title, 
					   assets.description  
		          FROM feeds_assets_mkts AS fam
				  JOIN adr ON adr.adr=fam.adr
				  JOIN assets ON assets.symbol=fam.asset_symbol
				 WHERE fam.cur='MSK' 
			  ORDER BY adr.balance DESC 
			     LIMIT 0,5";
	    $result=$this->kern->execute($query);	
	 
	  
		?>
        
           <table class="table-responsive" style="width:95%">
           
           <?
		      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			  {
		   ?>
           
                <tr height="40px">
                <td width="60%"><span class="font_14"><strong><? print base64_decode($row['title']); ?></strong></span><p class="font_10">
				<? print substr(base64_decode($row['description']), 0, 70)."..."; ?></p>
                </td>
                <td width="30%"><? print "<span class='font_16'>".round($row['last_price'], 2)." </span><span class='font_10'>".$row['cur']."</span>"; ?></td>
                <td><a class="btn btn-sm btn-info" width="20%" href="./pages/assets/margin_mkts/story.php?posID=<? print $row['posID']; ?>">Details</a></td>
                </tr>
               
        
           <?
			  }
		   ?>
           
           </table>
        
        <?
	}
  }
?>