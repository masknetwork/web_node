<?
class CVote
{
	function CVote($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showVotes($target_type, $targetID)
	{
		?>
        
        <br><br>
        <table class="table table-bordered table-hover table-striped" style="width:90%">
        <thead class="font_14">
        <th width="50%">Content</th>
        <th width="15%">Vote Type</th>
        <th width="13%">Power</th>
        <th width="22%">Block</th>
       
        <tbody>
        <tr class="font_14">
        <td><strong><a href="#">Test Article</a></strong><p class="font_12">f vdf vfd vfd vfd vfdv df vfdv fd vfd vfdv dfvd...</p></td>
        <td align="center" style="color:#009900"><strong>UPVOTE</strong></td>
        <td align="center" style="color:#009900"><strong>+32.12</strong><p class="font_10" style="color:#aaaaaa">points</p></td>
        <td align="center">4322<p class="font_10">~3 hours ago</p></td>
        </tr>
        </tbody>
        </table>
        
        <?
	}
	
	function showPanel($adr)
	{
		?>
        
        <div class="panel panel-default" style="width:90%">
  <div class="panel-heading">
    <h3 class="panel-title font_14">Panel title</h3>
  </div>
  <div class="panel-body font_14">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td>Every 24 hours, users are rewarded for content they create like applications, blog posts, comments or even votes. The most voted content receive the biggest reward. Content is voted by regular users like you, and voters get also a reward. In order to get your voting reward, you need to vote at least 5 comments, 3 blog posts and one other kind of content like applications, data feeds, assets or even bets. Below are displayed your last votes with this address. Keep in mind that voting power decreases after each vote. Voting power depends on voting address balance and number of votes in the last 24 hours.</td>
            </tr>
          <tr>
            <td><hr></td>
            </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="25%" height="40" align="center">Voted Comments</td>
                  <td width="25%" align="center">Blog posts</td>
                  <td width="25%" align="center">Other content</td>
                  <td width="25%" align="center">Voting Reward</td>
                </tr>
                <tr>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/p2.png" style="background-size:contain" class="font_20">2/5</td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/p10.png" style="background-size:contain" class="font_20">2/5</td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="150" align="center" background="GIF/p2.png" style="background-size:contain" class="font_20">2/5</td>
                      </tr>
                    </tbody>
                  </table>
                  </td>
                  
                  <td align="center">
                  <table width="150" border="0" align="center">
  <tbody>
    <tr>
      <td align="center" valign="top" height="130">
      
      <table width="120" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="120" align="center" background="GIF/p6.png" style="background-size:contain; color:#009900" class="font_18">$21<span class="font_12">.21</span></td>
                      </tr>
                    </tbody>
                  </table>
                  
                  </td>
    </tr>
    <tr>
      <td bgcolor="#fafafa" class="font_12" align="center">payment in ~3 hours</td>
    </tr>
  </tbody>
</table>
                  </td>
                  
                </tr>
              </tbody>
            </table></td>
            </tr>
        </tbody>
      </table>
      
  </div>
</div>
        
        <?
	}
}
?>