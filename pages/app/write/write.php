<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CWrite.php";
   include "CGlobals.php";

   
   // Db
   $db=new db();
   
   // Template
   $template=new CTemplate($db);
   
   // User data
   $ud=new CUserData($db);
   
   // System data
   $sd=new CSysData($db);
   
   // Write
   $write=new CWrite($db, $template);
   
   // Globals
   $globals=new CGlobals($db, $template, $_REQUEST['appID']);
   
  
   switch ($_REQUEST['act'])
   {
	  // Save code
      case "save_code" : $write->save($_REQUEST['appID'], $_REQUEST['code']); 
	                     break;
						  
	  // Save globals
      case "save_globals" : $globals->save($_REQUEST['appID'], $_REQUEST['globals']); 
	                        break;
						 
	  // Get status
	  case "get_status" : $write->getStatus($_REQUEST['appID'], $_REQUEST['target']); 
	                      break;
						  
	  // Get run results
	  case "get_run_res" : $write->getRunPage($_REQUEST['appID']); 
	                      break;
   }
?>