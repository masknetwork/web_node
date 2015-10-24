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
			   $help->showLeftMenu(8);
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
                  <td align="center" valign="top" background="../../template/template/GIF/tab_middle.png">
                  <table width="500" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><span class="simple_red_18"><strong>Asset-uri</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Un asset reprezinta o moneda digitala emisa de o adresa. Asset-urile pot fi transferate intre adrese exact cum este transferata MaskCoin si se bucura de aceleasi metode de protectie. Singura diferenta este ca un asset este emis de o adresa si se afla sub controlul unei adrese, care poate oricand emite ma monezi noi iar initial intreaga cantitate este detinuta de o singura adresa.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Asset-urile pot spre exemplu reprezenta ceva fizic si real dar pentru a intelege cel mai bine despre ce este vorba o sa prezentam un exemplu</td>
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
                              <td align="left" class="simple_gri_inchis_12"><p>Sa spunem ca detii $10.000 in viata reala si ai dori sa folosesti acesti $10.000 intr-un mod cat mai profitabil. Asa ca emiti un asset numit BitUSD si anunti ca fiecare bitUSD poate fi schimbat pentru un dolar real si invers, adica 1 BitUSD poate fi cumparat cu un dolar real. </p>
                                <p>Emiti initial $10.000 pentru ca doar de atat dispui in viata reala si pentru ca doresti un mic profit,  setezi o taxa de tranzactie de %1</p>
                                <p>Dupa ce emiti un asset, oricine il poate achizitiona de la tine sau alte perosane folosit MaskNetwork. Deoarece 1 BitUSD reprezinta 1 dolar real, isi va pastra valoarea in timp si va putea fi chiar folosit pentru a achizitiona bunuri din piata. Deoarece ai setat o taxa de tranzactie, de fiecare data cand cineva transfera 1 BitUSD, iti va plati 1% din suma transferata. </p>
                                <p>Peo parte cel care a emis asset-ul castiga din taxele de tranzactie iar pe de alta parte cei care folosesc asset-ul il pot transforma oricand in bani reali ceea ce inseamna ca il pot folosi fara grija devalorizarii.</p></td>
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
                        <td class="simple_gri_inchis_12">Bineinteles, daca cel care a emis asset-ul &quot;dispare&quot; sau refuza sa plateasca 1 dolar real pentru 1 BitUSD, valoarea unui BitUSD se va duce rapid la zero, dar asta este deja o alta tema de discutie.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">O alta utilizare a unui asset este pentru a strange fonduri sau finantare pentru afacerea ta. Poti emise un asset care reprezinta de exemplu o actiune la firma pe care o detii sau pe care doresti sa o infiintezi iar actionarii vor primii o parte din profit-uri.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Asset-urile pot fi transferate intre adrese la fel de simplu ca MaskCoin si beneficiaza deasemenea de toate optiunile de securitate cum ar fii semnaturile multiple. O adresa poate detine un numar nelimitat de asset-uri.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Asset-urile pot fi cumparate sau vandute in doua moduri. Prima varianta este in cadrul unei piete care functioneaza in cadrul MaskNetwork sau folosind un exchanger. In ambele cazuri poti folosi alte asset-uri pe post de moneda pentru a cumpara sau vinde un anumit asset de care esti interesat.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Deasemenea, poti seta pentru asset-ul tau o taxa de tranzactie care va fi platita dupa fiecare tranzactie intr-o adresa specificata de tine. Taxa este setata in special la asset-uri care reprezinta monezi sau metale reale. Este metoda prin care cel care emite un asset isi acopera costuile de functionare si obtine chiar un profit.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_14"><strong>Emiterea unui nou asset</strong></span></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti emite oricand un asset nou. Taxa pentru a emite un asset nou tine de cantitatea pe care doresti sa o emiti plus durata. Orice asset este emis pe o durata determinata dar poate fi reiinnoit oricand. </td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Pentru a emite un asset nou, mergi in pagina Assets ( apasa Assets in bara de meniu ), dupa care selecteaza Issued Assets. Aici sunt listate asset-urile emise de tine. Apasa butonul Issue Asset din partea de sus a paginii. Va fi afisat urmatorul dialog :</td> 
                      
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" class="simple_gri_inchis_12"><img src="GIF/assets_new.png" width="500" height="809" alt=""/></td>
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
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Asset Address</strong></span> - Adresa asociata cu acest asset. Vei avea nevoie de aceasta adresa pentru a face modificari asupra asset-ului cum ar fi schimbarea descrierii, emiterea de noi unitati...</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Name</strong></span> - Numele asset-ului. Se accepta doar litere si cifre.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Short Description</strong></span> - O scurta descriere a asetului</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Website</strong></span> - Website-ul oficial</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Pic</strong></span> - Link-ul catre o imagine .JPG cu dimensiunea de maxim 1 MB.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Symbol</strong></span> - Symbol-ul asset-ului. Un simbol este un sir de 6 caractere. Se acepta doar litere si cifre.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Initial Qty</strong></span> - Cantitatea initiala. Taxa de amitere se stabileste in functie de cantitatea initiala (0.0001 MSK / unitate) si market bid (0.0001 MSK / zi). </td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Transaction Fee</strong></span> - Taxa de tranzactie luata ca procent. Valoarea minima este 0. Valoarea maxima este de 10.  Dupa fiecare tranzactie, destinatarul va plati aceasta taxa in adresa specificata de tine.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Transfer the fee to this address </strong></span> - Adresa in care doresti sa fie varsata taxa de tranzactie.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Market Days</strong></span> - Orice asset este emis pe o perioada limitata de timp. Aici poti specifica pentru cat timp va fi emis asset-ul. Daca un asset expira fara a fii reinnoit, reteaua il va sterge. </td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Market Bid</strong></span> - Pozitia pe care va fi listat asset-ul tau este data de suma pe care o oferi / zi. Cu cat aceasta suma este mai mare, cu atat asset-ul tau va fi listat mai sus.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa butonul Send. Dupa ce reteaua confirma tranzactia, va creea un nou asset. Toata cantitatea initiala se va afla in adresa asset-ului. Asset-ul tau va fi imediat listat in pagina Issued Assets si Assets. Poti face deasemenea modificari asupra asset-ului cu ar fi schimbarea descrierii sau a adresei in care se va varsa taxa de transfer.</td>
                      </tr>
                      <tr>
                        <td height="50" class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Transferul de asset-uri</strong></td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti transfera asset-uri catre orice alta adresa. Deasemenea iti poti proteja asset-urile detinute in acelasi mod in care iti protejezi MSK, prin atasarea de optiuni de securitate adreselor care cdetin asset-uri.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Cel care transfera asset-uri catre alte adrese, va plati o taxa catre retea de 0.0001 MSK  pentru fiecare unitate transferata. Daca se transfera sub 1 unitate, se va plati taxa minima de 0.0001 MSK. Destinatarul va plati taxa de transfer a asset-ului care este stabilita de emitent. Un anumit procent din cantitatea primita va fi transferata automat de retea catre adresa specificata de emitent.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Pentru a transfera un asset, mergi in pagina Assets (apasa Assets in bara de meniu principala) si selecteaza My Assets. Aici sunt listate toate asset-urile pe care le detii. Apasa butonul Send. Va fi afisat urmatorul dialog :</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" class="simple_gri_inchis_12"><img src="GIF/send_asset.png" width="500" height="638" alt=""/></td>
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
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Send From Address</strong></span> - Adresa care detine asset-ul. Va fi selectata automat.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Recpient Address</strong></span> - Adresa destinatie.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Short Message</strong></span> - Optional. Un scurt mesaj pentru destinatar. Mesajul va fi criptat si doar destinatarul va putea citi mesajul.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Amount</strong></span> - Cantitatea pe care vrei sa o trimiti.</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"><span class="simple_red_12"><strong>Escrower</strong></span> - Optional. Poti face tranzactia prin intermediul unui escrower.</td>
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