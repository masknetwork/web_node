<?
class CAffiliates
{
	function CAffiliates($kernel, $template)
	{
		$this->kern=$kernel;
		$this->template=$template;
	}
	
	function showMyAff()
	{
		?>
        
          <br>
          <table class="table table-condensed table-hover table-responsive" style="width:90%">
          <thead>
          <th class="font_14" width="70%">Address</th>
          <th class="font_14" width="10%">Balance</th>
          <th class="font_14" width="10%">Created</th>
          </thead>     
          <tbody>
          <tr>   
          <td class="font_14"><a href="#">...vdfvfdvdfvdfvdfvdf...</a></td>
          <td class="font_14">433 MSK</td>
          <td class="font_14">12 days ago</td>
          </tr>
          </tbody>
          </table>
        
        <?
	}
}
?>