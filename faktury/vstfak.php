<HTML>
<?php
@session_start();

// celkovy zaciatok dokumentu
       do
       {
// cislo operacie
$copern = strip_tags($_REQUEST['copern']);
$sys = 'FAK';
$urov = 1100;
if( $copern == 10 ) $urov = 1000;
if( $copern == 155 OR $copern == 156 OR $copern == 167 OR $copern == 168 )
{
$sys = 'ALL';
$urov = 10000;
} 
$drupoh = strip_tags($_REQUEST['drupoh']);
$regpok = 1*$_REQUEST['regpok'];
if( $drupoh == 31 OR $drupoh == 12 OR $drupoh == 22 OR $drupoh == 42 OR $drupoh == 52 OR $copern == 267 OR $copern == 268 )
{
$sys = 'DOP';
$urov = 2000;
if( $regpok == 1 ) { $sys = 'FAK'; }
}
$DOPRAVA = "DOPRAVA";
$vyroba = $_REQUEST['vyroba'];
if(!isset($vyroba)) $vyroba = 0;
if($vyroba == 1 ) $DOPRAVA = "V�ROBA";

$pocstav = $_REQUEST['pocstav'];
if(isset($pocstav)) { $_SESSION['pocstav'] = $pocstav; }
$pocstav=$_SESSION['pocstav'];

//echo $pocstav;

$uziv = include("../uziv.php");
if( !$uziv ) exit;

if(!isset($kli_vxcf)) $kli_vxcf = 1;

  require_once("../pswd/password.php");
  @$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);

$sql = "SELECT * FROM F$kli_vxcf"."_vtvall";
$vysledok = mysql_query($sql);
if (!$vysledok):
$vtvall = include("../cis/vtvall.php");
endif;
$sql = "SELECT * FROM F$kli_vxcf"."_vtvfak";
$vysledok = mysql_query($sql);
if (!$vysledok):
$vtvfak = include("../faktury/vtvfak.php");
endif;
$sql = "SELECT * FROM F$kli_vxcf"."_vtvskl";
$vysledok = mysql_query($sql);
if (!$vysledok):
$vtvskl = include("../sklad/vtvskl.php");
endif;

$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];

//datove struktury mes.vykaz dph
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha2new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{
echo "Def mesVykDPH";

$sql = "ALTER TABLE F$kli_vxcf"."_uctpokuct MODIFY unk VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctban MODIFY unk VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctvsdp MODIFY unk VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctodb MODIFY unk VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctdod MODIFY unk VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");

$sql = "ALTER TABLE F$kli_vxcf"."_uctpokuct MODIFY rdp DECIMAL(4,0) DEFAULT 0";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctban MODIFY rdp DECIMAL(4,0) DEFAULT 0";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctvsdp MODIFY rdp DECIMAL(4,0) DEFAULT 0";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctodb MODIFY rdp DECIMAL(4,0) DEFAULT 0";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctdod MODIFY rdp DECIMAL(4,0) DEFAULT 0";
$vysledek = mysql_query("$sql");

$sql = "ALTER TABLE F$kli_vxcf"."_pokpri MODIFY sp4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_pokvyd MODIFY sp4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_banvyp MODIFY sp4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctvsdh MODIFY sp4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakodb MODIFY sz4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakdod MODIFY sz4 VARCHAR(20) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakodb MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakdod MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakodb MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakdod MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");

$sql = "ALTER TABLE F$kli_vxcf"."_uctdrdp MODIFY crd3 VARCHAR(10) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctdrdp MODIFY nrd VARCHAR(70) NOT NULL ";
$vysledek = mysql_query("$sql");

if( $kli_vrok == 2014 )
  {
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='B2' WHERE rdp >  10 AND rdp < 50 AND szd > 0 ";
$vysledek = mysql_query("$sql");

$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='A1' WHERE rdp >= 50 AND rdp < 99 AND szd > 0 ";
$vysledek = mysql_query("$sql");

$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='', crz3=1 WHERE rdp = 84 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='', crz3=1 WHERE rdp = 85 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='', crz3=1 WHERE rdp = 90 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='', crz3=1 WHERE rdp = 385 ";
$vysledek = mysql_query("$sql");

$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='B1', crz3=1 WHERE rdp = 34 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='B1', crz3=1 WHERE rdp = 35 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='B1', crz3=1 WHERE rdp = 40 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='B1', crz3=1 WHERE rdp = 335 ";
$vysledek = mysql_query("$sql");

$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='' WHERE rdp = 61 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='' WHERE rdp = 62 ";
$vysledek = mysql_query("$sql");
  }

$sql = "DROP TABLE F".$kli_vxcf."_uctvykdpha2new ";
$vysledek = mysql_query("$sql");

$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha2".$sqlt;
$vysledek = mysql_query("$sql");

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha2new".$sqlt;
$vysledek = mysql_query("$sql");

$sql = "ALTER TABLE F$kli_vxcf"."_uctvykdpha2 ADD sumd DECIMAL(10,2) DEFAULT 0 AFTER merj ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctvykdpha2 ADD zkld DECIMAL(10,2) DEFAULT 0 AFTER merj ";
$vysledek = mysql_query("$sql");
}
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha3new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{


$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;

if( $kli_vrok == 2014 )
{
//prenos DPH nejde do priznania DPH
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crz=0 WHERE rdp = 361 OR rdp = 461";
$vysledek = mysql_query("$sql");
}

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha3new".$sqlt;
$vysledek = mysql_query("$sql");
}
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha4new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{


$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;

if( $kli_vrok == 2014 )
{
//JCD nejdu do KV DPH
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd3='' WHERE crz1 = 1 ";
$vysledek = mysql_query("$sql");
}

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha4new".$sqlt;
$vysledek = mysql_query("$sql");
}
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha6new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{


$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;
$sql = "ALTER TABLE F$kli_vxcf"."_dopfak MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_dopfak MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");

if( $sys == 'DOP' )
  {
$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha6new".$sqlt;
$vysledek = mysql_query("$sql");
  }
}
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha7new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{

$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;
$sql = "ALTER TABLE F$kli_vxcf"."_fakdod MODIFY sz4 DATE NOT NULL ";
$vysledek = mysql_query("$sql");

if( $kli_vrok == 2014 ) {
$sql = "UPDATE F$kli_vxcf"."_fakdod SET sz4=daz WHERE daz != '0000-00-00' ";
$vysledek = mysql_query("$sql");
                        }


$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha7new".$sqlt;
$vysledek = mysql_query("$sql");
}
$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha8new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{

$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;

if( $kli_vrok == 2014 )
{
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crd=10 WHERE rdp = 385 ";
$vysledek = mysql_query("$sql");
$sql = "UPDATE F".$kli_vxcf."_uctdrdp SET crz=9 WHERE rdp = 335 ";
$vysledek = mysql_query("$sql");
}

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha8new".$sqlt;
$vysledek = mysql_query("$sql");
}

$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha91new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{


$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;
$sql = "ALTER TABLE F$kli_vxcf"."_fakdod MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakodb MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha91new".$sqlt;
$vysledek = mysql_query("$sql");
}

$sql = "SELECT prx6 FROM F$kli_vxcf"."_uctvykdpha21new ";
$vysledok = mysql_query($sql);
if (!$vysledok)
{


$sqlt = <<<banvyp
(
   cpld        int not null auto_increment,
   dok         DECIMAL(10,0) DEFAULT 0,
   kodt        VARCHAR(20) NOT NULL,
   dtov        VARCHAR(5) NOT NULL,
   mnot        DECIMAL(10,2) DEFAULT 0,
   merj        VARCHAR(5) NOT NULL,
   zkld        DECIMAL(10,2) DEFAULT 0,
   sumd        DECIMAL(10,2) DEFAULT 0,
   prx1        DECIMAL(4,0) DEFAULT 0,
   prx2        DECIMAL(4,0) DEFAULT 0,
   prx3        DECIMAL(4,0) DEFAULT 0,
   prx6        DECIMAL(4,0) DEFAULT 0,
   PRIMARY KEY(cpld)
);
banvyp;

$sql = "ALTER TABLE F$kli_vxcf"."_fakdodpoc MODIFY sz4 DATE NOT NULL ";
$vysledek = mysql_query("$sql");
if( $kli_vrok == 2014 ) {
$sql = "UPDATE F$kli_vxcf"."_fakdodpoc SET sz4=daz WHERE daz != '0000-00-00' ";
$vysledek = mysql_query("$sql");
                        }
$sql = "ALTER TABLE F$kli_vxcf"."_fakodbpoc MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakdodpoc MODIFY sz3 VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakodbpoc MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_fakdodpoc MODIFY dav VARCHAR(30) NOT NULL ";
$vysledek = mysql_query("$sql");

$sql = "CREATE TABLE F".$kli_vxcf."_uctvykdpha21new".$sqlt;
$vysledek = mysql_query("$sql");
}
//koniec datove struktury mes.vykaz dph

$citfir = include("../cis/citaj_fir.php");

//datumove funkcie
$sDat = include("../funkcie/dat_sk_us.php");

//oprava ciselnika uctdrdp
if( $kli_vrok == 2015 )
{
$sqlttt = "SELECT * FROM F$kli_vxcf"."_uctdrdp WHERE rdp = 25 ";
$sqldok = mysql_query("$sqlttt");
if(@$zaznam=mysql_data_seek($sqldok,0))
{
$riaddok=mysql_fetch_object($sqldok);
$crd3=$riaddok->crd3;

if ($crd3 != 'B2')
    {
$databaza="";
$dtb2 = include("../cis/oddel_dtbz1.php");
$kli_vmcf=1*$fir_allx11;

$dsqlt = "DELETE FROM F$kli_vxcf"."_uctdrdp ";
$dsql = mysql_query("$dsqlt");

$sql = "ALTER TABLE F$kli_vxcf"."_uctdrdp MODIFY crd3 VARCHAR(10) NOT NULL ";
$vysledek = mysql_query("$sql");
$sql = "ALTER TABLE F$kli_vxcf"."_uctdrdp MODIFY nrd VARCHAR(70) NOT NULL ";
$vysledek = mysql_query("$sql");

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctdrdp SELECT * FROM ".$databaza."F$kli_vmcf"."_uctdrdp ";
if( $kli_vmcf > 0 ) { $dsql = mysql_query("$dsqlt"); echo "Znovu prenos ��seln�ka druhov DPH z firmy ".$kli_vmcf; }
    }
}
}
//koniec oprava ciselnika uctdrdp

//tlacove okno
$tlcuwin="width=700, height=' + vyskawin + ', top=0, left=200, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";

// druh pohybu 1=odber , 2=dodav , 11=dodacielisty , 12=dodacielisty doprava , 21=VNF, 22=VNFdoprava
$drupoh = strip_tags($_REQUEST['drupoh']);

$rozuct='NIE';
$sysx='INE';
$zmenauce=0;
if( $drupoh > 1000 AND $drupoh < 2000 )
{
$drupoh=$drupoh-1000;
$rozuct='ANO';
$sysx='UCT';
$zmenauce=1;
}
if( $drupoh > 2000 )
{
$drupoh=$drupoh-2000;
$rozuct='NEI';
$sysx='FAK';
$zmenauce=1;
}

$ucto_sys=$_SESSION['ucto_sys'];
//echo $ucto_sys;
if( $ucto_sys == 1 )
{
$rozuct='ANO';
$sysx='UCT';
}

$kli_vxcfskl=$kli_vxcf;
if( $drupoh == 1 )
{
$tabl = "fakodb";
$tablsluzby = "fakslu";
$cisdok = "fakodb";
$adrdok = "fakodber";
$zassluzby = "sluzbyzas";
if( $pocstav == 1 ) $tabl = "fakodbpoc";
if( $fir_xfa06 > 0 ) { $kli_vxcfskl=$fir_xfa06; }
}
if( $drupoh == 31 )
{
$tabl = "dopfak";
$tablsluzby = "dopslu";
$cisdok = "dopfak";
$adrdok = "dopfak";
$zassluzby = "dopsluzbyzas";
$rozuct='NIE';
$sysx='INE';
}
if( $drupoh == 11 )
{
$tabl = "fakdol";
$tablsluzby = "fakslu";
$cisdok = "fakdol";
$adrdok = "fakdol";
$zassluzby = "sluzbyzas";
$rozuct='NIE';
$sysx='INE';
}
if( $drupoh == 12 )
{
$tabl = "dopdol";
$tablsluzby = "dopslu";
$cisdok = "dopdol";
$adrdok = "dopdol";
$zassluzby = "dopsluzbyzas";
$rozuct='NIE';
$sysx='INE';
}
if( $drupoh == 21 )
{
$tabl = "fakvnp";
$tablsluzby = "fakslu";
$cisdok = "fakvnp";
$adrdok = "fakvnp";
$zassluzby = "sluzbyzas";
$rozuct='NIE';
$sysx='INE';
}
if( $drupoh == 22 )
{
$tabl = "dopvnp";
$tablsluzby = "dopslu";
$cisdok = "dopvnp";
$adrdok = "dopvnp";
$zassluzby = "dopsluzbyzas";
$rozuct='NIE';
$sysx='INE';
}
if( $drupoh == 42 )
{
$tabl = "dopreg";
$tablsluzby = "dopslu";
$cisdok = "dopreg";
$adrdok = "dopreg";
$zassluzby = "dopsluzbyzas";
$rozuct='NIE';
$sysx='INE';
if( $regpok == 1 ) 
  {  
$tablsluzby = "fakslu"; 
  }
}
if( $drupoh == 52 )
{
$tabl = "dopprf";
$tablsluzby = "dopslu";
$cisdok = "fakprf";
$adrdok = "fakprf";
$zassluzby = "dopsluzbyzas";
$rozuct='NIE';
$sysx='INE';
}

if( $drupoh == 2 )
{
$tabl = "fakdod";
$tablsluzby = "fakslu";
$cisdok = "fakdod";
$adrdok = "fakdodav";
$zassluzby = "sluzbyzas";
if( $pocstav == 1 ) $tabl = "fakdodpoc";
}

//z listy
$zmenajucet = 1*$_REQUEST['zmenajucet'];
if( $zmenajucet == 1 ) { $zmenauce=1; }


//nastavenie uctu
$hladaj_uce = $_REQUEST['hladaj_uce'];
//ak viac rad pre jednu analytiku
if( $fir_uctx14 == 1 AND ( $drupoh == 1 OR $drupoh == 2 ) AND $zmenauce == 0  )
{
$hladaj_uce = $_SESSION['nastavene_uce'];
}
$hladaj_ucex=$hladaj_uce;
//echo "uce".$hladaj_uce;

if(!isset($hladaj_uce) OR $hladaj_uce == '')
{
if( $drupoh != 2 AND $drupoh != 42 )
{
//odberatelske faktury
if( $drupoh == 1 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 1 ) ORDER BY dodb"); }
//odberatelske faktury DOPRAVA
if( $drupoh == 31 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 11 ) ORDER BY dodb"); }
//dodacie listy
if( $drupoh == 11 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 2 ) ORDER BY dodb"); }
//dodacie listy DOPRAVA
if( $drupoh == 12 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 12 ) ORDER BY dodb"); }
//vnotrupodnikove faktury
if( $drupoh == 21 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 3 ) ORDER BY dodb"); }
//vnutropodnikove faktury DOPRAVA
if( $drupoh == 22 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 13 ) ORDER BY dodb"); }
//predfaktury DOPRAVA
if( $drupoh == 52 ) { $sqluce = mysql_query("SELECT dodb,nodb FROM F$kli_vxcf"."_dodb WHERE ( drod = 14 ) ORDER BY dodb"); }
  if (@$zaznam=mysql_data_seek($sqluce,0))
  {
  $riaduce=mysql_fetch_object($sqluce);
  $hladaj_uce=$riaduce->dodb;
  }
}
//reg.pokladnica DOPRAVA
if( $drupoh == 42 )
{
$sqluce = mysql_query("SELECT dpok,npok FROM F$kli_vxcf"."_dpok WHERE ( drpk = 9 ) ORDER BY dpok");
  if (@$zaznam=mysql_data_seek($sqluce,0))
  {
  $riaduce=mysql_fetch_object($sqluce);
  $hladaj_uce=$riaduce->dpok;
  }
}
//dodavatelska faktura
if( $drupoh == 2 )
{
$sqluce = mysql_query("SELECT ddod,ndod FROM F$kli_vxcf"."_ddod WHERE ( drdo = 1 ) ORDER BY ucdo");
  if (@$zaznam=mysql_data_seek($sqluce,0))
  {
  $riaduce=mysql_fetch_object($sqluce);
  $hladaj_uce=$riaduce->ddod;
  }
}


}
//koniec nastavenia uctu


$uloz="NO";
$zmaz="NO";
$uprav="NO";

//import z ../import/dopdol.csv a dopslu.csv
    if (( $copern == 167 OR $copern == 168 OR $copern == 155 OR $copern == 156 ) AND ( $drupoh == 1 OR $drupoh == 2 ) AND $pocstav == 1 )
    { 
    if( $kli_vduj != 9 ) { $uziv = include("../faktury/fakpoc_import.php"); }
    if( $kli_vduj == 9 ) { $uziv = include("../faktury/fakpoc_import_ju.php"); } 
    }
    if ( $copern == 155 AND $drupoh == 12 )
    {
?>
<script type="text/javascript">
if( !confirm ("Chcete importova� polo�ky \r zo s�boru ../import/dopdol.csv ?") )
         { window.close()  }
else
         { location.href='vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=156&page=1&drupoh=12'  }
</script>
<?php
    }
    if ( $copern == 156 AND $drupoh == 12 )
    {
$copern=1;

if( file_exists("../import/dopdol.csv")) echo "S�bor ../import/dopdol.csv existuje<br />";

$subor = fopen("../import/dopdol.csv", "r");
while (! feof($subor))
{
  $riadok = fgets($subor, 500);
  print "$riadok<br />";
  $pole = explode(";", $riadok);
  $x_dol = $pole[0];
  $x_ico = $pole[1];
  $x_str = $pole[2];
  $x_zak = $pole[3];
  $x_dat = $pole[4];
  $x_zk2 = $pole[5];
  $x_dn2 = $pole[6];
  $x_sp2 = $pole[7];
  $x_uce = $pole[8];
  $x_kon = $pole[9];
  $x_dok = 59000 + $x_dol;
  $dat_sql = SqlDatum($x_dat);
  $pole = explode("-", $dat_sql);
  $x_ume = $pole[1].".".$pole[0];
  if( $x_str == '0' ) $x_str = $fir_dopstr;
  if( $x_zak == '0' ) $x_zak = $fir_dopzak;

 
$sqult = "INSERT INTO F$kli_vxcf"."_dopdol ( uce,dok,doq,dol,ico,str,zak,dat,daz,das,zk2,dn2,sp2,hod,id,".
"zk1,dn1,sp1,zk0,zao,zal,ruc,uhr,zk3,zk4,dn3,dn4,sz1,sz2,sz3,sz4,fak,prf,skl,poh,".
"obj,unk,dpr,ksy,ssy,poz,txz,txp,ume)".
" VALUES ( '$x_uce', '$x_dok', '$x_dok', '$x_dol', '$x_ico', '$x_str', '$x_zak', '$dat_sql', '$dat_sql', '$dat_sql',".
" '$x_zk2', '$x_dn2', '$x_sp2', '$x_sp2', '$kli_uzid',".
" '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',".
" '', '', '', '', '', '', '', '', '$x_ume' ".
");";

$ulozene = mysql_query("$sqult"); 
}
echo "Tabulka F$kli_vxcf"."_dopdol!"." naimportovan� <br />";
fclose ($subor);

if( file_exists("../import/dopslu.csv")) echo "S�bor ../import/dopslu.csv existuje<br />";

$subor = fopen("../import/dopslu.csv", "r");
while (! feof($subor))
{
  $riadok = fgets($subor, 500);
  print "$riadok<br />";
  $pole = explode(";", $riadok);
  $x_dol = $pole[0];
  $x_slu = $pole[1];
  $x_nsl = $pole[2];
  $x_cep = $pole[3];
  $x_ced = $pole[4];
  $x_mno = $pole[5];
  $x_uce = $pole[6];
  $x_cfak = $pole[7];
  $x_kon = $pole[8];

  $x_dok = 59000 + $x_dol;

 
$sqult = "INSERT INTO F$kli_vxcf"."_dopslu ( dok,dol,slu,nsl,cep,ced,mno,dph,id,".
"fak,prf,cen,pfak,cfak,dfak,pop,pon,mer,xfak)".
" VALUES ( '$x_dok', '$x_dol', '$x_slu', '$x_nsl', '$x_cep', '$x_ced', '$x_mno', '19', '$kli_uzid',".
" '0', '0', '0', '0', '$x_cfak', '0', '', '', '', '' ".
");";

$ulozene = mysql_query("$sqult"); 
}
echo "Tabulka F$kli_vxcf"."_dopslu!"." naimportovan� <br />";
fclose ($subor);
    }

//vymazanie vsetkych poloziek dodacie listy,pociatok odber dodav
    if ( $copern == 167 AND $drupoh == 12 )
    {
?>
<script type="text/javascript">
if( !confirm ("Chcete vymaza� v�etky polo�ky \r dodac�ch listov ?") )
         { window.close()  }
else
         { location.href='vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=168&page=1&drupoh=12'  }
</script>
<?php
    }
    if ( $copern == 168 AND $drupoh == 12 )
    {
$copern=1;
$sqlt = 'TRUNCATE F'.$kli_vxcf.'_dopdol';
$vysledok = mysql_query("$sqlt");
//echo $sqlt;
if ($vysledok)
echo "Tabulka F$kli_vxcf"."_dopdol!"." vynulovan� <br />";
$sqlt = 'TRUNCATE F'.$kli_vxcf.'_dopslu';
$vysledok = mysql_query("$sqlt");
//echo $sqlt;
if ($vysledok)
echo "Tabulka F$kli_vxcf"."_dopslu!"." vynulovan� <br />";
    }
//koniec nahratia a vymazania databazy dopdol a dopslu


//vymazanie vsetkych poloziek VNF Doprava Agrostav
    if ( $copern == 267 )
    {
?>
<script type="text/javascript">
if( !confirm ("Chcete vymaza� v�etky polo�ky \r vn�tropodnikov�ch fakt�r za mesiac <?php echo $kli_vume; ?> ?") )
         { window.close()  }
else
         { location.href='vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=268&page=1&drupoh=22'  }
</script>
<?php
    }
    if ( $copern == 268 )
    {
$copern=1;
$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];

$zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_dopvnp WHERE ( ume = $kli_vume AND poh = 99 )"); 
$zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_dopslu WHERE ( cfak = 99 AND dfak = 99 AND pfak = $kli_vmes )"); 

$sqtz = "UPDATE F$kli_vxcf"."_dopslu SET pfak=0, dfak=0,".
" cfak=0, xfak=''".
" WHERE ( xfak = $kli_vume AND cfak = 98 AND dfak = 98 AND pfak = 98 )";
$upravene = mysql_query("$sqtz");
$sqtz = "UPDATE F$kli_vxcf"."_dopstzp SET pfak=0, dfak=0,".
" cfak=0, xfak=''".
" WHERE ( xfak = $kli_vume AND cfak = 98 AND dfak = 98 AND pfak = 98 )";
$upravene = mysql_query("$sqtz");

//vratenie cisla dokladov z cx01
$dsqlt = "UPDATE F$kli_vxcf"."_dodb".
" SET cfak = cx01".
" WHERE drod = 13".
"";
$dsql = mysql_query("$dsqlt");

    }
//koniec nahratia a vymazania databazy dopvnp a dopslu z DOPRAVA Agrostav

// 16=vymazanie dokladu potvrdene v vstf_u.php ak nie je pociatocny stav
if ( $copern == 16 AND $pocstav != 1 )
     {
$cislo_dok = strip_tags($_REQUEST['cislo_dok']);
$cislo_fak = strip_tags($_REQUEST['cislo_fak']);

//odpocitat polozky z sklzas
$dsqlt = "SELECT skl,cis,cen,mno FROM F$kli_vxcfskl"."_sklfak WHERE dok='$cislo_dok' AND cis > 0 ";
$dsql = mysql_query("$dsqlt");

$pzaz = mysql_num_rows($dsql);

$i = 0;
   while ($i < $pzaz )
   {
  if (@$dzak=mysql_data_seek($dsql,$i))
  {

$driadok=mysql_fetch_object($dsql);
$sqtu = "UPDATE F$kli_vxcfskl"."_sklzas SET zas=zas+($driadok->mno) WHERE skl='$driadok->skl' AND cis='$driadok->cis' AND cen='$driadok->cen'";
$upravene = mysql_query("$sqtu");

  }
$i = $i + 1;
   }

//odpocitat polozky zo sluzbyzas
$dslqlt = "SELECT slu,cen,mno FROM F$kli_vxcf"."_$tablsluzby WHERE dok='$cislo_dok'";
$dslql = mysql_query("$dslqlt");

$pzlaz = mysql_num_rows($dslql);

$j = 0;
   while ($j < $pzlaz )
   {
  if (@$dzak=mysql_data_seek($dslql,$j))
  {

$dlriadok=mysql_fetch_object($dslql);


//pripisat do zasob
$x_skl=0;
$x_cen=0;
$x_slu=$dlriadok->slu;
$x_mno=$dlriadok->mno;
$sqltu = "UPDATE F$kli_vxcf"."_$zassluzby SET zas=zas+($x_mno) WHERE ( skl=$x_skl AND slu=$x_slu AND cen=$x_cen );";
//echo $sqltu;
$upravene = mysql_query("$sqltu");

  }
$j = $j + 1;
   }

//vymaz polozky faktury
if( $drupoh == 1 OR $drupoh == 11 OR $drupoh == 21 OR $drupoh == 12 OR $drupoh == 31 OR $drupoh == 22 OR $drupoh == 42 OR $drupoh == 52 )
{
$zmazane = mysql_query("DELETE FROM F$kli_vxcfskl"."_sklfak WHERE dok='$cislo_dok' ");
$zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_$tablsluzby WHERE dok='$cislo_dok' ");
}

//vymaz polozky zauctovania
if( $drupoh == 1 ) { $zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_uctodb WHERE dok='$cislo_dok' "); 
include("../ucto/saldo_zmaz_ok.php");
} 
if( $drupoh == 2 ) { $zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_uctdod WHERE dok='$cislo_dok' "); 
include("../ucto/saldo_zmaz_ok.php");
} 

//odznac vyfakturovane pre dopravu v stazkach a dodakoch a odznac aj z kuchyne dodaky
if( $drupoh == 31 OR $drupoh == 42 OR $drupoh == 22 )
{
$sqtoz = "UPDATE F$kli_vxcf"."_dopslu SET pfak='0', cfak='0',".
" dfak='0', xfak='' ".
" WHERE cfak = $cislo_dok ";
$oznac = mysql_query("$sqtoz");

$sqtoz = "UPDATE F$kli_vxcf"."_dopstzp SET pfak='0', cfak='0',".
" dfak='0', xfak='' ".
" WHERE cfak = $cislo_dok ";
$oznac = mysql_query("$sqtoz");

$kuchyna=0;
if (File_Exists ("../kuchyna/pouzivam_kuchynu.php")) 
  { 
$kuchyna=1;
$sqtoz = "UPDATE F$kli_vxcf"."_kuchdodp SET cfak='0', dfak='0' ".
" WHERE cfak = $cislo_dok ";
$oznac = mysql_query("$sqtoz");
  }
$ubyt=0;
if (File_Exists ("../ubyt/pouzivam_ubyt.php")) 
  { 
$ubyt=1;
$sqtoz = "UPDATE F$kli_vxcf"."_ubytdodp SET cfak='0', dfak='0' ".
" WHERE cfak = $cislo_dok ";
$oznac = mysql_query("$sqtoz");
  }
}

//vymazat zo zoznamu dokladov
$zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_$tabl WHERE dok='$cislo_dok' "); 
$copern=1;
if (!$zmazane):
?>
<script type="text/javascript"> alert( " POLO�KA NEBOLA VYMAZAN� " ) </script>
<?php
endif;
if ($zmazane):
$zmaz="OK";
//uprav cislo v tabulke
if( $cisdokodd != 1 OR $drupoh == 42 )
        {
$upravene = mysql_query("UPDATE F$kli_vxcf"."_ufir SET $cisdok='$cislo_dok' WHERE $cisdok > '$cislo_dok'"); 
        }
if( $cisdokodd == 1 AND $drupoh != 42 )
        {
$zhodaradu="";
if( $fir_uctx14 == 1 AND ( $drupoh == 1 OR $drupoh == 2 ) ) { $zhodaradu="LEFT(cfak,2) = LEFT($cislo_dok,2) AND "; }

 if( $drupoh == 1  ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE $zhodaradu cfak > '$cislo_dok' AND drod = 1 AND dodb = $hladaj_ucex"; }
 if( $drupoh == 31 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 11 AND dodb = $hladaj_uce"; }
 if( $drupoh == 11 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 2 AND dodb = $hladaj_uce"; }
 if( $drupoh == 12 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 12 AND dodb = $hladaj_uce"; }
 if( $drupoh == 21 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 3 AND dodb = $hladaj_uce"; }
 if( $drupoh == 22 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 13 AND dodb = $hladaj_uce"; }
 if( $drupoh == 52 ) { $upravttt = "UPDATE F$kli_vxcf"."_dodb SET cfak='$cislo_dok' WHERE cfak > '$cislo_dok' AND drod = 14 AND dodb = $hladaj_uce"; }
 if( $drupoh == 2  ) { $upravttt = "UPDATE F$kli_vxcf"."_ddod SET cfak='$cislo_dok' WHERE $zhodaradu cfak > '$cislo_dok' AND drdo = 1 AND ddod = $hladaj_ucex"; }

 $upravene = mysql_query("$upravttt");
        }
//uprav cislo cbl ak registracka
if( $drupoh == 42 )
{
$upravcbl = mysql_query("UPDATE F$kli_vxcf"."_dopdkp SET cbl='$cislo_fak' WHERE cbl > '$cislo_fak'"); 
}
//echo "POLO�KA DOK:$cislo_dok BOLA VYMAZAN� ";
endif;

     }
//koniec vymazania ak nie poc.stav

// 16=vymazanie dokladu potvrdene v vstf_u.php ak je pociatocny stav
if ( $copern == 16 AND $pocstav == 1 )
     {
$cislo_dok = strip_tags($_REQUEST['cislo_dok']);
$cislo_fak = strip_tags($_REQUEST['cislo_fak']);

//vymazat zo zoznamu dokladov
$zmazane = mysql_query("DELETE FROM F$kli_vxcf"."_$tabl WHERE dok='$cislo_dok' "); 
$copern=1;
if (!$zmazane):
?>
<script type="text/javascript"> alert( " POLO�KA NEBOLA VYMAZAN� " ) </script>
<?php
endif;
if ($zmazane):
$zmaz="OK";
endif;
     }
//koniec vymazania poc.stav

// 55=nova kopia dokladu potvrdene v vstf_u.php
if ( $copern == 55 )
     {
$cislo_dok = strip_tags($_REQUEST['cislo_dok']);

//copern55zaloz novy doklad
$maxdok=0;
$sqldoktt = "SELECT * FROM F$kli_vxcf"."_$tabl WHERE dok > 0 AND uce = $hladaj_uce ORDER BY dok DESC LIMIT 1";
//echo $sqldoktt;
$sqldok = mysql_query("$sqldoktt");
  if (@$zaznam=mysql_data_seek($sqldok,0))
  {
  $riaddok=mysql_fetch_object($sqldok);
  $maxdok=$riaddok->dok;
  }
$newdok=1*$maxdok+1;

$h_fak = $newdok;

$sqlhh = "INSERT INTO F$kli_vxcf"."_$tabl ( dok,doq,fak,id ) VALUES ( $newdok, $newdok, $newdok, $kli_uzid )";
//echo $sqlhh;
$ulozene = mysql_query("$sqlhh"); 

//copern55skopiruj donho udaje zahlavia
$faktt = "SELECT * FROM F$kli_vxcf"."_$tabl"." WHERE dok = $cislo_dok "." ORDER BY dok";
$dsql = mysql_query("$faktt");

  if (@$dzak=mysql_data_seek($dsql,0))
  {
$friadok=mysql_fetch_object($dsql);

 $uprt = "UPDATE F$kli_vxcf"."_$tabl SET uce='$friadok->uce', dat='$friadok->dat', ume='$friadok->ume',".
" daz='$friadok->daz', das='$friadok->das', poh='$friadok->poh', skl='$friadok->skl', ico='$friadok->ico',".
" ksy='$friadok->ksy', ssy='$friadok->ssy',".
" poz='$friadok->poz', str='$friadok->str', zak='$friadok->zak', txp='$friadok->txp', txz='$friadok->txz',".
" dol='$friadok->dol', prf='$friadok->prf',".
" hod='$friadok->hod', zk0='$friadok->zk0', zk1='$friadok->zk1', zk2='$friadok->zk2', dn1='$friadok->dn1',".
" dn2='$friadok->dn2', sz1='$friadok->sz1', sz2='$friadok->sz2',".
" zk3='$friadok->zk3', zk4='$friadok->zk4', dn3='$friadok->dn3', dn4='$friadok->dn4', sz3='$friadok->sz3',".
" sz4='$friadok->sz4', sp1='$friadok->sp1', sp2='$friadok->sp2',".
" obj='$friadok->obj', unk='$friadok->unk', dpr='$friadok->dpr', zal='$friadok->zal'".
" WHERE id='$kli_uzid' AND dok='$newdok'";

$upravene = mysql_query("$uprt");
  }
  

//copern55skopiruj polozky do fakslu
$sluztt = "SELECT dok, fak, dol, prf, cpl, slu, nsl, pop, dph,".
" mno, mer, cep, ced". 
" FROM F$kli_vxcf"."_fakslu".
" WHERE dok = $cislo_dok ".
" ORDER BY cpl";
$dsql = mysql_query("$sluztt");

$pzaz = mysql_num_rows($dsql);

$i = 0;
   while ($i < $pzaz )
   {
  if (@$dzak=mysql_data_seek($dsql,$i))
  {

$driadok=mysql_fetch_object($dsql);

$sqty = "INSERT INTO F$kli_vxcf"."_fakslu ( dok,fak,dol,prf,slu,nsl,pop,dph,cep,ced,mno,mer,id )".
" VALUES ('$newdok', '$newdok', '$driadok->dol', '$driadok->prf', '$driadok->slu', '$driadok->nsl', '$driadok->pop',".
" '$driadok->dph', '$driadok->cep', '$driadok->ced', '$driadok->mno', '$driadok->mer', '$kli_uzid' );"; 

//echo $sqty;
$kopia = mysql_query("$sqty");

  }
$i = $i + 1;
   }

$copern=1;
     }
//koniec novej kopie


// 416=vymazanie rozuctovania dokladu z fakrozuct.php
if ( $copern == 416  )
     {
$cislo_dok = strip_tags($_REQUEST['cislo_dok']);

if( $drupoh == 1 ) {
$upravttt = "UPDATE F$kli_vxcf"."_fakodb SET zk0u=0, zk1u=0, zk2u=0, dn1u=0, dn2u=0, sp0u=0, sp1u=0, sp2u=0, hodu=0 WHERE dok='$cislo_dok' "; 
$upravene = mysql_query("$upravttt"); 

$zmazttt = "DELETE FROM F$kli_vxcf"."_uctodb WHERE dok='$cislo_dok' "; 
$zmazane = mysql_query("$zmazttt"); 
                   }

if( $drupoh == 2 ) {
$upravttt = "UPDATE F$kli_vxcf"."_fakdod SET zk0u=0, zk1u=0, zk2u=0, dn1u=0, dn2u=0, sp0u=0, sp1u=0, sp2u=0, hodu=0 WHERE dok='$cislo_dok' "; 
$upravene = mysql_query("$upravttt"); 

$zmazttt = "DELETE FROM F$kli_vxcf"."_uctdod WHERE dok='$cislo_dok' "; 
$zmazane = mysql_query("$zmazttt"); 
                   }

include("../ucto/saldo_zmaz_ok.php");
$copern=1;

     }
//koniec vymazania rozuctovania

//echo "ucex ".$_SESSION['nastavene_uce'];

?>

<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
  <link type="text/css" rel="stylesheet" href="../css/styl.css">
<?php
if ( $drupoh == 1 OR $drupoh == 2 )
  {
?>
<title>Zoznam fakt�r</title>
<?php
}
?>
<?php
if ( $drupoh == 11 )
  {
?>
<title>Zoznam dodac�ch listov</title>
<?php
}
?>
<?php
if ( $drupoh == 12 )
  {
?>
<title>Zoznam dodac�ch listov</title>
<?php
}
?>
<?php
if ( $drupoh == 31 )
  {
?>
<title>Zoznam odberate�sk�ch fakt�r</title>
<?php
}
?>
<?php
if ( $drupoh == 21 )
  {
?>
<title>Zoznam vn�tropod.fakt�r</title>
<?php
}
?>
<?php
if ( $drupoh == 22 )
  {
?>
<title>Zoznam vn�tropod.fakt�r</title>
<?php
}
?>
<?php
if ( $drupoh == 42 )
  {
?>
<title>Registra�n� pokladnica</title>
<?php
}
?>
<?php
if ( $drupoh == 52 )
  {
?>
<title>Predfakt�ra</title>
<?php
}
?>
  <style type="text/css">
    #Okno{ display: none; cursor: hand; width: 150px;
             position: absolute; top: 0; left: 0;
             border: "1 solid";
             background-color: "#f0f8ff";
             border-top-color: "blue";
             border-left-color: "blue";
             border-right-color: "blue";
             border-bottom-color: "blue";
             font-size: 8pt; font-family: Arial;
           }
  </style>
<SCRIPT Language="JavaScript" Src="../js/cookies.js">
</SCRIPT>
<script type="text/javascript">
//sirka a vyska okna
var sirkawin = screen.width-10;
var vyskawin = screen.height-175;


      function ZmazatDok( doklad ) 
      { 

  var ucet = document.formhl1.hladaj_uce.value;

<?php
if( $fir_xfa01 != 2 )
{
?>
  var okno = window.open("vstf_u.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&cislo_dok=" + doklad + "&copern=6&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&hladaj_uce=" + ucet + "&h_tlsl=1&h_tltv=0&rozb1=NOT&rozb2=NOT", "_self");
<?php
}
?>
<?php
if( $fir_xfa01 == 2 )
{
?>
  var okno = window.open("vstf_u.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&cislo_dok=" + doklad + "&copern=6&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&hladaj_uce=" + ucet + "&h_tltv=1&h_tlsl=0&rozb1=NOT&rozb2=NOT", "_self");
<?php
}
?>

      }


      function dajuce() 
      { 

  var ucet = document.formhl1.hladaj_uce.value;
<?php if( $sysx != 'UCT' ) { ?>
  var okno = window.open("vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&sysx=<?php echo $sysx; ?>&rozuct=<?php echo $rozuct; ?>&hladaj_uce=" + ucet + "&drupoh=<?php echo 2*1000+$drupoh;?>&page=1&copern=1", "_self");
<?php                      } ?>
<?php if( $sysx == 'UCT' ) { ?>
  var okno = window.open("vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&sysx=<?php echo $sysx; ?>&rozuct=<?php echo $rozuct; ?>&hladaj_uce=" + ucet + "&drupoh=<?php echo 1*1000+$drupoh;?>&page=1&copern=1", "_self");
<?php                      } ?>
      }


//funkcia na zobrazenie popisu 
    function UkazSkryj (text)
    {
<?php if( $_SESSION['nieie'] == 0 )  { ?>
        Okno.innerHTML = text ;
        Okno.style.display="block";
        Okno.style.top=event.clientY;
        Okno.style.left=event.clientX;
<?php                                } ?>
    }

// Kontrola cisla celeho v rozsahu x az y  
      function intg(x1,x,y,Oznam) 
      { 
       var b;
       b=x1.value;
       var anyString=b;
       Oznam.style.display="none";
         if (b == "") return true;
         else{
         if (Math.floor(b)==b && b>=x && b<=y) return true; 
         else {
         Oznam.style.display="";
         document.formv1.uloz.disabled = true;
         x1.focus();
         return false;
              } 
             }
      }

// Kontrola des.cisla v rozsahu x az y  
      function cele(x1,x,y,Oznam) 
      { 
       var b;
       b=x1.value;
       var anyString=b;
       Oznam.style.display="none";
         if (b == "") return true;
         else{
         if (b>=x && b<=y) return true; 
         else {
         Oznam.style.display="";
         document.formv1.uloz.disabled = true;
         x1.focus();
         return false;
              } 
             }
      }

<?php
//hladanie
  if ( $copern == 7 )
  {
?>
    function VyberVstup()
    {
    document.formhl1.hladaj_nai.focus();
<?php if ( $copern != 10 AND ( $drupoh == 1 OR $drupoh == 31 ) ) echo "document.formp2.pokl.disabled = true;"; ?>
    }

<?php
  }
//koniec hladania
?>
<?php
//hladanie
  if ( $copern == 9 )
  {
?>
    function VyberVstup()
    {

    }

    function ObnovUI()
    {
    document.formhl1.hladaj_uce.value='<?php echo $hladaj_uce;?>';
<?php if ( $copern != 10 AND ( $drupoh == 1 OR $drupoh == 31 ) ) echo "document.formp2.pokl.disabled = true;"; ?>
    }

<?php
  }
//koniec hladania
?>

<?php
//zobrazenie
  if ( $copern == 1 OR $copern == 2 OR $copern == 3 OR $copern == 4 OR $copern == 10 )
  {
?>
//  Kontrola cisla
    function KontrolaCisla(Vstup, Oznam)
    {
     if ( Vstup.value.search(/[^0-9]/g) != -1) { Oznam.style.display=""; document.forma3.sstrana.disabled = true; }
     else { Oznam.style.display="none"; document.forma3.sstrana.disabled = false; }
    }

    function VyberVstup()
    {
    document.forma3.page.focus();
    document.forma3.page.select();
    }

    function ObnovUI()
    {
    document.formhl1.hladaj_uce.value='<?php echo $hladaj_uce;?>';
<?php if ( $copern != 10 AND ( $drupoh == 1 OR $drupoh == 31 ) ) echo "document.formp2.pokl.disabled = true;"; ?>
    var ii=1*<?php echo strip_tags($_REQUEST['page']);?>;
    if ( ii == 1 ) document.forma2.pstrana.disabled = true;
    <?php if( $zmaz == 'OK' ) echo "Zm.style.display='';";?>
    <?php if( $uprav == 'OK' ) echo "Up.style.display='';";?>
    }

<?php
  }
?>

<?php if ( $drupoh == 1 AND $_SESSION['nieie'] == 0 )  { ?>
    function ZoznamFaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/zozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1', '_blank', '<?php echo $tlcswin; ?>' )
    }

    function ZoznamRaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/rozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1', '_blank', '<?php echo $tlcswin; ?>' )
    }
<?php  } ?>

<?php if ( $drupoh == 2 AND $_SESSION['nieie'] == 0 )  { ?>
    function ZoznamFaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/zozdok.php?copern=102&drupoh=2&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1', '_blank', '<?php echo $tlcswin; ?>' )
    }
    function ZoznamRaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/rozdok.php?copern=102&drupoh=2&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1', '_blank', '<?php echo $tlcswin; ?>' )
    }
<?php  } ?>

<?php if ( $drupoh == 1 AND $_SESSION['nieie'] == 1 )  { ?>
    function ZoznamFaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/zozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )
    }

    function ZoznamRaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/rozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )
    }
<?php  } ?>

<?php if ( $drupoh == 2 AND $_SESSION['nieie'] == 1 )  { ?>
    function ZoznamFaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/zozdok.php?copern=102&drupoh=2&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )
    }
    function ZoznamRaktur()
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var ucet = document.formhl1.hladaj_uce.value;
    window.open('../ucto/rozdok.php?copern=102&drupoh=2&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )
    }
<?php  } ?>

<?php if( $_SESSION['nieie'] == 0 )  { ?>
    function VytlacFakt(doklad)
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var cislo_dok = doklad;
    window.open('vstf_pdf.php?sysx=<?php echo $sysx; ?>&rozuct=<?php echo $rozuct; ?>&hladaj_dok=' + hladaj_dok + '&copern=20&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&cislo_dok=' + cislo_dok + '&fff=1', '_blank', '<?php echo $tlcuwin; ?>' )

    }
<?php                                } ?>

<?php if( $_SESSION['nieie'] == 1 )  { ?>
    function VytlacFakt(doklad)
    {
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var hladaj_dok = document.formhl1.hladaj_dok.value;
    var cislo_dok = doklad;
    window.open('vstf_pdf.php?sysx=<?php echo $sysx; ?>&rozuct=<?php echo $rozuct; ?>&hladaj_dok=' + hladaj_dok + '&copern=20&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&cislo_dok=' + cislo_dok + '&fff=1', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )

    }
<?php                                } ?>

<?php if ( $agrostav == 1 OR $_SERVER['SERVER_NAME'] == "localhost" )  { ?>
    function VytlacPokl(doklad)
    {
    var cislo_dok = doklad;
    window.open('pokldok_pdf.php?cislo_dok=' + cislo_dok + '&fff=1', '_blank', '<?php echo $tlcuwin; ?>' )
    }
<?php                        } ?>

<?php if ( $drupoh == 42 )  { ?>
    function uzavierka(doklad)
    {
    var cislo_dok = doklad;
    window.open('../doprava/regpok_rozpis.php?cislo_dok=' + cislo_dok + '&fff=1&regpok=<?php echo $regpok;?>', '_blank',  'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )
    }
<?php                       } ?>


function ExportFakturyCsv()
                {

window.open('../faktury/int_fakt.php?copern=55&page=1&h_sys=85&h_obdp=<?php echo $kli_vmes; ?>&drupoh=1&uprav=1&docsv=1',
 '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' );

                }

//Z ciarky na bodku
  function CiarkaNaBodku(Vstup)
  {
   if ( Vstup.value.search(/[^0-9.-]/g) != -1) { Vstup.value=Vstup.value.replace(",","."); }
  }

  </script>
</HEAD>
<BODY class="white" onload="ObnovUI(); VyberVstup();" >

<?php 


// aktualna strana
$page = strip_tags($_REQUEST['page']);
// nasledujuca strana
$npage =  $page + 1;
// predchadzajuca strana
$ppage =  $page - 1;
// pocet poloziek na stranu
$pols = 15;
if( $copern == 9 ) $pols = 900;
// zaciatok cyklu
$i = ( $page - 1 ) * $pols;
// koniec cyklu
$konc =($pols*($page-1))+($pols-1);

?>

<table class="h2" width="100%" >
<tr>
<?php if( $drupoh == 1 ) echo "<td>EuroSecom  -  Odberate�sk� fakt�ry"; ?>
<?php if( $drupoh == 11 ) echo "<td>EuroSecom  - Dodacie listy"; ?>
<?php if( $drupoh == 12 ) echo "<td>EuroSecom  - Dodacie listy"; ?>
<?php if( $drupoh == 21 ) echo "<td>EuroSecom  - Vn�tropodnikov� fakt�ry"; ?>
<?php if( $drupoh == 22 ) echo "<td>EuroSecom  - Vn�tropodnikov� fakt�ry"; ?>
<?php if( $drupoh == 42 ) echo "<td>EuroSecom  - Registra�n� pokladnica"; ?>
<?php if( $drupoh == 52 ) echo "<td>EuroSecom  - Predfakt�ry"; ?>
<?php if( $drupoh == 2 ) echo "<td>EuroSecom  -  Dod�vate�sk� fakt�ry"; ?>
<?php if( $drupoh == 31 ) echo "<td>EuroSecom  -  Odberate�sk� fakt�ry"; ?>
<?php if( $pocstav == 1 ) echo " - Po�iato�n� stav"; ?>
</td>
<td align="right"><span class="login"><?php echo "UME $kli_vume FIR$kli_vxcf-$kli_nxcf  login: $kli_uzmeno $kli_uzprie / $kli_uzid ";?></span></td>
</tr>
</table>
<br />
<div id="Okno"></div>

<?php
// toto je cast na zobrazenie tabulky a prechody medzi stranami
// 1=volanie z menu.php
// 2=dalsia strana
// 3=predosla strana
// 4=prejst na stranu
// 5=nova polozka
// 6=mazanie
// 7=hladanie
// 8=uprava
// 9=hladanie
$citcbl='';
if( $drupoh == 42 ) $citcbl=", cbl";

if ( $copern == 1 || $copern == 2 || $copern == 3 || $copern == 4 || $copern == 7 || $copern == 9 OR $copern == 10 )
     {

if ( $copern != 1 && $copern != 2 && $copern != 3 && $copern != 4 && $copern != 7 && $copern != 9 AND $copern != 10 ) break;

    do
    {
// zobraz vsetko co je v tabulke
if ( $copern == 1 || $copern == 2 || $copern == 3 || $copern == 4 || $copern == 7 || $copern == 9 OR $copern == 10 )
  {
//[[[[[

$hladaj_ucex=$hladaj_uce;

//ak viac rad pre jednu analytiku
if( $fir_uctx14 == 1 AND ( $drupoh == 1 OR $drupoh == 2 ))
{
if( $drupoh == 1 )
     {
$sqluce = mysql_query("SELECT dodb,nodb,ucod FROM F$kli_vxcf"."_dodb WHERE ( dodb = $hladaj_uce ) ORDER BY dodb"); 
  if (@$zaznam=mysql_data_seek($sqluce,0))
  {
  //echo "idem";
  $riaduce=mysql_fetch_object($sqluce);
  $hladaj_ucex=$riaduce->ucod;
  }
     }
if( $drupoh == 2 )
     {
$sqluce = mysql_query("SELECT ddod,ndod,ucdo FROM F$kli_vxcf"."_ddod WHERE ( ddod = $hladaj_uce ) ORDER BY ddod"); 
  if (@$zaznam=mysql_data_seek($sqluce,0))
  {
  //echo "idem";
  $riaduce=mysql_fetch_object($sqluce);
  $hladaj_ucex=$riaduce->ucdo;
  }
     }
$_SESSION['nastavene_uce'] = $hladaj_uce;
}



$akeume=">= ".$kli_vume;
$hladaj_ucep="= ".$hladaj_ucex;
$order="dok";
if( $drupoh == 22 ) { $akeume="= ".$kli_vume; $hladaj_ucep=" > 0 "; }
if( $pocstav == 1 ) { $akeume="> 0 "; $order="F".$kli_vxcf."_".$tabl.".ume,dok";}
$chodu="";
if( $sysx == 'UCT' ) $chodu="hodu,poh,";
if( $drupoh == 42 ) $chodu="ruc,";
if( $drupoh == 11 ) $chodu="unk,";

$sqltt = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, F$kli_vxcf"."_ico.nai,".
" fak, dol, prf, str, zak, hod,".$chodu." strv, zakv".$citcbl. 
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN F$kli_vxcf"."_dodb".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dodb.dodb".
" WHERE ( F$kli_vxcf"."_$tabl.uce $hladaj_ucep AND F$kli_vxcf"."_$tabl.dok > 0 AND F$kli_vxcf"."_$tabl.ume $akeume )".
" OR isnull( F$kli_vxcf"."_$tabl.ume) ".
" OR isnull( F$kli_vxcf"."_$tabl.dat) OR isnull( F$kli_vxcf"."_$tabl.uce) OR F$kli_vxcf"."_$tabl.uce = ''". 
" ORDER BY ".$order." DESC".
"";

//echo $sqltt;

$sql = mysql_query("$sqltt");

  }
// zobraz hladanie vo vsetkych prijemkach
if ( $copern == 9 )
  {

$hladaj_nai = strip_tags($_REQUEST['hladaj_nai']);
$hladaj_dok = strip_tags($_REQUEST['hladaj_dok']);
$hladaj_dat = strip_tags($_REQUEST['hladaj_dat']);
$hladaj_uce = strip_tags($_REQUEST['hladaj_uce']);
$hladaj_unk = 1*$_REQUEST['hladaj_unk'];

if ( $hladaj_unk == 1 ) {

if( $drupoh == 11 )
{
$sqltx = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, F$kli_vxcf"."_ico.nai, unk, ".
" fak, dol, prf, str, zak, hod,".$chodu." strv, zakv".$citcbl. 
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN F$kli_vxcf"."_dodb".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dodb.dodb".
" WHERE F$kli_vxcf"."_$tabl.uce $hladaj_ucep AND unk = 1 ".
" ORDER BY dok DESC".
"";

}

$sql = mysql_query("$sqltx");

                        }

if ( $hladaj_nai != "" ) {

$chlad_nai = 1*$hladaj_nai;

if( $drupoh == 1 OR $drupoh == 2 OR $drupoh == 11 OR $drupoh == 21 OR $drupoh == 12 OR $drupoh == 31 OR $drupoh == 22 OR $drupoh == 42 OR $drupoh == 52 )
{
$sqltx = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, F$kli_vxcf"."_ico.nai,".
" fak, dol, prf, str, zak, hod,".$chodu." strv, zakv".$citcbl. 
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN F$kli_vxcf"."_dodb".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dodb.dodb".
" WHERE F$kli_vxcf"."_$tabl.uce $hladaj_ucep AND ( F$kli_vxcf"."_ico.nai LIKE '%$hladaj_nai%' OR F$kli_vxcf"."_$tabl.ico = $chlad_nai ) ".
" ORDER BY dok DESC".
"";

}

$sql = mysql_query("$sqltx");

                        }

if ( $hladaj_dat != "" ) {

$chladaj_dat=1*$hladaj_dat;
if( $chladaj_dat == 1 OR $chladaj_dat == 2 OR $chladaj_dat == 3 OR $chladaj_dat == 4 OR $chladaj_dat == 5 OR $chladaj_dat == 6 OR $chladaj_dat == 7 OR $chladaj_dat == 8 OR $chladaj_dat == 9 OR $chladaj_dat == 10 OR $chladaj_dat == 11 OR $chladaj_dat == 12 ) 
{ $hladaj_dat=$chladaj_dat.".".$kli_vrok; }

    if( strlen($hladaj_dat) == 6 OR strlen($hladaj_dat) == 7 )
         {
$sqltt = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, F$kli_vxcf"."_ico.nai,".
" fak, dol, prf, str, zak, hod,".$chodu." strv, zakv".$citcbl. 
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN F$kli_vxcf"."_dodb".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dodb.dodb".
" WHERE F$kli_vxcf"."_$tabl.uce $hladaj_ucep AND F$kli_vxcf"."_$tabl.ume = $hladaj_dat ".
" ORDER BY dok DESC".
"";
         }  

    if( strlen($hladaj_dat) != 6 AND strlen($hladaj_dat) != 7 )
         {
         $datsql = SqlDatum($hladaj_dat);
$sqltt = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, F$kli_vxcf"."_ico.nai,".
" fak, dol, prf, str, zak, hod,".$chodu." strv, zakv".$citcbl.
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN F$kli_vxcf"."_dodb".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dodb.dodb".
" WHERE F$kli_vxcf"."_$tabl.uce $hladaj_ucep AND F$kli_vxcf"."_$tabl.dat = '$datsql' ".
" ORDER BY dok DESC".
"";

         } 

$sql = mysql_query("$sqltt");
}

if ( $hladaj_dok != "" ) {

$sqltt = "SELECT uce, dok, DATE_FORMAt(dat, '%d.%m.%Y' ) AS dat, F$kli_vxcf"."_$tabl.ico, ".
" fak, dol, prf, str, zak, hod,".$chodu." F$kli_vxcf"."_ico.nai ".$citcbl. 
" FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" WHERE ".
" ( F$kli_vxcf"."_$tabl.dok = '$hladaj_dok' OR F$kli_vxcf"."_$tabl.fak = '$hladaj_dok' OR F$kli_vxcf"."_$tabl.dol = '$hladaj_dok' )".
" ORDER BY dok DESC".
"";
//echo $sqltt;
$sql = mysql_query("$sqltt");
}

  }

// celkom poloziek
$cpol = mysql_num_rows($sql);
$npol = $cpol + 1;
// pocet stran
$xstr =1*(ceil($cpol / $pols));
if ( $xstr == 0 ) $xstr=1;
?>

<table class="fmenu" width="100%" >

<?php
//nezobraz hladanie pre novu,upravu a mazanie
if ( $copern != 5 AND $copern != 6 AND $copern != 8 )
     {
$hdrupoh=$drupoh;
if ( $rozuct == 'ANO' ) $hdrupoh=1*1000+$drupoh;
?>
<tr>
<FORM name="formhl1" class="hmenu" method="post" action="vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&drupoh=<?php echo $hdrupoh;?>&page=1&copern=9
&rozuct=<?php echo $rozuct;?>&sysx=<?php echo $sysx;?>&hladaj_uce=<?php echo $hladaj_uce; ?>" >
<td class="hmenu" ><img src='../obr/hladaj.png' width=15 height=10 border=0 alt="Vyh�ad�vanie" title="Vyh�ad�vanie" >
<?php
  if ( $drupoh == 11 )
  {
?>
<td class="hmenu" >
<a href="#" onClick="window.open('vstfak.php?copern=9&page=1&drupoh=11&hladaj_unk=1', '_self')">
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="Dodacie listy unk 1" ></a>
</td>
<?php
     }
?>
<?php
  if ( $drupoh == 42 )
  {
$ajmes=0;
?>
<td class="hmenu" >
<a href="#" title="K�pia posledn�ho pokladni�n�ho dokladu" onClick="window.open('../doprava/regpok_pdf.php?copern=490&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">kPD
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="K�pia posledn�ho pokladni�n�ho dokladu" ></a>
<a href="#" title="K�pia poslednej dennej uz�vierky" onClick="window.open('../doprava/regpok_pdf.php?copern=290&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">kDU
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="K�pia poslednej dennej uz�vierky" ></a>
<?php if( $ajmes == 1 ) { ?>
<a href="#" title="K�pia poslednej mesa�nej uz�vierky" onClick="window.open('../doprava/regpok.php?copern=390&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">kMU
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="K�pia poslednej mesa�nej uz�vierky" ></a>
<?php                   } ?>
<a href="#" title="Nastavenie registra�nej pokladnice" onClick="window.open('../doprava/regpok.php?copern=1&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">Set
<img src='../obr/naradie.png' width=20 height=15 border=0 title="Nastavenie registra�nej pokladnice" ></a>
</td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ><a href="#" title="Tla� dennej uz�vierky" onClick="window.open('../doprava/regpok_pdf.php?copern=200&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">DU
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="Tla� dennej uz�vierky" ></a>
<?php if( $ajmes == 1 ) { ?>
<td class="hmenu" ><a href="#" title="Tla� mesa�nej uz�vierky" onClick="window.open('../doprava/regpok.php?copern=300&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">MU
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="Tla� mesa�nej uz�vierky" ></a>
<?php                   } ?>
<td class="hmenu" ><a href="#" title="Tla� priebe�nej uz�vierky" onClick="window.open('../doprava/regpok_pdf.php?copern=400&page=1&drupoh=42', '_blank', 'width=1080, height=900, top=0, left=10, status=yes, resizable=yes, scrollbars=yes' )">PU
<img src='../obr/ziarovka.png' width=20 height=15 border=0 title="Tla� priebe�nej uz�vierky" ></a>
<?php
     }
?>
<?php
  if ( ( $drupoh == 2 OR $drupoh == 1 OR $drupoh == 12 ) AND $pocstav == 1 AND $kli_uzall > 3000 )
  {
?>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ><a href='../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>
&copern=167&page=1&drupoh=<?php echo $drupoh; ?>&pocstav=<?php echo $pocstav; ?>'>
<img src='../obr/kos.png' width=20 height=15 border=0 alt="Vymazanie v�etk�ch polo�iek"></a>
<td class="hmenu" ><a href='../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>
&copern=155&page=1&drupoh=<?php echo $drupoh; ?>&pocstav=<?php echo $pocstav; ?>'>
<img src='../obr/import.png' width=20 height=15 border=0 alt="Import �dajov z TXT"></a>
<?php
     }
?>
<?php
  if ( $drupoh == 22 AND $fir_fico == '31419623' )
  {
?>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ><a href='../doprava/vnfpdf.php?copern=10&page=1&drupoh=22' target="_blank">
<img src='../obr/pdf.png' width=20 height=15 border=0 alt="Tla� v�etk�ch vn�tropodnikov�ch fakt�r za mesiac <?php echo $kli_vume;?>"></a>
<td class="hmenu" ></td>
<td class="hmenu" ></td>
<td class="hmenu" ><a href='vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=267&page=1&drupoh=22'>
<img src='../obr/kos.png' width=20 height=15 border=0 alt="Vymazanie v�etk�ch vn�tropodnikov�ch fakt�r za mesiac <?php echo $kli_vume;?>"></a>
<?php
     }
?>
</tr>
<tr>
<?php
if( $drupoh != 2 AND $drupoh != 42 )
{
if( $drupoh == 1 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 1 ) ORDER BY dodb"); }
if( $drupoh == 31 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 11 ) ORDER BY dodb"); }
if( $drupoh == 11 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 2 ) ORDER BY dodb"); }
if( $drupoh == 12 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 12 ) ORDER BY dodb"); }
if( $drupoh == 21 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 3 ) ORDER BY dodb"); }
if( $drupoh == 22 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 13 ) ORDER BY dodb"); }
if( $drupoh == 52 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dodb WHERE ( drod = 14 ) ORDER BY dodb"); }
?>
<td class="hmenu">
<select class="hvstup" size="1" name="hladaj_uce" id="hladaj_uce" value="<?php echo $hladaj_uce;?>"  
 onchange="dajuce();" >
<?php while($zaznam=mysql_fetch_array($sqls)):?>
<option value="<?php echo $zaznam["dodb"];?>" >
<?php 
$polmen = $zaznam["nodb"];
$poltxt = SubStr($polmen,0,20);
?>
<?php echo $zaznam["dodb"];?></option>
<?php endwhile;?>
</select>
</td>
<?php
}
?>
<?php
if( $drupoh == 2  )
{
if( $drupoh == 2 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_ddod WHERE ( drdo = 1 ) ORDER BY ucdo"); }
?>
<td class="hmenu">
<select class="hvstup" size="1" name="hladaj_uce" id="hladaj_uce" value="<?php echo $hladaj_uce;?>"  
 onchange="dajuce();" >
<?php while($zaznam=mysql_fetch_array($sqls)):?>
<option value="<?php echo $zaznam["ddod"];?>" >
<?php 
$polmen = $zaznam["ndod"];
$poltxt = SubStr($polmen,0,20);
?>
<?php echo $zaznam["ddod"];?></option>
<?php endwhile;?>
</select>
</td>
<?php
}
?>
<?php
if( $drupoh == 42  )
{
if( $drupoh == 42 ) { $sqls = mysql_query("SELECT * FROM F$kli_vxcf"."_dpok WHERE ( drpk = 9 ) ORDER BY dpok"); }
?>
<td class="hmenu">
<select class="hvstup" size="1" name="hladaj_uce" id="hladaj_uce" value="<?php echo $hladaj_uce;?>"  
 onchange="dajuce();" >
<?php while($zaznam=mysql_fetch_array($sqls)):?>
<option value="<?php echo $zaznam["dpok"];?>" >
<?php 
$polmen = $zaznam["npok"];
$poltxt = SubStr($polmen,0,20);
?>
<?php echo $zaznam["dpok"];?></option>
<?php endwhile;?>
</select>
</td>
<?php
}
?>


<td class="hmenu"><input type="text" name="hladaj_dok" id="hladaj_dok" size="15" value="<?php echo $hladaj_dok;?>" />
<td class="hmenu"><input type="text" name="hladaj_dat" id="hladaj_dat" size="8" value="<?php echo $hladaj_dat;?>" onkeyup="CiarkaNaBodku(this);" />
<td class="hmenu"><input type="text" name="hladaj_nai" id="hladaj_nai" size="30" value="<?php echo $hladaj_nai;?>" /> 
<td class="obyc" align="left"><INPUT type="submit" id="hlad1" name="hlad1" value="H�ada�" ></td>
</FORM>
<FORM name="formhl2" class="hmenu" method="post" action="vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&drupoh=<?php echo $hdrupoh;?>&page=1&copern=1
&rozuct=<?php echo $rozuct;?>&sysx=<?php echo $sysx;?>&hladaj_uce=<?php echo $hladaj_uce; ?>" >
<td class="obyc" align="left"><INPUT type="submit" id="hlad2" name="hlad2" value="V�etko" ></td>
<td class="hmenu"></td>
<td class="hmenu"></td>
<?php
if ( ( $drupoh == 1 OR $drupoh == 2 ) AND $pocstav != 1 )
{
?>
<td class="hmenu">
<a href="#" onClick="ZoznamFaktur();">
<img src='../obr/orig.png' width=15 height=15 border=0 alt="Zoznam fakt�r PDF" title="Zoznam fakt�r PDF"></a>
<a href="#" onClick="ZoznamRaktur();">
<img src='../obr/pdf.png' width=15 height=15 border=0 alt="Zoznam fakt�r s roz��tovan�m PDF" title="Zoznam fakt�r s roz��tovan�m PDF" ></a>
</td>
<?php
}
?>

<?php
if ( $drupoh == 2 )
  {
?>
<td class="hmenu">
<a href="#" onClick="window.open('vstf_importorangexml.php?copern=1&drupoh=<?php echo $drupoh;?>&page=1&cislo_uce=<?php echo $hladaj_uce;?>', '_self' )">
<img src='../obr/import.png' width=15 height=15 border=0 title="Import ORANGE fakt�r" ></a>
</td>
<td class="hmenu">
<a href="#" onClick="window.open('vstf_importfakxml.php?copern=1&drupoh=<?php echo $drupoh;?>&page=1&cislo_uce=<?php echo $hladaj_uce;?>', '_self' )">
<img src='../obr/import.png' width=15 height=15 border=0 title="Import XML fakt�r" ></a>
</td>
<?php
  }
?>


<?php
if ( $drupoh == 1 AND $pocstav != 1 )
  {
?>
<td class="hmenu">
<a href="#" onClick="ExportFakturyCsv();">
<img src='../obr/export.png' width=15 height=15 border=0 title="Export odberate�sk�ch fakt�r za <?php echo $kli_vume;?> do CSV" ></a>
</td>
<?php
  }
?>
</tr>
</FORM>
<?php
     }
?>
<FORM name="formp2" class="obyc" method="post" action="../ucto/vspk_u.php?drupoh=<?php echo $drupoh;?>&page=1&copern=55" >
<tr>
<?php
if( $hladaj_dat == '' ) $hladaj_dat=$kli_vume;
$pole = explode(".", $hladaj_dat);
$mesiac_dat=1*$pole[0];
$rok_dat=1*$pole[1];
$mesiac_dap=$mesiac_dat-1;
if( $mesiac_dap == 0 ) $mesiac_dap=1;
$mesiac_dan=$mesiac_dat+1;
if( $mesiac_dan > 12 ) $mesiac_dan=12;
$kli_pume=$mesiac_dap.".".$rok_dat;
$kli_nume=$mesiac_dan.".".$rok_dat;
  if ( $drupoh == 1 OR $drupoh == 31 )
  {
?>
<th class="hmenu">��et<th class="hmenu">Doklad - Fakt�ra
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" title="Doklady za mesiac <?php echo $kli_pume; ?>"></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" title="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu">Odberate�<th class="hmenu">STR - Z�K<th class="hmenu"><?php if( $sysx == 'UCT' AND $pocstav != 1 ) { echo '��tovan�/'; } ?>Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
<?php
  if ( $drupoh == 11 OR $drupoh == 12 )
  {
?>
<th class="hmenu">��et<th class="hmenu">Doklad - Dodac� list
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" title="Doklady za mesiac <?php echo $kli_pume; ?>" ></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" title="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu">Odberate�<th class="hmenu">STR - Z�K<th class="hmenu">Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
<?php
  if ( $drupoh == 21 OR $drupoh == 22 )
  {
?>
<th class="hmenu"><br />��et<th class="hmenu"><br />Doklad - VNF
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" ></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu"><br />Odberate� - STR - Z�K<th class="hmenu">Dod�vate� STR-Z�K<th class="hmenu"><br />Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
<?php
  if ( $drupoh == 2 )
  {
?>
<th class="hmenu">��et<th class="hmenu">Doklad - Fakt�ra
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" title="Doklady za mesiac <?php echo $kli_pume; ?>" ></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" title="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu">Dod�vate�<th class="hmenu">STR - Z�K<th class="hmenu"><?php if( $sysx == 'UCT' AND $pocstav != 1 ) { echo '��tovan�/'; } ?>Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
<?php
  if ( $drupoh == 42 )
  {
?>
<th class="hmenu"><br />��et<th class="hmenu"><br />Doklad - ��slo v dni
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" ></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu"><br />Odberate�<th class="hmenu">DKP<th class="hmenu"><br />Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
<?php
  if ( $drupoh == 52 )
  {
?>
<th class="hmenu">��et<th class="hmenu">Doklad - Predfakt�ra
<th class="hmenu">
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_pume; ?>', '_self' )">
<img src='../obr/prev.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_pume; ?>" ></a>
DAT/UME
<a href="#" onClick="window.open('../faktury/vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=9&page=1&hladaj_uce=<?php echo $hladaj_uce; ?>
&drupoh=<?php echo $drupoh; ?>&hladaj_dat=<?php echo $kli_nume; ?>', '_self' )">
<img src='../obr/next.png' width=10 height=10 border=0 alt="Doklady za mesiac <?php echo $kli_nume; ?>" ></a>
<th class="hmenu">Odberate�<th class="hmenu">STR - Z�K<th class="hmenu">Hodnota
<th class="hmenu">Tla�<th class="hmenu">Uprav<th class="hmenu">Zma�<th class="hmenu">Orig<th class="hmenu">Ozn
<?php
  }
?>
</tr>

<?php
   while ($i <= $konc )
   {
?>
<?php
  if (@$zaznam=mysql_data_seek($sql,$i))
  {
$riadok=mysql_fetch_object($sql);
?>
<tr>
<td class="fmenu" width="10%" ><?php echo $riadok->uce;?></td>
<?php
  if ( $drupoh == 1 OR $drupoh == 2 OR $drupoh == 21 OR $drupoh == 31 OR $drupoh == 22 OR $drupoh == 42 )
  {
?>
<td class="fmenu" width="15%" >
<?php
$uctminusdok=$riadok->hodu-$riadok->hod;
  if ( $sysx == 'UCT' AND $kli_vduj >= 0 AND $pocstav != 1 )
  {
?>
<a href="#" onClick="window.open('../faktury/vstf_t.php?sysx=<?php echo $sysx; ?>&hladaj_uce=<?php echo $hladaj_uce; ?>
&rozuct=ANO&copern=20&drupoh=<?php echo $drupoh;?>
&page=<?php echo $page;?>&h_tlsl=1&rozb1=NOT&rozb2=NOT&cislo_dok=<?php echo $riadok->dok;?>&h_ico=<?php echo $riadok->ico;?>
&h_uce=<?php echo $riadok->uce;?>&h_unk=<?php echo $riadok->unk;?>&h_poh=<?php echo $riadok->poh;?>', '_self' ); window.name = 'zoznam'; ">
<img src='../obr/zoznam.png' width=15 height=12 border=0 alt="Roz��tovanie dokladu" title="Roz��tovanie dokladu" ></a>
<?php
  }
?>
<span id="dokfak" onclick="ListaFakUct(<?php echo $riadok->fak;?>);" ><?php echo $riadok->dok;?> - <?php echo $riadok->fak;?></span>
<?php if( $uctminusdok != 0 AND $riadok->hod != 0 AND $sysx == 'UCT' AND $kli_vduj >= 0 AND $pocstav != 1 )
 echo " <img src='../obr/pozor.png' width=12 height=12 border=0 alt='Doklad nie je spr�vne roz��tovan�' title='Doklad nie je spr�vne roz��tovan�' >"; ?>
</td>
<?php
  }
?>
<?php
  if ( $drupoh == 11 OR $drupoh == 12 )
  {
?>
<td class="fmenu" width="15%" ><?php echo $riadok->dok;?> - <?php echo $riadok->dol;?>

<?php 
if( $drupoh == 11 AND $riadok->unk == "1" ) 
  {
 echo " <img src='../obr/pozor.png' width=12 height=12 border=0 title='Dodac� list unk=1' >";
  }
?>

</td>
<?php
  }
?>
<?php
  if ( $drupoh == 52 )
  {
?>
<td class="fmenu" width="15%" ><?php echo $riadok->dok;?> - <?php echo $riadok->prf;?></td>
<?php
  }
?>
<td class="fmenu" width="8%" >
<?php if ( $drupoh == 42 ) { ?>
<a href="#" title="Rozpis dennej uz�vierky za <?php echo $riadok->dat;?>" onclick="uzavierka(<?php echo $riadok->dok;?>)" >
<?php                      } ?>
<?php echo $riadok->dat;?>
<?php if ( $drupoh == 42 ) { ?>
</a>
<?php                      } ?>
</td>
<?php
if( $drupoh == 1 OR $drupoh == 2 OR $drupoh == 11 OR $drupoh == 12 OR $drupoh == 31 OR $drupoh == 52 )
{
?>
<td class="fmenu" width="28%" >
<span id="dokfak" onclick="ListaIcoUct(<?php echo $riadok->ico;?>);" ><?php echo $riadok->ico;?> <?php echo $riadok->nai;?></span></td>
<td class="fmenu" width="6%" ><?php echo $riadok->str;?> - <?php echo $riadok->zak;?></td>
<?php
}
?>
<?php
if( $drupoh == 21 OR $drupoh == 22 )
{
?>
<td class="fmenu" width="28%" ><?php echo $riadok->ico;?> <?php echo $riadok->nai;?> - <?php echo $riadok->str;?> - <?php echo $riadok->zak;?></td>
<td class="fmenu" width="6%" ><?php echo $riadok->strv;?> - <?php echo $riadok->zakv;?></td>
<?php
}
?>
<?php
if( $drupoh == 42 )
{
?>
<td class="fmenu" width="28%" ><?php echo $riadok->ico;?> <?php echo $riadok->nai;?></td>
<td class="fmenu" width="6%" ><?php echo $riadok->txp;?></td>
<?php
}
?>
<?php
$tlaclenpdf=1;
if( $kli_vrok < 2014 ) { $tlaclenpdf=0; }
if( $_SESSION['nieie'] == 1 ) { $tlaclenpdf=1; }
?>
<td class="fmenu" width="10%" align="right"><?php if( $sysx == 'UCT' AND $pocstav != 1 ) { echo $riadok->hodu.' / '; } ?><?php echo $riadok->hod;?></td>
<td class="fmenu" width="7%" >
<?php if( $tlaclenpdf == 0 AND $drupoh != 42 ) { ?>
<a href="#" onClick="window.open('vstf_t.php?copern=20&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&sysx=<?php echo $sysx;?>
&cislo_dok=<?php echo $riadok->dok;?>', '_blank', '<?php echo $tlcuwin; ?>' )">
<img src='../obr/tlac.png' width=15 height=10 border=0 alt="Tla� vybran�ho dokladu HTML" title="Tla� vybran�ho dokladu HTML" ></a>
<?php                                          } ?>
<?php if( $drupoh == 42 )  { ?>
<a href="#" onClick="window.open('../doprava/regpok_pdf.php?copern=20&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&sysx=<?php echo $sysx;?>
&cislo_dok=<?php echo $riadok->dok;?>&regpok=<?php echo $regpok;?>', '_blank', '<?php echo $tlcuwin; ?>' )">
<img src='../obr/tlac.png' width=15 height=10 border=0 title="Tla� vybran�ho dokladu " ></a>
<?php                                                 } ?>

<?php
if ( $drupoh == 1 OR $drupoh == 2 OR $drupoh == 11 OR $drupoh == 31 OR $drupoh == 21 OR $drupoh == 12 OR $drupoh == 22 OR $drupoh == 52 )
     {
?>
<a href="#" onClick="VytlacFakt(<?php echo $riadok->dok;?>);">
<img src='../obr/pdf.png' width=15 height=10 border=0 alt="Tla� vybran�ho dokladu PDF" title="Tla� vybran�ho dokladu PDF"></a>
<?php
     }
?>
</td>
<td class="fmenu" width="4%" >
<?php
$ukazzmaz=1;
if( $drupoh == 42 ) { $nezar = 1*$riadok->ruc; }
if( $drupoh == 42 AND $nezar != 0 ) { $ukazzmaz = 0; }
if( $drupoh == 42 AND $kli_uzid == 17 ) { $ukazzmaz = 1; }
if( $drupoh == 42 AND $kli_uzid == 114 AND $_SERVER['SERVER_NAME'] == "www.educto.sk" ) { $ukazzmaz = 1; }

if( $copern != 10 AND $ukazzmaz == 1  )
{
?>
<a href='vstf_u.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=8&drupoh=<?php echo $drupoh;?>&page=<?php echo $page;?>&hladaj_uce=<?php echo $hladaj_uce; ?>
<?php
if( $fir_xfa01 == 1 )
{
?>
&h_tlsl=1&h_tltv=0&rozb1=NOT&rozb2=NOT
<?php
}
?>
<?php
if( $fir_xfa01 == 2 )
{
?>
&h_tltv=1&h_tlsl=0&rozb1=NOT&rozb2=NOT
<?php
}
?>
&cislo_dok=<?php echo $riadok->dok;?>&h_fak=<?php echo $riadok->fak;?>&h_dol=<?php echo $riadok->dol;?>
&h_prf=<?php echo $riadok->prf;?>'>
<img src='../obr/uprav.png' width=15 height=10 border=0 alt="�prava vybran�ho dokladu" title="�prava vybran�ho dokladu" onclick="window.name = 'zoznam';"></a></td>
<?php
}
?>
<td class="fmenu" width="4%" >
<?php
if( $copern != 10 AND $ukazzmaz == 1 AND $kli_nemazat != 1  )
{
?>
<img src='../obr/zmaz.png' onclick='ZmazatDok(<?php echo $riadok->dok;?>)' width=15 height=10 border=0 alt="Vymazanie vybran�ho dokladu" title="Vymazanie vybran�ho dokladu" ></a></td></a>
<?php
}
?>
</td>
<td class="fmenu" width="4%" >
<?php
//aky subor existuje podla toho daj koncovku
$jesub=0;
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/d$riadok->dok.pdf"))  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/d<?php echo $riadok->dok;?>.pdf' target="_blank">
<img src='../obr/pdf.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
<?php
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/d$riadok->dok.jpg") AND $jesub == 0 )  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/d<?php echo $riadok->dok;?>.jpg' target="_blank">
<img src='../obr/orig.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
<?php
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/d$riadok->dok.bmp") AND $jesub == 0 )  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/d<?php echo $riadok->dok;?>.bmp' target="_blank">
<img src='../obr/orig.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
<?php
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/d$riadok->dok.gif") AND $jesub == 0 )  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/d<?php echo $riadok->dok;?>.gif' target="_blank">
<img src='../obr/orig.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
<?php
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/d$riadok->dok.png") AND $jesub == 0 )  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/d<?php echo $riadok->dok;?>.png' target="_blank">
<img src='../obr/orig.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
<?php
if (File_Exists ("../dokumenty/FIR$kli_vxcf/$adrdok/dd$riadok->dok.pdf"))  
{
$jesub=1;
?>
<a href='../dokumenty/FIR<?php echo $kli_vxcf;?>/<?php echo $adrdok;?>/dd<?php echo $riadok->dok;?>.pdf' target="_blank">
<img src='../obr/pdf.png' width=15 height=10 border=0 alt="Zobrazenie origin�lu dokladu" title="Zobrazenie origin�lu dokladu" ></a>
<?php
} 
?>
</td>
<td class="fmenu" width="4%" >
<input type="checkbox" name="h_tl<?php echo $i; ?>" value="<?php echo $riadok->dok;?>" width=10 height=10 />
</td>
</tr>
<?php
  }
$i = $i + 1;
   }
if ( $copern != 5 AND $copern != 8 AND $copern != 6 ) echo "</table>";
?>
<table class="fmenu" width="100%" >
<tr>
<td class="bmenu" width="80%" >
<?php echo "Strana:$page  Celkom polo�iek/str�n v celej tabulke:$cpol/$xstr ";?>
</td>
<td class="bmenu" width="20%" align="center">
<?php
if ( $drupoh == 1 OR $drupoh == 31 )
{
?>
<input type="checkbox" name="uhradp" value="1"  onmouseover="UkazSkryj('Uhradi� v hotovosti<br />vybran� doklady<br />za�krtnite a OK');"
 onmouseout="Okno.style.display='none';" onclick="document.formp2.pokl.disabled = false;">
<INPUT type="submit" id="pokl" name="pokl" value="OK" />
<?php
}
?>
<?php
if ( $drupoh == 11 )
{
?>
<input type="checkbox" name="dodfak" value="1" >
<img src='../obr/ok.png' width=15 height=15 border=0 onclick="dodacieFak();" title="Vyfakt�rova� vybran� dodacie listy na jednu fakt�ru ( maxim�lne 10 riadkov ), ak s�hlas�te �a�krtnite checkbox" >
<script type="text/javascript">
    function dodacieFak()
    {
    var dodfak = 0;
    if( document.formp2.dodfak.checked ) var dodfak=1;
    var h_t10 = 0;
    <?php if( $cpol > 0 ) { echo "if( document.formp2.h_tl0.checked ) var h_t10=document.formp2.h_tl0.value;"; } ?>
    var h_t11 = 0;
    <?php if( $cpol > 1 ) { echo "if( document.formp2.h_tl1.checked ) var h_t11=document.formp2.h_tl1.value;"; } ?>
    var h_t12 = 0;
    <?php if( $cpol > 2 ) { echo "if( document.formp2.h_tl2.checked ) var h_t12=document.formp2.h_tl2.value;"; } ?>
    var h_t13 = 0;
    <?php if( $cpol > 3 ) { echo "if( document.formp2.h_tl3.checked ) var h_t13=document.formp2.h_tl3.value;"; } ?>
    var h_t14 = 0;
    <?php if( $cpol > 4 ) { echo "if( document.formp2.h_tl4.checked ) var h_t14=document.formp2.h_tl4.value;"; } ?>
    var h_t15 = 0;
    <?php if( $cpol > 5 ) { echo "if( document.formp2.h_tl5.checked ) var h_t15=document.formp2.h_tl5.value;"; } ?>
    var h_t16 = 0;
    <?php if( $cpol > 6 ) { echo "if( document.formp2.h_tl6.checked ) var h_t16=document.formp2.h_tl6.value;"; } ?>
    var h_t17 = 0;
    <?php if( $cpol > 7 ) { echo "if( document.formp2.h_tl7.checked ) var h_t17=document.formp2.h_tl7.value;"; } ?>
    var h_t18 = 0;
    <?php if( $cpol > 8 ) { echo "if( document.formp2.h_tl8.checked ) var h_t18=document.formp2.h_tl8.value;"; } ?>
    var h_t19 = 0;
    <?php if( $cpol > 9 ) { echo "if( document.formp2.h_tl9.checked ) var h_t19=document.formp2.h_tl9.value;"; } ?>


    if( dodfak == 1 )
          {
    window.open('presundod_fak.php?&copern=20&drupoh=<?php echo $drupoh;?>&tl10=' + h_t10 + '&tl11=' + h_t11 + '&tl12=' + h_t12 + '&tl13=' + h_t13 + '&tl14=' + h_t14 + '&tl15=' + h_t15 + '&tl16=' + h_t16 + '&tl17=' + h_t17 + '&tl18=' + h_t18 + '&tl19=' + h_t19 + '&tt=1', '_self')
          }

    }
  </script>
<?php
}
?>
</td>
</FORM>
</td>
</tr>
</table>
<?php
//mysql_close();
mysql_free_result($sql);
    } while (false);
//koniec 1,2,3,4,7,9

//nezobraz pre novu,upravu a mazanie
if ( $copern != 5 AND $copern != 6 AND $copern != 8 )
     {
?>
<tr>
<span id="Ax" style="display:none; width:100%; align:center; font-family:bold; font-weight:bold; background-color:red; color:black;">
 Zadajte ��slo strany - �daj mus� by� cel� kladn� ��slo</span>
<span id="Zm" style="display:none; width:100%; align:center; font-family:bold; font-weight:bold; background-color:yellow; color:black;">
 Doklad DOK=<?php echo $cislo_dok;?> vymazan�</span>
</tr>

<table>
<tr>
<td>
<FORM name="forma2" class="obyc" method="post" action="vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&
<?php
if ( $copern != 9 )
{
echo "copern=3&hladaj_uce=$hladaj_uce";
}
if ( $copern == 9 )
{
echo "copern=9&hladaj_dat=$hladaj_dat&hladaj_dok=$hladaj_dok&hladaj_nai=$hladaj_nai&hladaj_uce=$hladaj_uce";
}
?>
&drupoh=<?php echo $drupoh;?>&page=<?php echo $ppage;?>" >
<INPUT type="submit" id="pstrana" name="pstrana" value="Predo�l� strana" >
</FORM>
</td>
<td>
<FORM name="forma1" class="obyc" method="post" action="vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&
<?php
if ( $copern != 9 )
{
echo "copern=2&hladaj_uce=$hladaj_uce";
}
if ( $copern == 9 )
{
echo "copern=9&hladaj_dat=$hladaj_dat&hladaj_dok=$hladaj_dok&hladaj_nai=$hladaj_nai&hladaj_uce=$hladaj_uce";
}
?>
&drupoh=<?php echo $drupoh;?>&page=<?php echo $npage;?>" >
<INPUT type="submit" id="dstrana" value="�al�ia strana" >
</FORM>
</td>
<td>
<FORM name="forma3" class="obyc" method="post" action="vstfak.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=4&drupoh=<?php echo $drupoh;?>&hladaj_uce=<?php echo $hladaj_uce; ?>
&page=<?php echo $xstr;?>" >
<INPUT type="submit" id="sstrana" value="Prejs� na stranu:" >
<input type="text" name="page" id="page" value="<?php echo $xstr;?>" size="4" onkeyup="KontrolaCisla(this, Ax)"/>
</FORM>
</td>
<td>
<FORM name="forma4" class="obyc" method="post" action="vstf_u.php?regpok=<?php echo $regpok; ?>&vyroba=<?php echo $vyroba; ?>&copern=5&drupoh=<?php echo $drupoh;?>&hladaj_uce=<?php echo $hladaj_uce; ?>
&page=1" >
<INPUT type="submit" name="npol" id="npol" value="Nov� doklad" onclick="window.name = 'zoznam';">
</FORM>
</td>
</tr>
</table>

<?php
     }
//koniec nezobraz pre novu,upravu a mazanie
?>


<?php
// toto je koniec casti na zobrazenie tabulky a prechody medzi stranami 
     }

if( $sys != 'DOP' AND $sysx != 'UCT' ) 
{
if( $copern == 1 ) { 
$zmenume=1; 
$odkaz="../faktury/vstfak.php?regpok=$regpok&vyroba=$vyroba&copern=1&drupoh=$drupoh&page=1&sysx=$sysx&hladaj_uce=$hladaj_uce&rozuct=$rozuct"; 
                   }

$cislista = include("fak_lista.php");
}

if( $sys == 'DOP' ) $cislista = include("../doprava/dop_lista.php");

if( $sysx == 'UCT' ) 
{
if( $copern == 1 ) { 
$zmenume=1; 
$odkaz="../faktury/vstfak.php?regpok=$regpok&vyroba=$vyroba&copern=1&drupoh=$drupoh&page=1&sysx=$sysx&hladaj_uce=$hladaj_uce&rozuct=$rozuct"; 
                   }
$cislista = include("../ucto/uct_lista.php");
}

// celkovy koniec dokumentu
       } while (false);
?>
</BODY>
</HTML>
