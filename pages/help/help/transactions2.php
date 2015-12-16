<?
    session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CHelp.php";

   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $help=new CHelp($db, $template);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>MaskNetwwork - Assets</title>
<link rel="stylesheet" href="../../../style.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="../../../dd.js" type="text/javascript"></script>
<script src="../../../utils.js" type="text/javascript"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">$(document).ready(function() { $("body").tooltip({ selector: '[data-toggle=tooltip]' }); });</script>


</head>
<center>
<body background="../../template/template/GIF/back.png" style="margin-top:0px; margin-left:0px; margin-right:0px; ">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="66" align="center" valign="top" background="../../template/template/GIF/top_bar.png" style="background-position:center"><table width="1020" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="left">
            <?
			    $template->showTopMenu(8);
			?>
            </td>
            </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<table width="1018" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="800" align="center" valign="top" background="../../template/template/GIF/back_middle.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="205" height="18" align="right" valign="top"><table width="201" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td height="170" align="center" valign="middle" style="border-bottom-width:1px; border-bottom-style:inset; border-bottom-color:#ffffff;">
                  <?
				     $template->showBalancePanel();
                  ?>
                  
                  </td>
                </tr>
              </tbody>
            </table>
            <?
			   $help->showLeftMenu(2);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
            
            <br>
            <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><span class="simple_red_18"><strong>Transactions</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Making transactions with MaskCoin is the most common operation performed by users. You can send  coins to any other address. Transactions can be accompanied by a message and you can also often send escrowed transactions. Of course the messages that accompany transactions are encrypted and only the recipient of the funds will be able to read them.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">The fee paid for a transaction is 0.1% of the amount traded, minimum 0.0001 MSK.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Absolutely all MaskCoin transactions are irreversible and final. This means that once you've sent the amount to an address, it is impossible to ask for money back. Since there is no central administrator, you cannot address anyone to make a "complaint". The money sent is money sent. If you want your money back, the only solution is to ask it politely back from the one who received it, but in 9 of 10 cases you will probably be just politely refused. It is important to be careful to whom you send money and if you do not trust the recipient use the integrated escrow system.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="td_escrow"><strong class="simple_red_14">Integrated escrowed system</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Escrowed transactions are essential in an anonymous market as MaskNetwork because it is very easy to defraud other users. A seller can always take the money and leave with it without delivering any product, even more so he/she can immediately post a new offer, as fraudulent as the first under a different address just as anonymous as the first.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Also, a rating system would not function effectively because it would be too easy to fake positive comments left by "satisfied customers". These comments can be posted by the seller using a series of anonymous addresses not related to one another.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">The only way to avoid mass fraud made by anonymous sellers is an escrow system. When you send an escrowed transaction, money does not reach the seller. It is taken from your account and that’s it. In an escrow system, another person believed to be reliable will ultimately decide whether or not the money goes to the seller. </span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><p><span class="simple_gri_inchis_12">The best thing would be to analyze a real example. Let's say Mary wants to buy books from a seller named BookStore, but she does not trust that after she pays she will receive the cards, especially as MaskCoin transactions are irreversible and have no one else to turn to get your money back. So, she uses a third person called Phil who will offer escrow services.</span></p></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="50"><span class="simple_gri_inchis_12">When Maria sends money to BookStore she will specify that they want the transaction to be assisted by Phil, so she will deliver Phil’s address as escrow address. The money will leave from Maria’s account but it shall not reach Bookstore or Phil. Right now everyone expects BookStore to deliver the books.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Three things can happen:</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria gets her books after 5 days and approves alone the release of the funds to BookStore</span></td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria gets her books after 5 days and tells Phil to release the funds.</span></td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria does not get her books, but BookStore says they sent it and possibly present evidence. In this case, Phil is able to decide who will get the money. Phil can release the funds to BookStore or can send it back to Maria.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">This escrow system was implemented at the network level and everything that the one sending the funds has to do is to specify who they want as escrow. After sending the funds, both the recipient and the escrow address will be notified of the transaction. Any address can be designated as Escrow address, as long as the buyer and seller both agree and trust the escrow address.</span></td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0" bgcolor="#f6f2e7">
                        <table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">Of course "Phil" will want a fee for this service, so MaskNetwork comes with a dedicated market for escrowers. If you want to broker transactions for a fee you can post a market offer dedicated to intermediaries and for any transaction in which you are specified as escrower, you will automatically receive a commission from the amount traded, regardless of the outcome of the transaction.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Initiating a transaction</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">To initiate a new transaction, click Submit Coins found in all wallet pages. The button is located on the left , under your account balance. The following dialog will be displayed:</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" align="center"><img src="GIF/trans.png" width="450" height="502" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>From Address</strong></span> <span class="simple_gri_inchis_12">- the address from which the funds to be transferred will be taken. It can be the same with the address from which the commission paid to the network will be taken.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>To Address</strong></span> <span class="simple_gri_inchis_12">- The address which will receive the funds.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Message</strong></span> <span class="simple_gri_inchis_12">- Optional. A short message for the recipient.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Escrower</strong></span> <span class="simple_gri_inchis_12">- Optional. The address of the escrower which will mediate the transaction.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> Click the Send button. The transaction will be sent to the network and after confirmation (1 minute) funds will reach the recipient.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> If you specified an escrower, funds will be deducted from your address and the recipient and the address designated as an intermediate will be notified. In this case, your transaction will also be displayed in the Escrowed section from the transactions page. You can always release the funds to the recipient by clicking the button Release next to the transaction.</span></td>
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
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
            </table>
           
           
            
            </td>
            <td width="203" align="center" valign="top">
            <?
			   $template->showRightPanel();
			   $template->showAds();
			?>
            </td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom_sep.png" width="1018" height="20" alt=""/></td>
    </tr>
    <tr>
      <td height="50" align="center" background="../../template/template/GIF/bottom_middle.png">
      <?
	     $template->showBottomMenu();
	  ?>
      </td>
    </tr>
    <tr>
      <td height="0"><img src="../../template/template/GIF/bottom.png" width="1018" height="20" alt=""/></td>
    </tr>
  </tbody>
</table>
</body>
</center>
</html>