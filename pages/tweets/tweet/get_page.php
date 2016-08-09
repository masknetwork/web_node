<?
    session_start();
      
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../../tweets/CTweets.php";
  
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $tweets=new CTweets($db, $template);
   
   switch ($_REQUEST['act'])
   {
	   case "get_power" : $template->getPower($_REQUEST['adr'], $_REQUEST['type']); break;
   }
?>