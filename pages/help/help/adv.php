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
			   $help->showLeftMenu(7);
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
                        <td><span class="simple_red_18"><strong>Publicitatea</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Because MaskNetwork is a global market, advertising is essential to be able to function optimally. It is the first system of peer to peer advertising, anonymously and distributed. When you post an advertising message, there is no pre-approval, or conditions relating to content. Nobody will know who is behind an advertising message.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Advertising messages are displayed by the entire network of web wallets, not only on the current wallet. Also, messages can be viewed on desktop wallets. You can also set wallets to display advertisements only to users in a particular country or geographic area.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">The cost of an advertisement is at least 0.0001 MSK / hour, but the higher the price / hour you offer, the higher your message will be displayed in the list. You can post a message for an unlimited number of hours and you can also change the price offered for display.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12" bgcolor="#f6f2e7">Advertising messages are recorded in the distributed database and only you can modify or delete them. Because administrators of Web nodes are legally liable for the content posted on the site, there is the 
possibility that an advertisement be "hidden" from the display, if the message content is considered illegal or inappropriate by the node’s administrator. But no one can delete that message from the network and the message will still be viewed by those who use the desktop wallet.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Posting an ad message</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">To post an advertisement, go to any page and click the big yellow button that says Advertise Here. The button is located just below the ads displayed on the right side of any page. The following dialogue will be displayed:</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" align="center"><img src="GIF/ads.png" width="450" height="571" alt=""/></td>
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
                        <td><span class="simple_red_12"><strong>Title</strong></span> <span class="simple_gri_inchis_12">- Title  (5-30 caractere)</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Mesaj</strong></span> <span class="simple_gri_inchis_12">- Ad message (50-70 caractere)</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Link</strong></span><span class="simple_gri_inchis_12">- The website where the users who click it will be redirected.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Country </strong></span><span class="simple_gri_inchis_12">- the country where the ad will be displayed.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Hours </strong></span><span class="simple_gri_inchis_12">- For how many hours it will be displayed.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Bid </strong></span><span class="simple_gri_inchis_12">- How much are you willing to give for this message to be published. The more you give, the higher your message will be displayed in the message list.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Changing the bid price</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Once you published a message, it could lose the position on which it was originally displayed as other addresses could offer more. To change the bid price, click the red button next to the "Advertise Here" button. This button is found in the wallet page, just below the list of ads on the right side.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">The page with the ads posted by you will be displayed. Click the yellow button next to an ad and select "Increase Market Bid". The following dialogue will be displayed:</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/ads_bid.png" width="450" height="294" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Any service as renting an address name or setting additional options must be paid. In this field you specify where the coins will be taken for the payment of this service.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>New Bid </strong></span><span class="simple_gri_inchis_12">- The new price that you want to offer</span></td>
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