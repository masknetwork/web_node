<?
   session_start();
   
   include "../../../kernel/db.php";
   include "../../../kernel/CUserData.php";
   include "../../../kernel/CSysData.php";
   include "../../template/template/CTemplate.php";
   include "../CAssets.php";
   include "CRegMkts.php";
   include "CRegMkt.php";
   
   $db=new db();
   $template=new CTemplate($db);
   $ud=new CUserData($db);
   $sd=new CSysData($db);
   $assets=new CAssets($db, $template);
   $reg_mkts=new CRegMkts($db, $template);
   $reg_mkt=new CRegMkt($db, $template);
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
			    $template->showTopMenu(4);
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
			   $assets->showLeftMenu(4);
			?>
            </td>
            <td width="610" height="1000" align="center" valign="top">
            
            <script>
			function menuClicked(tab)
			{
				$('#div_traders_ID_SELL').css('display', 'none');
				$('#div_traders_ID_BUY').css('display', 'none');
				$('#div_my_orders').css('display', 'none');
				$('#div_trans').css('display', 'none');
				
				switch (tab)
				{
					case 1 : $('#div_traders_ID_SELL').css('display', 'block'); break;
				    case 2 : $('#div_traders_ID_BUY').css('display', 'block'); break;
				    case 3 :$('#div_my_orders').css('display', 'block'); break;
				    case 4 : $('#div_trans').css('display', 'block'); break;
				}
			}
			</script>
           
            <?
			   $template->showHelp();
			   
			   switch ($_REQUEST['act'])
		       {
			   case "new_pos" :  $reg_mkt->newMarketPos($_REQUEST['mkt_symbol'], 
			                                           $_REQUEST['tip'], 
									                   $_REQUEST['dd_net_fee_adr'], 
									                   $_REQUEST['dd_adr'], 
									                   $_REQUEST['txt_price'], 
									                   $_REQUEST['txt_qty'], 
									                   $_REQUEST['txt_days']);
								break;
								
			   case "new_order" :  $reg_mkt->newMarketOrder($_REQUEST['dd_order_net_fee_adr'], 
									                       $_REQUEST['dd_order_adr'], 
									                       $_REQUEST['uid'],
									                       $_REQUEST['txt_order_qty']);
								   break;
								   
				case "del" :  $reg_mkt->delPos($_REQUEST['dd_net_fee'], 
				                              $_REQUEST['par_1']);
						      break;
		   }
		   
		  
		   $reg_mkt->showNewPosMarketModal($_REQUEST['symbol']); 
		   $reg_mkt->showNewOrderMarketModal($_REQUEST['symbol']);
		   
		   
			   $reg_mkt->showMarketPage($_REQUEST['symbol']);
			?>
            
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