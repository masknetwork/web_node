<?
  class CIndex
  {
	  function CIndex($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  function showLastPosts()
	  {
		  $query="SELECT tw.*, vs.*
		             FROM tweets AS tw 
					 LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
					  AND tw.block>".($_REQUEST['sd']['last_block']-10000)."
				 ORDER BY (vs.upvotes_power_total-vs.downvotes_power_total) DESC 
			        LIMIT 0, 5"; 
		  $result=$this->kern->execute($query);	
			  
	      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  {
			  
		  ?>
          
            <div class="panel panel-default" style="width:90%">
            <div class="panel-body">
              <table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="21%"><img src="
				          <? 
				             if ($row['pic']=="") 
					            print "./pages/template/template/GIF/mask.jpg"; 
					         else 
					            print "crop.php?src=".base64_decode($row['pic'])."&w=75&h=75"; 
					      ?>" width="100" height="100" alt="" class="img img-responsive img-rounded"/></td>
                          <td width="3%">&nbsp;</td>
                          <td width="76%" valign="top">
                          
                          <a href="./pages/tweets/tweet/index.php?ID=<? print $row['tweetID']; ?>" class="font_14">
						  <? 
						       if (strlen($row['title'])>35)
					             print substr(base64_decode($row['title']), 0, 35)."...";
					           else
					             print base64_decode($row['title']);
						  ?>
                          </a>
                          
                          <p class="font_12">
                          <? 
						       if (strlen($row['mes'])>90)
					             print substr(base64_decode($row['mes']), 0, 90)."...";
					           else
					             print base64_decode($row['mes']);
						  ?>
                          </p>
                          
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    </td>
                  </tr>
                  <tr><td><hr></td></tr>
                  <tr>
                    <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                        
                          <td width="39%" align="left">
                          <span class="font_16" style="color:#d03f30"><strong><? print "$".$this->kern->split($row['pay'], 2)[0]; ?></strong></span><span class="font_12" style="color:#d0675c"><? print ".".$this->kern->split($row['pay'], 2)[1]; ?></span>
                          </td>
                          <td width="26%" align="center">&nbsp;</td>
                          
                          <td width="18%" align="center"><span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#009900"><? print $row['upvotes_24']; ?></span></td>
                          
                          <td width="17%" align="center"><span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;<span class="font_12" style="color:#990000"><? print $row['downvotes_24']; ?></span></td>
                        
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
	
  }
?>