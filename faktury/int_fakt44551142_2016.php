<HTML>
<?php

//toto je prenos faktur EDcom do firmy 36 z 136 rok 2016
// celkovy zaciatok dokumentu
       do
       {
$sys = 'UCT';
$urov = 2000;
$clsm = 900;
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

// cislo operacie
$copern = 1*$_REQUEST['copern'];
$drupoh = 1*$_REQUEST['drupoh'];
$cislo_dok = 1*$_REQUEST['cislo_dok'];
$hladaj_uce = 1*$_REQUEST['hladaj_uce'];
$h_sys = 1*$_REQUEST['h_sys'];
$h_obdp = 1*$_REQUEST['h_obdp'];

$citfir = include("../cis/citaj_fir.php");
$citfir = include("../cis/citaj_nas.php");

if( $kli_vrok == 2014 ) { $h_sys=134; }
if( $kli_vrok == 2015 ) { $h_sys=135; }
if( $kli_vrok == 2016 ) { $h_sys=136; }

if( $kli_vxcf != 36 )    { echo "SYS $h_sys nem��ete prev�dza� do  FIR $kli_vxcf ."; exit; } 
if( $kli_vxcf == 36 AND $h_sys != 136 ) { echo "SYS $h_sys nem��ete prev�dza� do  FIR $kli_vxcf ."; exit; } 


//tlacove okno
$tlcuwin="width=700, height=' + vyskawin + ', top=0, left=200, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcswin="width=980, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcvwin="width=1020, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$uliscwin="width=' + sirkawic + ', height=' + vyskawic + ', top=0, left=0, status=yes, resizable=yes, scrollbars=yes, menubar=no, toolbar=no";


///////zauctuj sluzbove faktury z inej FIR ako UCTO
if( $copern == 56  )
{

//echo "uctujem";

$uzk2="60200";
$uzk1="60200";
$uzk0="60200";
$udn2="34300";
$udn1="34300";
$dzk2=55;
$dzk1=60;
$dzk0=51;
if( $h_sys == 511 ) 
{
$uzk2="395000";
$udn2="343550";
$udn1="343550";
$dzk2=55;
}
if( $h_sys == 512 ) 
{
$uzk2="395000";
$udn2="343550";
$udn1="343550";
$dzk2=55;
}


$sql = 'DROP TABLE F'.$kli_vxcf.'_uctodbx'.$kli_uzid.' ';
$vsql = mysql_query("$sql");

$sqlt = <<<uctodb
(
   prx          INT,
   slu          DECIMAL(15,0),
   strx         INT,
   zakx         INT,
   dok         INT(8),
   poh         INT,
   cpl         int not null auto_increment,
   ucm         VARCHAR(10),
   ucd         VARCHAR(10),
   rdp         INT(2),
   dph         INT(2),
   hod         DECIMAL(10,2),
   ico         INT(10),
   fak         DECIMAL(10,0),
   pop         VARCHAR(80),
   str         INT,
   zak         INT,
   unk         VARCHAR(15),
   id          INT,
   datm        TIMESTAMP(14),
   zk0         DECIMAL(10,2),
   zk1         DECIMAL(10,2),
   zk2         DECIMAL(10,2),
   dn1         DECIMAL(10,2),
   dn2         DECIMAL(10,2),
   PRIMARY KEY(cpl)
);
uctodb;

$sql = 'CREATE TABLE F'.$kli_vxcf.'_uctodbx'.$kli_uzid.$sqlt;
$vsql = mysql_query("$sql");

$sqlt = 'DROP TABLE prcdatum'.$kli_uzid;
$vysledok = mysql_query("$sqlt");

$sqlt = <<<prcdatum
(
   datp          DATE,
   datk          DATE,
   fic          INT
);
prcdatum;

$vsql = 'CREATE TABLE prcdatum'.$kli_uzid.$sqlt;
$vytvor = mysql_query("$vsql");

$datp_ume=$kli_vrok.'-'.$h_obdp.'-01';

$ttvv = "INSERT INTO prcdatum".$kli_uzid." ( datp,datk,fic ) VALUES ( '$datp_ume', '$datp_ume', 0 )";
$ttqq = mysql_query("$ttvv");


$dsqlt = "UPDATE prcdatum".$kli_uzid.
" SET datp=SUBDATE('$datp_ume',0),  datk=LAST_DAY('$datp_ume')".
" WHERE fic >= 0 ".
"";
$dsql = mysql_query("$dsqlt");

$sql = mysql_query("SELECT * FROM prcdatum$kli_uzid");
  if (@$zaznam=mysql_data_seek($sql,0))
  {
  $riadok=mysql_fetch_object($sql);
  $datp_ume=$riadok->datp;
  $datk_ume=$riadok->datk;
  }

$sqlt = 'DROP TABLE prcdatum'.$kli_uzid;
$vysledok = mysql_query("$sqlt");

$podmdat=" daz >= '".$datp_ume."' AND daz <= '".$datk_ume."' ";


//fakslu
//dok  fak  dol  prf  cpl  slu  nsl  pop  pon  dph  cen  cep  ced  mno  mer  pfak  cfak  dfak  id  datm  

$databaza="";

$pohcis=1000*$h_obdp+$h_sys;
$kli_vzcf=$h_sys;
//$kli_vzcf=529;

//echo $pohcis;
//exit;

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" SELECT 0,slu,".$databaza."F$kli_vzcf"."_fakodb.str,".$databaza."F$kli_vzcf"."_fakodb.zak,".$databaza."F$kli_vzcf"."_fakslu.dok,$pohcis,0,".$databaza."F$kli_vzcf"."_fakodb.uce,'',0,dph,(cep*mno),".
" ".$databaza."F$kli_vzcf"."_fakodb.ico,".$databaza."F$kli_vzcf"."_fakodb.fak,'',0,0,'',$kli_uzid,now(),zk0,zk1,zk2,dn1,dn2 ".
" FROM ".$databaza."F$kli_vzcf"."_fakslu,".$databaza."F$kli_vzcf"."_fakodb ".
" WHERE ".$databaza."F$kli_vzcf"."_fakslu.dok >= 0 AND ".$databaza."F$kli_vzcf"."_fakslu.dok=".$databaza."F$kli_vzcf"."_fakodb.dok ".
" AND $podmdat ";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET ucd=$uzk2, rdp=$dzk2 WHERE dph=$fir_dph2 OR dph=19 ";
$dsql = mysql_query("$dsqlt");
$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET ucd=$uzk1, rdp=$dzk1 WHERE dph=$fir_dph1 ";
$dsql = mysql_query("$dsqlt");
$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET ucd=$uzk0, rdp=$dzk0 WHERE dph=0 ";
$dsql = mysql_query("$dsqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid.",".$databaza."F$kli_vzcf"."_sklsluudaje SET ".
" ucd=xuce1, str=xuce2, zak=xuce3 WHERE F$kli_vxcf"."_uctodbx".$kli_uzid.".slu=".$databaza."F$kli_vzcf"."_sklsluudaje.xcis ";
$dsql = mysql_query("$dsqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET str=strx WHERE str = 0 AND strx != 0 ";
$dsql = mysql_query("$dsqlt");
$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET zak=zakx WHERE zak = 0 AND zakx != 0 ";
$dsql = mysql_query("$dsqlt");

//exit;

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" SELECT 1,slu,strx,zakx,dok,poh,0,ucm,ucd,rdp,dph,sum(hod),ico,fak,pop,str,zak,unk,".
" $kli_uzid,now(),zk0,zk1,zk2,dn1,dn2 ".
" FROM F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" WHERE hod != 0 GROUP BY dok,ucm,ucd,str,zak";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" SELECT 1,slu,strx,zakx,dok,poh,0,ucm,'$udn2',rdp,dph,dn2,ico,fak,pop,0,0,unk,".
" $kli_uzid,now(),zk0,zk1,zk2,dn1,dn2 ".
" FROM F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" WHERE dn2 != 0 AND prx = 0 GROUP BY dok ";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");
//exit;

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" SELECT 1,slu,strx,zakx,dok,poh,0,ucm,'$udn1',rdp,dph,dn1,ico,fak,pop,0,0,unk,".
" $kli_uzid,now(),zk0,zk1,zk2,dn1,dn2 ".
" FROM F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" WHERE dn1 != 0 AND prx = 0 GROUP BY dok ";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");

$dsqlt = "DELETE FROM F$kli_vxcf"."_uctodbx".$kli_uzid."  WHERE prx=0 ";
$dsql = mysql_query("$dsqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_uctodbx".$kli_uzid." SET ucm='31100' WHERE poh = $pohcis";
$dsql = mysql_query("$dsqlt");


//daj do faktur fakodb
$dsqlt = "DELETE FROM F$kli_vxcf"."_fakodb WHERE skl=$pohcis ";
$dsql = mysql_query("$dsqlt");

//fakodb
//uce  ume  dat  dav  das  daz  dok  doq  skl  poh  ico  fak  dol  prf  obj  unk  dpr  ksy  ssy  poz  str  zak  txz  txp  zk0  zk1  zk2  zk3  zk4  
//dn1  dn2  dn3  dn4  sp1  sp2  sz1  sz2  sz3  sz4  zk0u  zk1u  zk2u  dn1u  dn2u  sp0u  sp1u  sp2u  hodu  hod  hodm  kurz  mena  zmen  odbm  
//zal  zao  ruc  uhr  id  datm  

$dsqlt = "INSERT INTO F$kli_vxcf"."_fakodb ".
" SELECT ".
"uce,ume,daz,dav,das,daz,dok,dok,'$pohcis',poh,ico,fak,dol,prf,obj,unk,dpr,ksy,ssy,poz,str,zak,txz,txp,zk0,zk1,zk2,zk3,zk4,".  
"dn1,dn2,dn3,dn4,sp1,sp2,sz1,sz2,fak,sz4,zk0u,zk1u,zk2u,dn1u,dn2u,sp0u,sp1u,sp2u,hodu,hod,hodm,kurz,mena,zmen,odbm,".  
"zal,zao,ruc,uhr,".
" $kli_uzid,now() ".
" FROM ".$databaza."F$kli_vzcf"."_fakodb ".
" WHERE hod != 0 AND $podmdat";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");


//daj to do rozuctovania uctodb
$dsqlt = "DELETE FROM F$kli_vxcf"."_uctodb WHERE poh=$pohcis AND ( LEFT(ucm,3) = 311 OR LEFT(ucm,3) = 315 ) ";
$dsql = mysql_query("$dsqlt");

$dsqlt = "INSERT INTO F$kli_vxcf"."_uctodb ".
" SELECT dok,poh,0,ucm,ucd,rdp,dph,hod,ico,fak,pop,str,zak,unk,".
" $kli_uzid,now() ".
" FROM F$kli_vxcf"."_uctodbx".$kli_uzid." ".
" WHERE hod != 0 ";
//echo $dsqlt;
$dsql = mysql_query("$dsqlt");


$dsqlt = "UPDATE F$kli_vxcf"."_fakodb  SET ".
" hodu=hod, zk2u=zk2, zk1u=zk1, ".
" zk0u=zk0, dn2u=dn2, dn1u=dn1 ".
" WHERE skl=$pohcis ";
$dsql = mysql_query("$dsqlt");

//daj do ico

$sqlt = "DROP TABLE F".$kli_vxcf."_icoprenos";
$vysledok = mysql_query("$sqlt");

$sql = "CREATE TABLE F".$kli_vxcf."_icoprenos SELECT * FROM ".$databaza."F".$kli_vzcf."_ico WHERE ico >= 0";
$vysledek = mysql_query("$sql");

$sql = "ALTER TABLE F$kli_vxcf"."_icoprenos ADD plati DECIMAL(3,0) DEFAULT 0 FIRST";
$vysledek = mysql_query("$sql");

$sqtoz = "UPDATE F$kli_vxcf"."_icoprenos,F$kli_vxcf"."_ico SET plati=9 WHERE F$kli_vxcf"."_icoprenos.ico = F$kli_vxcf"."_ico.ico ";
$oznac = mysql_query("$sqtoz");

$dsqlt = "DELETE FROM F$kli_vxcf"."_icoprenos WHERE plati = 9 ";
$dsql = mysql_query("$dsqlt");

$dsqlt = "INSERT INTO F$kli_vxcf"."_ico SELECT ".
"ico,dic,icd,nai,na2,uli,psc,mes,tel,fax,em1,em2,em3,www,uc1,nm1,ib1,uc2,nm2,ib2,uc3,nm3,ib3,dns,datm  FROM F$kli_vxcf"."_icoprenos WHERE plati != 9";
$dsql = mysql_query("$dsqlt");

$sqlt = "DROP TABLE F".$kli_vxcf."_icoprenos";
$vysledok = mysql_query("$sqlt");

//zablokuj
$sqlt = <<<saldo
(
   drx         VARCHAR(10),
   idx         INT(4),
   datm        TIMESTAMP(14)
);
saldo;

$sql = "CREATE TABLE F$kli_vxcf"."_uctblokfak".$h_sys."_".$h_obdp."".$sqlt;
$urob = mysql_query("$sql");


?>



<?php
}
///////koniec zauctuj sluzbove faktury z inej FIR ako UCTO


?>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
  <link type="text/css" rel="stylesheet" href="../css/styl.css">
<title>Za��tovanie intern�ch fakt�r</title>
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

<script type="text/javascript">
//sirka a vyska okna
var sirkawin = screen.width-10;
var vyskawin = screen.height-175;
var vyskawic = screen.height;
var sirkawic = screen.width-10;

    

</script>
</HEAD>
<BODY class="white" >


<table class="h2" width="100%" >
<tr>
<td>EuroSecom  -  Za��tovanie intern�ch fakt�r

</td>
<td align="right"><span class="login"><?php echo "UME $kli_vume FIR$kli_vxcf-$kli_nxcf  login: $kli_uzmeno $kli_uzprie / $kli_uzid ";?></span></td>
</tr>
</table>
<br />


<?php
if( $copern == 56 )
{
$uceodb="31100";
if( $h_sys == 511 ) $uceodb="311100";
if( $h_sys == 512 ) $uceodb="311100";

$sql = 'DROP TABLE F'.$kli_vxcf.'_uctodbx'.$kli_uzid.' ';
$vsql = mysql_query("$sql");

//window.open('../ucto/rozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1', '_blank', '' )
//../ucto/rozdok.php?copern=101&drupoh=1&page=1&cislo_uce=' + ucet + '&hladaj_dok=' + hladaj_dok + '&page=1&tlacitR=1


?>
OK
<script type="text/javascript">
    window.open('../faktury/vstfak.php?copern=1&drupoh=1001&page=1&pocstav=0', '_self' );
</script>
<?php
}
?>


<?php


// celkovy koniec dokumentu
       } while (false);
?>
</BODY>
</HTML>
