<?
class CStorage
{
	function CStorage($db, $template, $appID)
	{
		$this->kern=$db;
		$this->template=$template;
		$this->aID=$appID; 
		
		// Load data
		$query="SELECT * 
		          FROM agents_mine 
				 WHERE ID='".$appID."'";
		$result=$this->kern->execute($query);	
	    $row = mysql_fetch_array($result, MYSQL_ASSOC);
		
		// Storage
		$storage=base64_decode($row['storage']);
		
		// Decoded
		$this->decoded=json_decode($storage);
	}
	
	function showTables()
	{
		?>
        
           <div class="panel panel-default">
           <div class="panel-heading font_16">Tables</div>
           <div class="panel-body">
           <table width="100%">
          
           <?
		      for ($a=0; $a<=sizeof($this->decoded->tables)-1; $a++)
			  {
				 print "<tr><td><a href='storage.php?ID=".$_REQUEST['ID']."&table=".$this->decoded->tables[$a]->name."' class='font_14'>".$this->decoded->tables[$a]->name."</a></td></tr>";
				 print "<tr><td><hr></td></tr>";
			  }
           ?>
		   
           </table>
           </div>
           </div>
        
        <?
	}
	
	function showColumns($table)
	{
		if ($table=="")
		{
			$pos=0;
		}
		else
		{
		   for ($a=0; $a<=sizeof($this->decoded->tables)-1; $a++)
               if ($this->decoded->tables[$a]->name==$table)
			      $pos=$a;	
		}
	    
		// Table	
		$table=$this->decoded->tables[$pos]->name;
		
		?>
        
            <form id="form_col" name="form_col" action="storage.php?ID=<? print $_REQUEST['ID']; ?>&table=<? print $table; ?>" method="post">
            <select id="dd_col" name="dd_col" class="form-control" onChange="$('#form_col').submit()">
            
			<?
			    for ($a=0; $a<=sizeof($this->decoded->tables[$pos]->columns)-1; $a++) 
				{
			       if ($_REQUEST['dd_col']==$this->decoded->tables[$pos]->columns[$a]->name) 
				      print "<option selected value='".$this->decoded->tables[$pos]->columns[$a]->name."'>";
				   else
				      print "<option value='".$this->decoded->tables[$pos]->columns[$a]->name."'>";
					  
				   print $this->decoded->tables[$pos]->columns[$a]->name;
				   print "</option>"; 
				}
			?>
            
            </select>
            </form>
        
        <?
	}
	
	function showData($table, $col)
	{
		if ($table=="")
		{
			$pos=0;
		}
		else
		{
		   for ($a=0; $a<=sizeof($this->decoded->tables)-1; $a++)
               if ($this->decoded->tables[$a]->name==$table)
			      $pos=$a;	
		}
	    
		// Table	
		$table=$this->decoded->tables[$pos];
		
		// Column
		if ($col=="") 
		{
			$col=$table->columns[0];
		}
		else
		{
			for ($a=0; $a<=sizeof($table->columns)-1; $a++)
               if ($table->columns[$a]->name==$col)
			      $pos=$a;	
				  
		    $col=$table->columns[$pos];
		}
		
		
		?>
        
          <table width="100%" class="table-responsive">
          
          
          <?
		     for ($a=0; $a<=sizeof($col->data)-1; $a++)
			 {
				print "<tr>"; 
                print "<td class='font_14'>#$a</td>";
                print "<td align='right' class='font_14'>".$col->data[$a]."</td>";
				print "</tr>";
				print "<tr><td colspan='2'><hr></td></tr>";
			 }
          ?>
          
          
          </table>
        
        <?
	}
}
?>