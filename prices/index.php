<?
   include "../db.php";   
   include "CPrices.php";
   
   $db=new db();
   $prices=new CPrices($db);
   
   switch ($_REQUEST['act'])
   {
	   case "update" : $prices->updatePrices(); break;
	   case "get_feed" : $prices->getFeed($_REQUEST['symbol']); break;
   }
   
   //$prices->stocksRates();
?>