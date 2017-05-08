<?
   include "./kernel/db.php";
   
   $db=new db();
   $result=$db->execute("SELECT * FROM adr WHERE adr='default'");
   $row = mysql_fetch_array($result, MYSQL_ASSOC);
   print $row['balance'];
?>