<HTML>
<?php
$sys = 'UCT';
$urov = 1000;
$uziv = include("../uziv.php");
if( !$uziv ) exit;

if(!isset($kli_vxcf)) $kli_vxcf = 1;
if(!isset($kli_vduj)) $kli_vduj = 1;

//od 1.1.2018 nov� �trukt�ra CSV FIN 404

       do
       {

// cislo operacie
$copern = 1*$_REQUEST['copern'];
$drupoh = 1*$_REQUEST['drupoh'];

require_once("../pswd/password.php");
@$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);

$citfir = include("../cis/citaj_fir.php");
$mena1 = $fir_mena1;
$mena2 = $fir_mena2;
$kurz12 = $fir_kurz12;

$rozuct = $_REQUEST['rozuct'];
if(!isset($rozuct)) $rozuct = 'NIE';
$sysx = $_REQUEST['sysx'];
if(!isset($sysx)) $sysx = 'INE';
if( $sysx == 'UCT' ) $rozuct="ANO";

$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];


$fir_ficox=$fir_fico;
if( $fir_fico < 100000 ) $fir_ficox="00".$fir_fico;
$mesiac="03";
$typorg="31";
$cislo_oc=1;

$cislo_oc = 1*$_REQUEST['cislo_oc'];
if( $cislo_oc == 1 ) { $mesiac="01"; }
if( $cislo_oc == 2 ) { $mesiac="02"; }
if( $cislo_oc == 3 ) { $mesiac="03"; }
if( $cislo_oc == 4 ) { $mesiac="04"; }
if( $cislo_oc == 5 ) { $mesiac="05"; }
if( $cislo_oc == 6 ) { $mesiac="06"; }
if( $cislo_oc == 7 ) { $mesiac="07"; }
if( $cislo_oc == 8 ) { $mesiac="08"; }
if( $cislo_oc == 9 ) { $mesiac="09"; }
if( $cislo_oc == 10 ) { $mesiac="10"; }
if( $cislo_oc == 11 ) { $mesiac="11"; }
if( $cislo_oc == 12 ) { $mesiac="12"; }

if( $fir_fico == 44551142 )
{
$fir_ficox="00310000";
$typorg="22";
}

$dat_bez = Date ("Ymdhi", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))); 
$fico8=$fir_fico;
if( $fico8 < 999999 ) { $fico8="00".$fico8; }
$rokmes=$kli_vmes.$kli_vrok;

//nazov
$nazsub="FIN4_".$dat_bez.".csv";

if (File_Exists ("../tmp/$nazsub")) { $soubor = unlink("../tmp/$nazsub"); }


//////////////////////////////////////////////////////////// FIN 404



//urob csv
$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];
$kli_vxr=substr($kli_vrok,2,2);;
if( $kli_vmes < 10 ) $kli_vmes = ""."0".$kli_vmes;

$dat_bez = Date ("Ymdhi", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))); 
$fico8=$fir_fico;
if( $fico8 < 999999 ) { $fico8="00".$fico8; }
$rokmes=$kli_vmes.$kli_vrok;

$soubor = fopen("../tmp/$nazsub", "a+");

$dotaz = "SELECT * FROM F$kli_vxcf"."_uctvykaz_fin404 WHERE F$kli_vxcf"."_uctvykaz_fin404.oc = $cislo_oc  ORDER BY oc";

$sql = mysql_query("$dotaz");
$pol = mysql_num_rows($sql);
//exit;


$i=0;
  while ($i <= $pol )
  {


  if (@$zaznam=mysql_data_seek($sql,$i))
{
$hlavicka=mysql_fetch_object($sql);


//hlavicka pasiva

  $text = "\r\n";
  fwrite($soubor, $text);

  $text = "\"hlavicka,1\""."\r\n";
  fwrite($soubor, $text);

  $text = "\"ico\","."\"rok\","."\"mesiac\""."\r\n";
  fwrite($soubor, $text);

  $text = "\"".$fico8."\","."\"".$kli_vrok."\","."\"".$kli_vmes."\""."\r\n";
  fwrite($soubor, $text);

  $text = "\r\n";
  fwrite($soubor, $text);

  $text = "\"vybrane-pasiva,50\""."\r\n";
  fwrite($soubor, $text);

  $text = "\"R\","."\"S1\","."\"S2\","."\"S3\","."\"S4\","."\"S5\",".
"\"S6\","."\"S7\","."\"S8\","."\"S9\","."\"S10\",";

  $text = $text."\"S11\","."\"S12\","."\"S13\","."\"S14\","."\"S15\",".
"\"S16\","."\"S17\","."\"S18\","."\"S19\","."\"S20\",";

  $text = $text."\"S21\","."\"S22\","."\"S23\","."\"S24\","."\"S25\",".
"\"S26\","."\"S27\","."\"S28\"";

  $text = $text."\r\n";


  fwrite($soubor, $text);


//polozky 


  $text = "\"R1\",\"".$hlavicka->pocs01."\",\"".$hlavicka->pocs02."\",\"".$hlavicka->pocs03."\",\"".$hlavicka->pocs04.
"\",,,\"".$hlavicka->pocs07.
"\",\"".$hlavicka->pocs08."\",\"".$hlavicka->pocs09."\",\"".$hlavicka->pocs10."\",";

  $text = $text."\"".$hlavicka->pocs11."\",\"".$hlavicka->pocs12."\",\"".$hlavicka->pocs13."\",\"".$hlavicka->pocs14.
"\",\"".$hlavicka->pocs15."\",\"".$hlavicka->pocs16."\",\"".$hlavicka->pocs17.
"\",\"".$hlavicka->pocs18."\",\"".$hlavicka->pocs19."\",\"".$hlavicka->pocs20."\",";

  $text = $text."\"".$hlavicka->pocs21."\",\"".$hlavicka->pocs22."\",\"".$hlavicka->pocs23."\",\"".$hlavicka->pocs24.
"\",\"".$hlavicka->pocs25."\",\"".$hlavicka->pocs26."\",\"".$hlavicka->pocs27."\",\"".$hlavicka->pocs28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



//bez 5,6,11,12,13,14,15,16

  $text = "\"R2\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R3\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R4\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R5\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r6,7,8,9 bez stlpcov 5,6,11,12,13,14,15,16,19,20

  $text = "\"R6\",\"".$hlavicka->pocs01."\",\"".$hlavicka->pocs02."\",\"".$hlavicka->pocs03."\",\"".$hlavicka->pocs04.
"\",,,\"".$hlavicka->pocs07.
"\",\"".$hlavicka->pocs08."\",\"".$hlavicka->pocs09."\",\"".$hlavicka->pocs10."\",";

  $text = $text."\"".$hlavicka->pocs11."\",\"".$hlavicka->pocs12."\",\"".$hlavicka->pocs13."\",\"".$hlavicka->pocs14.
"\",\"".$hlavicka->pocs15."\",\"".$hlavicka->pocs16."\",\"".$hlavicka->pocs17.
"\",\"".$hlavicka->pocs18."\",,,";

  $text = $text."\"".$hlavicka->pocs21."\",\"".$hlavicka->pocs22."\",\"".$hlavicka->pocs23."\",\"".$hlavicka->pocs24.
"\",\"".$hlavicka->pocs25."\",\"".$hlavicka->pocs26."\",\"".$hlavicka->pocs27."\",\"".$hlavicka->pocs28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R7\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R8\",\"".$hlavicka->pocs01."\",\"".$hlavicka->pocs02."\",\"".$hlavicka->pocs03."\",\"".$hlavicka->pocs04.
"\",,,\"".$hlavicka->pocs07.
"\",\"".$hlavicka->pocs08."\",\"".$hlavicka->pocs09."\",\"".$hlavicka->pocs10."\",,,,,,,\"".$hlavicka->pocs17.
"\",\"".$hlavicka->pocs18."\",,,";

  $text = $text."\"".$hlavicka->pocs21."\",\"".$hlavicka->pocs22."\",\"".$hlavicka->pocs23."\",\"".$hlavicka->pocs24.
"\",\"".$hlavicka->pocs25."\",\"".$hlavicka->pocs26."\",\"".$hlavicka->pocs27."\",\"".$hlavicka->pocs28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R9\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r10 je riadok 17 v CSV

  $text = "\"R10\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R11\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R12\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R13\",\"".$hlavicka->zvys01."\",\"".$hlavicka->zvys02."\",\"".$hlavicka->zvys03."\",\"".$hlavicka->zvys04.
"\",,,\"".$hlavicka->zvys07.
"\",\"".$hlavicka->zvys08."\",\"".$hlavicka->zvys09."\",\"".$hlavicka->zvys10."\",";

  $text = $text."\"".$hlavicka->zvys11."\",\"".$hlavicka->zvys12."\",\"".$hlavicka->zvys13."\",\"".$hlavicka->zvys14.
"\",\"".$hlavicka->zvys15."\",\"".$hlavicka->zvys16."\",\"".$hlavicka->zvys17.
"\",\"".$hlavicka->zvys18."\",\"".$hlavicka->zvys19."\",\"".$hlavicka->zvys20."\",";

  $text = $text."\"".$hlavicka->zvys21."\",\"".$hlavicka->zvys22."\",\"".$hlavicka->zvys23."\",\"".$hlavicka->zvys24.
"\",\"".$hlavicka->zvys25."\",\"".$hlavicka->zvys26."\",\"".$hlavicka->zvys27."\",\"".$hlavicka->zvys28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r14 je riadok 21 v CSV

  $text = "\"R14\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R15\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R16\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R17\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r18 je riadok 25 v CSV

  $text = "\"R18\",\"".$hlavicka->zvys01."\",\"".$hlavicka->zvys02."\",\"".$hlavicka->zvys03."\",\"".$hlavicka->zvys04.
"\",,,\"".$hlavicka->zvys07.
"\",\"".$hlavicka->zvys08."\",\"".$hlavicka->zvys09."\",\"".$hlavicka->zvys10."\",";

  $text = $text."\"".$hlavicka->zvys11."\",\"".$hlavicka->zvys12."\",\"".$hlavicka->zvys13."\",\"".$hlavicka->zvys14.
"\",\"".$hlavicka->zvys15."\",\"".$hlavicka->zvys16."\",\"".$hlavicka->zvys17.
"\",\"".$hlavicka->zvys18."\",,,";

  $text = $text."\"".$hlavicka->zvys21."\",\"".$hlavicka->zvys22."\",\"".$hlavicka->zvys23."\",\"".$hlavicka->zvys24.
"\",\"".$hlavicka->zvys25."\",\"".$hlavicka->zvys26."\",\"".$hlavicka->zvys27."\",\"".$hlavicka->zvys28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R19\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r20 je riadok 27 v CSV

  $text = "\"R20\",\"".$hlavicka->zvys01."\",\"".$hlavicka->zvys02."\",\"".$hlavicka->zvys03."\",\"".$hlavicka->zvys04.
"\",,,\"".$hlavicka->zvys07.
"\",\"".$hlavicka->zvys08."\",\"".$hlavicka->zvys09."\",\"".$hlavicka->zvys10."\",";

  $text = $text.",,,,,,\"".$hlavicka->zvys17.
"\",\"".$hlavicka->zvys18."\",,,";

  $text = $text."\"".$hlavicka->zvys21."\",\"".$hlavicka->zvys22."\",\"".$hlavicka->zvys23."\",\"".$hlavicka->zvys24.
"\",\"".$hlavicka->zvys25."\",\"".$hlavicka->zvys26."\",\"".$hlavicka->zvys27."\",\"".$hlavicka->zvys28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R21\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R22\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R23\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R24\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R25\",\"".$hlavicka->znis01."\",\"".$hlavicka->znis02."\",\"".$hlavicka->znis03."\",\"".$hlavicka->znis04.
"\",,,\"".$hlavicka->znis07.
"\",\"".$hlavicka->znis08."\",\"".$hlavicka->znis09."\",\"".$hlavicka->znis10."\",";

  $text = $text."\"".$hlavicka->znis11."\",\"".$hlavicka->znis12."\",\"".$hlavicka->znis13."\",\"".$hlavicka->znis14.
"\",\"".$hlavicka->znis15."\",\"".$hlavicka->znis16."\",\"".$hlavicka->znis17.
"\",\"".$hlavicka->znis18."\",\"".$hlavicka->znis19."\",\"".$hlavicka->znis20."\",";

  $text = $text."\"".$hlavicka->znis21."\",\"".$hlavicka->znis22."\",\"".$hlavicka->znis23."\",\"".$hlavicka->znis24.
"\",\"".$hlavicka->znis25."\",\"".$hlavicka->znis26."\",\"".$hlavicka->znis27."\",\"".$hlavicka->znis28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//r26 je riadok 33 v CSV

  $text = "\"R26\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R27\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R28\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R29\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



//R30 je riadok 37. v CSV 

  $text = "\"R30\",\"".$hlavicka->znis01."\",\"".$hlavicka->znis02."\",\"".$hlavicka->znis03."\",\"".$hlavicka->znis04.
"\",,,\"".$hlavicka->znis07.
"\",\"".$hlavicka->znis08."\",\"".$hlavicka->znis09."\",\"".$hlavicka->znis10."\",";

  $text = $text."\"".$hlavicka->znis11."\",\"".$hlavicka->znis12."\",\"".$hlavicka->znis13."\",\"".$hlavicka->znis14.
"\",\"".$hlavicka->znis15."\",\"".$hlavicka->znis16."\",\"".$hlavicka->znis17.
"\",\"".$hlavicka->znis18."\",,,";

  $text = $text."\"".$hlavicka->znis21."\",\"".$hlavicka->znis22."\",\"".$hlavicka->znis23."\",\"".$hlavicka->znis24.
"\",\"".$hlavicka->znis25."\",\"".$hlavicka->znis26."\",\"".$hlavicka->znis27."\",\"".$hlavicka->znis28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R31\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R32\",\"".$hlavicka->znis01."\",\"".$hlavicka->znis02."\",\"".$hlavicka->znis03."\",\"".$hlavicka->znis04.
"\",,,\"".$hlavicka->znis07.
"\",\"".$hlavicka->znis08."\",\"".$hlavicka->znis09."\",\"".$hlavicka->znis10."\",";

  $text = $text.",,,,,,\"".$hlavicka->znis17.
"\",\"".$hlavicka->znis18."\",,,";

  $text = $text."\"".$hlavicka->znis21."\",\"".$hlavicka->znis22."\",\"".$hlavicka->znis23."\",\"".$hlavicka->znis24.
"\",\"".$hlavicka->znis25."\",\"".$hlavicka->znis26."\",\"".$hlavicka->znis27."\",\"".$hlavicka->znis28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R33\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R34\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R35\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R36\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R37\",\"".$hlavicka->oces01."\",\"".$hlavicka->oces02."\",\"".$hlavicka->oces03."\",\"".$hlavicka->oces04.
"\",\"".$hlavicka->oces05."\",\"".$hlavicka->oces06."\",\"".$hlavicka->oces07.
"\",\"".$hlavicka->oces08."\",\"".$hlavicka->oces09."\",\"".$hlavicka->oces10."\",";

  $text = $text."\"".$hlavicka->oces11."\",\"".$hlavicka->oces12."\",\"".$hlavicka->oces13."\",\"".$hlavicka->oces14.
"\",\"".$hlavicka->oces15."\",\"".$hlavicka->oces16."\",\"".$hlavicka->oces17.
"\",\"".$hlavicka->oces18."\",\"".$hlavicka->oces19."\",\"".$hlavicka->oces20."\",";

  $text = $text."\"".$hlavicka->oces21."\",\"".$hlavicka->oces22."\",\"".$hlavicka->oces23."\",\"".$hlavicka->oces24.
"\",\"".$hlavicka->oces25."\",\"".$hlavicka->oces26."\",\"".$hlavicka->oces27."\",\"".$hlavicka->oces28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R38\",\"".$hlavicka->osts01."\",\"".$hlavicka->osts02."\",\"".$hlavicka->osts03."\",\"".$hlavicka->osts04.
"\",\"".$hlavicka->osts05."\",\"".$hlavicka->osts06."\",\"".$hlavicka->osts07.
"\",\"".$hlavicka->osts08."\",\"".$hlavicka->osts09."\",\"".$hlavicka->osts10."\",";

  $text = $text."\"".$hlavicka->osts11."\",\"".$hlavicka->osts12."\",\"".$hlavicka->osts13."\",\"".$hlavicka->osts14.
"\",\"".$hlavicka->osts15."\",\"".$hlavicka->osts16."\",\"".$hlavicka->osts17.
"\",\"".$hlavicka->osts18."\",\"".$hlavicka->osts19."\",\"".$hlavicka->osts20."\",";

  $text = $text."\"".$hlavicka->osts21."\",\"".$hlavicka->osts22."\",\"".$hlavicka->osts23."\",\"".$hlavicka->osts24.
"\",\"".$hlavicka->osts25."\",\"".$hlavicka->osts26."\",\"".$hlavicka->osts27."\",\"".$hlavicka->osts28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



  $text = "\"R39\",\"".$hlavicka->zoss01."\",\"".$hlavicka->zoss02."\",\"".$hlavicka->zoss03."\",\"".$hlavicka->zoss04.
"\",,,\"".$hlavicka->zoss07.
"\",\"".$hlavicka->zoss08."\",\"".$hlavicka->zoss09."\",\"".$hlavicka->zoss10."\",";

  $text = $text."\"".$hlavicka->zoss11."\",\"".$hlavicka->zoss12."\",\"".$hlavicka->zoss13."\",\"".$hlavicka->zoss14.
"\",\"".$hlavicka->zoss15."\",\"".$hlavicka->zoss16."\",\"".$hlavicka->zoss17.
"\",\"".$hlavicka->zoss18."\",\"".$hlavicka->zoss19."\",\"".$hlavicka->zoss20."\",";

  $text = $text."\"".$hlavicka->zoss21."\",\"".$hlavicka->zoss22."\",\"".$hlavicka->zoss23."\",\"".$hlavicka->zoss24.
"\",\"".$hlavicka->zoss25."\",\"".$hlavicka->zoss26."\",\"".$hlavicka->zoss27."\",\"".$hlavicka->zoss28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


//R40 je riadok 47. v CSV 

  $text = "\"R40\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R41\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R42\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R43\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);

//R44 je riadok 51. v CSV 

  $text = "\"R44\",\"".$hlavicka->zoss01."\",\"".$hlavicka->zoss02."\",\"".$hlavicka->zoss03."\",\"".$hlavicka->zoss04.
"\",,,\"".$hlavicka->zoss07.
"\",\"".$hlavicka->zoss08."\",\"".$hlavicka->zoss09."\",\"".$hlavicka->zoss10."\",";

  $text = $text."\"".$hlavicka->zoss11."\",\"".$hlavicka->zoss12."\",\"".$hlavicka->zoss13."\",\"".$hlavicka->zoss14.
"\",\"".$hlavicka->zoss15."\",\"".$hlavicka->zoss16."\",\"".$hlavicka->zoss17.
"\",\"".$hlavicka->zoss18."\",,,";

  $text = $text."\"".$hlavicka->zoss21."\",\"".$hlavicka->zoss22."\",\"".$hlavicka->zoss23."\",\"".$hlavicka->zoss24.
"\",\"".$hlavicka->zoss25."\",\"".$hlavicka->zoss26."\",\"".$hlavicka->zoss27."\",\"".$hlavicka->zoss28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R45\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R46\",\"".$hlavicka->zoss01."\",\"".$hlavicka->zoss02."\",\"".$hlavicka->zoss03."\",\"".$hlavicka->zoss04.
"\",,,\"".$hlavicka->zoss07.
"\",\"".$hlavicka->zoss08."\",\"".$hlavicka->zoss09."\",\"".$hlavicka->zoss10."\",";

  $text = $text.",,,,,,\"".$hlavicka->zoss17.
"\",\"".$hlavicka->zoss18."\",,,";

  $text = $text."\"".$hlavicka->zoss21."\",\"".$hlavicka->zoss22."\",\"".$hlavicka->zoss23."\",\"".$hlavicka->zoss24.
"\",\"".$hlavicka->zoss25."\",\"".$hlavicka->zoss26."\",\"".$hlavicka->zoss27."\",\"".$hlavicka->zoss28."\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R47\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R48\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R49\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);


  $text = "\"R50\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".",".
","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",";

  $text = $text.",".",".",".",".",".
","."\"0.00\","."\"0.00\",".",".",";

  $text = $text."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\","."\"0.00\",".
"\"0.00\","."\"0.00\","."\"0.00\"";

  $text = $text."\r\n";

  fwrite($soubor, $text);



}
$i = $i + 1;
  }





fclose($soubor);
////////////////////////////////////////////////////////////KONIEC FIN 404


?> 

<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
  <link type="text/css" rel="stylesheet" href="../css/styl.css">
<title>CSV</title>
  <style type="text/css">
  </style>
<script type="text/javascript">   
</script>
</HEAD>
<BODY class="white">


<table class="h2" width="100%" >
<tr>
<td>EuroSecom  -  FIN 4-04 CSV s�bor FIN4.csv</td>
<td align="right"><span class="login"><?php echo "UME $kli_vume FIR$kli_vxcf-$kli_nxcf  login: $kli_uzmeno $kli_uzprie / $kli_uzid ";?></span></td>
</tr>
</table>
<br />


<?php
if( $copern == 1 )
{
?>
<br />
<br />
Stiahnite si ni��ie uveden� s�bor na V� lok�lny disk a ulo�te ho s n�zvom FIN4.csv :
<br />
<br />
<a href="../tmp/<?php echo $nazsub; ?>"><?php echo $nazsub; ?></a>
<br />
<br />
<?php
}
?>




<br /><br />
<?php
// celkovy koniec dokumentu



       } while (false);
?>
</BODY>
</HTML>
