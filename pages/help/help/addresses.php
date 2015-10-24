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
			   $help->showLeftMenu(3);
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
                        <td align="left" class="simple_red_16"><strong>Adrese</strong></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">O adresa este ca un numar de cont bancar anonim in lumea reala. Este un sir de caractere pe care o poti trimite oricui pentru a primii monezi, asset-uri sau mesaje. La fel ca un cont bancar, poti trimite monezi sau mesaje catre o adresa. O adresa arata asa :</td>
                      </tr>
                      <tr>
                        <td height="80" align="center" class="simple_gri_12">ME4wEAYHKoZIzj0CAQYFK4EEACEDOgAESw6vT5Oz43xw/6Wa7tt0RrUQ<br>9Bj4c7Qhr/gj5XZmMLp1ALqUG46+VOiLLII7ua5mzfuylwHaoLU=</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Poti detine un numar nelimitat de adrese. Unei adrese ii poti asocia un nume sau diverse optiuni de securitate (vezi capitolul Optiunile Adreselor).</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">Deoarece o adresa este de fapt o cheie publica ( forma base64 a cheii publice ), poti trimite mesaje sau date criptate pe care doar detinatorul adresei le poate citi chiar daca mesajele traverseaza intreaga retea MaskNetwork.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="0" align="left" valign="top" class="simple_red_14"><strong>Adresele Mele</strong></td>
                      </tr>
                      <tr>
                        <td height="25" align="left" background="../../template/template/GIF/lp.png" class="simple_gri_inchis_12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Imediat dupa ce creezi un cont folosind portofelul web, vei avea o adresa creeata automat. Pentru a vizualiza lista adreselor pe care le detii, mergi in pagina Adrese ( apasa Adrese in meniul principal din partea de sus a paginii ). </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">In aceasta pagina sunt prezentate adresele si balanta in MSK. In cazul in care o adresa are un nume asociat, vei vedea soar numele adresei. Pentru restul dreselor, doar o parte din sirul de caractere este vizibil. Pentru a vizualiza adresa completa apasa butonul Optiuni din dreptul adresei si selcteaza Codul QR. Va fi afisat un dialog ca cel de mai jos. Poti copia forma completa a adresei sau iti poti folosi telefonul mobil pentru a scana codul QR afisat.</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/qr_code.png" width="402" height="275" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">Deoarece adresele au minim 64 caractere aleatoare sunt aproape imposibil de retinut sau scris pe o hartie. Din fericire poti inchiria un nume pentru adresa ta, cum ar fi Maria or John. Vei putea sa spui tuturor &quot;trimite-mi 10 MSK la Maria&quot; ceea ce este mult mai comod si eficient.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_red_14"><strong>Cum creezi o adresa noua</strong></td>
                      </tr>
                      <tr>
                        <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Poti obtine o adresa noua in 3 moduri. Poti folosi portofelul pentru a genera o adresa, poti importa o adresa cineva iti poate trimite cheia privata si cheia publica. Cea mai simpla metoda este generarea unei adrese noi. </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Pentru a genera o adresa noua, mergi in pagina adrese (apasa Adrese in bara de meniu principal ). Apasa butonul verde &quot;Adresa Noua&quot; aflat in partea de jos a paginii. Vei vedea urmatorul dialog :</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/new_adr.png" width="453" height="248" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Encryption Type</strong></span> <span class="simple_gri_inchis_12">- Recomandam sa lasi aceasta optiune nechimbata. Cu cat tipul criptarii este mai avansat, cu atat mai lunga este adresa generata. 224 biti sunt mai mult decat suficienti pentru a-ti proteja fondurile.</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Address Tag</strong></span> <span class="simple_gri_inchis_12">- Poti atasa o scurta descriere adresei. A nu se confunda cu inchirierea unui nume. Descrierea atasata aici nu este visibila decat in cadrul contului tau.</span> </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Apasa butonul Trimite si vei avea o adresa nou-nouta pe care o poti trimite prietenilor / clientilor. Poti detine un numar nelimitat adrese.</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="150" align="left" bgcolor="#f6f2e7"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="16%" align="center" valign="top"><p><img src="GIF/idea.png" width="75" height="75" alt=""/></p></td>
                              <td width="84%" valign="middle" class="simple_maro_12">MaskNetwork foloseste Elliptic Curve Cryptography pentru a cripta date si tranzactii. Encryption Type reprezinta o submetoda de criptare si cu cat este mai mare ca numar de biti, cu atat este mai greu <strong>teoretic</strong>, pentru un atacator sa aprga un text criptat. Spunem <strong>teoretic</strong>, pentru ca daca aduni toate calculatoarele din lume si le pui sa sparga prin forta bruta, un text criptat cu cea mai slaba metoda (224 biti), vor avea nevoie de putin peste <strong>1000 de ani</strong>.</td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="50" align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_red_14"><strong>Importul unei adrese</strong></td>
                      </tr>
                      <tr>
                        <td align="left" background="../../template/template/GIF/lp.png">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">O alta metoda de a obtine o adresa noua este sa o &quot;importi&quot;. Pentru asta vei avea nevoie de cheia publica si cheia privata a unei adrese. Daca detii perechea, mergi in pagina Adrese (apasa Adrese in bara principala de meniu) si apasa butonul galben Importa adresa din partea de jos a paginii. Vei vedea urmatorul dialog </td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="GIF/import_adr.png" width="451" height="363" alt=""/></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Public Key </strong></span> <span class="simple_gri_inchis_12">- Cheia publica a adresei</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Private Key</strong></span> <span class="simple_gri_inchis_12">- Cheia privata a adresei</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left"><span class="simple_red_12"><strong>Address Tag</strong></span> <span class="simple_gri_inchis_12">- Poti atasa o scurta descriere adresei. A nu se confunda cu inchirierea unui nume. Descrierea atasata aici nu este visibila decat in cadrul contului tau.</span></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" class="simple_gri_inchis_12">Apasa butonul Send. Daca perechea cheie publica / cheie privata este valida, adresa va fi importata si o vei putea folosi imediat.</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
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