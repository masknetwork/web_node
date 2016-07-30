<?
    class CTest
	{
	   function CTest($db)
	   {
		   $this->kern=$db;
	   }
	   
	   function genTab($table)
	   {
		   $query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'wallet' AND TABLE_NAME = '".$table."'";
		   $result=$this->kern->execute($query);
		   
		   print "// ".ucfirst($table)."<br>";
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		       print "this.fields_adr.add(\"".$row['COLUMN_NAME']."\");<br>";
	   }
	   
       function generate($table)
	   {
		    $query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'wallet' AND TABLE_NAME = '".$table."'";
		    $result=$this->kern->execute($query);
		
		    print "public CCell load".ucfirst($table)."() throws Exception<br>";
		    print "&nbsp;&nbsp;&nbsp;&nbsp;{<br>";
		
		    print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Result<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ResultSet rs=this.query();<br><br>";
        
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Creates new cell<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CCell c=new CCell(\"\");<br><br>";
        
		    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		    {
               print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// ".ucfirst($row['COLUMN_NAME'])."<br>";
               print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CCell col".ucfirst($row['COLUMN_NAME'])."=new CCell(\"\");<br>";
               print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;col".ucfirst($row['COLUMN_NAME']).".name=\"".$row['COLUMN_NAME']."\";<br><br>";
		    }
		   
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Has data<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (UTILS.DB.hasData(rs))<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Load data<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;while (rs.next())<br>";
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br>";
		   
		   $result=$this->kern->execute($query);
		   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		   {
               print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// ".ucfirst($row['COLUMN_NAME'])."<br>";
               print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;col".ucfirst($row['COLUMN_NAME']).".addCell(new CCell(rs.getString(\"".$row['COLUMN_NAME']."\")));<br><br>";
		   }
               
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else<br>"; 
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c.copy(new CCell(0));<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return c;<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br><br>";  
        
		  $result=$this->kern->execute($query);
		  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		  {
             print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// ".ucfirst($row['COLUMN_NAME'])."<br>";
             print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c.addCell(col".ucfirst($row['COLUMN_NAME']).");<br><br>";
		  }
		
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Copy<br>";
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return c;<br>";
		  print "&nbsp;&nbsp;&nbsp;&nbsp}";
		
	   }
	}
?>