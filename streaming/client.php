<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
 <script type = "text/javascript" 
         src = "http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		
      <script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		

<script>
var ws=new WebSocket("ws://localhost:8181");
ws.onopen=function(e) 
{
	console.log('Connection started...');
	
	var packet={'type' : 'get_pos', 'pos' : ['7080067776', '5743716913']};
	ws.send(JSON.stringify(packet));
}

ws.onmessage=function(e)
{
   console.log(e.data);
   var data=JSON.parse(e.data);
   
   for (a=0; a<=data['positions'].length-1; a++)
   {
      var posID=data['positions'][a]['posID'];
      var pl=data['positions'][a]['pl'];
      var pl_proc=data['positions'][a]['pl_proc']; 
      
	  // Old pl
	  var old_pl=parseFloat($('#td_pos_'+posID).text());
	  
	  if (old_pl==parseFloat(pl)) 
	     $('#td_pos_'+posID).effect( "highlight", {color:"#f0f0f0"}, 1000 );
	  else if (old_pl>parseFloat(pl)) 
	     $('#td_pos_'+posID).effect( "highlight", {color:"#990000"}, 1000 );
	  else if (old_pl<parseFloat(pl)) 
	     $('#td_pos_'+posID).effect( "highlight", {color:"#009900"}, 1000 );
	  
	  
	  // Old proc
	  var old_pl_proc=parseFloat($('#td_pos_proc_'+posID).text());
	 
	  if (old_pl_proc==parseFloat(pl_proc)) 
	     $('#td_pos_proc_'+posID).effect( "highlight", {color:"#f0f0f0"}, 1000 );
	  else if (old_pl_proc>parseFloat(pl_proc)) 
	     $('#td_pos_proc_'+posID).effect( "highlight", {color:"#990000"}, 1000 );
	  else if (old_pl_proc<parseFloat(pl_proc)) 
	     $('#td_pos_proc_'+posID).effect( "highlight", {color:"#009900"}, 1000 );
	  
	  $('#td_pos_'+posID).text(pl)
	  $('#td_pos_proc_'+posID).text(pl_proc+"%")
   }
}

ws.onError=function(e)
{
	console.log(e);
}

</script>




</head>

<body>
<table width="200" border="1">
  <tbody>
    <tr>
      <td id="td_pos_7080067776">0.0000</td>
      <td id="td_pos_proc_7080067776">0%</td>
    </tr>
    <tr>
      <td id="td_pos_5743716913">0.0000</td>
      <td id="td_pos_proc_5743716913">0%</td>
    </tr>
  </tbody>
</table>
</body>
</html>