<?
   include "../kernel/db.php";
   include "CCrons.php";
   
   $db=new db();
   $crons=new CCrons($db);
   
   $crons->runCrons();
?>