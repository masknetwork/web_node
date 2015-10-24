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
   $ud=new CSysData($db);
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
			   $help->showLeftMenu(10);
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
                  <td align="center" valign="top" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><span class="simple_red_18"><strong>Pietele de produse si servicii</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Masknetwork iti permite sa postezi anunturi de vanzare pentru orice tip de produs fizic, digital sau servicii. Nu ai nevoie de un site web sau un magazin virtual. Odata postat un anunt, el va fi distribuit in toata reteaua si vei incepe sa primesti comenzi din toata lumea. Deoarece o adresa MaskNetwork reprezinta de fapt o cheie publica, vei putea comunica usor si securizat cu orice client si orice potential cumparator te va putea contacta pentru mai multe detali.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> Poti posta deasemenea anunturi insotite de pana la 5 poze si poti restrictiona accesul la un anunt pe baza locatiti unde se afla vizitatorii. Spre exemplu poti face un anunt vizibil doar pentru vizitatorii din SUA sau chiar mai mult, doar pentru cei care locuiesc intr-un anunit oras, idiferent ca vizitatorii folosesc un nod web sau desktop.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Atentie !!! Deoarece este o retea p2p distribuita nu exista un administrator care sa verifice legalitatea anunturilor si deci nu exista limitari in priviinta a ce poti vinde. In cazul in care produsele pe care le vinzi sunt ilegale iti vei fi complet raspunzator pentru ceea ce vinzi.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Nimeni nu poate sterge un anunt odata postat dar administratorii nodurilor web vor putea sa &quot;ascunda&quot; un anunt si nimeni care foloseste nodul web nu il va putea vizualiza. Administratorii nodurilor web sunt raspunzatori deasemenea pentru continutul afisat pe site-ul lor, asa ca vor sterge orice anunt care incalca legilsatia in vigoare.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> Deasemenea in cazul in care folosesti un nod web pentru a posta anunturi cu continut ilegal, administratorul ar putea sa predea autoritatilor toate datele pe care le are despre tine / adresele tale. Bineinteles, poti folosi un nod desktop pentru a nu fi detectat dar recomandam tuturor vanzatorilor sa verifice legalitatea produselor pe care le pun in vanzare.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_16"><strong>Cum postezi un anunt</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Pentru a posta un anunt mergi in pagina Markets ( apasa Markets in bara de meniu principala ) dupa care apasa butonul verde &quot;Oferta Noua&quot;. Va fi afisata o pagina cu 5 sectiuni, unde care poti introduce datele produsului :</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_14"><strong>General Data</strong></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/mkt_gen_data.png" width="500" height="989" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Item Address</strong></span> - Adresa care detine acest anunt. In aceasta adresa vor fi facute platile pentru produs si vei primii eventuale mesaje. Deasemenea doar aceasta adresa poate modifica / sterge un anunt.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Main Category</strong></span> - Categoria principala. Alege cu atentie categoria deoarece multi cumparatori navigheaza la categoria pe care ii intereseaza si nu folosesc functia de search.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Sub-Category</strong></span> - Categoria secundara.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
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