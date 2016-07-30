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
   .balance_msk { font-size: 40px; }
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
   $template->showTopBar(6);
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
	      $write->showRefMenu(1);
	   ?>
       
       </td>
       <td width="6%" align="right" valign="top">&nbsp;</td>
       <td width="71%" align="right" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tbody>
           <tr>
             <td class="font_16"><strong>Intro</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14"><strong>Decentralized applications </strong>(or smart contracts) are applications that have the source code and data stored in a public decentralized blockchain without any possibility of downtime, censorship, fraud or third party interference. Instead of running on a single machine like regular applications, a decentralized application runs on hundreads of computers in the same time avoiding central points of failure. Decentralized applications may enable a wide range of possible business models that were previously impossible or too costly to run</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">MaskNetwork protocol allows you to build and run decentralized applications that will be executed inside the <strong>Mask Network Virtual Machine</strong> (MVM). <strong>MaskNetwork Scripting Language </strong>(MSL) is an assembly-like <strong>turing complete</strong> programming language designed for developing decentralized applications that will run on MaskNetwork blockchain. MSL is no tcompiled to bytecode, is directly executed by MVM.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14"><strong>Execution Fees</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14">In order to stop denial of service attacks from infinite loops and encourage efficiency in the code, all applications will pay a small fee for each step executed inside  virtual machine (VM) . The fee is paid in <strong>MaskCoins</strong> (MSK), the the underlying currency of the network.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><span class="font_14">The price for a single step is <strong>not fixed</strong>. It depends on two factors. The first is the number of steps <strong>already executed</strong> by VM and the second is the instruction's <strong>complexity</strong>. Some computational steps cost <strong>more</strong> than others as well either because they are computationally expensive or because they increase the amount of data that has to be stored in the state.</span></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">For most instructions like simple mathematical operations, the cost is 1 <strong>MaskBit</strong> (MSB) or <strong>0.00000001</strong> MaskCoins (MSK). The price is increased by 1 MSB after each step executed. For example, an application that runs in 10 steps, will not pay 1 MSB + 2 MSB +....+10 MSB, or 50 MSB.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14"><strong>Storage Fees</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14">After an application runs, all memory data like variables or stack is <strong>lost</strong>. In order to permanently store data, programmers can use application's data storage. The storage is never lost reset and it can be used to store settings or other important data. It's like a <strong>database stored on the blockchain</strong>, that can be freely accessed by anyone (data in application's storage is not encrypted) but <strong>only</strong> the application can change it.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">To prevent applications from storing too much data, a fee of <strong>100 MSB / kb</strong> stored is paid after each block by all applications that used the storage. For example, an application that stores 10kb of data will pay 0.00001 MSK / block or <strong>0.014 MSK / day.</strong></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">In case an application<strong> runs out of funds</strong>, the network will automatically <strong>uninstall</strong> it (the nodes will delete application data from the distributed ledger).</td>
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
