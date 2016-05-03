<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "CWrite.php";
   include "CGlobals.php";
   include "CInterface.php";
   include "CSignals.php";
   
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
   
   // Interface
   $interface=new CInterface($db, $template, $_REQUEST['appID']);
   
   // Signals
   $signals=new CSignals($db, $template, $_REQUEST['appID']);
   
   print $globals->compile("ew0KCSJnbG9iYWxzIjogW3sNCgkJCSJJRCI6ICJzaWduZXJfMSIsDQoJCQkiZXhwbCI6ICJZb3UgaGF2ZSB0byBwcm92aWRlIGF0IGxlYXN0IG9uZSBzaWduZXIgYWRkcmVzcyIsDQoJCQkiZGF0YV90eXBlIjogInN0cmluZyIsDQoJCQkibWluIjogMjAsDQoJCQkibWF4IjogMTAwLA0KCQkJInZhbHVlIjogIm5vbmUiDQoJCX0sDQoNCgkJew0KCQkJIklEIjogInNpZ25lcl8yIiwNCgkJCSJleHBsIjogIllvdSBoYXZlIHRvIHByb3ZpZGUgYXQgbGVhc3Qgb25lIHNpZ25lciBhZGRyZXNzIiwNCgkJCSJkYXRhX3R5cGUiOiAic3RyaW5nIiwNCgkJCSJtaW4iOiAyMCwNCgkJCSJtYXgiOiAxMDAsDQoJCQkidmFsdWUiOiAibm9uZSINCgkJfQ0KCV0NCn0=");
?>