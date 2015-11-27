<?
  include "./kernel/db.php";
  $db=new db(false);
   
  if (isset($_REQUEST['hash']))
  {
    $query="SELECT * FROM imgs WHERE hash='".$_REQUEST['hash']."'";
	$result=$db->execute($query);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
    header("Content-type: content-type: image/gif");
    echo $row['img']; 
  }	
?>