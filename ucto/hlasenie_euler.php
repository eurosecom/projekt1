<!doctype html>
<HTML>
<?php
do
{
$sys = 'UCT';
$urov = 3000;
$copern = $_REQUEST['copern'];
$tis = $_REQUEST['tis'];
if (!isset($tis)) $tis = 0;

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

$rokdmv=2015;
if ( $kli_vrok > 2015 ) { $rokdmv=2015; }

$citfir = include("../cis/citaj_fir.php");

$cislo_oc = 9999;
$subor = $_REQUEST['subor'];
$strana = 1*$_REQUEST['strana'];
if ( $strana == 0 ) $strana=1;
$zoznamaut=1;


$xml = 1*$_REQUEST['xml'];
$cislo_ico = 1*$_REQUEST['cislo_ico'];
$cislo_cpl = 1*$_REQUEST['cislo_cpl'];

//ramcek fpdf 1=zap,0=vyp
$rmc=0;
$rmc1=0;

//.jpg podklad
$jpg_cesta="../dokumenty/statistika2016/hlasenie_pohladavok/hlasenie_euler";
$jpg_popis="tla�ivo Hl�senie poh�ad�vok po splatnosti Euler Hermes za rok ".$kli_vrok;

//pridaj Hla 
    if ( $copern == 416 )
    {
$cislo_ico = $_REQUEST['cislo_ico'];

$nahlx=0;
$sqlttt = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE ico = $cislo_ico ORDER BY ico LIMIT 1";
$sqldok = mysql_query("$sqlttt");
 if (@$zaznam=mysql_data_seek($sqldok,0))
 {
 $riaddok=mysql_fetch_object($sqldok);
 $nahlx=1*$riaddok->nahl;
 }

if( $nahlx == 0 )
  {
$sqtoz = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET nahl=1 WHERE ico = $cislo_ico ";
$oznac = mysql_query("$sqtoz");
  }
if( $nahlx == 1 )
  {
$sqtoz = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET nahl=0 WHERE ico = $cislo_ico ";
$oznac = mysql_query("$sqtoz");
  }

$copern=20;
$strana=5;
$zoznamaut=1;
    }
//pridaj Hla

//nacitanie zo salda
    if ( $copern == 3155 ) { ?>
<script type="text/javascript">
if( !confirm ("Chcete na��ta� �daje zo saldokonta ?") )
         { window.close() }
else
         { location.href='hlasenie_euler.php?copern=3156&page=1&drupoh=1&cislo_oc=<?php echo $cislo_oc; ?>' }
</script>
<?php                      }

    if ( $copern == 3156 )
    {


//http://localhost/ucto/saldo_alchem.php?pol=1&dea=0&h_uce=31100&h_ico=&h_obd=0&h_spl=0&h_dsp=26.04.2016&copern=11&drupoh=1&page=1&analyzy=0&pol30=1

$sqlt = "DROP TABLE F".$kli_vxcf."_ucthlasenie_eulerfak".$kli_uzid;
$vysledok = mysql_query("$sqlt");

$sqlt = <<<prsaldo
(
   cplf         int not null auto_increment,
   pox1        INT,
   pox2        INT,
   pox         INT,
   drupoh      INT,
   uce         VARCHAR(10),
   puc         DECIMAL(10,0),
   ume         FLOAT(8,4),
   dat         DATE,
   das         DATE,
   daz         DATE,
   dok         INT(8),
   ico         DECIMAL(10,0),
   fak         DECIMAL(10,0),
   poz         VARCHAR(80),
   ksy         VARCHAR(10),
   ssy         DECIMAL(10,0),
   hdp         DECIMAL(10,2),
   hdu         DECIMAL(10,2),
   hod         DECIMAL(10,2),
   uhr         DECIMAL(10,2),
   zos         DECIMAL(10,2),
   dau         DATE,
   PRIMARY KEY(cplf)
);
prsaldo;

$vsql = "CREATE TABLE F".$kli_vxcf."_ucthlasenie_eulerfak".$kli_uzid.$sqlt;
$vytvor = mysql_query("$vsql");



$dsqlt = "INSERT INTO F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid".
" SELECT 0,0,0,1,drupoh,uce,0,ume,dat,das,daz,dok,ico,fak,".
"poz,ksy,ssy,SUM(hdp),SUM(hdu),SUM(hod),SUM(uhr),SUM(zos),dau".
" FROM F$kli_vxcf"."_prsaldoicofaknesp$kli_uzid".
" WHERE LEFT(uce,3) = 311 ".
" GROUP BY uce,ico,fak";
$dsql = mysql_query("$dsqlt");


$tovtt = "DELETE FROM F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid WHERE zos <= 0 ";
$tov = mysql_query("$tovtt");

$dnes = Date ("Y-m-d", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

$sqtoz = "UPDATE F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid SET puc=TO_DAYS('$dnes')-TO_DAYS(das) WHERE hod != 0";
$oznac = mysql_query("$sqtoz");

$sqtoz = "UPDATE F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid SET puc=0 WHERE puc < 0 ";
//$oznac = mysql_query("$sqtoz");

$tovtt = "DELETE FROM F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid WHERE dat < '2016-02-01' ";
//$tov = mysql_query("$tovtt");

$tovtt = "DELETE FROM F$kli_vxcf"."_ucthlasenie_eulerfak$kli_uzid WHERE puc < 30 ";
//$tov = mysql_query("$tovtt");

$sqlt = "DROP TABLE F".$kli_vxcf."_ucthlasenie_eulerfak";
$vysledok = mysql_query("$sqlt");

$vsql = "CREATE TABLE F".$kli_vxcf."_ucthlasenie_eulerfak SELECT * FROM F".$kli_vxcf."_ucthlasenie_eulerfak".$kli_uzid;
$vytvor = mysql_query("$vsql");

$dsqlt = "UPDATE F$kli_vxcf"."_ucthlasenie_eulerfak SET hdp=zos/1.2 ";
$dsql = mysql_query("$dsqlt");

$sqlt = "DROP TABLE F".$kli_vxcf."_ucthlasenie_eulerfak".$kli_uzid;
$vysledok = mysql_query("$sqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET dsuma=0, dbdph=0 ";
$dsql = mysql_query("$dsqlt");

$sqlttt = "SELECT SUM(zos) AS sumzos, SUM(hdp) AS sumhdp, ico FROM F$kli_vxcf"."_ucthlasenie_eulerfak GROUP BY ico ";
$sql = mysql_query("$sqlttt");
$cpol = mysql_num_rows($sql);
$i=0;

while ($i <= $cpol )
{
  if (@$zaznam=mysql_data_seek($sql,$i))
    {
  $riadok=mysql_fetch_object($sql);

$jeico=0;
$sqltt2 = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE ico = $riadok->ico ORDER BY ico DESC LIMIT 1"; 
$sqldo2 = mysql_query("$sqltt2");
 if (@$zaznam=mysql_data_seek($sqldo2,0))
 {
 $riaddo2=mysql_fetch_object($sqldo2);
 $jeico=1;
 }

$sqli = "INSERT INTO F".$kli_vxcf."_ucthlasenie_euler (oc, ico, konx1) VALUES ( 1, '$riadok->ico', 0 ) ";
if( $jeico == 0 ) { $vysledok = mysql_query($sqli); }

$dsqlt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET dsuma='$riadok->sumzos', dbdph='$riadok->sumhdp' WHERE ico = $riadok->ico ";
$dsql = mysql_query("$dsqlt");



    }
$i=$i+1;
}

$dsqlt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET dath='$dnes', nahl=0 ";
$dsql = mysql_query("$dsqlt");



//echo $dsqlt; 
//exit;


$copern=20;
$strana=5;
    }
//nacitanie zo salda

//uprav 
    if ( $copern == 346 )
    {

$copern=20;
$zoznamaut=0;
    }
//koniec uprav 

//nove 
    if ( $copern == 336 )
    {
$sql = "INSERT INTO F".$kli_vxcf."_ucthlasenie_euler (oc,konx1) VALUES ( 1, 0 ) ";
$vysledok = mysql_query($sql);

$cislo_cpl=0;
$sqlttt = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE oc = 1 ORDER BY cpl DESC LIMIT 1";
$sqldok = mysql_query("$sqlttt");
 if (@$zaznam=mysql_data_seek($sqldok,0))
 {
 $riaddok=mysql_fetch_object($sqldok);
 $cislo_cpl=$riaddok->cpl;
 }
$copern=20;
$strana=1;
$zoznamaut=0;
$_REQUEST['cislo_cpl']=$cislo_cpl;
    }
//koniec nove 

//zmaz 
    if ( $copern == 316 )
    {
$cislo_cpl = 1*$_REQUEST['cislo_cpl'];
$cislo_ico = $_REQUEST['cislo_ico'];
?>
<script type="text/javascript">
if( !confirm ("Chcete zmaza� i�o <?php echo $cislo_ico; ?> ?") )
         { location.href='hlasenie_euler.php?cislo_oc=9999&drupoh=1&page=1&subor=0&copern=20&strana=5' }
else
         { location.href='hlasenie_euler.php?copern=3166&page=1&drupoh=1&cislo_cpl=<?php echo $cislo_cpl; ?>' }
</script>
<?php
exit;                      
    }

    if ( $copern == 3166 )
    {

$cislo_cpl = 1*$_REQUEST['cislo_cpl'];
$sql = "DELETE FROM F".$kli_vxcf."_ucthlasenie_euler WHERE cpl = $cislo_cpl ";
$vysledok = mysql_query($sql);

$copern=20;
$strana=5;
$zoznamaut=1;
    }
//zmaz 



//zapis upravene udaje
if ( $copern == 23 )
     {
if ( $strana == 1 ) {
$mnin1 = strip_tags($_REQUEST['mnin1']);
$mnin2 = strip_tags($_REQUEST['mnin2']);
$mnin=0;
if ( $mnin1 == 1 ) { $mnin=1; }
$ico = strip_tags($_REQUEST['ico']);
$ktos = strip_tags($_REQUEST['ktos']);
$euid = strip_tags($_REQUEST['euid']);
$cislo_ico=$ico;


$uprtxt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET ".
" mnin='$mnin', ".
" ktos='$ktos', euid='$euid', ico='$ico' ".
" WHERE oc = 1 AND cpl = $cislo_cpl ";
//echo $uprtxt;


                    }

if ( $strana == 2 ) {

$povins1 = strip_tags($_REQUEST['povins1']);
$povins2 = strip_tags($_REQUEST['povins2']);
$povins=0;
if( $povins1 == 1 ) { $povins=1; }
$sumvmh = strip_tags($_REQUEST['sumvmh']);
$dovnez = strip_tags($_REQUEST['dovnez']);
$upomky = strip_tags($_REQUEST['upomky']);
$dohspl = $_REQUEST['dohspl'];
$dalinf = $_REQUEST['dalinf'];

$dath = strip_tags($_REQUEST['dath']);
$dathsql=SqlDatum($dath);


$uprtxt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler SET ".
" sumvmh='$sumvmh',  povins='$povins', dovnez='$dovnez', upomky='$upomky', ".
" dohspl='$dohspl', dalinf='$dalinf', ".
" dath='$dathsql' ".
" WHERE oc = 1 AND cpl = $cislo_cpl ";
//echo $uprtxt;

                    }



//echo $uprtxt;
$upravene = mysql_query("$uprtxt");

$copern=20;
if (!$upravene):
?>
<script type="text/javascript"> alert( "�DAJE NEBOLI UPRAVEN�" ) </script>
<?php
endif;
if ($upravene):
$uprav="OK";
endif;
     }
//koniec zapisu upravenych udajov

//vypocty

$dsqlt = "UPDATE F$kli_vxcf"."_ucthlasenie_euler,F$kli_vxcf"."_ico ".
"SET zorad=nai WHERE F$kli_vxcf"."_ucthlasenie_euler.ico=F$kli_vxcf"."_ico.ico ";
$dsql = mysql_query("$dsqlt");

//koniec vypocty


//prac.subor a subor vytvorenych rocnych
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvypl'.$kli_uzid;
$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvyplx'.$kli_uzid;
$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvyplz'.$kli_uzid;
$vysledok = mysql_query("$sqlt");
$sql = "SELECT px03 FROM F".$kli_vxcf."_ucthlasenie_euler";
$vysledok = mysql_query($sql);
if (!$vysledok)
{
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_ucthlasenie_euler';
$vysledok = mysql_query("$sqlt");

$sqlt = <<<mzdprc
(
   cpl          int not null auto_increment,
   oc           DECIMAL(10,0) DEFAULT 0,
   ico          DECIMAL(10,0) DEFAULT 0,
   druh         DECIMAL(10,0) DEFAULT 0,
   dsuma        DECIMAL(10,2) DEFAULT 0,
   konx1        DECIMAL(10,0) DEFAULT 0,
   px03         DECIMAL(10,2) DEFAULT 0,
   PRIMARY KEY(cpl)
);
mzdprc;

$vsql = 'CREATE TABLE F'.$kli_vxcf.'_ucthlasenie_euler'.$sqlt;
$vytvor = mysql_query("$vsql");

$sql = "INSERT INTO F".$kli_vxcf."_ucthlasenie_euler (oc,konx1) VALUES ( 9999, 0 ) ";
$vysledok = mysql_query($sql);
}

$sql = "SELECT dath FROM F".$kli_vxcf."_ucthlasenie_euler";
$vysledok = mysql_query($sql);
if (!$vysledok)
{
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD ktos VARCHAR(40) NOT NULL AFTER ico";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD euid DECIMAL(10,0) DEFAULT 0 AFTER ico";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD dath DATE NOT NULL AFTER ico";
$vysledek = mysql_query("$sql");
}
$sql = "SELECT dbdph FROM F".$kli_vxcf."_ucthlasenie_euler";
$vysledok = mysql_query($sql);
if (!$vysledok)
{
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD sumvmh DECIMAL(10,2) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD povins DECIMAL(2,0) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD dovnez VARCHAR(80) NOT NULL AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD upomky DECIMAL(2,0) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD dohspl DECIMAL(2,0) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD dalinf TEXT NOT NULL AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD mnin DECIMAL(2,0) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD dbdph DECIMAL(10,2) DEFAULT 0 AFTER konx1";
$vysledek = mysql_query("$sql");
}
$sql = "SELECT nahl FROM F".$kli_vxcf."_ucthlasenie_euler";
$vysledok = mysql_query($sql);
if (!$vysledok)
{
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD zorad VARCHAR(40) NOT NULL AFTER ico";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_ucthlasenie_euler ADD nahl DECIMAL(2,0) DEFAULT 0 AFTER ico";
$vysledek = mysql_query("$sql");
}
//koniec vytvorenie



//koniec uprav def. tabulky
$vsql = 'CREATE TABLE F'.$kli_vxcf.'_mzdprcvypl'.$kli_uzid." SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler";
$vytvor = mysql_query("$vsql");
$vsql = 'CREATE TABLE F'.$kli_vxcf.'_mzdprcvyplx'.$kli_uzid." SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler";
$vytvor = mysql_query("$vsql");

$vsql = 'TRUNCATE TABLE F'.$kli_vxcf.'_mzdprcvypl'.$kli_uzid." ";
$vytvor = mysql_query("$vsql");
$vsql = 'TRUNCATE TABLE F'.$kli_vxcf.'_mzdprcvyplx'.$kli_uzid." ";
$vytvor = mysql_query("$vsql");


?>

<?php
//nacitaj udaje pre upravu
if ( $copern == 20 )
     {
$sqlfir = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE oc = 1 AND cpl = $cislo_cpl ORDER BY oc";
$fir_vysledok = mysql_query($sqlfir);
$fir_riadok=mysql_fetch_object($fir_vysledok);

$cislo_ico=$fir_riadok->ico;

if ( $strana == 1 ) {
//$fir_pripoisteny="��pripoisteny";
//$fir_zmluva="��zmluva";
//$fir_kontakt="��kontakt";
//$fir_konemail="Kontakt@email.sk";
//$fir_kontel="034/1234567";
//$fir_konfax="035/7654321";
$ico = $fir_riadok->ico;
$ktos = $fir_riadok->ktos;
$euid = $fir_riadok->euid;
$mnin = $fir_riadok->mnin;
$mnin1=1;
$mnin2=0;
if ( $mnin == 0 ) {
$mnin1=0;
$mnin2=1;
                    }

//$ico_allsaldo="12346.11";
                    }

if ( $strana == 2 ) {
$dath = $fir_riadok->dath;
$dathsk=SkDatum($dath);

$sumvmh = $fir_riadok->sumvmh;
$povins = $fir_riadok->povins;
$povins1=1;
$povins2=0;
if ( $povins == 0 ) {
$povins1=0;
$povins2=1;
                    }

$dovnez = $fir_riadok->dovnez;
$upomky = $fir_riadok->upomky;
$dohspl = $fir_riadok->dohspl;
$dalinf = $fir_riadok->dalinf;
$dath = $fir_riadok->dath;


//$podpisujuci="��podpisujuci";
//$podpispozicia="��pozicia";

                    }

mysql_free_result($fir_vysledok);

$sqlfir = "SELECT * FROM F$kli_vxcf"."_ico WHERE ico = $cislo_ico";
$fir_vysledok = mysql_query($sqlfir);
$fir_riadok=mysql_fetch_object($fir_vysledok);

$ico_nai = $fir_riadok->nai;
$ico_mes = $fir_riadok->mes;
$ico_uli = $fir_riadok->uli;
$ico_psc = $fir_riadok->psc;
$ico_konemail = $fir_riadok->em1;
$ico_kontel = $fir_riadok->tel;
$ico_konfax = $fir_riadok->fax;
$ico_stat="SR";


mysql_free_result($fir_vysledok);
     }
//koniec nacitania

//6-miestne ico
$fir_ficox=$fir_fico; if ( $fir_ficox < 999999 ) { $fir_ficox="00".$fir_ficox; }

?>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
 <link rel="stylesheet" href="../css/reset.css">
 <link rel="stylesheet" href="../css/tlaciva.css">
<title>EuroSecom - Euler</title>
<style type="text/css">
div.sadzby-area {
  position: absolute;
  background-color: #ffff90;
  z-index: 100;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); /* prefixy */
  padding-bottom: 5px;
}
div.sadzby-area-heading {
  clear: both;
  overflow: auto;
  height: 36px;
}
div.sadzby-area-heading > h1 {
  font-size: 14px;
  text-transform: uppercase;
  margin-top: 14px;
  margin-left: 15px;
}
div.sadzby-area-heading > img {
  width:18px;
  height:18px;
  margin-top: 8px;
  margin-right: 8px;
  opacity: 1; /* prefixy */
  cursor: pointer;
}
div.sadzby-area-heading > img:hover {
  opacity: 0.8; /* prefixy */
}
div.sadzby-area-body {
  clear: both;
}
div.sadzby-area-body > div {
  margin-left: 15px;
}
div.sadzby-section-heading {
  font-size:14px;
  height: 14px;
  padding: 8px 0 2px 0;
  font-weight: bold;
}
table.sadzby {
  background-color: #add8e6;
  margin-right: 15px;
}
table.sadzby caption {
font-size: 14px;
font-weight: ;
text-align: left;
height: 14px;
background-color:;
padding: 8px 0 6px 0;
}
tr.odd {
  background-color: #90ccde;
}
table.sadzby tr td > a {
  height: 24px;
  line-height: 24px;
  background-color: #fff;
  color: #000;
  text-align: right;
  font-weight: bold;
  display: block;
  border-right: 3px solid #add8e6;
  border-bottom: 3px solid #add8e6;
  padding-right: 4px;
}
table.sadzby tr td > a:hover {
  background-color: #eee;
}
table.sadzby th {
  font-size: 11px;
  font-weight: normal;
  padding-top: 3px;
  line-height: 15px;
}
table.sadzby td {
  font-size: 12px;
  text-align: center;
  line-height: 24px;
}
tr.zero-line > td {
  border: 0 !important;
  height: 12px !important;
}
div.wrap-vozidla {
  overflow: auto;
  width: 100%;
  background-color: #fff;
}
table.vozidla {
  width: 900px;
  margin: 16px auto;
}
table.vozidla caption {
  height: 22px;
  font-weight: bold;
  font-size: 14px;
  text-align: left;
}
a.btn-item-new {
  position: absolute;
  top: 35px;
  left: 160px;
  cursor: pointer;
  font-weight: bold;
  color: #fff;
  font-size: 10px;
  padding: 8px 12px 7px 12px;
  border-radius: 2px;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.25);
  text-transform: uppercase;
  background-color: #1ccc66;
}
a.btn-item-new:hover {
  background-color: #1abd5f;
}

table.vozidla tr.body:hover {
  background-color: #f1faff;
}
table.vozidla th {
  height: 14px;
  font-size: 11px;
  font-weight: bold;
  color: #999;
}
table.vozidla td {
  height: 28px;
  line-height: 28px;
  border-top: 2px solid #add8e6;
  font-size: 14px;
}
table.vozidla td img {
  width: 18px;
  height: 18px;
  vertical-align: text-bottom;
  cursor: pointer;
}
div.input-echo {
  position: absolute;
  font-size: 18px;
  background-color: #fff;
}
.tooltip-body ul li {
  font-size: 13px;
  line-height: 20px;
}
.tooltip-body ul li strong {
  font-size: 14px;
}
</style>

<script type="text/javascript">
<?php
//uprava
  if ( $copern == 20 )
  {
?>
  function ObnovUI()
  {
<?php if ( $strana == 1 OR $strana == 9999 ) { ?>

   document.formv1.ico.value = '<?php echo "$ico";?>';
   document.formv1.euid.value = '<?php echo "$euid";?>';
   document.formv1.ktos.value = '<?php echo "$ktos";?>';
<?php if ( $mnin1 == 1 ) { ?> document.formv1.mnin1.checked = "checked"; <?php } ?>
<?php if ( $mnin2 == 1 ) { ?> document.formv1.mnin2.checked = "checked"; <?php } ?>

<?php                                        } ?>

<?php if ( $strana == 2 OR $strana == 9999 ) { ?>

   document.formv1.sumvmh.value = '<?php echo "$sumvmh";?>';
<?php if ( $povins1 == 1 ) { ?> document.formv1.povins1.checked = "checked"; <?php } ?>
<?php if ( $povins2 == 1 ) { ?> document.formv1.povins2.checked = "checked"; <?php } ?>
   document.formv1.dovnez.value = '<?php echo "$dovnez";?>';
<?php if ( $upomky == 1 ) { ?> document.formv1.upomky.checked = "checked"; <?php } ?>
<?php if ( $dohspl == 1 ) { ?> document.formv1.dohspl.checked = "checked"; <?php } ?>
   document.formv1.dath.value = '<?php echo "$dathsk";?>';

<?php                                        } ?>



   }
<?php
//koniec uprava
  }
?>

<?php if ( $copern != 20 ) { ?>
  function ObnovUI()
  {
  }
<?php                      } ?>

//Z ciarky na bodku
  function CiarkaNaBodku(Vstup)
  {
   if ( Vstup.value.search(/[^0-9.-]/g) != -1) { Vstup.value=Vstup.value.replace(",","."); }
  }

  function TlacHlasenie()
  {
   window.open('../ucto/hlasenie_euler.php?cislo_oc=<?php echo $cislo_oc;?>&copern=10&drupoh=1&page=1&subor=0&strana=9999', '_blank', 'width=1050, height=900, top=0, left=20, status=yes, resizable=yes, scrollbars=yes');
  }
  function TlacHlasenieIco( cpl )
  {
  var cislo_cpl = cpl;
   window.open('../ucto/hlasenie_euler.php?cislo_oc=<?php echo $cislo_oc;?>&cislo_cpl=' + cislo_cpl + '&copern=10&drupoh=1&page=1&subor=0&strana=9999', '_blank', 'width=1050, height=900, top=0, left=20, status=yes, resizable=yes, scrollbars=yes');
  }
  function NacitajSaldo()
  {
   window.open('../ucto/hlasenie_euler.php?cislo_oc=<?php echo $cislo_oc;?>&copern=3155&drupoh=1&page=1', '_self', 'width=1060, height=900, top=0, left=12, status=yes, resizable=yes, scrollbars=yes')
  }


  function UpravVzd(cpl, ico)
  {
   var cislo_cpl = cpl;
   var cislo_ico = ico;
   window.open('../ucto/hlasenie_euler.php?copern=346&cislo_cpl='+ cislo_cpl + '&cislo_ico='+ cislo_ico + '&uprav=0&strana=1', '_self' )
  }
  function ZmazVzd(cpl, cislo_ico)
  {
   var cislo_cpl = cpl;
   window.open('../ucto/hlasenie_euler.php?copern=316&cislo_cpl='+ cislo_cpl + '&cislo_ico='+ cislo_ico + '&uprav=0', '_self' )
  }
  function NoveVzd()
  {
   window.open('../ucto/hlasenie_euler.php?copern=336&uprav=0', '_self' )
  }
  function doCSV()
  {
   window.open('../ucto/hlasenie_euler.php?copern=11&page=1&sysx=UCT&drupoh=1&uprav=1',
 '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes');
  }

  function pridajHla(ico)
  {
   var cislo_ico = ico;
   window.open('../ucto/hlasenie_euler.php?copern=416&cislo_ico='+ cislo_ico + '&uprav=0', '_self' )
  }
</script>
</HEAD>
<BODY id="white" onload="ObnovUI();">
<?php
  if ( $copern == 20 )
  {
?>
<div id="wrap-heading">
 <table id="heading">
  <tr>
   <td class="ilogin">EuroSecom</td>
   <td class="ilogin" align="right"><?php echo "<strong>UME</strong> $kli_vume&nbsp;&nbsp;<strong>FIR</strong> $kli_vxcf:$kli_nxcf&nbsp;&nbsp;<strong>login</strong> $kli_uzmeno $kli_uzprie / $kli_uzid ";?></td>
  </tr>
  <tr>
   <td class="header">Euler <?php echo $kli_vrok; ?></td>
   <td>
    <div class="bar-btn-form-tool">
     <img src="../obr/ikony/download_blue_icon.png" onclick="NacitajSaldo();"
          title="Na��ta� �daje zo salda" class="btn-form-tool">
     <img src="../obr/ikony/upbox_blue_icon.png" onclick="doCSV();"
          title="Export do CSV" class="btn-form-tool">
     <img src="../obr/ikony/printer_blue_icon.png" onclick="TlacHlasenie();"
          title="Zobrazi� v�etky strany v PDF" class="btn-form-tool">
    </div>
   </td>
  </tr>
 </table>
</div>

<div id="content">
<FORM name="formv1" method="post" action="hlasenie_euler.php?copern=23&cislo_oc=<?php echo $cislo_oc;?>&cislo_cpl=<?php echo $cislo_cpl;?>&strana=<?php echo $strana;?>">
<?php
$clas1="noactive"; $clas2="noactive"; $clas5="noactive";
if ( $strana == 1 ) $clas1="active"; if ( $strana == 2 ) $clas2="active";
if ( $strana == 5 ) $clas5="active";

$source="../ucto/hlasenie_euler.php?cislo_oc=".$cislo_oc."&drupoh=1&page=1&subor=0&cislo_ico=".$cislo_ico."&cislo_cpl=".$cislo_cpl."";
?>
<div class="navbar">
<?php if ( $strana  < 5 ) { ?>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=1', '_self');" class="<?php echo $clas1; ?> toleft">1</a>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=2', '_self');" class="<?php echo $clas2; ?> toleft">2</a>
<?php                     } ?>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=5', '_self');" class="<?php echo $clas5; ?> toleft">Zoznam</a>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=10&strana=2', '_blank');" class="<?php echo $clas2; ?> toright">2</a>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=10&strana=1', '_blank');" class="<?php echo $clas1; ?> toright">1</a>
 <h6 class="toright">Tla�i�:</h6>
<?php if ( $strana != 5 ) { ?> <INPUT type="submit" id="uloz" name="uloz" value="Ulo�i� zmeny" class="btn-top-formsave"> <?php } ?>
</div>

<?php if ( $strana == 1 OR $strana == 9999 ) { ?>
<img src="<?php echo $jpg_cesta; ?>_str1.jpg" class="form-background"
     alt="<?php echo $jpg_popis; ?> 1.strana 265kB">

<!-- 1.Vase udaje -->
<span class="text-echo" style="top:272px; left:256px;"><?php echo $fir_fnaz; ?></span>
<span class="text-echo" style="top:303px; left:256px;"><?php echo $fir_pripoisteny; ?></span>
<span class="text-echo" style="top:334px; left:256px;"><?php echo $fir_zmluva; ?></span>
<span class="text-echo" style="top:365px; left:256px;"><?php echo $fir_kontakt; ?></span>
<span class="text-echo" style="top:396px; left:256px;"><?php echo $fir_konemail; ?></span>
<span class="text-echo" style="top:426px; left:256px;"><?php echo $fir_kontel; ?></span>
<span class="text-echo" style="top:426px; left:635px;"><?php echo $fir_konfax; ?></span>
<input type="checkbox" name="mnin1" value="1" style="top:468px; left:387px;"/> <!-- dopyt, dorobi� funkciu na prep�nanie -->
<input type="checkbox" name="mnin2" value="1" style="top:468px; left:467px;"/>

<!-- 2.Udaje o dlznikovi -->
<span class="text-echo" style="top:577px; left:256px;"><?php echo $ico_nai; ?></span>
<span class="text-echo" style="top:608px; left:256px;"><?php echo $ico_uli; ?></span>
<span class="text-echo" style="top:608px; left:635px;"><?php echo $ico_psc; ?></span>
<span class="text-echo" style="top:639px; left:256px;"><?php echo $ico_mes; ?></span>
<span class="text-echo" style="top:639px; left:635px;"><?php echo $ico_stat; ?></span>
<input type="text" name="ico" id="ico" style="width:100px; top:663px; left:250px;"/>
<input type="text" name="euid" id="euid" style="width:235px; top:663px; left:630px;"/>
<input type="text" name="ktos" id="ktos" style="width:300px; top:694px; left:250px;"/>
<span class="text-echo" style="top:732px; left:256px;"><?php echo $ico_konemail; ?></span>
<span class="text-echo" style="top:764px; left:256px;"><?php echo $ico_kontel; ?></span>
<span class="text-echo" style="top:764px; left:635px;"><?php echo $ico_konfax; ?></span>

<!-- 3.Neuhradene faktury -->
<span class="text-echo" style="top:824px; right:86px;"><?php echo $ico_allsaldo; ?></span>
<span class="text-echo" style="top:894px; left:110px;">vi� pr�loha</span>
<span class="text-echo" style="top:1194px; right:86px;"><?php echo $spolu_sdph; ?></span>
<?php                                        } ?>


<?php if ( $strana == 2 OR $strana == 9999 ) { ?>
<img src="<?php echo $jpg_cesta; ?>_str2.jpg" class="form-background"
     alt="<?php echo $jpg_popis; ?> 2.strana 265kB">

<!-- 4.Inkaso pohladavok -->
<input type="text" name="sumvmh" id="sumvmh" onkeyup="CiarkaNaBodku(this);" style="width:142px; top:176px; left:243px;"/>
<span class="text-echo" style="top:184px; left:565px;">EUR</span>
<input type="checkbox" name="povins1" value="1" style="top:261px; left:394px;"/> <!-- dopyt, dorobi� funkciu na prep�nanie -->
<input type="checkbox" name="povins2" value="1" style="top:261px; left:472px;"/>

<!-- 5.Dalsie informacie -->
<input type="text" name="dovnez" id="dovnez" style="width:621px; top:360px; left:243px;"/>
<input type="checkbox" name="upomky" value="1" style="top:407px; left:500px;"/>
<input type="checkbox" name="dohspl" value="1" style="top:407px; left:661px;"/>
<textarea name="dalinf" id="dalinf" style="width:781px; height:230px; top:478px; left:83px;"><?php echo $dalinf; ?></textarea>

<!-- 6.Prehlasenie -->
<span class="text-echo" style="top:831px; left:248px;"><?php echo $podpisujuci; ?></span>
<span class="text-echo" style="top:861px; left:248px;"><?php echo $podpispozicia; ?></span>
<span class="text-echo" style="top:891px; left:248px;"><?php echo $fir_fnaz; ?></span>
<input type="text" name="dath" id="dath" onkeyup="CiarkaNaBodku(this);" style="width:100px; top:948px; left:666px;"/>
<?php                                        } ?>


<?php if ( $strana == 5 OR $strana == 9999 ) {
//VYPIS ZOZNAMU 
$sluztt = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE oc = 1 ORDER BY zorad,ico ";

//echo $sluztt;
$sluz = mysql_query("$sluztt");
$slpol = mysql_num_rows($sluz);
?>
<div class="wrap-vozidla">
 <a href="#" onclick="NoveVzd();" title="Prida� I�O" class="btn-item-new" >+ Dl�n�k</a>
<table class="vozidla">
<caption>Zoznam dl�n�kov</caption>
<tr class="zero-line">
 <td style="width:4%;"></td><td style="width:9%;"></td><td style="width:37%;"></td>
 <td style="width:10%;"></td><td style="width:12%;"></td><td style="width:18%;"></td>
 <td style="width:10%;"></td>
</tr>
<tr>
 <th>&nbsp;</th>
 <th>i�o</th>
 <th class="left">n�zov</th>
 <th>euid</th>
 <th>suma</th>
 <th>vytvoren�</th>
 <th>&nbsp;</th>
</tr>
<?php
$i=0;
  while ( $i <= $slpol )
  {
  if (@$zaznam=mysql_data_seek($sluz,$i))
 {
$rsluz=mysql_fetch_object($sluz);
$cisloi=$i+1;

$sqlfir = "SELECT * FROM F$kli_vxcf"."_ico WHERE ico = $rsluz->ico ";
$fir_vysledok = mysql_query($sqlfir);
$fir_riadok=mysql_fetch_object($fir_vysledok);

$ico_nai = $fir_riadok->nai;
$ico_mes = $fir_riadok->mes;
$ico_uli = $fir_riadok->uli;

mysql_free_result($fir_vysledok);
?>
<tr class="body"> 
 <td class="center" style="border-top:0; font-size:12px; color:#999; font-weight: ;"><?php echo "$cisloi."; ?></td>
 <td>&nbsp;<?php echo $rsluz->ico; ?></td>
 <td>
  <input type="checkbox" name="nahl<?php echo $rsluz->ico; ?>" value="1" 
<?php if ( $rsluz->nahl == 1 ) { ?> checked="checked" <?php } ?>
onclick="pridajHla(<?php echo $rsluz->ico; ?>);" title="Prida� do hl�senia" style="position:relative; top:1px;">&nbsp;<?php echo $ico_nai; ?>
 </td>
 <td class="center"><?php echo $rsluz->euid; ?></td>
 <td class="right"><?php echo $rsluz->dsuma; ?>&nbsp;&nbsp;</td>
 <td class="center">
  <img src="../obr/ikony/list_blue_icon.png" onclick="TlacHlasenieIco(<?php echo $rsluz->cpl; ?>);"
       title="Vytvori� PDF a CSV hl�senie pre I�O <?php echo $rsluz->ico; ?>">&nbsp;&nbsp;<?php echo SkDatum($rsluz->dath); ?>
 </td>
 <td align="center">
<?php
$icox=1*$rsluz->ico;
?>
  <img src="../obr/ikony/pencil_blue_icon.png" onclick="UpravVzd(<?php echo $rsluz->cpl; ?>, <?php echo $icox; ?>);"
       title="Upravi�">&nbsp;&nbsp;&nbsp;
  <img src="../obr/ikony/xmark_lred_icon.png" onclick="ZmazVzd(<?php echo $rsluz->cpl; ?>, '<?php echo $icox; ?>');"
       title="Vymaza�">
 </td>
</tr>
<?php
 }
$i=$i+1;
   }
?>
 </table>
</div>
<?php                                        } ?>

<div class="navbar">
<?php if ( $strana  < 5 ) { ?>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=1', '_self');" class="<?php echo $clas1; ?> toleft">1</a>
 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=2', '_self');" class="<?php echo $clas2; ?> toleft">2</a>
<?php                     } ?>

 <a href="#" onclick="window.open('<?php echo $source; ?>&copern=20&strana=5', '_self');" class="<?php echo $clas5; ?> toleft">Zoznam</a>
<?php if ( $strana != 5 ) { ?>
 <INPUT type="submit" id="uloz" name="uloz" value="Ulo�i� zmeny" class="btn-bottom-formsave">
<?php                     } ?>
</div>

</FORM>
</div> <!-- koniec #content -->
<?php
//mysql_free_result($vysledok);
  }
//koniec uprav
?>


<?php
/////////////////////////////////////////////////VYTLAC
if ( $copern == 10 )
{

$ddir="../dokumenty/FIR".$kli_vxcf."/euler";
mkdir ($ddir, 0777);

$hhmmss = Date ("is", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

 $outfilexdel="../dokumenty/FIR".$kli_vxcf."/euler/hlasenie_*.*";
 foreach (glob("$outfilexdel") as $filename) {
    unlink($filename);
 }


$sqltt5 = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE dsuma > 0 ORDER BY ico ";
if( $cislo_cpl > 0 ) 
{
$sqltt5 = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE cpl = $cislo_cpl ORDER BY ico ";
}
$sql5 = mysql_query("$sqltt5");                                                   
$pol5 = mysql_num_rows($sql5);

$i5=0;
  while ($i5 <= $pol5 )
  {
  if (@$zaznam=mysql_data_seek($sql5,$i5))
{
$hlavicka5=mysql_fetch_object($sql5);

$sqlfir = "SELECT * FROM F$kli_vxcf"."_ico WHERE ico = $hlavicka5->ico ";
$fir_vysledok = mysql_query($sqlfir);
$fir_riadok=mysql_fetch_object($fir_vysledok);

$ico_nai = $fir_riadok->nai;
$ico_mes = $fir_riadok->mes;
$ico_uli = $fir_riadok->uli;
$ico_psc = $fir_riadok->psc;
$ico_konemail = $fir_riadok->em1;
$ico_kontel = $fir_riadok->tel;
$ico_konfax = $fir_riadok->fax;
$ico_stat="SR";

//urob jedno pdf


$outfilex="../dokumenty/FIR".$kli_vxcf."/euler/hlasenie_".$ico_nai.".pdf";
if (File_Exists ("$outfilex")) { $soubor = unlink("$outfilex"); }

     define('FPDF_FONTPATH','../fpdf/font/');
     require('../fpdf/fpdf.php');

$sirka_vyska="210,320";
$velkost_strany = explode(",", $sirka_vyska);
$pdf=new FPDF("P","mm", $velkost_strany );
$pdf->Open();
$pdf->AddFont('arial','','arial.php');

//vytlac
$sqltt = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE oc = 1 AND ico = $hlavicka5->ico ORDER BY ico ";

$sql = mysql_query("$sqltt");
$pol = mysql_num_rows($sql);

$i=0;
$j=0; //zaciatok strany ak by som chcel strankovat
  while ($i <= $pol )
  {
  if (@$zaznam=mysql_data_seek($sql,$i))
{
$hlavicka=mysql_fetch_object($sql);


if ( $strana == 1 OR $strana == 9999 ) {
$pdf->AddPage();
$pdf->SetFont('arial','',10);
$pdf->SetLeftMargin(10);
$pdf->SetTopMargin(10);
if ( File_Exists($jpg_cesta.'_str1.jpg') )
{
$pdf->Image($jpg_cesta.'_str1.jpg',0,0,210,297);
}
$pdf->SetY(10);

//1.VASE UDAJE
//Poisteny
$text=$fir_fnaz;
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,45.5," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Pripoisteny
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Poistna zmluva
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Kontaktna osoba
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Email
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Telefon a Fax
$text1="";
$text2="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text1","$rmc",0,"L");
$pdf->Cell(31,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text2","$rmc",1,"L");
//Mandatorne inkaso
$text1="x"; $text2=" ";
if ( $hlavicka->mnin == 0 ) { $text1=" "; $text2="x"; }
$pdf->Cell(195,4," ","$rmc1",1,"L");
$pdf->Cell(75,5," ","$rmc1",0,"C");$pdf->Cell(5.5,6,"$text1","$rmc",0,"C");
$pdf->Cell(12,5," ","$rmc1",0,"C");$pdf->Cell(6,6,"$text2","$rmc",1,"C");

//2.DLZNIK
//Dlznik
$text=$ico_nai;
$pdf->Cell(195,18.5," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Adresa a PSC
$text1=$ico_uli;
$text2=$ico_psc;
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text1","$rmc",0,"L");
$pdf->Cell(31,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text2","$rmc",1,"L");
//Mesto a Krajina
$text1=$ico_mes;
$text2=$ico_stat;
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text1","$rmc",0,"L");
$pdf->Cell(31,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text2","$rmc",1,"L");
//ICO a Euler ID
$text1=$hlavicka->ico;
$text2=$hlavicka->euid;
if( $text2 == 0 ) {  $text2=""; }
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text1","$rmc",0,"L");
$pdf->Cell(31,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text2","$rmc",1,"L");
//Kontaktna osoba
$text=$hlavicka->ktos;
$pdf->Cell(195,1.5," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Email
$text=$ico_konemail;
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(137,6,"$text","$rmc",1,"L");
//Telefon a Fax
$text1=$ico_kontel;
$text2=$ico_konfax;
$text="";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(45.5,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text1","$rmc",0,"L");
$pdf->Cell(31,5," ","$rmc1",0,"C");$pdf->Cell(53,6,"$text2","$rmc",1,"L");

//3.NEUHRADENE
//Celkove saldo
$text="";
$textx="0123456789";
$pdf->Cell(195,8," ","$rmc1",1,"L");
$pdf->Cell(148.5,5," ","$rmc1",0,"C");$pdf->Cell(34,6,"$text","$rmc",1,"R");
//Cislo faktury
$text="vi� pr�loha";
$pdf->Cell(195,10," ","$rmc1",1,"L");
$pdf->Cell(8.5,5," ","$rmc1",0,"C");$pdf->Cell(34,6,"$text","$rmc",1,"C");
//Spolu bez/s dph
$text=$hlavicka->dbdph;
$textx="0123456789";
$pdf->SetY(266);
$pdf->Cell(113.5,5," ","$rmc1",0,"C");$pdf->Cell(34,6,"$text","$rmc",0,"R");

$text=$hlavicka->dsuma;
$pdf->Cell(1,5," ","$rmc1",0,"C");$pdf->Cell(34,6,"$text","$rmc",1,"R");
                                       } //koniec 1.strany

if ( $strana == 2 OR $strana == 9999 ) {
$pdf->AddPage();
$pdf->SetFont('arial','',10);
$pdf->SetLeftMargin(10);
$pdf->SetTopMargin(10);
if ( File_Exists($jpg_cesta.'_str2.jpg') )
{
$pdf->Image($jpg_cesta.'_str2.jpg',0,0,210,297);
}
$pdf->SetY(10);

//4.INKASO
//Suma a Mena
$text1=$hlavicka->sumvmh;
$text2="EUR";
$textx="0123456789";
$pdf->Cell(195,25," ","$rmc1",1,"L");
$pdf->Cell(44,5," ","$rmc1",0,"C");$pdf->Cell(32.5,6,"$text1","$rmc",0,"R");
$pdf->Cell(23,5," ","$rmc1",0,"C");$pdf->Cell(38,6,"$text2","$rmc",1,"C");
//Poverenie
$text1="x"; $text2=" ";
if ( $hlavicka->povins == 0 ) { $text1=" "; $text2="x"; }
$pdf->Cell(195,12," ","$rmc1",1,"L");
$pdf->Cell(76.5,5," ","$rmc1",0,"C");$pdf->Cell(5.5,6,"$text1","$rmc",0,"C");
$pdf->Cell(12,5," ","$rmc1",0,"C");$pdf->Cell(5.5,6,"$text2","$rmc",1,"C");

//5.DALSIE INFO
//Dovod
$text=$hlavicka->dovnez;
$pdf->Cell(195,18," ","$rmc1",1,"L");
$pdf->Cell(44,5," ","$rmc1",0,"C");$pdf->Cell(138,6,"$text","$rmc",1,"L");
//Opatrenia
$text1="x"; if ( $hlavicka->upomky == 0 ) $text1=" ";
$text2="x"; if ( $hlavicka->dohspl == 0 ) $text2=" ";
$pdf->Cell(195,4," ","$rmc1",1,"L");
$pdf->Cell(100,5," ","$rmc1",0,"C");$pdf->Cell(5.5,5,"$text1","$rmc",0,"C");
$pdf->Cell(30,5," ","$rmc1",0,"C");$pdf->Cell(6,5,"$text2","$rmc",1,"C");
//Dalsie
$pdf->Cell(190,12," ","$rmc1",1,"L");
$pole = explode("\r\n", $hlavicka->dalinf); //dopyt, skontrolova�
$ipole=1;
foreach( $pole as $hodnota ) {
$pdf->Cell(8,5," ","$rmc1",0,"L");$pdf->Cell(174,7,"$hodnota","$rmc",1,"L");
$ipole=$ipole+1;
                             }

//6.PREHLASENIE
//Podpisujuci
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->SetY(183);
$pdf->Cell(44,5," ","$rmc1",0,"C");$pdf->Cell(138,6,"$text","$rmc",1,"L");
//Pozicia
$text="";
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(44,5," ","$rmc1",0,"C");$pdf->Cell(138,6,"$text","$rmc",1,"L");
//Pozicia
$text=$fir_fnaz;
$textx="0123456789abcdefghijklmnoprstuv";
$pdf->Cell(195,1," ","$rmc1",1,"L");
$pdf->Cell(44,5," ","$rmc1",0,"C");$pdf->Cell(138,6,"$text","$rmc",1,"L");
//Datum
$text=SkDatum($hlavicka->dath);
//if ( $text == '00.00.0000' ) { $text=" " }
$pdf->Cell(195,8.5," ","$rmc1",1,"L");
$pdf->Cell(137.5,5," ","$rmc1",0,"C");$pdf->Cell(40,6,"$text","$rmc",1,"C");

                                       } //koniec 2.strany
}
$i = $i + 1;
  }
$pdf->Output("$outfilex");


//koniec urob jedno pdf

}
$i5 = $i5 + 1;
  }

?>

<?php if ( $xml == 0 AND $cislo_cpl > 0 ) { ?>
<script type="text/javascript">
  var okno = window.open("<?php echo $outfilex; ?>","_self");
</script>
<?php                  } ?>
<?php if ( $xml == 0 AND $cislo_cpl == 0 ) { ?>

<br /><br /><br /><br />
  PDF Hl�senia vytvoren�.
<br /><br /><br /><br />

<?php                  } ?>
<?php
}
/////////////////////////////////////////KONIEC VYTLACENIA
?>


<?php
/////////////////////////////////////////////////export csv
if ( $copern == 11 )
{
$hhmmss = Date ("is", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

$ddir="../dokumenty/FIR".$kli_vxcf."/euler";
mkdir ($ddir, 0777);

$hhmmss = Date ("is", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));

 $outfilexdel="../dokumenty/FIR".$kli_vxcf."/euler/saldo_*.*";
 foreach (glob("$outfilexdel") as $filename) {
    unlink($filename);
 }



$sqltt5 = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_euler WHERE dsuma > 0 AND nahl = 1 ORDER BY ico ";
$sql5 = mysql_query("$sqltt5");                                                   
$pol5 = mysql_num_rows($sql5);

$i5=0;
  while ($i5 <= $pol5 )
  {
  if (@$zaznam=mysql_data_seek($sql5,$i5))
{
$hlavicka5=mysql_fetch_object($sql5);

$sqlfir = "SELECT * FROM F$kli_vxcf"."_ico WHERE ico = $hlavicka5->ico ";
$fir_vysledok = mysql_query($sqlfir);
$fir_riadok=mysql_fetch_object($fir_vysledok);

$ico_nai = trim($fir_riadok->nai);
$ico_mes = trim($fir_riadok->mes);
$ico_uli = trim($fir_riadok->uli);
$ico_psc = $fir_riadok->psc;
$ico_konemail = $fir_riadok->em1;
$ico_kontel = $fir_riadok->tel;
$ico_konfax = $fir_riadok->fax;
$ico_stat="SR";

//urob jedno csv

$outfilex="../dokumenty/FIR".$kli_vxcf."/euler/saldo_".$ico_nai.".csv";
if ( File_Exists("$outfilex") ) { $soubor = unlink("$outfilex"); }

$soubor = fopen("$outfilex", "a+");


$sqltt = "SELECT * FROM F$kli_vxcf"."_ucthlasenie_eulerfak WHERE ico = $hlavicka5->ico ORDER BY fak ";
$sql = mysql_query("$sqltt");
if($sql)                                                     
$pol = mysql_num_rows($sql);

$i=0;
  while ($i <= $pol )
  {
  if (@$zaznam=mysql_data_seek($sql,$i))
{
$hlavicka=mysql_fetch_object($sql);


if ( $i == 0 )
     {
$text = "$ico_nai, $ico_uli, $ico_mes, $ico_psc, email $ico_konemail, tel $ico_kontel, ico $hlavicka5->ico "."\r\n";
fwrite($soubor, $text);

$text = "cislo_faktury".";"."vystavena".";"."splatna".";"."bez_dph".";"."s_dph".";"."\r\n";
fwrite($soubor, $text);
     }

$dat=SkDatum($hlavicka->dat);
$das=SkDatum($hlavicka->das);
$cen=$hlavicka->zos; $ecen=str_replace(".",",",$cen); 
$ced=$hlavicka->hdp; $eced=str_replace(".",",",$ced); 



  $text = $hlavicka->fak.";".$dat.";".$das.";".$eced.";".$ecen."\r\n"; 
  //if( $i == 0 ) { $text = "112233;7800345;12.01.2016;26.01.2016;10000;12000"."\r\n"; }

  fwrite($soubor, $text);

}
$i = $i + 1;
  }

fclose($soubor);

//koniec urob jedno csv

}
$i5 = $i5 + 1;
  }


?>
<br /><br /><br /><br />
  CSV Hl�senia vytvoren�.
<br /><br /><br /><br />
<?php
}
/////////////////////////////////////////export csv
?>



<?php
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvypl'.$kli_uzid;
$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvyplx'.$kli_uzid;
//$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_mzdprcvyplz'.$kli_uzid;
$vysledok = mysql_query("$sqlt");

//celkovy koniec dokumentu
} while (false);
?>
</BODY>
</HTML>