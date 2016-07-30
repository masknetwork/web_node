<?
   $total=0;
   $cost=0.00000001;
   
   for ($a=1; $a<=10000; $a++)
     $total=$total+($a*0.00000001);
	 
   
   print number_format($total, 8);
?>