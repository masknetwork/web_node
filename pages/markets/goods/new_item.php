<?
   session_start();
    
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CMarkets.php";
   include "CMarket.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $mkts=new CMarkets($db, $template);
   $market=new CMarket($db, $acc, $template);
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
			    $template->showTopMenu(6);
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
			   $mkts->showLeftMenu(1);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
           
           
            <?
			   $template->showHelp();
			   
			?>
            <br>
            <table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="20%" align="right"> 
                  <a href="#" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Offert </a></td>
                  <td width="21%" align="right">
                  <a href="my_offerts.php" class="btn btn-warning"><span class="glyphicon glyphicon-th-list"></span>&nbsp;My Offerts </a>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <br><br>
            <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                </tr>
                <tr>
                  <td align="center" background="../../template/template/GIF/tab_middle.png">
                  <?
				     if ($_REQUEST['act']=="new_item")
					   $market->newItem($_REQUEST['dd_net_fee_adr'],
	                                   $_REQUEST['dd_adr'], 
	                                   $_REQUEST['dd_categ'],
	                                   $_REQUEST['dd_subcateg'], 
					                   $_REQUEST['txt_title'], 
					                   $_REQUEST['txt_desc'], 
					                   $_REQUEST['txt_webpage'], 
				                  	   $_REQUEST['txt_internal_ID'], 
					                   $_REQUEST['txt_pic_1'], 
				         	           $_REQUEST['txt_pic_2'],
					                   $_REQUEST['txt_pic_3'],
					                   $_REQUEST['txt_pic_4'],
					                   $_REQUEST['txt_pic_5'],
					                   $_REQUEST['dd_location'], 
					                   $_REQUEST['txt_town'], 
					                   $_REQUEST['dd_delivery_reg'], 
					                   $_REQUEST['txt_delivery_reg'],
					                   $_REQUEST['dd_condition'],
					                   $_REQUEST['txt_delivery'],
					                   $_REQUEST['txt_postage'],
					                   $_REQUEST['txt_return_policy'],
					                   $_REQUEST['txt_price'],
					                   $_REQUEST['txt_mkt_bid'],
					                   $_REQUEST['txt_mkt_days'],
					                   $_REQUEST['dd_escrow'],
					                   $_REQUEST['txt_escrower_1'],
					                   $_REQUEST['txt_escrower_2'], 
					                   $_REQUEST['txt_escrower_3']);
					 else
				       $market->showNewItemPanel();
				  ?>
                  
                 
                  </td>
                </tr>
                <tr>
                  <td><img src="../../template/template/GIF/tab_bottom.png" width="566" height="22" alt=""/></td>
                </tr>
              </tbody>
          </table>
          
           <br><br>
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