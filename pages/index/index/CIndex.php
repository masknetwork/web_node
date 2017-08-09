<?
  class CIndex
  {
	  function CIndex($db, $template)
	  {
		  $this->kern=$db;
		  $this->template=$template;
	  }
	  
	  
	  function showBigPanel($row)
	  {
		  ?>
          
              <div class="panel panel-default">
                <div class="panel-body">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="60" align="center" valign="top">
                        <a href="./pages/tweets/tweet/index.php?ID=<? print $row['tweetID']; ?>" style="color:#202E35" class="font_16"><strong>
                         <? 
						       if (strlen($row['title'])>35)
					             print substr($this->kern->noescape(base64_decode($row['title'])), 0, 35)."...";
					           else
					             print $this->kern->noescape(base64_decode($row['title']));
						  ?>
                        </strong></a></td>
                        </tr>
                      <tr>
                        <td align="center" valign="bottom" height="50px">
                        <a href="./pages/tweets/tweet/index.php?ID=<? print $row['tweetID']; ?>" border="0">
                        <img src="<? 
				                     if ($row['pic']=="") 
					                    print "crop.php?src=./pages/template/template/GIF/mask.jpg&w=290&h=188"; 
					                else 
					                    print "crop.php?src=".$this->kern->noescape(str_replace("www.www", "www", base64_decode($row['pic'])))."&w=290&h=188"; 
					      ?>" width="290" height="188" alt="" class="img img-responsive img-rounded"/>
                          </a>
                          </td>
                        </tr>
                      <tr>
                        <td align="center" height="40px">
                        
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                        
                          <td width="39%" align="left">
                          <span class="font_16" style="color:#d03f30"><strong>
						  <? print "$".$this->kern->split($row['pay'], 2)[0]; ?>
                          </strong></span><span class="font_12" style="color:#d0675c">
						  <? print ".".$this->kern->split($row['pay'], 2)[1]; ?></span>
                          </td>
                          <td width="26%" align="center">&nbsp;</td>
                          
                          <td width="18%" align="center">
                          <span class="glyphicon glyphicon-thumbs-up" style="color:#009900; font-size:14px"></span>&nbsp;&nbsp;
                          <span class="font_12" style="color:#009900"><? print $row['upvotes_24']; ?></span>
                          </td>
                          
                          <td width="17%" align="center">
                          <span class="glyphicon glyphicon-thumbs-down" style="color:#990000; font-size:14px"></span>&nbsp;&nbsp;
                          <span class="font_12" style="color:#990000"><? print $row['downvotes_24']; ?></span>
                          </td>
                        
                        </tr>
                        </tbody>
                        </table>
                        
                        </td>
                        </tr>
                      </tbody>
                    </table>
                  </div> 
                </div>
          
          <?
	  }
	  
	  function showSmallPanel($row)
	  {
		  ?>
          
          <div class="panel panel-default">
                <div class="panel-body">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td height="60" align="center" valign="top"><a href="#" style="color:#202E35" class="font_14"><strong>Why A Bitcoin ETF Will Change Everything For All..</strong></a></td>
                      </tr>
                      <tr>
                        <td align="center"><img src="blonde.jpg" width="200" height="139" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
          
          <?
	  }
	  
	  function showLastPosts()
	  {
		  $query="SELECT tw.*, vs.*
		             FROM tweets AS tw 
					 LEFT JOIN votes_stats AS vs ON vs.targetID=tw.tweetID
					  AND tw.block>".($_REQUEST['sd']['last_block']-50000)."
				 ORDER BY pay DESC 
			        LIMIT 0, 20"; 
		  $result=$this->kern->execute($query);	
		  
		  ?>
          
           <table width="1000" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="300" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="332" align="center">&nbsp;</td>
            <td width="14" align="center">&nbsp;</td>
            <td width="336" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td width="300" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			  ?>
              
             
              
              </td>
            <td width="18" align="center">&nbsp;</td>
            <td width="332" align="center">
            
			<?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
            
            </td>
            <td width="14" align="center">&nbsp;</td>
            <td width="336" align="center">
            
            <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
              
              </td>
          </tr>
          <tr>
            <td align="center">
            
            <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
            
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">
            
            <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
            
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">
            
            <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
            
            </td>
          </tr>
          </tbody>
      </table>
        <table width="1000" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
		   	  ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			  ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			   ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
              
              </td>
            </tr>
           
           <tr>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
		   	  ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			  ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			   ?>
              
              </td>
              <td width="30" align="center">&nbsp;</td>
              <td width="230" align="center">
              
              <?
			     $row = mysqli_fetch_array($result, MYSQL_ASSOC);
				 $this->showBigPanel($row);
			?>
              
              </td>
            </tr>
            
          </tbody>
      </table>
          
          
          <?
	  }
	
  }
?>