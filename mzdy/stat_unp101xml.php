<HTML>
<?php

do
{
$sys = 'UCT';
$urov = 1000;
$copern = $_REQUEST['copern'];
$drupoh = $_REQUEST['drupoh'];

$uziv = include("../uziv.php");
if( !$uziv ) exit;

require_once("../pswd/password.php");
@$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);

//datumove funkcie
$sDat = include("../funkcie/dat_sk_us.php");

$citfir = include("../cis/citaj_fir.php");
$mena1 = $fir_mena1;
$mena2 = $fir_mena2;
$kurz12 = $fir_kurz12;

//tlacove okno
$tlcuwin="width=700, height=' + vyskawin + ', top=0, left=200, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcswin="width=980, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$uliscwin="width=' + sirkawic + ', height=' + vyskawic + ', top=0, left=0, status=yes, resizable=yes, scrollbars=yes, menubar=no, toolbar=no";

$hhmm = Date ("H_i", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

$nazsub="../tmp/UNP101_rok_".$kli_vrok."_".$kli_uzid."_".$hhmm.".xml";

 $outfilexdel="../tmp/UNP101_rok_".$kli_vrok."_".$kli_uzid."_*.*";
 foreach (glob("$outfilexdel") as $filename) {
    unlink($filename);
 }

$copern=10;
$elsubor=2;
?>

<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
<link rel="stylesheet" href="../css/reset.css">
<link rel="stylesheet" href="../css/tlaciva.css">
<title>EuroSecom - DMV xml export</title>
<script type="text/javascript">
</script>
<style>
#content {
  box-sizing: border-box;
  background-color: white;
  padding: 30px 25px;
   -webkit-box-shadow: 1px 1px 6px 0px rgba(0, 0, 0, 0.298);
  -moz-box-shadow: 1px 1px 6px 0px rgba(0, 0, 0, 0.298);
  box-shadow: 1px 1px 6px 0px rgba(0, 0, 0, 0.298);
}
#content > p {
  line-height: 22px;
  font-size: 14px;
}
#content > p > a {
  color: #00e;
}
#content > p > a:hover {
  text-decoration: underline;
}
#upozornenie > h2 {
  line-height: 20px;
  margin-top: 25px;
  margin-bottom: 10px;
  overflow: auto;
}
#upozornenie > h2 > strong {
  font-size: 16px;
  font-weight: bold;
}
#upozornenie > ul > li {
  line-height: 18px;
  margin: 10px 0;
  font-size: 13px;
}
.red {
  border-left: 4px solid #f22613;
  text-indent: 8px;
}
.orange {
  border-left: 4px solid #f89406;
  text-indent: 8px;
}
dl.legend-area {
  height: 14px;
  line-height: 14px;
  font-size: 11px;
  position: relative;
  top: 5px;
}
dl.legend-area > dt {
  width:10px;
  height:10px;
  margin: 2px 5px 0 12px;
}
.box-red {
  background-color: #f22613;
}
.box-orange {
  background-color: #f89406;
}
.header-section {
  padding-top: 5px;
}
</style>
</HEAD>
<BODY>
<div id="wrap-heading">
 <table id="heading">
  <tr>
   <td class="ilogin">EuroSecom</td>
   <td class="ilogin" align="right"><?php echo "<strong>UME</strong> $kli_vume&nbsp;&nbsp;<strong>FIR</strong> $kli_vxcf:$kli_nxcf&nbsp;&nbsp;<strong>login</strong> $kli_uzmeno $kli_uzprie / $kli_uzid ";?></td>
  </tr>
  <tr>
   <td class="header">UNP 101 / export do XML</td>
   <td></td>
  </tr>
 </table>
</div>
<?php
///////////////////////////////////////////////////VYTVORENIE XML SUBORU PRE ELEKTRONIKU elsubor=1,2
if ( $copern == 10 AND $elsubor == 2 )
    {
//prva strana

$sqlt = <<<mzdprc
(
<?xml version="1.0" encoding="UTF-8"?>
<!--Sample XML file generated by XMLSpy v2008 rel. 2 (http://www.altova.com)-->
<unp xsi:schemaLocation="http://www.trexima.sk/xsd/unp/1 unp.xsd" xmlns="http://www.trexima.sk/xsd/unp/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<subor>
		<zistovanie>�NP 1-01</zistovanie>
		<obdobie>2016</obdobie>
		<sw_firma>Nazov vasej firmy</sw_firma>
		<program>Nazov programu</program>
		<verzia>1.0</verzia>
	</subor>
	<respondent>
		<ico>99999999</ico>
		<nazov>Testovacia firma 1000</nazov>
		<ul_cis>Drobn�ho 29/3431</ul_cis>
		<obec>Bratislava - mestsk� �as� D�bravka</obec>
		<psc>84407</psc>
		<PF>30</PF>
		<PFP>29</PFP>
		<PFS>1</PFS>
		<PP>29</PP>
		<PU>0</PU>
		<PUP>0</PUP>
		<T>45201</T>
		<TP>44679</TP>
		<TS>522</TS>
		<TU>0</TU>
		<TCP>45201</TCP>
		<TPP>44679</TPP>
		<TSP>522</TSP>
		<TUP>0</TUP>
		<MZ>199689</MZ>
		<MR>199689</MR>
		<MN>0</MN>
		<MOC>8050</MOC>
		<MOM>8050</MOM>
		<MOD>0</MOD>
		<MP>0</MP>
		<MA>0</MA>
		<MO>0</MO>
		<MZDY>207739</MZDY>
		<N>26464</N>
		<NV>0</NV>
		<ND>20126</ND>
		<NP>2688</NP>
		<NS>3650</NS>
		<SP>0</SP>
		<PH>0</PH>
		<PLZ>0</PLZ>
		<OPN>0</OPN>
		<VV>0</VV>
		<FV>0</FV>
		<FB>0</FB>
		<PPA>0</PPA>
		<OU>0</OU>
		<PNPS>234203</PNPS>
		<PZP>69126</PZP>
		<PDP>32979</PDP>
		<PIP>6610</PIP>
		<PUS>1886</PUS>
		<PNP>3096</PNP>
		<PZDP>21802</PZDP>
		<PPN>2171</PPN>
		<PGP>582</PGP>
		<DSP>60</DSP>
		<DDP>60</DDP>
		<SD>1920</SD>
		<ODS>607</ODS>
		<NPN>1313</NPN>
		<DMV>0</DMV>
		<DZ>0</DZ>
		<NUC>0</NUC>
		<SVS>0</SVS>
		<ST>0</ST>
		<OPZ>0</OPZ>
		<PSO>0</PSO>
		<NSVP>314054</NSVP>
		<NSZ>92</NSZ>
		<PSS>0</PSS>
		<ONN>0</ONN>
		<NBZ>0</NBZ>
		<NNPS>79943</NNPS>
		<SVP>0</SVP>
		<CNP>314146</CNP>
		<ZZS>0</ZZS>
	</respondent>
</unp>

);
mzdprc;

$soubor = fopen("$nazsub", "a+");

//hlavicka
$sqltt = "SELECT * FROM F$kli_vxcf"."_statistika_unp101 WHERE ico=0 ";

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

  $text = "<unp xsi:schemaLocation=\"http://www.trexima.sk/xsd/unp/1 unp.xsd\" 
          xmlns=\"http://www.trexima.sk/xsd/unp/1\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">"."\r\n"; fwrite($soubor, $text);



  $text = "</unp>"."\r\n"; fwrite($soubor, $text);
}
//koniec ak j=0

}
$i = $i + 1;
$j = $j + 1;
  }


fclose($soubor);
?>
<div id="content">
<?php if ( $elsubor == 2 ) { ?>
<p>
<p>Stiahnite si ni��ie uveden� s�bor <strong>.xml</strong> do V�ho po��ta�a a na��tajte ho na
<a href="https://zbery.trexima.sk" target="_blank" title="Str�nka Trexima">https://zbery.trexima.sk</a>:
</p>
<p>
<a href="<?php echo $nazsub; ?>"><?php echo $nazsub; ?></a>
</p>

<?php                      } ?>

<?php
/////////////////////////////////////////////////////////////////////UPOZORNENIE
$upozorni1=0; $upozorni2=0; $upozorni3=0; $upozorni4=0;
?>
<div id="upozornenie" style="display:none;">
<h2>
<strong class="toleft">Upozornenie</strong>
<dl class="toright legend-area">
 <dt class="toleft box-red"></dt><dd class="toleft">kritick�</dd>
 <dt class="toleft box-orange"></dt><dd class="toleft">logick�</dd>
</dl>
</h2>
<ul id="alertpage1" style="display:none;">
<li class="header-section">STRANA 1</li>
<li class="red">
<?php if ( $hlavicka->zoo == '0000-00-00' )
{
$upozorni1=1;
echo "Nie je vyplnen� <strong>zda�ovacie obdobie</strong> da�ov�ho priznania.";
}
?>
</li>
</ul>

<ul id="alertpage3" style="display:none;">
<li class="header-section">Pr�loha za vozidl�</li>
<?php
$sqlttw = "SELECT * FROM F$kli_vxcf"."_uctpriznanie_dmv WHERE F$kli_vxcf"."_uctpriznanie_dmv.oc = 1 ORDER BY vzspz";
$sqlw = mysql_query("$sqlttw");
$polw = mysql_num_rows($sqlw);
$iw=0; 
  while ( $iw < $polw )
  {
@$zaznam=mysql_data_seek($sqlw,$iw);
$hlavickaw=mysql_fetch_object($sqlw);
$wrongprm=0;
$text3=0;
if ( $hlavickaw->vzkat == 'M' AND $hlavickaw->vzobm == 0 ) { $wrongprm=1; $text3=1;}
if ( $hlavickaw->vzkat == 'L' AND $hlavickaw->vzobm == 0 ) { $wrongprm=1; $text3=1;}

if ( $hlavickaw->vzkat == 'N' AND ( $hlavickaw->vzchm == 0 OR $hlavickaw->vznpr == 0 )) { $wrongprm=1; $text3=2;}
if ( $hlavickaw->vzkat == 'O' AND ( $hlavickaw->vzchm == 0 OR $hlavickaw->vznpr == 0 )) { $wrongprm=1; $text3=2;}

if ( $hlavickaw->vzkat == 'N' AND $hlavickaw->vzobm != 0 ) { $wrongprm=1; $text3=3;}
if ( $hlavickaw->vzkat == 'O' AND $hlavickaw->vzobm != 0 ) { $wrongprm=1; $text3=3;}

if ( $hlavickaw->vzkat == 'M' AND ( $hlavickaw->vzchm != 0 OR $hlavickaw->vznpr != 0 )) { $wrongprm=1; $text3=4;}
if ( $hlavickaw->vzkat == 'L' AND ( $hlavickaw->vzchm != 0 OR $hlavickaw->vznpr != 0 )) { $wrongprm=1; $text3=4;}

if ( $hlavickaw->vzvyk != 0 AND $hlavickaw->r15s1zni50a != 1 ) { $wrongprm=1; $text3=5;}
if ( $wrongprm == 1 )
      {
?>
<li class="red">
<?php
$upozorni3=1;
if ( $text3 == 1 )
         {
echo "<strong>Vozidlo $hlavickaw->vzspz </strong> pri kateg�rii L,M vypl�te zdvihov� objem.";
         }
if ( $text3 == 2 )
         {
echo "<strong>Vozidlo $hlavickaw->vzspz </strong> pri kateg�rii N,O vypl�te hmotnos� a  po�et n�prav.";
         }
if ( $text3 == 3 )
         {
echo "<strong>Vozidlo $hlavickaw->vzspz </strong> pri kateg�rii N,O nevypl�ujte zdvihov� objem.";
         }
if ( $text3 == 4 )
         {
echo "<strong>Vozidlo $hlavickaw->vzspz </strong> pri kateg�rii L,M nevypl�ujte hmotnos� a  po�et n�prav.";
         }
if ( $text3 == 5 )
         {
echo "<strong>Vozidlo $hlavickaw->vzspz </strong> v�kon motora v kW vyp��ajte len u hybridn�ch alebo elektro vozidiel.";
         }
?>
</li>
<?php
      }
$iw = $iw + 1;
  }
?>
</ul>
</div> <!-- #upozornenie -->

<script type="text/javascript">
<?php
if ( $upozorni1 == 1 OR $upozorni2 == 1 OR $upozorni3 == 1 OR $upozorni4 == 1 )
     { echo "upozornenie.style.display='block';"; }
if ( $upozorni1 == 1 ) { echo "alertpage1.style.display='block';"; } 
if ( $upozorni4 == 1 ) { echo "alertpage4.style.display='block';"; } 
if ( $upozorni3 == 1 ) { echo "alertpage3.style.display='block';"; }
?>
</script>

</div> <!-- #content -->
<?php
//mysql_free_result($vysledok);
    }
/////////////////////////////////////////////////////koniec VYTVORENIE XML SUBORU PRE ELEKTRONIKU


$sqlt = 'DROP TABLE F'.$kli_vxcf.'_prcprizdphsx'.$kli_uzid;
$vysledok = mysql_query("$sqlt");


//celkovy koniec dokumentu
       } while (false);
?>
</BODY>
</HTML>