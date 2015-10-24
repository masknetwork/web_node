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
                        <td class="simple_gri_inchis_12">Efectuarea de tranzactii cu MaskCoin este cea mai frecventa operatie facuta de utilizatori. Poti trimite monezi catre orice alta adresa. Tranzactiile pot fi insotite de un mesaj si poti deasemnea trimite escrowed transactions. Bineinteles ca mesajele care insotesc tranzactiile sunt sunt criptate si doar destinatarul fondurilor le va putea citi.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Taxa platita pentru o tranzactie este de 0.1% din suma tranzactionata, minim 0.0001 MSK.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Absolut toate tranzactiile cu MaskCoin sunt ireversibile si finale. Asta inseamna ca odata ce ai trimis o suma catre o adresa, este imposibil sa-ti ceri banii inapoi. Deoarece nu exista un administrator central, nu te poti adresa nimanui pentru a face o &quot;reclamatie&quot;. Banii trimisi inseamna bani trimisi. Daca iti vrei banii inapoi, singura solutie este sa-i ceri inapoi politicos de la cel care i-a primit doar ca in 9 din 10 cazuri  vei fi probabil refuzat la fel de politicos. Este important sa fii atent cui trimiti bani iar daca nu ai incredere in destinatar, foloseste sistemul escrow integrat.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Sistemul escrow integrat</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Escrowed transactions sunt esentiale intr-o piata anonima asa cum este MaskNetwork, deoarece este foarte usor sa fraudezi alti utilizatori. Un vanzator poate oricand sa ia banii si sa plece cu ei fara a livra nici un produs, ba chiar mai mult poate imediat sa posteze o oferta noua, la fel de frauduloasa ca prima, sub o alta adresa la fel fel de anonima ca prima. </span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Deasemena un sistem de rating nu ar functiona eficient, pentru ca ar fi foarte usor sa falsifici comentariile pozitive lasate de &quot;clientii multumiti&quot;. Aceste comentarii pot fi postate chiar de vanzator folosind o serie de adrese anonime, fara legatura una cu alta.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Singura modalitate de a evita fraudele in masa facute de vanzatori anonimi este un sistem de escrow. Cand trimiti o escrowed transaction, banii nu ajung la vanzator. Sunt luati din contul tau si atat.  Intr-un sistem escrow, o alta persoana, considerata de incredere va decide in final daca banii ajung sau nu la vanzator. </span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><p><span class="simple_gri_inchis_12">Cel mai bine ar fi sa analizam un exemplu real. Sa spunem ca Maria doreste sa cumpere carti de la un vanzator, numit BookStore, doar ca nu are incredere ca dupa ce va plati isi va primii cartile, mai ales ca tranzactiile MaskCoin sunt ireversibile si nu ai la cine sa apelezi pentru a-ti primii banii inapoi. Asa ca va folosi o a treia persoana, numita Phil care ofera servicii de escrow. </span></p></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="50"><span class="simple_gri_inchis_12">Atunci cand Maria va trimite bani catre BookStore va specifica faptul ca doreste ca tranzactia sa fie asistata de Phil, as ca va furniza adresa lui Phil ca adresa escrow. Banii vor pleca de la Maria din cont dar nu vor ajunge nici la BookStore nici la Phil. In momentul asta toata lumea asteapta ca BookStore sa livreze cartile.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Se pot intampla 3 lucruri :</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria isi primeste peste 5 zile cartile si aproba singura eliberarea fondurilor catre BookStore</span></td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria isi primeste peste 5 zile cartile si ii spune lui Phil sa elibereze fondurile.</span></td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">- Maria nu isi primeste cartile, dar BookStore spune ca le-a trimis si eventual prezinta dovezi. In acest caz, Phil este cel in masura sa decida cine va primii banii. Phil pate elibera fondurile catre BookStore sau le poate trimite inapoi catre Maria.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_gri_inchis_12">Acest sistem de escrow a fost implementat la nivelul retelei si tot ce are de facut cel care trimite fonduri este sa specifice pe cine doreste in calitate de escrow. Dupa trimiterea fonduriloe, atat destinatarul cat si adresa escrow, vor fi notificate asupra tranzactiei. Absolut orice adresa poate fi desemnata ca adresa escrow, atat timp cat cumparatorul si vanzatorul cad de acord si ambii au incredere in adresa escrow.</span></td>
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
                              <td width="84%" valign="middle" class="simple_maro_12">Bineinteles ca &quot;Phil&quot; va dori un comision pentru acest serviciu, asa ca MaskNetwork vine si cu o piata dedicata pentru escrowers. Daca doresti sa intermediezi tranzactii pentru un comision poti posta o oferta pe piata dedicata intermediarilor si pentru orice tranzactie in care esti specificat ca escrower, vei primii automat un comision din suma tranzactionata, indiferent de rezultatul tranzactiei.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Initierea unei tranzactii</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Pentru a initia o tranzactie noua, apasa butonul Trimite Monezi, aflat in toate paginile portofelului. Butonul se gaseste in partea stanga, sub balanta contului. Va fi afisat urmatorul dialog :</td>
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
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>From Address</strong></span> <span class="simple_gri_inchis_12">- Adresa din care se vor lua fondurile care urmeaza sa fie transferate. Poate fi acceasi cu adresa din care se va lua comisionul platit catre retea.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>To Address</strong></span> <span class="simple_gri_inchis_12">- Adresa care va primii fondurile.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Message</strong></span> <span class="simple_gri_inchis_12">- Otpional. Un scurt mesaj pentru destinatar.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Escrower</strong></span> <span class="simple_gri_inchis_12">- Optional. Adresa escrower care va intermedia tranzactia.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> Apasa butonul send. Tranzactia va fi trimisa in retea si dupa confirmare (1 minut) fondurile vor ajunge la destinatar.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12"> In cazul in care ai spcificat un escrower, fondurile vor fi retrase din adresa ta, iar destinatarul si adresa desemnata ca intermediar vor fi notificate. In acest caz, tranzactia ta va fi deasemenea afisata in sectiunea Escrowed din pagina tranzactiilor. Poti oricand elibera fondurile catre destinatar, apasand butonul Elibereaza din dreptul tranzactiei.</span></td>
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