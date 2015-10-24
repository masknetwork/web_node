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
			   $help->showLeftMenu(9);
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
                  <td align="center" valign="top" background="../../template/template/GIF/tab_middle.png"><table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><img src="../../template/template/GIF/tab_top_simple.png" width="566" height="22" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="center" valign="top" background="../../template/template/GIF/tab_middle.png"><table width="500" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td><span class="simple_red_18"><strong>Fluxuri de Date</strong></span></td>
                            </tr>
                            <tr>
                              <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Un flux de date reprezinta o colectie de date nume-valoare care este injectata in retea la intervale regulate de o adresa. Un flux de date poate prezenta orice, cum ar fi cursul de schimb BTC / USD sau orice alt tip de informatii externe retelei. Poate reprezenta de exemplu scorul unui meci de fotbal.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Fluxurile de date pot fi creeate de orice adresa si dau posibilitatea oricui de a specula / paria pe date reale externe retelei. Sunt folosite pentru a emite market pegged assets a caror valoare este in permanenta actualizata sau pentru a lansa pariuri bazate pe informatiile prezentate de un flux.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Deoarece reprezinta un concept putin mai greu de inteles, o sa prezentam cateva exemple de utilizare.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" bgcolor="#f0f0f0"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="left" class="simple_gri_inchis_12"><p>Sa spunem ca dispui de pretul aurului real raportat la MSK si doresti sa furnizezi aceasta informatie catre retea pentru o mica taxa, bineinteles.  Daca dispui de pretul aurului raportat la USD si de pretul unui MSK in raport cu USD, este simplu sa furnizezi pretul unui gram de aur in MSK. Pentru a furniza aceasta infromatie creezi un flux de date si in cadrul fluxului creezi o sectiune care prezinta pretul GOLD / MSK. Dupa care in fiecare minut trimiti aceste date in retea pentru a fi folosite de cei interesati. Practic la fiecare bloc vei &quot;injecta&quot; in retea ultimul pret al aurului raportat la MSK. Cei care doresc sa foloseasca datele tale vor plati o mica taxa stabilita de tine care iti va permite pe viitor sa acoperi costurile si sa faci un profit din furnizarea de date in timp real. Aceste date pot fi folosite de cei interesati in trei moduri.</p></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_red_14"><strong>Market-pegged assets</strong></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Prima utilizare sunt emiterea unui tip special de asset, numit market-pegged asset. Acest asset va putea fi tranzactionat lnumai a pretul prezentat de fluxul de date. Practic este un asset normal care poate fi trimis intre adrese ca oricare alt asset dar are o valoare data de fluxul de date asociat si poate fi cumparat / vandut doar la acel pret. </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Un astfel de asset are un mare avantaj. Nu depinde de adresa emitenta, iar adresa emitenta nu are nici un control asupra lui. Daca emitentul unui astfel de asset dispare, nu se va intampla nimic. Traderii vor putea in continuare sa tranzactioneze acest asset iar asset-ul isi va mentine in continuare valoarea, deoarece piata pe care se tranzactioneaza depinde doar de fluxul de date asociat.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12"> In cazul unui asset obisnuit care reprezinta o moneda reala, spre exemplu, in cazul in care emitentul dispare sau refuza sa mai faca plati, valoarea asset-ului se va prabusi, deoarece nu va mai putea fi schimbat pe bani reali. Un asset obisnuit depinde 100% de emitent si de solventa / seriozitatea emitentului. Sa dam un exemplu :</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" bgcolor="#f0f0f0"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="left" class="simple_gri_inchis_12"><p>Doresti sa emiti un asset, denumit bitGOLD iar fiecare asset sa reprezinte 1 gram real de aur, dar nu doresti sa faci livrari fizice de aur, in cazul in care cineva doreste sa transforme un BitGOLD in aur real. </p>
                                      <p>Pentru asta emiti asset-ul sub forma unui market-pegged asset bazat pe fluxul de date prezentat mai sus. Fluxul de date prezinta in permanenta pretul unui gram de aur raportat la MSK. </p>
                                      <p>Sa spunem ca doresti sa emiti 10 grame de aur. Pentru asta creezi un asset nou denumit BitGOLD, asociat acestui flux de date, care va putea fi vandut / cumparat cu MSK. </p>
                                      <p>Cantitatea initiala va fi de 10 unitati (grame). Sa presupunem ca ultimul pret GOLD/MSK este 38 (38 MSK pentru 1 gram de aur). Ca sa poti lansa acest asset trebuie sa depui o garantie de  100% din valoarea totala a asset-ului. In cazul nostru, va trebui sa depui 380 MSK drept garantie. Aceasta adresa nu va mai fi controlata de tine ci de retea. Cat timp piata exista, nu vei mai putea retrage BitGOLD sau MSK din adresa. Reteaua va accepta doar ordine de vanzare / cumparare.</p>
                                      <p>Va fi deschisa automat si o piata BitGOLD / MSK, unde cei care dispun de MSK vor putea achizitiona BitGOLD la pretul indicat de flux. O piata este de fapt o adresa detinuta de tine care va dispune initial de 10 BitGOLD si 380 MSK.</p>
                                      <p>In acest moment, oricine poate cumpara / vinde BitGOLD la pretul real extern al aurului. Daca ai cumparat 0.1 unitati BitGOLD, este ca si cum ai detine 0.1 grame de aur real. Tot timpul cei 0.1 BitGOLD vor putea fi echivalenti cu 0.1 grame de aur real. Sa spunem ca pretul aurului creste la 42 MSK pentru 1 gram. Poti merge oricand in pata asociata pentru a-ti vinde asset-ul cu 4.2 MSK.</p></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Bineinteles, pentru fiecare tranzactie cu asset se va plati o mica taxa care merge la emitent. Cel care emis asset-ul nu poate fugi cu asset-ul sau monezile din adresa deoarece adresa este controlata de retea iar detinatorii de asset-uri pot fi siguri ca lui va fi tot timpul legata de valoarea aurului real deoarece pretul este schimbat automat in functie de pretul prezentat de flux. </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Este o situatie in care toata lumea castiga. Cel care prezinta datele in timp real va primii o mica taxa pentru fiecare asset care foloseste datele sale. Cel care a emis un market-pegged asset va primii o mica taxa dupa fiecare tranzactie. Cei care cumpara market-pegged assets pot fii siguri ca valoarea asset-ului va ramane stabila si pot face chiar un profit daca pretul aurului creste in lumea reala.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><span class="simple_gri_inchis_12">In exemplul pe care l-am dat mai sus, moneda cu care se poate tranzactiona asset-ul este MSK dar reteaua nu impune o anumita moneda. Poti emite un market-pegged asset care va fi tranzactionat pentru orice alt asset. Sa presupunem ca fluxul de date prezinta pretul aurului exprimat in dolari nu in MSK si ca exista un alt asset care este legat de valoarea dolarului in lumea reala, numit BitUSD. Poti lansa un asset nou bazat pe acest flux de date, unde moneda va fi BitUSD in loc de MSK. </span></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Am mentionat faptul ca atunci cand creezi un market-pegged asset va trebui sa depui o garantie egala sau mai mare decat valoare asset-ului raportat la moneda cu care va fi tranzactionat. Garantia este necesara in cazul in care valoarea asset-ului raportata la moneda cu care este tranazactionat creste si detinatorii vand asset-ul detinut. Sa reveni la exemplul cu BitGOLD.</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" bgcolor="#f0f0f0" class="simple_gri_inchis_12"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="left" class="simple_gri_inchis_12"><p>Sa presupunem la scurt timp dupa ce  BitGOLD este lansat, sunt cumparati 2 BitGOLD de mai multi investitori la pretul de 38 MSK. Dupa cateva zile pretul creste la 41 MSK iar investitorii vor sa-si vanda detinerile pentru un profit. Au platit 76 MSK iar acum vor inapoi 82 MSK. Fara garantia de 380 MSK, nu ar fi avut cum sa-si recupereze banii. In cazul in care pretul continua sa creasca si se apropie de o anumita valoare (valoarea tutror BitGOLD aflati in mainile investitorilor depaseste 90% din balanta de MSK a pietei), reteaua va refuza sa mai proceseze ordine de cumparare pana cand garantia nu este marita. In acest caz cel care detine asset-ul va trebui sa realimenteze piata cu MSK. Este un caz rar intanit doar pentru asset-uri extrem de volatile.</p></td>
                                  </tr>
                                  <tr>
                                    <td align="left">&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="35" valign="top"><span class="simple_red_14"><strong>Cum creezi un market-pegged asset</strong></span></td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">Pentru a creea un market_pegged asset, mergi in pagina Feeds (apasa  Assets in bara de meniu principala) si selecteaza Issued Assets (meniul din stanga). In aceasta pagina sunt prezentate asset-urile emise de tine. Apasa butonul verde Issue Asset. Va fi afisat dialogul de mai jos :</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" class="simple_gri_inchis_12"><img src="GIF/new_mkt_pegged_asset.png" width="500" height="762" alt=""/></td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12">&nbsp;</td>
                            </tr>
                            <tr>
                              <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Network Fee Address</strong></span> - Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</td>
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