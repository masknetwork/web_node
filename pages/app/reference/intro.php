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
	      $write->showRefMenu();
	   ?>
       
       </td>
       <td width="6%" align="right" valign="top">&nbsp;</td>
       <td width="71%" align="right" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tbody>
           <tr>
             <td align="left" class="font_20"><strong>Instructions</strong></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="add.php" style="font-size:14px">ADD</a></span>
               <p class="font_12" style="color:#999999">Add two operands</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="atpos.php" style="font-size:14px">ATPOS</a></span>
               <p class="font_12" style="color:#999999">Returns the character / cell at a specified position</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="concat.php" style="font-size:14px">CONCAT</a></span>
               <p class="font_12" style="color:#999999">Concatenates two operands</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="div.php" style="font-size:14px">DIV</a></span>
               <p class="font_12" style="color:#999999">Divides operand one to operand two</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="email.php" style="font-size:14px">EMAIL</a></span>
               <p class="font_12" style="color:#999999">Sends an email</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="exit.php" style="font-size:14px">EXIT</a></span>
               <p class="font_12" style="color:#999999">Stops the execution and close the virtual machine</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="goto.php" style="font-size:14px">GOTO</a></span>
               <p class="font_12" style="color:#999999">Jump the execution to the specified line</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="hash.php" style="font-size:14px">HASH</a></span>
               <p class="font_12" style="color:#999999">Returns the hash of an operand</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="http.php" style="font-size:14px">HTTP</a></span>
               <p class="font_12" style="color:#999999">Calls a web address</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="if.php" style="font-size:14px">IF</a></span>
               <p class="font_12" style="color:#999999">Compares two operands</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="indexof.php" style="font-size:14px">INDEXOF</a></span>
               <p class="font_12" style="color:#999999">Returns the index of a character / substring in a string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="lastch.php" style="font-size:14px">LASTCH</a></span>
               <p class="font_12" style="color:#999999">Returns the last character of a string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="lastch.php" style="font-size:14px">LISTPUSH</a>
               <p class="font_12" style="color:#999999">Push an operand to an array</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="mov.php" style="font-size:14px">MOV</a></span>
               <p class="font_12" style="color:#999999">Copy the value of operand</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="math.php" style="font-size:14px">MATH</a></span>
               <p class="font_12" style="color:#999999">Applies a mathematic function to an operand</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="mul.php" style="font-size:14px">MUL</a></span>
               <p class="font_12" style="color:#999999">Multiplies two operands</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="mes.php" style="font-size:14px">MES</a></span>
               <p class="font_12" style="color:#999999">Sends a message</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="pop.php" style="font-size:14px">POP</a></span>
               <p class="font_12" style="color:#999999">Pops an operand to stack</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="push.php" style="font-size:14px">PUSH</a></span>
               <p class="font_12" style="color:#999999">Push an operand from stack</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="rand.php" style="font-size:14px">RAND</a></span>
               <p class="font_12" style="color:#999999">Generates a random number</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="regex.php" style="font-size:14px">REGEX</a></span>
               <p class="font_12" style="color:#999999">Performs a regular expression match</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="replace.php" style="font-size:14px">REPLACE</a></span>
               <p class="font_12" style="color:#999999">Replace all occurrences of the search string with the replacement string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="refund.php" style="font-size:14px">REFUND</a></span>
               <p class="font_12" style="color:#999999">Refunds a transaction</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="seal.php" style="font-size:14px">SEAL</a></span>
               <p class="font_12" style="color:#999999">Seales the application address for a specified number of days</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="size.php" style="font-size:14px">SIZE</a></span>
               <p class="font_12" style="color:#999999">Returns the size of an array / string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="sub.php" style="font-size:14px">SUB</a></span>
               <p class="font_12" style="color:#999999">Substract two operands</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="split.php" style="font-size:14px">SPLIT</a></span>
               <p class="font_12" style="color:#999999">Splits a string by a specified delimiter</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="substr.php" style="font-size:14px">SUBSTR</a></span>
               <p class="font_12" style="color:#999999">Returns a substring of a string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="storquery.php" style="font-size:14px">STORQUERY</a></span>
               <p class="font_12" style="color:#999999">Executes a SQL-like instruction on application's internal storage</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="tostring.php" style="font-size:14px">TOSTRING</a></span>
               <p class="font_12" style="color:#999999">Converts an operand to string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="trim.php" style="font-size:14px">TRIM</a></span>
               <p class="font_12" style="color:#999999">Converts an operand to string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="trans.php" style="font-size:14px">TRANS</a></span>
               <p class="font_12" style="color:#999999">Converts an operand to string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"><a href="tweet.php" style="font-size:14px">TWEET</a></span>
               <p class="font_12" style="color:#999999">Converts an operand to string</p></td>
           </tr>
           <tr>
             <td align="left"><hr></td>
           </tr>
           <tr>
             <td align="left"></td>
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
