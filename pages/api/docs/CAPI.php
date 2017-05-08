<?
class CAPI
{
	function CAPI($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showLeftMenu($sel=1)
	{
		?>
        
         <div class="panel panel-default" style="width:180px">
         <div class="panel-heading font_14">Public API Tables</div>
         <div class="panel-body">
         <table width="100%">
         
         <tr><td>
         <a href="index.php" class="font_14"><? if ($sel==1) print "<strong>Overview</strong>"; else print "Overview"; ?></a>
         </td><td colspan="2"><? if ($sel==1) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_adr.php" class="font_14"><? if ($sel==2) print "<strong>Addresses</strong>"; else print "Addresses"; ?></a>
         </td><td colspan="2"><? if ($sel==2) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_ads.php" class="font_14"><? if ($sel==3) print "<strong>Ads</strong>"; else print "Ads"; ?></a>
         </td><td colspan="2"><? if ($sel==3) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_agents.php" class="font_14"><? if ($sel==4) print "<strong>Agents</strong>"; else print "Agents"; ?></a>
         </td><td colspan="2"><? if ($sel==4) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_assets.php" class="font_14"><? if ($sel==5) print "<strong>Assets</strong>"; else print "Assets"; ?></a>
         </td><td colspan="2"><? if ($sel==5) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_assets_owners.php" class="font_14"><? if ($sel==6) print "<strong>Assets Owners</strong>"; else print "Assets Owners"; ?></a>
         </td><td colspan="2"><? if ($sel==6) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_assets_markets.php" class="font_14"><? if ($sel==7) print "<strong>Assets Markets</strong>"; else print "Assets Markets"; ?></a>
         </td><td colspan="2"><? if ($sel==7) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_assets_markets_orders.php" class="font_14"><? if ($sel==8) print "<strong>Assets Market Orders</strong>"; else print "Assets Markets Orders"; ?></a>
         </td><td colspan="2"><? if ($sel==8) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_blocks.php" class="font_14"><? if ($sel==9) print "<strong>Blocks</strong>"; else print "Blocks"; ?></a>
         </td><td colspan="2"><? if ($sel==9) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_domains.php" class="font_14"><? if ($sel==10) print "<strong>Domains</strong>"; else print "Domains"; ?></a>
         </td><td colspan="2"><? if ($sel==10) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_escrowed.php" class="font_14"><? if ($sel==11) print "<strong>Escrowed</strong>"; else print "Escrowed"; ?></a>
         </td><td colspan="2"><? if ($sel==11) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_feeds.php" class="font_14"><? if ($sel==12) print "<strong>Feeds</strong>"; else print "Feeds"; ?></a>
         </td><td colspan="2"><? if ($sel==12) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_feeds_branches.php" class="font_14"><? if ($sel==13) print "<strong>Feeds Branches</strong>"; else print "Feeds Branches"; ?></a>
         </td><td colspan="2"><? if ($sel==13) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_feeds_bets.php" class="font_14"><? if ($sel==14) print "<strong>Feeds Bets</strong>"; else print "Feeds Bets"; ?></a>
         </td><td colspan="2"><? if ($sel==14) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_feeds_bets_positions.php" class="font_14"><? if ($sel==15) print "<strong>Feeds Bets Positions</strong>"; else print "Feeds Bets Positions"; ?></a>
         </td><td colspan="2"><? if ($sel==15) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_packets.php" class="font_14"><? if ($sel==16) print "<strong>Packets</strong>"; else print "Packets"; ?></a>
         </td><td colspan="2"><? if ($sel==16) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_profiles.php" class="font_14"><? if ($sel==17) print "<strong>Profiles</strong>"; else print "Profiles"; ?></a>
         </td><td colspan="2"><? if ($sel==17) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_tweets.php" class="font_14"><? if ($sel==18) print "<strong>Blog posts</strong>"; else print "Blog posts"; ?></a>
         </td><td colspan="2"><? if ($sel==18) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_votes.php" class="font_14"><? if ($sel==19) print "<strong>Votes</strong>"; else print "Votes"; ?></a>
         </td><td colspan="2"><? if ($sel==19) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_follows.php" class="font_14"><? if ($sel==20) print "<strong>Follows</strong>"; else print "Follows"; ?></a>
         </td><td colspan="2"><? if ($sel==20) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="table_comments.php" class="font_14"><? if ($sel==21) print "<strong>Comments</strong>"; else print "Comments"; ?></a>
         </td><td colspan="2"><? if ($sel==21) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
        
         </table>
       </div>
  </div>
  <br><br>
        
        <?
	}
	
	function showKeyLeftMenu($sel=1)
	{
		?>
        
         <div class="panel panel-default" style="width:180px">
         <div class="panel-heading font_14">Public API Tables</div>
         <div class="panel-body">
         <table width="100%">
         
         <tr><td>
         <a href="key_api.php" class="font_14"><? if ($sel==1) print "<strong>Overview</strong>"; else print "Overview"; ?></a>
         </td><td colspan="2"><? if ($sel==1) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         <tr><td><hr></td></tr>
         
         <tr><td>
         <a href="key_trans.php" class="font_14"><? if ($sel==2) print "<strong>Transactions</strong>"; else print "Transactions"; ?></a>
         </td><td colspan="2"><? if ($sel==2) print "<img src=\"../../app/reference/GIF/arrow.png\">"; ?></td></tr>
         
         
         
        
         </table>
       </div>
  </div>
  <br><br>
        
        <?
	}
	
	function showMenu($sel=1)
	{
		 // Menu
	     if ($_REQUEST['ud']['ID']>0)
	     $this->template->showNav($sel,
	                              "index.php", "Public API", "",
	                              "key_api.php", "Key API", "");
	}
}
?>