<?
class CAssets
{
	function CAssets($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	
	function showMyAssets()
	{
		$query="SELECT aso.*, ass.title, ass.description, ass.pic 
		          FROM assets_owners AS aso 
				  JOIN assets AS ass ON ass.symbol=aso.symbol 
				 WHERE aso.owner IN (SELECT adr 
				                     FROM my_adr 
									WHERE userID='".$_REQUEST['ud']['ID']."') 
				ORDER BY aso.qty DESC
			     LIMIT 0,20";
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="95%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="8%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=80&h=80"; ?>"  class="img-circle img-responsive"/></td>
                            <td width="1%">&nbsp;</td>
                            <td width="34%" valign="top">
                            <span class="font_14"><a href="../../assets/user/asset.php?symbol=<? print $row['symbol']; ?>">
							<? print $this->kern->noescape(base64_decode($row['title']))." (".$row['symbol'].")"; ?></a>
                            <p class="font_10"><? print $this->kern->noescape(substr(base64_decode($row['description']), 0, 100))."..."; ?></p></td>
                            <td width="22%" align="center"><a href="../../tweets/adr/index.php" class="font_14"><? print $this->template->formatAdr($row['owner']); ?></a></td>
                            <td width="16%" class="font_14" align="center"><strong>
							<? print round($this->kern->getBalance($row['owner'], $row['symbol']), 8); ?></strong></td>
                            
                            <td width="19%" ><a class="btn btn-danger" href="javascript:void(0)" onclick="$('#send_coins_modal').modal(); $('#tab_MSK').css('display', 'none'); $('#tab_assets').css('display', 'block'); $('#txt_cur').val('<? print $row['symbol']; ?>'); $('#dd_from').val('<? print $row['owner']; ?>');"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Send</a></td>
                            </tr>
                            <tr>
                            <td colspan="6"><hr></td>
                            </tr>
                      
                      <?
	                      }
					  ?>
                        
                  </table>
                  
                 
        
        <?
	}
	
	function showIssuedAssets()
	{
		$query="SELECT * 
		          FROM assets 
				 WHERE linked_mktID=0 
				   AND adr IN (SELECT adr 
				                 FROM my_adr 
								WHERE userID='".$_REQUEST['ud']['ID']."')
			  ORDER BY ID ASC
			     LIMIT 0,20";
		 $result=$this->kern->execute($query);	
		 
		?>
        
          <table width="95%" border="0" cellspacing="0" cellpadding="0">
                      
                      <?
					     while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
						 {
					  ?>
                      
                            <tr>
                            <td width="4%"><img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100"; ?>"  class="img-circle img-responsive"/></td>
                            <td width="1%">&nbsp;</td>
                            <td width="95%">
                            <span class="font_16"><a href="asset.php?symbol=<? print $row['symbol']; ?>">
							<? print $this->kern->noescape(base64_decode($row['title']))." (".$row['symbol'].")"; ?></a>
                            <p class="font_12"><? print $this->kern->noescape(substr(base64_decode($row['description']), 0, 250))."..."; ?></p></td>
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