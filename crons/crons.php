<?
   include "../kernel/db.php";
   include "../kernel/CSysData.php";
   include "CCrons.php";
   
   $db=new db();
   $sd=new CSysData($db);
   $crons=new CCrons($db);
   
   $crons->runCrons();
?>