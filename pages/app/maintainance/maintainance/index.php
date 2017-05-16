<?
   session_start();
    
   include "../../../kernel/db.php";
   
   $db=new db();
   
   $query="SELECT * FROM web_sys_data";
   $result=$db->execute($query);	
   $row = mysql_fetch_array($result, MYSQL_ASSOC);
  
   if (($row['status']=="ID_ONLINE" || $row['status']=="ID_SYNC") && 
      (time()-$row['last_ping'])<10)
      $db->redirect("../../../index.php");
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Maintainance</title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>

<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="../../../gallery.css">
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">

<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="../../../gallery.min.js"></script>

<script src="../../../jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="../../../jupload/js/jquery.iframe-transport.js"></script>
<script src="../../../jupload/js/jquery.fileupload.js"></script>

<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

</head>

<body style="background-color:#292a3c">
<table width="100%" border="1">
  <tbody>
    <tr>
      <td height="750" align="center"><table width="800" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="400" align="left"><img src="GIF/main.jpg" width="400" height="454" alt=""/></td>
            <td width="360" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td class="font_30" style="color:#ffffff">Maintainance in progress...</td>
                </tr>
                <tr>
                  <td class="font_16" style="color:#ebecff">Don't worry. Your coins are safe with us. Every website has to perform <strong>maintenance</strong> at some point or another. Whether it&rsquo;s just to upgrade a portion of the site or because of some problem with the site, it&rsquo;s an inevitable fact of website ownership. </td>
                </tr>
                <tr>
                  <td><hr style="color:#ffffff"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>