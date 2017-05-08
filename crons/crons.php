<?
   include "../kernel/db.php";
   include "../kernel/CSysData.php";
   include "CCrons.php";
   
   $db=new db();
   $sd=new CSysData($db);
   $crons=new CCrons($db);
   
   //print $db->getReward("ID_COM");
   $crons->runCrons();
   $crons->checkBalances();
?>