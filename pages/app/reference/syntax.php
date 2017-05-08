<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CApp.php";
   include "../write/CWrite.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $app=new CApp($db, $template);
   $write=new CWrite($db, $template, $app);
   
   if (!isset($_SESSION['userID'])) $this->kern->redirect("../../../index.php");
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><? print $_REQUEST['sd']['website_name']; ?></title>
<script src="../../../flat/js/vendor/jquery.min.js"></script>
<script src="../../../flat/js/flat-ui.js"></script>
<link rel="stylesheet"./ href="../../../flat/css/vendor/bootstrap/css/bootstrap.min.css">
<link href="../../../flat/css/flat-ui.css" rel="stylesheet">
<link href="../../../style.css" rel="stylesheet">
<link rel="shortcut icon" href="../../../flat/img/favicon.ico">

<style>
@media only screen and (max-width: 1000px)
{
   .balance_usd { font-size: 40px; }
   .balance_MSK { font-size: 40px; }
   #but_send { font-size:30px; }
   #td_balance { height:100px; }
   #div_ads { display:none; }
   .txt_help { font-size:20px;  }
   .font_14 { font-size:20px;  }
   .font_10 { font-size:18px;  }
   .font_14 { font-size:22px;  }
}


.font_141 {font-size:20px;  }
.font_141 {font-size:22px;  }
</style>

</head>

<body>

<?
   $template->showTopBar("help");
?>
 

 <div class="container-fluid">
 
 <?
    $template->showBalanceBar();
 ?>


 <div class="row">
 <div class="col-md-1">&nbsp;</div>
 <div class="col-md-8" align="center" style="height:100%; background-color:#ffffff">
 
 <?
     // Location
     $template->showLocation("../../app/write/index.php", "Applications", "", "Reference");
	 
	
 ?>
 
 <br>
 <table width="90%" border="0" cellpadding="0" cellspacing="0">
   <tbody>
     <tr>
       <td width="23%" align="left" valign="top">
       
       <?
	      $write->showRefMenu(2);
	   ?>
       
       </td>
       <td width="6%" align="right" valign="top">&nbsp;</td>
       <td width="71%" align="right" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tbody>
           <tr>
             <td class="font_18"><strong>Syntax</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14"><strong>MaskNetwork Scripting Language </strong>(MSL) is an assembly-like <strong>turing complete</strong> programming language designed for developing decentralized applications that will run on MaskNetwork blockchain. MSL is no tcompiled to bytecode, is directly executed by MaskNetwork Virtual Machine (MVM).</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">MSL scripts are executed by each node of the network. Before execution, nodes instantiate a Virtual Machine that emulates a regular computer and start executing the script. The VM allows scripts to use registers, memory stack and data storage.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_16"><strong>Executable Instructions</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14">Executable Instructions or symply instructions tell the VM what to do. Each instruction consists of an <strong>operation code</strong> (opcode). Each executable instruction generates one VM language instruction.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">MSL language statements are entered one statement per line. A basic instruction has two parts, the first one is the name of the instruction (or the mnemonic), which is to be executed, and the second are the operands or the parameters of the command. Each statement follows the following format </td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14"><strong>INS operands</strong></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">For example the following instruction adds the value stored in register R1 to value stored in register R2 and moves the result in R1.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14"><strong>ADD r1, r2</strong></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
         </tbody>
       </table></td>
     </tr>
   </tbody>
 </table>
 <br>
 </div>
 <div class="col-md-2" id="div_ads"><? $template->showAds(); ?></div>
 <div class="col-md-1">&nbsp;</div>
 </div>
 </div>
 
 <?
    $template->showBottomMenu();
 ?>
 
</body>
</html>
