<?
class CAdr
{
	function CAdr($db, $template)
	{
		$this->kern=$db;
		$this->template=$template;
	}
	
	function showPanel($adr)
	{
		$query="SELECT * 
		          FROM adr 
			 LEFT JOIN profiles AS pr ON pr.adr=adr.adr 
			     WHERE adr.adr='".$adr."'";
		$result=$this->kern->execute($query);	
	    $row = mysqli_fetch_array($result, MYSQL_ASSOC);
	  
		?>
        
          <br>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="position:relative">
          <tbody>
          <tr>
          <td colspan="2">
          <img src="<? if ($row['pic_back']=="") print "../../template/template/GIF/default_top_img.png"; else print "../../../crop.php?src=".base64_decode($row['pic_back'])."&w=1200&h=400" ?>" class="img-responsive img-rounded">
          <img src="<? if ($row['pic']=="") print "../../template/template/GIF/empty_pic.png"; else print "../../../crop.php?src=".base64_decode($row['pic'])."&w=100&h=100" ?>" class="img-responsive img-rounded" style="position:absolute; left:5%; top:54%; width:20%; border:solid; border-color:#ffffff; border-width:4px">
          <span>
          </td>
          </tr>
          <tr>
            <td width="26%">&nbsp;</td>
            <td width="74%" class="font_24" valign="bottom" height="50"><strong><? print $this->template->formatAdr($adr); ?></strong><p class="font_12"><? if ($row['name']) print base64_decode($row['name']); if ($row['description']!="") print ", ".base64_decode($row['description']); ?></p></td>
          </tr>
          </tbody>
          </table>
          <br><br>
        
        <?
	}
}
?>