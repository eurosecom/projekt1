<HTML>
<?php
//XML pre HLASENIE 2014
do
{
$sys = 'MZD';
$urov = 1000;
$copern = $_REQUEST['copern'];
$drupoh = $_REQUEST['drupoh'];
//echo $copern;

$uziv = include("../uziv.php");
if ( !$uziv ) exit;

require_once("../pswd/password.php");
@$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);

//datumove funkcie
$sDat = include("../funkcie/dat_sk_us.php");

$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];

$citfir = include("../cis/citaj_fir.php");
$mena1 = $fir_mena1;
$mena2 = $fir_mena2;
$kurz12 = $fir_kurz12;

$zablokovane=1;
if ( $_SERVER['SERVER_NAME'] == "localhost" ) { $zablokovane=0; }
if ( $zablokovane == 1 )
     {
?>
<script type="text/javascript">
alert ("Hl�senie bude pripraven� v priebehu febru�ra 2015. Aktu�lne info n�jdete na vstupnej str�nke v bode Novinky, tipy, triky.");
window.close();
</script>
<?php
exit;
     }

//tlacove okno
$tlcuwin="width=700, height=' + vyskawin + ', top=0, left=200, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcswin="width=980, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$uliscwin="width=' + sirkawic + ', height=' + vyskawic + ', top=0, left=0, status=yes, resizable=yes, scrollbars=yes, menubar=no, toolbar=no";

$nazsub="HLASENIE_rok_".$kli_vrok."_".$kli_uzid.".xml";
$cislo_oc=1;
?>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
 <link type="text/css" rel="stylesheet" href="../css/styl.css">
<title>EuroSecom - HLASENIE xml</title>
<style type="text/css">
td.hvstup_zlte  { background-color:#ffff90; color:black; font-weight:bold;
                  height:12px; font-size:12px; }
td.hvstup_tzlte { background-color:#ecaa12; color:black; font-weight:bold;
                  height:12px; font-size:12px; }
td.hvstup_bsede { background-color:#eaeaea; color:black; font-weight:normal;
                  height:12px; font-size:12px; }
td.hvstup_bred { background-color:#ff6c6c; color:black; font-weight:normal;
                  height:12px; font-size:12px; }
</style>

<script type="text/javascript">
//sirka a vyska okna
var sirkawin = screen.width-10;
var vyskawin = screen.height-175;
var vyskawic = screen.height;
var sirkawic = screen.width-10;
</script>
</HEAD>
<BODY class="white">
 <table class="h2" width="100%" >
 <tr>
  <td>EuroSecom - Hl�senie o vy��tovan� dane a �hrne pr�jmov ... - export do XML</td>
  <td align="right"><span class="login"><?php echo "UME $kli_vume FIR$kli_vxcf-$kli_nxcf  login: $kli_uzmeno $kli_uzprie / $kli_uzid";?></span></td>
 </tr>
 </table>

<?php
///////////////////////////////////////////////////TLAC a VYTVORENIE XML SUBORU elsubor=1,2
if ( $copern == 110 )
     {
//prva strana
if ( File_Exists("../tmp/$nazsub") ) { $soubor = unlink("../tmp/$nazsub"); }
$soubor = fopen("../tmp/$nazsub", "a+");

//verzia 2014
$sqlt = <<<mzdprc
(
<?xml version="1.0" encoding="utf-8"?>

);
mzdprc;

//pocet stran je vypocitany v hlaseni netreba znovu

//hlavicka
$sqltt = "SELECT * FROM F$kli_vxcf"."_mzdrocnehlaseniedane".
" WHERE oc = $cislo_oc AND konx = 2 ORDER BY konx";

$sql = mysql_query("$sqltt");
$pol = mysql_num_rows($sql);

$i=0;
$j=0; //zaciatok strany ak by som chcel strankovat
  while ($i <= $pol )
  {
  if (@$zaznam=mysql_data_seek($sql,$i))
{
$hlavicka=mysql_fetch_object($sql);

$obdobie=$kli_vmes;
$dat_dat = Date ("d.m.Y", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

if ( $j == 0 )
     {
  $text = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"."\r\n"; fwrite($soubor, $text);
  $text = "<dokument xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"universal.xsd\">"."\r\n";   fwrite($soubor, $text);

  $text = " <hlavicka>"."\r\n"; fwrite($soubor, $text);

  $text = "  <dic><![CDATA[".$fir_fdic."]]></dic>"."\r\n"; fwrite($soubor, $text);
$fir_uctt01 = iconv("CP1250", "UTF-8", $fir_uctt01);
  $text = "  <danovyUrad><![CDATA[".$fir_uctt01."]]></danovyUrad>"."\r\n"; fwrite($soubor, $text);

  $text = "  <druhHlasenia>"."\r\n"; fwrite($soubor, $text);
$riadne="1"; $opravne="0"; $dodatocne="0";
if ( $hlavicka->mz12 == 2 ) { $riadne="0"; $opravne="1"; $dodatocne="0"; }
if ( $hlavicka->mz12 == 3 ) { $riadne="0"; $opravne="0"; $dodatocne="1"; }
  $text = "   <rh><![CDATA[".$riadne."]]></rh>"."\r\n"; fwrite($soubor, $text);
  $text = "   <oh><![CDATA[".$opravne."]]></oh>"."\r\n"; fwrite($soubor, $text);
  $text = "   <dh><![CDATA[".$dodatocne."]]></dh>"."\r\n"; fwrite($soubor, $text);
  $text = "  </druhHlasenia>"."\r\n"; fwrite($soubor, $text);

  $text = "  <zdanovacieObdobie>"."\r\n"; fwrite($soubor, $text);
$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];
  $text = "   <rok><![CDATA[".$kli_vrok."]]></rok>"."\r\n"; fwrite($soubor, $text);
$dat_ddp="";
if ( $dodatocne == 1 ) $dat_ddp=SkDatum($hlavicka->r01bd);
if ( $dat_ddp == '00.00.0000' ) $dat_ddp="";
  $text = "   <datumDDP><![CDATA[".$dat_ddp."]]></datumDDP>"."\r\n"; fwrite($soubor, $text);
  $text = "  </zdanovacieObdobie>"."\r\n"; fwrite($soubor, $text);

//priezvisko,meno,titul FO a nazov,forma PO
$sqlfir = "SELECT * FROM F$kli_vxcf"."_ufirdalsie ";
$fir_vysledok = mysql_query($sqlfir);
if ($fir_vysledok) { $fir_riadok=mysql_fetch_object($fir_vysledok); }
$dmeno = iconv("CP1250", "UTF-8", $fir_riadok->dmeno);
$dprie = iconv("CP1250", "UTF-8", $fir_riadok->dprie);
$dtitl = iconv("CP1250", "UTF-8", $fir_riadok->dtitl);
$dtitz = iconv("CP1250", "UTF-8", $fir_riadok->dtitz);
$duli = iconv("CP1250", "UTF-8", $fir_riadok->duli);
$dcdm = $fir_riadok->dcdm;
$dmes = iconv("CP1250", "UTF-8", $fir_riadok->dmes);
$dpsc = $fir_riadok->dpsc;
$xstat = "Slovensko";
if ( $fir_uctt03 != 999 ) {
$dmeno=""; $dprie=""; $dtitl=""; $dtitz="";
$fnazov = iconv("CP1250", "UTF-8", $fir_fnaz);
$cforma = $fir_uctt03;
$duli = iconv("CP1250", "UTF-8", $fir_fuli);
$dcdm=$fir_fcdm;
$dmes = iconv("CP1250", "UTF-8", $fir_fmes);
$dpsc=$fir_fpsc;
                          }
if ( $fir_uctt03 == 999 ) {
$fir_fnaz=""; $fir_uctt03="";
                          }
  $text = "  <fo>"."\r\n"; fwrite($soubor, $text);
  $text = "   <priezvisko><![CDATA[".$dprie."]]></priezvisko>"."\r\n"; fwrite($soubor, $text);
  $text = "   <meno><![CDATA[".$dmeno."]]></meno>"."\r\n"; fwrite($soubor, $text);
  $text = "   <titul><![CDATA[".$dtitl."]]></titul>"."\r\n"; fwrite($soubor, $text);
  $text = "   <titulZa><![CDATA[".$dtitz."]]></titulZa>"."\r\n"; fwrite($soubor, $text);
  $text = "  </fo>"."\r\n"; fwrite($soubor, $text);

  $text = "  <po>"."\r\n"; fwrite($soubor, $text);
  $text = "   <obchodneMeno><![CDATA[".$fnazov."]]></obchodneMeno>"."\r\n"; fwrite($soubor, $text);
  $text = "   <pravnaForma><![CDATA[".$cforma."]]></pravnaForma>"."\r\n"; fwrite($soubor, $text);
  $text = "  </po>"."\r\n"; fwrite($soubor, $text);

  $text = "  <sidlo>"."\r\n"; fwrite($soubor, $text);
  $text = "   <ulica><![CDATA[".$duli."]]></ulica>"."\r\n"; fwrite($soubor, $text);
  $text = "   <cislo><![CDATA[".$dcdm."]]></cislo>"."\r\n"; fwrite($soubor, $text);

$dpsc=str_replace(" ","",$dpsc);

  $text = "   <psc><![CDATA[".$dpsc."]]></psc>"."\r\n"; fwrite($soubor, $text);
  $text = "   <obec><![CDATA[".$dmes."]]></obec>"."\r\n"; fwrite($soubor, $text);
  $text = "   <stat><![CDATA[".$xstat."]]></stat>"."\r\n"; fwrite($soubor, $text);
  $text = "   <tel><![CDATA[".$fir_ftel."]]></tel>"."\r\n"; fwrite($soubor, $text);
  $text = "   <emailFax><![CDATA[".$fir_fem1."]]></emailFax>"."\r\n"; fwrite($soubor, $text);
  $text = "  </sidlo>"."\r\n"; fwrite($soubor, $text);

$dat_rz=SkDatum($hlavicka->r01ad);
if ( $dat_rz == '00.00.0000' ) $dat_rz="";
  $text = "  <datumZuctovania><![CDATA[".$dat_rz."]]></datumZuctovania>"."\r\n"; fwrite($soubor, $text);

  $text = "  <vypracoval>"."\r\n"; fwrite($soubor, $text);
$fir_mzdt05 = iconv("CP1250", "UTF-8", $fir_mzdt05);
  $text = "   <kto><![CDATA[".$fir_mzdt05."]]></kto>"."\r\n"; fwrite($soubor, $text);
$dat_pr=SkDatum($hlavicka->r07ad);
if ( $dat_pr == '00.00.0000' ) $dat_pr="";
  $text = "   <dna><![CDATA[".$dat_pr."]]></dna>"."\r\n"; fwrite($soubor, $text);
  $text = "   <tel><![CDATA[".$fir_mzdt04."]]></tel>"."\r\n"; fwrite($soubor, $text);
  $text = "  </vypracoval>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->str4;
  $text = "  <pocetStranC4><![CDATA[".$tlachod_c."]]></pocetStranC4>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->str5;
  $text = "  <pocetStranC5><![CDATA[".$tlachod_c."]]></pocetStranC5>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->zam4;
  $text = "  <pocetZamC4><![CDATA[".$tlachod_c."]]></pocetZamC4>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->zam5;
  $text = "  <pocetZamC5><![CDATA[".$tlachod_c."]]></pocetZamC5>"."\r\n"; fwrite($soubor, $text);

$dat_vyhl=SkDatum($hlavicka->r07bd);
if ( $dat_vyhl == '00.00.0000' ) $dat_vyhl="";
  $text = "  <datumVyhlasenia><![CDATA[".$dat_vyhl."]]></datumVyhlasenia>"."\r\n"; fwrite($soubor, $text);

  $text = "  <dedicZastupca>"."\r\n"; fwrite($soubor, $text);
$priezvisko=iconv("CP1250", "UTF-8", $hlavicka->zprie);
  $text = "   <priezvisko><![CDATA[".$priezvisko."]]></priezvisko>"."\r\n"; fwrite($soubor, $text);
$meno=iconv("CP1250", "UTF-8", $hlavicka->zmeno);
  $text = "   <meno><![CDATA[".$meno."]]></meno>"."\r\n"; fwrite($soubor, $text);
$titul=iconv("CP1250", "UTF-8", $hlavicka->ztitl);
  $text = "   <titul><![CDATA[".$titul."]]></titul>"."\r\n"; fwrite($soubor, $text);
$titul=iconv("CP1250", "UTF-8", $hlavicka->ztitz);
  $text = "   <titulZa><![CDATA[".$titul."]]></titulZa>"."\r\n"; fwrite($soubor, $text);
$rodneCislo=$hlavicka->zrdc.$hlavicka->zrdk;
  $text = "   <rodneCislo><![CDATA[".$rodneCislo."]]></rodneCislo>"."\r\n"; fwrite($soubor, $text);
$ulica=iconv("CP1250", "UTF-8", $hlavicka->zuli);
  $text = "   <ulica><![CDATA[".$ulica."]]></ulica>"."\r\n"; fwrite($soubor, $text);
$cislo=iconv("CP1250", "UTF-8", $hlavicka->zcdm);
  $text = "   <cislo><![CDATA[".$cislo."]]></cislo>"."\r\n"; fwrite($soubor, $text);
$psc=$hlavicka->zpsc;
$psc=str_replace(" ","",$psc);
  $text = "   <psc><![CDATA[".$psc."]]></psc>"."\r\n"; fwrite($soubor, $text);
$obec=iconv("CP1250", "UTF-8", $hlavicka->zmes);
  $text = "   <obec><![CDATA[".$obec."]]></obec>"."\r\n"; fwrite($soubor, $text);
$stat=iconv("CP1250", "UTF-8", $hlavicka->zstat);
  $text = "   <stat><![CDATA[".$stat."]]></stat>"."\r\n"; fwrite($soubor, $text);
$telefon=$hlavicka->ztel;
  $text = "   <tel><![CDATA[".$telefon."]]></tel>"."\r\n"; fwrite($soubor, $text);
$mailfax=iconv("CP1250", "UTF-8", $hlavicka->zemfax);
  $text = "   <emailFax><![CDATA[".$mailfax."]]></emailFax>"."\r\n"; fwrite($soubor, $text);
  $text = "  </dedicZastupca>"."\r\n"; fwrite($soubor, $text);
  $text = " </hlavicka>"."\r\n"; fwrite($soubor, $text);

  $text = " <telo>"."\r\n"; fwrite($soubor, $text);
  $text = "  <cast1>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r01a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r00><![CDATA[".$tlachod_c."]]></r00>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r01b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r01><![CDATA[".$tlachod_c."]]></r01>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r02a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r02><![CDATA[".$tlachod_c."]]></r02>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r03a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r03><![CDATA[".$tlachod_c."]]></r03>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r04a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r04><![CDATA[".$tlachod_c."]]></r04>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r05a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r05><![CDATA[".$tlachod_c."]]></r05>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r06a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r06><![CDATA[".$tlachod_c."]]></r06>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r07a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r07><![CDATA[".$tlachod_c."]]></r07>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r08a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r08><![CDATA[".$tlachod_c."]]></r08>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r09a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r09><![CDATA[".$tlachod_c."]]></r09>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r10a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <r10><![CDATA[".$tlachod_c."]]></r10>"."\r\n"; fwrite($soubor, $text);
  $text = "  </cast1>"."\r\n"; fwrite($soubor, $text);

  $text = "  <cast2>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r11a;
if ( $tlachod_c == 0 OR $hlavicka->mz12 != 3 ) $tlachod_c="";
  $text = "   <r11><![CDATA[".$tlachod_c."]]></r11>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r12a;
if ( $tlachod_c == 0 OR $hlavicka->mz12 != 3 ) $tlachod_c="";
  $text = "   <r12><![CDATA[".$tlachod_c."]]></r12>"."\r\n"; fwrite($soubor, $text);
  $text = "  </cast2>"."\r\n"; fwrite($soubor, $text);

  $text = "  <cast3>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->ra1a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rA><![CDATA[".$tlachod_c."]]></rA>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->rb1a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rB><![CDATA[".$tlachod_c."]]></rB>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->rc1a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rC><![CDATA[".$tlachod_c."]]></rC>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->ra1b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rD><![CDATA[".$tlachod_c."]]></rD>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->rb1b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rE><![CDATA[".$tlachod_c."]]></rE>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->rc1b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "   <rF><![CDATA[".$tlachod_c."]]></rF>"."\r\n"; fwrite($soubor, $text);
  $text = "  </cast3>"."\r\n"; fwrite($soubor, $text);

$medium=$hlavicka->mzc;
  $text = "  <medium5c><![CDATA[".$medium."]]></medium5c>"."\r\n"; fwrite($soubor, $text);
     }
//koniec ak j=0

}
$i = $i + 1;
$j = $j + 1;
  }
//koniec hlavicky

//vytlac cast IV. prilohy nevykonalRZ
$sqltt = "SELECT * FROM F$kli_vxcf"."_mzdrocnehlaseniedaneoc".
" LEFT JOIN F$kli_vxcf"."_mzdkun".
" ON F$kli_vxcf"."_mzdrocnehlaseniedaneoc.oc=F$kli_vxcf"."_mzdkun.oc".
" WHERE tz1 != 1 ORDER BY F$kli_vxcf"."_mzdrocnehlaseniedaneoc.oc";

$sql = mysql_query("$sqltt");
$pol = mysql_num_rows($sql);
$i=0;
$stlpec=1;
$strana=0;

$pocstr=1;
$sqldok = mysql_query("SELECT * FROM F$kli_vxcf"."_mzdrocnehlaseniedane WHERE oc = $cislo_oc AND konx = 2 ORDER BY konx");
  if (@$zaznam=mysql_data_seek($sqldok,0))
  {
  $riaddok=mysql_fetch_object($sqldok);
  $pocstr=1*$riaddok->str4;
  }

$celkovo=$pocstr;
if ( $celkovo < 1 ) { $celkovo=1; }
$pol=2*$celkovo;

  while ($i < $pol )
  {
  if (@$zaznam=mysql_data_seek($sql,$i) OR $stlpec < 3 )
{
$hlavicka=mysql_fetch_object($sql);

if ( $stlpec == 1 ) {
$strana=$strana+1;
  $text = "  <cast4>"."\r\n"; fwrite($soubor, $text);

  $text = "   <strana>"."\r\n"; fwrite($soubor, $text);
$aktualna=$strana;
  $text = "    <aktualna><![CDATA[".$aktualna."]]></aktualna>"."\r\n"; fwrite($soubor, $text);
  $text = "    <celkovo><![CDATA[".$celkovo."]]></celkovo>"."\r\n"; fwrite($soubor, $text);
  $text = "   </strana>"."\r\n"; fwrite($soubor, $text);
}

$cislordc=1*$hlavicka->rdc;
if ( $stlpec == 1 ) { $text = "   <stlpec1>"."\r\n"; fwrite($soubor, $text); }
if ( $stlpec == 2 ) { $text = "   <stlpec2>"."\r\n"; fwrite($soubor, $text); }

$zstat="Slovensko"; $zstak="703";
$sqldok = mysql_query("SELECT * FROM F".$kli_vxcf."_mzdtextmzd WHERE invt = $hlavicka->oc ");
  if (@$zaznam=mysql_data_seek($sqldok,0))
  {
  $riaddok=mysql_fetch_object($sqldok);
  $zstat=$riaddok->zstat;
  $zstak=$riaddok->zstak;
  }
if ( $zstat == '' ) { $zstat="Slovensko"; }
if ( $zstak == 0 ) { $zstak="703"; }
$niejeoc=1*$hlavicka->oc;
if( $niejeoc == 0 ) { $zstat=""; $zstak=""; }

$rodnecislo=$hlavicka->rdc.$hlavicka->rdk;
//if ( $zstak != 703 ) { $rodnecislo=""; }
  $text = "    <rodneCislo><![CDATA[".$rodnecislo."]]></rodneCislo>"."\r\n"; fwrite($soubor, $text);
$datumnarodenia=SkDatum($hlavicka->dar);
if ( $datumnarodenia == '00.00.0000' ) { $datumnarodenia=""; }
if ( $zstak == 703 ) { $datumnarodenia=""; }
  $text = "    <datumNarodenia><![CDATA[".$datumnarodenia."]]></datumNarodenia>"."\r\n"; fwrite($soubor, $text);
$prie = iconv("CP1250", "UTF-8", $hlavicka->prie);
  $text = "    <priezvisko><![CDATA[".$prie."]]></priezvisko>"."\r\n"; fwrite($soubor, $text);
$meno = iconv("CP1250", "UTF-8", $hlavicka->meno);
  $text = "    <meno><![CDATA[".$meno."]]></meno>"."\r\n"; fwrite($soubor, $text);
$uli = iconv("CP1250", "UTF-8", $hlavicka->zuli);
  $text = "    <ulica><![CDATA[".$uli."]]></ulica>"."\r\n"; fwrite($soubor, $text);
$cislo = $hlavicka->zcdm;
  $text = "    <cislo><![CDATA[".$cislo."]]></cislo>"."\r\n"; fwrite($soubor, $text);
$psc = $hlavicka->zpsc;
$psc=str_replace(" ","",$psc);
  $text = "    <psc><![CDATA[".$psc."]]></psc>"."\r\n"; fwrite($soubor, $text);
$obec = iconv("CP1250", "UTF-8", $hlavicka->zmes);
  $text = "    <obec><![CDATA[".$obec."]]></obec>"."\r\n"; fwrite($soubor, $text);
$stat = iconv("CP1250", "UTF-8", $zstat);
  $text = "    <stat><![CDATA[".$stat."]]></stat>"."\r\n"; fwrite($soubor, $text);
  $text = "    <kodStatu><![CDATA[".$zstak."]]></kodStatu>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r01a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r3a><![CDATA[".$tlachod_c."]]></r3a>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->doho;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r3b><![CDATA[".$tlachod_c."]]></r3b>"."\r\n"; fwrite($soubor, $text);

  $text = "    <r4>"."\r\n"; fwrite($soubor, $text);
//v obdobi
$obd112="1";
$obd1="1";
$obd2="1";
$obd3="1";
$obd4="1";
$obd5="1";
$obd6="1";
$obd7="1";
$obd8="1";
$obd9="1";
$obd10="1";
$obd11="1";
$obd12="1";
if ( $hlavicka->mz01 == 1 AND $hlavicka->mz02 == 1 AND $hlavicka->mz03 == 1 AND $hlavicka->mz04 == 1 AND $hlavicka->mz05 == 1 AND $hlavicka->mz06 == 1 AND $hlavicka->mz07 == 1 AND $hlavicka->mz08 == 1 AND $hlavicka->mz09 == 1 AND $hlavicka->mz10 == 1 AND $hlavicka->mz11 == 1 AND $hlavicka->mz12 == 1 )
{
$obd112="1";
$obd1="0";
$obd2="0";
$obd3="0";
$obd4="0";
$obd5="0";
$obd6="0";
$obd7="0";
$obd8="0";
$obd9="0";
$obd10="0";
$obd11="0";
$obd12="0";
}
if ( $hlavicka->mz01 != 1 ) { $obd112="0"; $obd1="0"; }
if ( $hlavicka->mz02 != 1 ) { $obd112="0"; $obd2="0"; }
if ( $hlavicka->mz03 != 1 ) { $obd112="0"; $obd3="0"; }
if ( $hlavicka->mz04 != 1 ) { $obd112="0"; $obd4="0"; }
if ( $hlavicka->mz05 != 1 ) { $obd112="0"; $obd5="0"; }
if ( $hlavicka->mz06 != 1 ) { $obd112="0"; $obd6="0"; }
if ( $hlavicka->mz07 != 1 ) { $obd112="0"; $obd7="0"; }
if ( $hlavicka->mz08 != 1 ) { $obd112="0"; $obd8="0"; }
if ( $hlavicka->mz09 != 1 ) { $obd112="0"; $obd9="0"; }
if ( $hlavicka->mz10 != 1 ) { $obd112="0"; $obd10="0"; }
if ( $hlavicka->mz11 != 1 ) { $obd112="0"; $obd11="0"; }
if ( $hlavicka->mz12 != 1 ) { $obd112="0"; $obd12="0"; }

if ( $hlavicka->mz01 == 0 AND $hlavicka->mz02 == 0 AND $hlavicka->mz03 == 0 AND $hlavicka->mz04 == 0 AND $hlavicka->mz05 == 0 AND $hlavicka->mz06 == 0 AND $hlavicka->mz07 == 0 AND $hlavicka->mz08 == 0 AND $hlavicka->mz09 == 0 AND $hlavicka->mz10 == 0 AND $hlavicka->mz11 == 0 AND $hlavicka->mz12 == 0 )
{
$obd112="1";
}
if ( $cislordc == 0 ) { $obd112="0"; }
  $text = "     <box00><![CDATA[".$obd112."]]></box00>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box01><![CDATA[".$obd1."]]></box01>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box02><![CDATA[".$obd2."]]></box02>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box03><![CDATA[".$obd3."]]></box03>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box04><![CDATA[".$obd4."]]></box04>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box05><![CDATA[".$obd5."]]></box05>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box06><![CDATA[".$obd6."]]></box06>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box07><![CDATA[".$obd7."]]></box07>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box08><![CDATA[".$obd8."]]></box08>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box09><![CDATA[".$obd9."]]></box09>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box10><![CDATA[".$obd10."]]></box10>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box11><![CDATA[".$obd11."]]></box11>"."\r\n"; fwrite($soubor, $text);
  $text = "     <box12><![CDATA[".$obd12."]]></box12>"."\r\n"; fwrite($soubor, $text);
  $text = "    </r4>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->socp;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r5a><![CDATA[".$tlachod_c."]]></r5a>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->zdrp;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r5b><![CDATA[".$tlachod_c."]]></r5b>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->r01b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r6><![CDATA[".$tlachod_c."]]></r6>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->dnbh;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r7suma><![CDATA[".$tlachod_c."]]></r7suma>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->tz3;
if ( $tlachod_c == 0 OR $hlavicka->dnbh == 0 ) $tlachod_c="";
  $text = "    <r7deti><![CDATA[".$tlachod_c."]]></r7deti>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->pred;
if ( $hlavicka->tz1 == 1 ) $tlachod_c="";
  $text = "    <r8ano><![CDATA[".$tlachod_c."]]></r8ano>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=1*$hlavicka->pdan;
if ( $tlachod_c == 0 OR $hlavicka->pred == 0 ) $tlachod_c="";
  $text = "    <r8pm><![CDATA[".$tlachod_c."]]></r8pm>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->ddssum;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <r9><![CDATA[".$tlachod_c."]]></r9>"."\r\n"; fwrite($soubor, $text);

if ( $stlpec == 1 ) {  $text = "   </stlpec1>"."\r\n"; fwrite($soubor, $text); }
if ( $stlpec == 2 ) {  $text = "   </stlpec2>"."\r\n"; fwrite($soubor, $text); }

if ( $stlpec == 2 ) {
  $text = "  </cast4>"."\r\n"; fwrite($soubor, $text);
}

}
$i = $i + 1;
$stlpec = $stlpec + 1;
if( $stlpec == 3 ) $stlpec=1;
  }
//koniec cast IV. prilohy


//vytlac cast V. prilohy vykonalRZ
$sqltt = "SELECT * FROM F$kli_vxcf"."_mzdrocnehlaseniedaneoc".
" LEFT JOIN F$kli_vxcf"."_mzdkun".
" ON F$kli_vxcf"."_mzdrocnehlaseniedaneoc.oc=F$kli_vxcf"."_mzdkun.oc".
" WHERE tz1 = 1 ORDER BY F$kli_vxcf"."_mzdrocnehlaseniedaneoc.oc";

$sql = mysql_query("$sqltt");
$pol = mysql_num_rows($sql);
$i=0;
$stlpec=1;
$strana=0;

$pocstr=1;
$sqldok = mysql_query("SELECT * FROM F$kli_vxcf"."_mzdrocnehlaseniedane WHERE oc = $cislo_oc AND konx = 2 ORDER BY konx");
  if (@$zaznam=mysql_data_seek($sqldok,0))
  {
  $riaddok=mysql_fetch_object($sqldok);
  $pocstr=1*$riaddok->str5;
  }
$celkovo=$pocstr;
if ( $celkovo < 1 ) { $celkovo=1; }
$pol=2*$celkovo;

  while ($i < $pol )
  {
  if (@$zaznam=mysql_data_seek($sql,$i) OR $stlpec < 3 )
{
$hlavicka=mysql_fetch_object($sql);

if ( $stlpec == 1 ) {
$strana=$strana+1;
  $text = "  <cast5>"."\r\n"; fwrite($soubor, $text);

  $text = "   <c5strana>"."\r\n"; fwrite($soubor, $text);
$aktualna=$strana;
  $text = "    <c5aktualna><![CDATA[".$aktualna."]]></c5aktualna>"."\r\n"; fwrite($soubor, $text);
  $text = "    <c5celkovo><![CDATA[".$celkovo."]]></c5celkovo>"."\r\n"; fwrite($soubor, $text);
  $text = "   </c5strana>"."\r\n"; fwrite($soubor, $text);
}

if ( $stlpec == 1 ) { $text = "   <c5stlpec1>"."\r\n"; fwrite($soubor, $text); }
if ( $stlpec == 2 ) { $text = "   <c5stlpec2>"."\r\n"; fwrite($soubor, $text); }

$zstat="Slovensko"; $zstak="703";
$sqldok = mysql_query("SELECT * FROM F".$kli_vxcf."_mzdtextmzd WHERE invt = $hlavicka->oc ");
  if (@$zaznam=mysql_data_seek($sqldok,0))
  {
  $riaddok=mysql_fetch_object($sqldok);
  $zstat=$riaddok->zstat;
  $zstak=$riaddok->zstak;
  }
if ( $zstat == '' ) { $zstat="Slovensko"; }
if ( $zstak == 0 ) { $zstak="703"; }
$niejeoc=1*$hlavicka->oc;
if ( $niejeoc == 0 ) { $zstat=""; $zstak=""; }

$rodnecislo=$hlavicka->rdc.$hlavicka->rdk;
if ( $zstak != 703 ) { $rodnecislo=""; }
  $text = "    <c5rodneCislo><![CDATA[".$rodnecislo."]]></c5rodneCislo>"."\r\n"; fwrite($soubor, $text);
$datumnarodenia=SkDatum($hlavicka->dar);
if ( $datumnarodenia == '00.00.0000' ) { $datumnarodenia=""; }
if ( $zstak == 703 ) { $datumnarodenia=""; }
  $text = "    <c5datumNarodenia><![CDATA[".$datumnarodenia."]]></c5datumNarodenia>"."\r\n";   fwrite($soubor, $text);
$prie = iconv("CP1250", "UTF-8", $hlavicka->prie);
  $text = "    <c5priezvisko><![CDATA[".$prie."]]></c5priezvisko>"."\r\n"; fwrite($soubor, $text);
$tlachod = iconv("CP1250", "UTF-8", $hlavicka->meno);
  $text = "    <c5meno><![CDATA[".$tlachod."]]></c5meno>"."\r\n"; fwrite($soubor, $text);
$uli = iconv("CP1250", "UTF-8", $hlavicka->zuli);
  $text = "    <c5ulica><![CDATA[".$uli."]]></c5ulica>"."\r\n"; fwrite($soubor, $text);
$cislo = $hlavicka->zcdm;
  $text = "    <c5cislo><![CDATA[".$cislo."]]></c5cislo>"."\r\n"; fwrite($soubor, $text);
$psc = $hlavicka->zpsc;
$psc=str_replace(" ","",$psc);
  $text = "    <c5psc><![CDATA[".$psc."]]></c5psc>"."\r\n"; fwrite($soubor, $text);
$obec = iconv("CP1250", "UTF-8", $hlavicka->zmes);
  $text = "    <c5obec><![CDATA[".$obec."]]></c5obec>"."\r\n"; fwrite($soubor, $text);
$stat = iconv("CP1250", "UTF-8", $zstat);
  $text = "    <c5stat><![CDATA[".$stat."]]></c5stat>"."\r\n"; fwrite($soubor, $text);
  $text = "    <c5kodStatu><![CDATA[".$zstak."]]></c5kodStatu>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->r01a;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r3a><![CDATA[".$tlachod_c."]]></c5r3a>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->doho;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r3b><![CDATA[".$tlachod_c."]]></c5r3b>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->socp;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r4a><![CDATA[".$tlachod_c."]]></c5r4a>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->zdrp;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r4b><![CDATA[".$tlachod_c."]]></c5r4b>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->nzdh;
if ( $tlachod_c == 0 ) $tlachod_c="";
if ( $hlavicka->ra1b > 0 ) $tlachod_c="";
  $text = "    <c5r5><![CDATA[".$tlachod_c."]]></c5r5>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->r01b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r6><![CDATA[".$tlachod_c."]]></c5r6>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->nzmh;
if ( $tlachod_c == 0 ) $tlachod_c="";
if ( $hlavicka->ra1b > 0 ) $tlachod_c="";
  $text = "    <c5r7suma><![CDATA[".$tlachod_c."]]></c5r7suma>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->tz3;
if ( $tlachod_c == 0 OR $hlavicka->dnbh == 0 ) $tlachod_c="";
  $text = "    <c5r7deti><![CDATA[".$tlachod_c."]]></c5r7deti>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->ddsnzc;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r8><![CDATA[".$tlachod_c."]]></c5r8>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->ra1b;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r9suma><![CDATA[".$tlachod_c."]]></c5r9suma>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->zmpm;
if ( $tlachod_c == 0 OR $hlavicka->ra1b == 0 ) $tlachod_c="";
  $text = "    <c5r9pm><![CDATA[".$tlachod_c."]]></c5r9pm>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->dnbh;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r10suma><![CDATA[".$tlachod_c."]]></c5r10suma>"."\r\n"; fwrite($soubor, $text);
$tlachod_c=$hlavicka->dnbm;
if ( $tlachod_c == 0 OR $hlavicka->dnbh == 0 ) $tlachod_c="";
  $text = "    <c5r10pm><![CDATA[".$tlachod_c."]]></c5r10pm>"."\r\n"; fwrite($soubor, $text);

$tlachod_c=$hlavicka->rocz;
if ( $tlachod_c == 0 ) $tlachod_c="";
  $text = "    <c5r11><![CDATA[".$tlachod_c."]]></c5r11>"."\r\n"; fwrite($soubor, $text);

if ( $stlpec == 1 ) {  $text = "   </c5stlpec1>"."\r\n"; fwrite($soubor, $text); }
if ( $stlpec == 2 ) {  $text = "   </c5stlpec2>"."\r\n"; fwrite($soubor, $text); }

if ( $stlpec == 2 ) {
  $text = "  </cast5>"."\r\n"; fwrite($soubor, $text);
}

}
$i = $i + 1;
$stlpec = $stlpec + 1;
if( $stlpec == 3 ) $stlpec=1;
  }
//koniec cast V. prilohy

//koniec celeho xml
  $text = " </telo>"."\r\n"; fwrite($soubor, $text);
  $text = "</dokument>"."\r\n"; fwrite($soubor, $text);
fclose($soubor);
?>

<?php if ( $copern == 110 ) { ?>
<br />
<br />
Stiahnite si ni��ie uveden� s�bor XML na V� lok�lny disk a na��tajte na www.drsr.sk alebo do aplik�cie eDane:
<br />
<br />
<a href="../tmp/<?php echo $nazsub; ?>">../tmp/<?php echo $nazsub; ?></a>
<br />
<br />
<?php                       } ?>

<?php
//mysql_free_result($vysledok);
    }
/////////////////////////////////////////////////////koniec TLAC a VYTVORENIE XML SUBORU PRE ELEKTRONIKU
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_prcprizdphsx'.$kli_uzid;
$vysledok = mysql_query("$sqlt");

//celkovy koniec dokumentu
} while (false);
?>
</BODY>
</HTML>