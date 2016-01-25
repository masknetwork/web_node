<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>jQuery File Upload Example</title>

<input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple style="display:none">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>

$(document).ready(

$(function (e) 
{
	var i=0;
	
    $('#fileupload').fileupload({
        dataType: 'json',
		url: './server/php/index.php',
		
		add: function(e, data) 
		{ 
		   data.files.forEach(function(file) 
		   { 
		      if (file.name.indexOf('.jpg')<0 && 
			      file.name.indexOf('.jpeg')<0) 
			  return false; 
		   });
		   
		   data.submit();
		},
		
		progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10); 
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        },
        
		done: function (e, data) {
			 $.each(data.result.files, function (index, file) 
			 {
				$('#img_'+i).attr('src', 'crop.php?src=./server/php/files/'+file.name+'&w=150&h=150');
				$('#img_'+i).css('display', 'block');
				i++;
            });
        }
    });
}));




</script>

</head>

<body>
<div id="progress" class="progress">
        <div class="progress-bar progress-bar-success">&nbsp;</div>
    </div>
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center" width="100"><img id="img_0" src="" width="100px" style="display:none"></td>
      <td align="center">&nbsp;</td>
      <td align="center" width="100"><img id="img_1" src="" width="100px" style="display:none"></td>
      <td align="center">&nbsp;</td>
      <td align="center" width="100"><img id="img_2" src="" width="100" style="display:none"></td>
      <td align="center">&nbsp;</td>
      <td align="center" width="100"><img id="img_3" src="" width="100" style="display:none"></td>
      <td align="center">&nbsp;</td>
      <td align="center" width="100"><img id="img_4" src="" width="100" style="display:none"></td>
    </tr>
  </tbody>
</table>
    
   
</body> 
</html>