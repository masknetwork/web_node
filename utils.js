
	function linkToNetFee(target, panel, init)
		   {
			   var num;
			   
			   $('#'+panel).text(init);
			   
			   $('#'+target).keyup(
			   function(event) 
			   {
				   
				   if ($('#'+target).val()!="")
				   {
					 $('#'+target).val(Math.round($('#'+target).val()));
				     num=parseInt($('#'+target).val())*0.0001; 
				     num=Math.round(num*10000)/10000;
				     $('#'+panel).text(String(num));
				   }
			   });
		   }
		   
		   function linkToNetFeeBid(target, panel, bid, init)
		   {
			   var num;
			   
			   $('#'+panel).text(init);
			   
			   $('#'+target).keyup(
			   function(event) 
			   {
				   if ($('#'+target).val()!="" && $('#'+bid).val()!="")
				   {
					 $('#'+target).val(Math.round($('#'+target).val()));
				     num=parseInt($('#'+target).val())*$('#'+bid).val(); 
				     num=Math.round(num*10000)/10000;
				     $('#'+panel).text(String(num));
				   }
			   });
			   
			   $('#'+bid).keyup(
			   function(event) 
			   {
				   if ($('#'+target).val()!="" && $('#'+bid).val()!="")
				   {
					 $('#'+target).val(Math.round($('#'+target).val()));
				     num=parseInt($('#'+target).val())*$('#'+bid).val(); 
				     num=Math.round(num*10000)/10000;
				     $('#'+panel).text(String(num));
				   }
			   });
		   }
