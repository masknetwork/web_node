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
             <td height="40">&nbsp;</td>
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
             <td height="40">&nbsp;</td>
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
             <td height="40">&nbsp;</td>
           </tr>
           <tr>
             <td class="font_16"><strong>Developing  applications</strong></td>
           </tr>
           <tr>
             <td class="font_14"><hr></td>
           </tr>
           <tr>
             <td class="font_14">Writing scripts using MaskNetwork Scripting Language can be done using any web wallet. Wallets have an editor and debugger integrated. You can write and even step by step debug your applications using a regular browser without downloading anything. </td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">You can also test different use cases or events like incoming transactions or messages. When you test a script, a full featured virtual machine will execute your code, but no real transactions / messages will be performed.</td>
           </tr>
           <tr>
             <td height="40">&nbsp;</td>
           </tr>
           <tr>
             <td class="font_16"><strong>Installing / uninstalling applications</strong></td>
           </tr>
           <tr>
             <td class="font_14"><hr></td>
           </tr>
           <tr>
             <td class="font_14">Just writing a script and testing it on your local virtual machine doesn't mean it's active on the network and can be used by anyone else. In order to be active on the network, you need to &quot;install&quot; it. Installing an application it's a 2 clicks process and can be done using any web wallet. </td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">Just like any other MaskNetwork record, applications are installed  for a limited period and will pay a daily fee of 0.0001 MSK. You can increase this period anytime you want. The fee is paid in advance.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">An application has an address associated. Once installed, the app  will  take full control of address funds. From that moment, only the app code will be able to spend funds from associated address. Always test your application using the local debugger before deploying.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">In order to install an application, go to Applications / Write Applications, click on the red selector and choose &quot;Deploy to Network&quot;. Select on which address you want to install it, set a number of days and click &quot;Deploy&quot;. You can also set a run interval. Applications can run autonomously at regular intervals (in blocks). For example, if you set run period to 1, your application will wake up after each block and execute the function #block#.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><span class="font_14">When you click Deploy,  a special packet containing the application code and other details will be broadcasted to all nodes and once included in a block, your application will be ready for action.</span></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">Once installed, your application will be visible in My Applications section (Applications / My Applications). In order to uninstall an application, click on the red selector and choose Remove from Network. You will need to provide just an address to pay the network fee for the unistall data packet (0.0001 MSK). </td>
           </tr>
           <tr>
             <td height="40">&nbsp;</td>
           </tr>
           <tr>
             <td  class="font_16"><strong>Application execution</strong></td>
           </tr>
           <tr>
             <td><hr></td>
           </tr>
           <tr>
             <td class="font_14">Once installed, an application can &quot;wake up&quot; itself and execute after a specified number of blocks or can be programmed to execute when certain events occur, like receiving a transaction or a message.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">In order to &quot;intercept&quot; those events, you need to implement some predefine functions that will be called when such an event occur. For example, when an application receives a transaction, the virtual machine will call #transaction# function. If this function is not defined by your code, nothing will happen. If #transaction# function is defined, it will be executed. Programmers will be able to access transaction details by using some predefine variables that stores events details.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_14">For more informations regarding application's events check Events section.</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td class="font_16"><strong>Publishing your application to Directory</strong></td>
           </tr>
           <tr>
             <td><hr></td>
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
