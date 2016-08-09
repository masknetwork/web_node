<?
class CStorage
{
	function CStorage($db, $template, $appID, $table, $local)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->ID=$appID;
		$this->table=$table;
		$this->local=$local; 
	}
	
	function showTable()
	{
		if ($this->local==true)
			   $tab="storage_local";
			else
			   $tab="storage";
			   
		if ($this->table=="")
		{
			$query="SELECT DISTINCT(tab) FROM ".$tab;
			$result=$this->kern->execute($query);
			if (mysql_num_rows($result)==0) die ("Storage is empty");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$this->table=$row['tab']; 
		}
		
		$query="SELECT * 
		          FROM ".$tab." 
				 WHERE tab='".$this->table."' 
				   AND aID='".$this->ID."'"; 
		$result=$this->kern->execute($query);
		
		?>
        
         <table width="3000px" border="1" class="table table-bordered table-bordered table-hover">
         <thead class="font_14">
         <th>S1</th>
         <th>S2</th>
         <th>S3</th>
         <th>S4</th>
         <th>S5</th>
         <th>S6</th>
         <th>S7</th>
         <th>S8</th>
         <th>S9</th>
         <th>S10</th>
         <th>D1</th>
         <th>D2</th>
         <th>D3</th>
         <th>D4</th>
         <th>D5</th>
         <th>D6</th>
         <th>D7</th>
         <th>D8</th>
         <th>D9</th>
         <th>D10</th>
         <th>Block</th>
         </thead>
         
         <tbody>
         
         <?
		    while  ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
		 ?>
         
            <tr>
            <td class="font_12"><? print base64_decode($row['s1']); ?></td>
            <td class="font_12"><? print base64_decode($row['s2']); ?></td>
            <td class="font_12"><? print base64_decode($row['s3']); ?></td>
            <td class="font_12"><? print base64_decode($row['s4']); ?></td>
            <td class="font_12"><? print base64_decode($row['s5']); ?></td>
            <td class="font_12"><? print base64_decode($row['s6']); ?></td>
            <td class="font_12"><? print base64_decode($row['s7']); ?></td>
            <td class="font_12"><? print base64_decode($row['s8']); ?></td>
            <td class="font_12"><? print base64_decode($row['s9']); ?></td>
            <td class="font_12"><? print base64_decode($row['s10']); ?></td>
            <td class="font_12"><? print $row['d1']; ?></td>
            <td class="font_12"><? print $row['d2']; ?></td>
            <td class="font_12"><? print $row['d3']; ?></td>
            <td class="font_12"><? print $row['d4']; ?></td>
            <td class="font_12"><? print $row['d5']; ?></td>
            <td class="font_12"><? print $row['d6']; ?></td>
            <td class="font_12"><? print $row['d7']; ?></td>
            <td class="font_12"><? print $row['d8']; ?></td>
            <td class="font_12"><? print $row['d9']; ?></td>
            <td class="font_12"><? print $row['d10']; ?></td>
            <td class="font_12"><? print $row['block']; ?></td>
            
            </tr>
     
        <?
			}
		?>
        
        </tbody>
        </table>
        
        <?
	}
	
	function showTables()
	{
		if ($this->local==true)
		   $query="SELECT DISTINCT(tab) FROM storage_local WHERE aID='".$this->ID."'"; 
		else
	       $query="SELECT DISTINCT(tab) FROM storage ".$table." WHERE aID='".$this->ID."'"; 
		   
		
		$result=$this->kern->execute($query);	
	    
		?>
        
        <div class="panel panel-default">
        <div class="panel-heading font_14">Tables</div>
        <div class="panel-body">
        <table style="width:100%">
        
        <?
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
		?>
        
                <tr><td><a href="storage.php?aID=<? print $this->ID; ?>&table=<? print $row['tab']; ?>" class="font_14"><? print $row['tab']; ?></a></td></tr>
                <tr><td><hr></td></tr>
        
        <?
			}
		?>
        
        </table>
        </div>
        </div>
        
        <?
	}
	
	function showStorage()
	{
		?>
        
        <table style="width:6000px">
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        <td width="30px">&nbsp;</td>
        <td width="200px" valign="top"><? $this->showTables(); ?></td>
        <td width="50px">&nbsp;</td>
        <td valign="top" align="left"><? $this->showTable(); ?></td>
        </tr>
        </table>
        
        <?
	}
	
}
?>