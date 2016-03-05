var WebSocketServer = require('ws').Server;
var wss=new WebSocketServer({port:8181});
var mysql = require('mysql');
 
wss.on('connection', 
function(ws) 
{ 
   var mes=""; 
   var interval;
   
   console.log('new client connected...'); 
   
   var connection = mysql.createConnection
   ({
      host     : 'localhost',
      user     : 'root',
      password : '',
      database : 'wallet'
   });

  connection.connect();

   
   ws.on('message', 
   function(message)
   {
	   mes=message;   
   });
   
    ws.on('close', 
   function(message)
   {
	   console.log("Connection closed.");
	   clearInterval(interval);
	   connection.close();
   });
   
  
   
   function ping()
   {
	   var packet;
	   var pos;
	   var posID="";
	   var response="{";
	   
	   try
	   {
	       if (mes!="")
	       {
		       packet=JSON.parse(mes)['type'];
		   
		       switch (packet)
		       {
			       case "get_pos" : pos=JSON.parse(mes)['data']; 
			   
			                       for (a=0; a<=pos.length-1; a++)
					  		       {
								      posID=posID+"'"+pos[a]+"'";
								      if (a<pos.length-1) posID=posID+',';
							       }
							   
							       connection.query("SELECT fsm.cur, fsmp.posID, fsmp.mktID, fsmp.pl, ROUND(fsmp.pl*100/fsmp.margin*100)/100 AS pl_proc FROM feeds_spec_mkts_pos AS fsmp JOIN feeds_spec_mkts AS fsm ON fsm.mktID=fsmp.mktID WHERE posID IN ("+posID+")", 
							       function(err, rows, fields) 
							       {
								        if (err) throw err;
									
									    response=response+"\"positions\" : [";
									    for (a=0; a<=rows.length-1; a++)
									    {
									         response=response+"{\"posID\" : \""+rows[a]['posID']+"\", \"pl\" : \""+rows[a]['pl']+"\", \"pl_proc\" : \""+rows[a]['pl_proc']+"\", \"cur\" : \""+rows[a]['cur']+"\"}";
										     if (a<rows.length-1) response=response+',';
									     }
									     
										 // Close response
									     response=response+"]}";
                                         
										 // Send response
									     ws.send(response);
							       });
							   
							       break;
		    }
	      }
	   }
	   catch (err)
	   {
		   console.log(err.message);
		   connection.close();
	   }
	  
   }
   
  interval=setInterval(ping, 5000);
});

