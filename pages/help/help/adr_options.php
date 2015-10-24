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
			   $help->showLeftMenu(4);
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
                  <td align="center" background="../../template/template/GIF/tab_middle.png"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td class="simple_red_18">Optiunile Adreselor</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="51%" height="30" align="left"><a href="#" class="maro_14">Dobanda zilnica</a></td>
                              <td width="49%" height="30"><a href="#" class="maro_14">Semnaturi Multiple</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Nume de adresa</a></td>
                              <td height="30"><a href="#" class="maro_14">Parola Unica</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Share Address</a></td>
                              <td height="30"><a href="#" class="maro_14">Notificarea Instanta</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Setarea unui profil</a></td>
                              <td height="30"><a href="#" class="maro_14">Date aditionale</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Inghetarea adresei</a></td>
                              <td height="30"><a href="#" class="maro_14">Autoresponders</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Sigilarea Adresei</a></td>
                              <td height="30"><a href="#" class="maro_14">Afisarea cheii private</a></td>
                            </tr>
                            <tr>
                              <td height="30" align="left"><a href="#" class="maro_14">Restrict Recipients</a></td>
                              <td height="30">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Dobanda</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">MasckCoin este distribuit in special prin dobanda. Asta inseamna ca orice detinator a minim 5 MSK va primii in fiecare zi o dobanda. Dobanda este variabila si depinde de cantitatea de monezi in circulatie. In fiecare zi, maxim 2400 MSK sunt distribuiti catre detinatori de monezi. Deoarece cantitatea de monezi in circulatie variaza permanent, si dobanda variaza dar pe termen lung are o tendinta de scadere.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12"> Ca sa iti setezi o adresa sa primeasca dobanda, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Primeste Dobanda Zilnica si va aparea urmatorul meniu.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/interest.png" width="450" height="317" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Pentru orice pachet de date care traverseaza reteaua, trebuie platita o taxa de minim 0.0001 MSK. Pentru a primi dobanda, portofelul va trimite o cerere la fiecare 24 de ore. Acest camp specifica de unde se vor lua bani pentru plata pachetului de date care cere dobanda. Costul este fix de 0.0001 MSK.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Receive the interest to this address</strong></span> <span class="simple_gri_inchis_12">- Dupa cum ii spune si numele, trebuie sa specifici o adresa unde se va primii dobanda zilnica. Adresa poate fi chiar adresa care solicita dobanda.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa Activate si asta este tot. La fiecare 24 de ore, portofelul va face o cerere de dobanda pentru adresa ta. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="120" bgcolor="#f6f2e7" class="simple_gri_inchis_12"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                                <td width="84%" valign="middle" class="simple_maro_12">Atentie !!! Dupa primirea dobanzii, timp de 24 de ore nu mai poti trimite bani din respectiva adresa. Pentru a opri primirea automata a dobanzii, sterge campul Livreaza Dobanda in Aceasta Adresa. Ca sa eviti blocarea dobanzii, iti recomandam folosirea unei alte adrese decat pentru a primii dobanda in ea.</td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Numele de adresa</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Adresele MaskNetwork sunt siruri de minim 64 caractere, imposibil de retinut si foarte greu de transmis altfel decat intr-un format digital. Pentru a usura transmiterea intre utilizatori au fost introduse numele de adresa. Numele de adrese sunt exact ce sunt domeniile web pentru website-uri. In loc sa navighezi la un ip de forma 89.121.90.32, poti naviga direct la www.masknetwork.com</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">In MaskNetwork, poti inchiria un nume de adresa cum este Maria sau John dupa care in loc sa trimiti clientilor / prietenilor un sir neinteligibil de caractere, le poti transmite un nume scurt usor de retinut. Reteaua va stii la ce adresa sa livreze moenzile sau mesajele trimise.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti sa inchiriezi un numar nelimitat de nume de adresa. De asemenea, o adresa poate avea asociate un numar nelimitat de nume de adrese. Inchirierea costa 0.0001 MSK / zi. Poti prelungi oricand valabilitatea unui nume. Deasemenea numele de adresa pot fi vandute pe o piata interna specializata.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a inchiria un nume pentru adresa tga, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Inchiriaza un Nume si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/adr_name.png" width="450" height="300" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit. Serviciile se inchiriaza. Exista o taxa unica de 0.0001 MSK / zi. In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Name</strong></span> <span class="simple_gri_inchis_12">- Numele pe care il doresti asociat adresei. Poate fi orice sir de caractere cu lungimea intre 3 si 30 caractere. Se accepta litere, cifre plus caracterele &quot;-&quot; si &quot;.&quot;. Nu se accepta spatii.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa butonul send. Cererea va fi trimisa in retea iar dupa confirmare (1 minut), adresa ta va dispune de un nume nou. Poti vedea o lista completa cu numele pe care le detii in pagina Numele Mele.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Adress Sharing</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Prin aceasta optiune, trimiti cheia privata a unei adrese catre alta persoana (adresa). Destinatarul va putea cheltui monezi si va avea aceleasi dreptui asupra adresei ca si tine. Recomandam folosirea cu atentie a acestei optiuni. Este foarte utila cand vrei sa iti folosesti adresa pe mai multe portofele web sau cand vrei sa dai acces total la dresa unei persoane de incredere care foloseste poate un alt portofel web decat tine.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a trimite datele adresei mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Share Addresssi va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/share.png" width="450" height="325" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Recipient Address</strong></span> <span class="simple_gri_inchis_12">- Adresa catre care doresti sa transmiti controlul acestei adrese. Fii foarte atent cand scrii numele pentru a evita transmiterea cheii private catre o adresa gresita.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa butonul Send. Portofelul va trimite datele criptate in retea. Destinatarul va fi notificat si va avea optiunea sa importe noua adresa in portofelul local.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_red_14"><strong>Setarea unui profil</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Daca nu doresti sa ramai anonim, poti seta un profil pentru adresa ta in care sa furnizezi informatii cum ar fi email-ul, site-ul web sau o poza de profil. Toate aceste informatii vor deveni publice si vor fi asociate cu adresa ta. O adresa poate dispune de un singur profil asociat.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a asocia un profil adresei tale mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Asociaza Profilva aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/profile.png" width="450" height="615" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Asocierea unui profil costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Profile Pic</strong></span> <span class="simple_gri_inchis_12">- Un link catre o poza de profil. Dimensiunea maxima admisa a pozei este de 1 Mb. Este un camp optional.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Name</strong></span> <span class="simple_gri_inchis_12">- Un nume oarecare. Poate contine spatii. A nu se confunda cu numele de adresa. Un nume de adresa inchiriat poate fi folosit pentru a primii monezi si mesaje. Numele din profil este doar afisat in cadrul profilului si nu are alt rol.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Description, Email, Telephone, Website, Facebook</strong></span> <span class="simple_gri_inchis_12">- Sunt campuri optionale.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Inghetarea unei adrese</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Prin activarea acestei optiuni, vei bloca trimiterea de bani din aceasta adresa pentru o perioada de timp. Recomandam folosirea cu prudenta a acestei optiuni, deoarece odata activata nu mai poate fi anulata. Este o optiune utila daca nu mai doresti sa folosesti adresa o perioada si vrei sa stai fara griji. Chiar daca trimiterea de fonduri este inghetata, poti primii dobanda aferenta intr-o alta adresa. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a ingheta o adresa mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Ingheata Adresa aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/froze.png" width="450" height="288" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Apasa butonul Activeaza. Dupa confirmarea cererii ( 1 minut ), nu vei mai putea trimite fonduri din adresa pentru o anumita perioada.</td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Sigilarea unei adrese</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Dintr-o adresa sigilata pot fi transferate fonduri dar adresa nu poate fi folosita pentru altceva. Nu pot fi atasate optiuni de securitate, nu se pot transmite mesaje sau efectua alte actiuni. Daca un atacator preia controlul adresei, nu va putea decat sa transfere fonduri. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Optiunea este folosita in combinatie cu alte optiuni de securitate. Spre exemplu, daca o adresa protejata prin semnaturi multiple este compromisa, atacatorul nu ar putea cheltui fonduri dar in schimb ar putea atasa o noua optiune prin care se restrictioneaza destinatarii adresei. In cazul acesta posesorul initial al adresei nu va putea sa mai transfere fonduri catre adresele proprii, chiar daca isi protejase adresa prin semnaturi multiple. O adresa sigilata, protejata prin semnaturi multiple este imuna la acest atac.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a sigila o adresa mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Sigileaza Adresa si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/seal.png" width="450" height="245" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Restrictionarea Destinatiilor</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">O adresa cu destinatarii restrictionati nu poate trimite fonduri decat catre anumite adrese predefinite, care de obicei apartin aceleiasi persoane / organizatii. Este o optiune foarte folositoare mai ales daca detii conturi pe mai multe portofele online. Un atacator nu va putea trimite fonduri decat catre alte adrese detinute de tine, care la randul lor ar utea fi protejate prin alte moduri. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a restrictiona destinatarii unei adrese, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Restrictioneaza Destinatarii  si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/restrict.png" width="450" height="531" alt=""/></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Recipients</strong></span> <span class="simple_gri_inchis_12">- Poti specifica pana la 5 destinatari. Doar primul destinatar este obligatoriu. </span></td>
                      </tr>
                      <tr>
                        <td height="0">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0"><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Semnaturi Multiple </strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">O adresa protejata prin aceasta optiune, are nevoie de aprobarea altor adrese pentru a putea trimite fonduri. Este una din cele mai folosite optiuni de securitate. Un potential atacator nu va putea cheltui fonduri fara aprobarea si altor adrese specificate.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti deasemenea specifica un numar de semnaturi minim pentru ca o tranzactie sa fie executata. Spre exemplu poti cere semnatura a 3 adrese suplimentare dar poti specifica ca doar 2 semnaturi din 3 sunt necesare pentru ca o tranzactie sa fie aprobata de retea.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a restrictiona destinatarii unei adrese, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Semnaturi Multiple si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/multisig.png" width="450" height="582" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Recipients</strong></span> <span class="simple_gri_inchis_12">- Poti specifica pana la co-semnatari. Trebuie sa specifici minim 1 co-semnatar.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Minimum</strong></span> <span class="simple_gri_inchis_12">- Numarul minim de semnaturi pentru ca o tranzactie sa fie aprobata de retea.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Parola Unica</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Prin activarea acestei optiuni, inainte de fiecare tranzactie va trebui sa introduci o parola care se schimba dupa fiecare tranzactie. Daca un atacator preia controlul adresei, nu va putea cheltui fonduri deoarece va avea nevoie de aceasta parola. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti deasemenea specifica o adresa unde s-ar putea transfera fonduri fara a fi nevoie de aceasta parola. Optiunea este foarte folositoare in cazul in care pierzi ultima parola generata de sistem.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Mecanismul de functionare este relativ simplu. Inainte de orice tranzactie portofelul va cere ultima parola generata. Dupa ce trimiti fondurile, portofelul iti va afisa urmatoarea parola, pe care va trebui sa o retii.  Portofelul trimite catre retea forma criptata ( hash256 ) a noii parole. Reteaua va aproba o noua tranzactie doar daca este insotita de parola corespunzatoare. Reteaua nu cunoaste parola ci doar forma criptata dar va putea verifica usor ca parola furnizata este corecta sau nu.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a activa aceasta optiune pentru o adresa, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Parola Unica si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/otp.png" width="450" height="407" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Recipients</strong></span> <span class="simple_gri_inchis_12">- Optional. Adresa de rezerva. Daca uiti sau pierzi parola generata iti poti recupera fondurile prin trimiterea lor catre adresa de urgenta.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Next Password</strong></span> <span class="simple_gri_inchis_12">- Parola pe care va trebui sa o introduci la urmatoarea tranzactie din aceasta adresa.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Notificarea de Plata</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Prin activarea acestei optiuni, poti fi notificat de fiecare data cand adresa primeste sau trimite fonduri. Notificarea se poate face in doua moduri. Prin apelarea unei adrese web specificata de tine sau prin trimiterea unui email. </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Este o optiune foarte utila mai ales pentru magazinele virtuale care vand servicii digitale si care ar putea sa livreze produsele imediat dupa receptionarea platii.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a activa notificarea de plata pentru o adresa, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Notificare de Plata si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/ipn.png" width="450" height="458" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Web Address </strong></span> <span class="simple_gri_inchis_12">- Adresa web care va fi apelata cand se trimit sau se primesc fonduri. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Email Address </strong></span> <span class="simple_gri_inchis_12">- Adresa wde email unde se vor trimite mesaje cand se trimit sau se primesc fonduri. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Web Password </strong></span> <span class="simple_gri_inchis_12">- O parola specificata de tine care va fi inclusa in datele trimise catre adresa web. Este optionala dar recomandam specificarea unei parole pentru a putea verifica faptul ca cererea a fost trimisa de portofelul web si nu de un atacator.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Solicitarea de date suplimentare</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti solicita date suplimentare de la cei care trimit fonduri catre adresa. Deoarece poti specifica formatul datelor, nu exista restrictii asupra tipului de date solicitat. Dupa activarea acestei optiuni, oricine doreste sa-ti trimita fonduri va trebui sa completeze un mic formular.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a activa aceatsa optiune pentru o adresa, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Date Suplimentare si va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/request_data.png" width="450" height="602" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Short Message</strong></span> <span class="simple_gri_inchis_12">- Un mesaj scurt in care poti explica spe exemplu, de ce ai nevoie de date suplimentare.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Field Name</strong></span> <span class="simple_gri_inchis_12">- Numele campului cerut, cum ar fi &quot;Adresa de Email&quot; sau &quot;Numarul de telefon&quot;</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Min Length</strong></span> <span class="simple_gri_inchis_12">- Lungimea minima a campului. Pentru o adresa Bitcoin, spre exemplu, ar trebui sa specifici o lungime de minim 33 caractere.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Max Length</strong></span> <span class="simple_gri_inchis_12">- Lungimea maxima a campului. Pentru un cod postal, spre exemplu, ar trebui sa specifici o lungime de maxim 10 caractere.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Days</strong></span> <span class="simple_gri_inchis_12">- Deoarece acesta este un serviciu inchirat trebuie sa specifici pentru cat timp doresti inchirierea adresei. Minimul este de 10 zile. Nu exista un maxim. Taxa este fixa de 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Raspunsuri Automate</strong></td>
                      </tr>
                      <tr>
                        <td  background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Poti instrui portofelul sa trimita mesaje automate dupa fiecare plata / mesaj primit, catre adresa care a initial plata sau a trimis mesajul. Este o optiune foarte utila mai ales pentru comercianti care ar putea trimite confirmari automate ale primirii fondurilor.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a activa aceatsa optiune pentru o adresa, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Raspunsuri Automatesi va aparea urmatorul meniu.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/autoresp.png" width="450" height="389" alt=""/></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Network Fee Address</strong></span> <span class="simple_gri_inchis_12">- Orice serviciu cum este inchirierea unui nume de adresa sau setarea unor optiuni suplimentare trebuie platit.   In acest camp specifici de unde se vor lua monezile pentru plata acestui serviciu. Inghetarea adresei costa 0.0001 MSK / zi.</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_red_12"><strong>Short Message</strong></span> <span class="simple_gri_inchis_12">- Mesajul pe care doresti sa-l trimiti automat.</span></td>
                      </tr>
                      <tr>
                        <td height="50">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><strong class="simple_red_14">Afisarea Cheii Private</strong></td>
                      </tr>
                      <tr>
                        <td background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="simple_gri_inchis_12">Poti vizualiza cheia privata a oricarei adrese pe care o detii. Aceasta este o optiune utilizata daca doresti sa mentii o copie pe langa cea mentinuta de portofel. Cu cheia privata poti controla o adresa. Nu dezvalui nimanui aceasta cheie.</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Pentru a vizualiza cheia privata a unei adrese, mergi in pagina Adrese (apasa pe Adrese din bara de meniu), dupa care apasa butonul Optiuni din dreptul adresei si alege Optiunile Adresei. Vei naviga in pagina de optiuni. </span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><span class="simple_gri_inchis_12">Apasa butonul setup din dreptul optiunii Dezvaluie Cheia Privata. Introdu parola contului iar portofelul va afisa combinatia cheie publica / cheie privata. Poti folosi datele pentru a importa adresa in orice alt portofel.</span></td>
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