<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CReveal.php";
   
   // Database
   $db=new db();
   
   // Template
   $template=new CTemplate($db);
   
   // User data
   $ud=new CUserData($db);
   
   // Sys data
   $ud=new CSysData($db);
   
   // Private key
   $pkey=new CReveal($db, $template);
   
   // Action
   switch ($_REQUEST['act'])
   {
	   case "show_pk" : $pkey->show($_REQUEST['ID']); break;
   }
?>