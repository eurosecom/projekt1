<HTML>
<?php

// celkovy zaciatok dokumentu
       do
       {
$sys = 'SKL';
$urov = 2000;
$clsm = 920;
$cslm=402107;
$uziv = include("../uziv.php");
if( !$uziv ) exit;

// cislo operacie
$copern = 1*$_REQUEST['copern'];
$drupoh = 1*$_REQUEST['drupoh'];

$cislo_skl = 1*$_REQUEST['cislo_skl'];

//copern=10 okamzity stav, 20-mesacny stav, 30=pociatocny stav

  require_once("../pswd/password.php");
  @$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);


$pole = explode(".", $kli_vume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];

$citnas = include("../cis/citaj_nas.php");
$citfir = include("../cis/citaj_fir.php");
$mena1 = $fir_mena1;
$mena2 = $fir_mena2;
$kurz12 = $fir_kurz12;

//datumove funkcie
$sDat = include("../funkcie/dat_sk_us.php");

//tlacove okno
$tlcuwin="width=700, height=' + vyskawin + ', top=0, left=200, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcswin="width=980, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$tlcvwin="width=1020, height=' + vyskawin + ', top=0, left=20, status=yes, resizable=yes, scrollbars=yes, menubar=yes, toolbar=yes";
$uliscwin="width=' + sirkawic + ', height=' + vyskawic + ', top=0, left=0, status=yes, resizable=yes, scrollbars=yes, menubar=no, toolbar=no";

$reko = include("skl_rekonstrukcia.php");



?>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=Windows 1250">
  <link type="text/css" rel="stylesheet" href="../css/styl.css">
<title>Regleta z�sob PDF</title>
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
<td>EuroSecom  -  Regleta z�sob PDF 

</td>
<td align="right"><span class="login"><?php echo "UME $kli_vume FIR$kli_vxcf-$kli_nxcf  login: $kli_uzmeno $kli_uzprie / $kli_uzid ";?></span></td>
</tr>
</table>
<br />

<?php
if (File_Exists ("../tmp/podklad$kli_uzid.pdf")) { $soubor = unlink("../tmp/podklad$kli_uzid.pdf"); }

   define('FPDF_FONTPATH','../fpdf/font/');
   require('../fpdf/fpdf.php');

$pdf=new FPDF("P","mm","A4");
$pdf->Open();
$pdf->AddFont('arial','','arial.php');

if ( $copern == 20 OR $copern == 10 OR $copern == 30 )
    {

//urob pracovnu sklprc zjednotenie pri,vyd,pre,poc

$sqlt = 'DROP TABLE F'.$kli_vxcf.'_sklprc'.$kli_uzid;
$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_sklprcd'.$kli_uzid;
$vysledok = mysql_query("$sqlt");

$sqlt = <<<sklprc
(
   druh        DECIMAL(15,0),
   pox1        INT,
   pox         INT,
   ume         FLOAT(8,4),
   dat         DATE,
   skl         INT,
   cis         DECIMAL(15,0),
   mno         DECIMAL(10,3),
   cen         DECIMAL(10,4),
   zas         DECIMAL(10,3),
   hod         DECIMAL(10,2),
   vdj         DECIMAL(10,3),
   prj         DECIMAL(10,3),
   pcs         DECIMAL(10,3)
);
sklprc;

$vsql = 'CREATE TABLE F'.$kli_vxcf.'_sklprc'.$kli_uzid.$sqlt;
$vytvor = mysql_query("$vsql");
$vsql = 'CREATE TABLE F'.$kli_vxcf.'_sklprcd'.$kli_uzid.$sqlt;
$vytvor = mysql_query("$vsql");


//poc.stav
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,skl,cis,mno,cen,(mno),'0','0','0',mno FROM F$kli_vxcf"."_sklpoc WHERE NOT ( cis = 0 )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");

if( $copern === 20 )
{
//prijem 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,skl,cis,mno,cen,(mno),'0','0',mno,0 FROM F$kli_vxcf"."_sklpri WHERE ( cis > 0 AND ume <= $kli_vume )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");
//vydaj 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,skl,cis,mno,cen,-(mno),'0',mno,'0',0 FROM F$kli_vxcf"."_sklvyd WHERE ( cis > 0 AND ume <= $kli_vume )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");
//faktury 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,skl,cis,mno,cen,-(mno),'0',mno,'0',0 FROM F$kli_vxcf"."_sklfak WHERE ( cis > 0 AND ume <= $kli_vume )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");
//presun- 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,skl,cis,mno,cen,-(mno),'0',mno,'0',0 FROM F$kli_vxcf"."_sklpre WHERE ( cis > 0 AND ume <= $kli_vume )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");
//presun+ 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprc$kli_uzid".
" SELECT 0,0,0,ume,dat,sk2,cis,mno,cen,(mno),'0','0',mno,0 FROM F$kli_vxcf"."_sklpre WHERE ( cis > 0 AND ume <= $kli_vume )".
" ORDER BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");

}
//koniec copern=20


//stare pohyby do pociatku
$sqlt = "UPDATE F".$kli_vxcf."_sklprc".$kli_uzid." SET pcs=pcs+prj-vdj WHERE ume < $kli_vume";
$vysledok = mysql_query("$sqlt");
$sqlt = "UPDATE F".$kli_vxcf."_sklprc".$kli_uzid." SET prj=0, vdj=0 WHERE ume < $kli_vume";
$vysledok = mysql_query("$sqlt");

//poliklinika suma za druhy
if( $poliklinikase == 1 )
{
$dsqlt = "UPDATE F$kli_vxcf"."_sklprc$kli_uzid ".
" SET druh=LEFT(cis,4) "; $dsql = mysql_query("$dsqlt");

}


//group za skl,cis,cen
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprcd$kli_uzid".
" SELECT druh,0,0,ume,dat,skl,cis,mno,cen,SUM(zas),SUM(hod),SUM(vdj),SUM(prj),SUM(pcs) FROM F$kli_vxcf"."_sklprc$kli_uzid ".
" GROUP BY skl,cis,cen".
"";
$dsql = mysql_query("$dsqlt");

    }
//koniec pracovneho suboru zjednotenie

//vypocitaj hodnotu
$sqlt = "UPDATE F".$kli_vxcf."_sklprcd".$kli_uzid." SET hod=zas*cen";
//echo $sqlt;
$vysledok = mysql_query("$sqlt");

//priemerne ceny pox1=1 a potom vynuluj
if( $fir_xsk04 == 1 ) 
{ 
$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprcd$kli_uzid".
" SELECT druh,1,0,ume,dat,skl,cis,mno,cen,SUM(zas),SUM(hod),SUM(vdj),SUM(prj),SUM(pcs) FROM F$kli_vxcf"."_sklprcd$kli_uzid ".
" GROUP BY skl,cis".
"";
$dsql = mysql_query("$dsqlt");

$sqlt = "DELETE FROM F".$kli_vxcf."_sklprcd".$kli_uzid." WHERE pox1 = 0";
$vysledok = mysql_query("$sqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_sklprcd$kli_uzid  SET pox1=0 "; 
$dsql = mysql_query("$dsqlt");

$dsqlt = "UPDATE F$kli_vxcf"."_sklprcd$kli_uzid  SET cen=hod/zas WHERE zas != 0 "; 
$dsql = mysql_query("$dsqlt");
}
//koniec priemerne ceny

//sumy

$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprcd$kli_uzid".
" SELECT 0,0,9,ume,dat,skl,cis,mno,cen,SUM(zas),SUM(hod),SUM(vdj),SUM(prj),SUM(pcs) FROM F$kli_vxcf"."_sklprcd$kli_uzid ".
" GROUP BY skl".
"";
$dsql = mysql_query("$dsqlt");

//poliklinika suma za druhy
if( $poliklinikase == 1 )
{

$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprcd$kli_uzid".
" SELECT druh,1,0,ume,dat,skl,999999999999999,mno,cen,SUM(zas),SUM(hod),SUM(vdj),SUM(prj),SUM(pcs) FROM F$kli_vxcf"."_sklprcd$kli_uzid ".
" WHERE pox = 0 GROUP BY skl,druh ".
"";
$dsql = mysql_query("$dsqlt");
}


$dsqlt = "INSERT INTO F$kli_vxcf"."_sklprcd$kli_uzid".
" SELECT 0,0,10,ume,dat,99999999,cis,mno,cen,SUM(zas),SUM(hod),SUM(vdj),SUM(prj),SUM(pcs) FROM F$kli_vxcf"."_sklprcd$kli_uzid ".
" WHERE pox = 9 GROUP BY pox ".
"";
$dsql = mysql_query("$dsqlt");



$neparne=1;

if ( $copern == 20 OR $copern == 10 OR $copern == 30 )
  {
$sqltt = "SELECT * FROM F$kli_vxcf"."_sklprcd$kli_uzid".
" LEFT JOIN F$kli_vxcf"."_sklcis".
" ON F$kli_vxcf"."_sklprcd$kli_uzid.cis=F$kli_vxcf"."_sklcis.cis".
" LEFT JOIN F$kli_vxcf"."_skl".
" ON F$kli_vxcf"."_sklprcd$kli_uzid.skl=F$kli_vxcf"."_skl.skl".
" WHERE ( pcs != 0 OR prj != 0 OR vdj != 0 OR zas != 0 )".
" ORDER BY F$kli_vxcf"."_sklprcd$kli_uzid.skl,pox,druh,F$kli_vxcf"."_sklprcd$kli_uzid.cis,F$kli_vxcf"."_sklprcd$kli_uzid.cen";
  }




//exit;

$tov = mysql_query("$sqltt");
$tvpol = mysql_num_rows($tov);

//exit;

$strana=0;
$j=0;           
$i=0;
  while ($i <= $tvpol )
  {

  if (@$zaznam=mysql_data_seek($tov,$i))
{
$riadok=mysql_fetch_object($tov);


//hlavicka strany
if ( $j == 0 )
     {

$pdf->AddPage();

$pdf->SetLeftMargin(15); 
$pdf->SetTopMargin(15); 

$strana=$strana+1;

$pdf->SetFont('arial','',10);
if ( $copern == 20 )  { $pdf->Cell(90,6,"Regleta z�sob za $kli_vume Sklad $riadok->skl $riadok->nas","LTB",0,"L"); }
if ( $copern == 10 )  { $pdf->Cell(90,6,"Stav z�sob okam�it� stav $kli_vume Sklad $riadok->skl $riadok->nas","LTB",0,"L"); }
if ( $copern == 30 )  { $pdf->Cell(90,6,"Po�iato�n� stav skladov Sklad $riadok->skl $riadok->nas","LTB",0,"L"); }
$pdf->Cell(0,6,"$kli_nxcf strana $strana","RTB",1,"R");

$pdf->SetFont('arial','',6);


$pdf->Cell(10,5,"SKL","1",0,"R");$pdf->Cell(20,5,"MAT","1",0,"R");$pdf->Cell(50,5,"N�zov","1",0,"L");
$pdf->Cell(15,5,"Cena za MJ","1",0,"R");$pdf->Cell(10,5,"MJ","1",0,"L");
$pdf->Cell(15,5,"Po�iatok MJ","1",0,"R");$pdf->Cell(15,5,"Pr�jem MJ","1",0,"R");$pdf->Cell(15,5,"V�daj MJ","1",0,"R");
$pdf->Cell(15,5,"Zostatok MJ","1",0,"R");$pdf->Cell(0,5,"Hodnota","1",1,"R");

     }
//koniec hlavicky j=0


$nassklad = $riadok->nas;
$pocstavCelkom = $pocstavCelkom + $riadok->pcs;
$Cislo=$pocstavCelkom+"";
$sPocstavCelkom=sprintf("%0.2f", $Cislo);

$prijemCelkom = $prijemCelkom + $riadok->prj;
$Cislo=$prijemCelkom+"";
$sPrijemCelkom=sprintf("%0.2f", $Cislo);


//koniec celkom zaciatok riadku






if( $riadok->pox == 0 AND $riadok->pox1 == 0 )
{

$pdf->SetFont('arial','',6);

$pcs = $riadok->pcs;
$Cislo=$pcs+"";
$spcs=sprintf("%0.3f", $Cislo);
if( $riadok->pcs == 0 ) $spcs="";
$prj = $riadok->prj;
$Cislo=$prj+"";
$sprj=sprintf("%0.3f", $Cislo);
if( $riadok->prj == 0 ) $sprj="";
$vdj = $riadok->vdj;
$Cislo=$vdj+"";
$svdj=sprintf("%0.3f", $Cislo);
if( $riadok->vdj == 0 ) $svdj="";
$zas = $riadok->zas;
$Cislo=$zas+"";
$szas=sprintf("%0.3f", $Cislo);
if( $riadok->zas == 0 ) $szas="";
$hod = $riadok->hod;
$Cislo=$hod+"";
$shod=sprintf("%0.3f", $Cislo);
if( $riadok->hod == 0 ) $shod="";

$pdf->Cell(10,5,"$riadok->skl","0",0,"R");$pdf->Cell(20,5,"$riadok->cis","0",0,"R");$pdf->Cell(50,5,"$riadok->nat","0",0,"L");
$pdf->Cell(15,5,"$riadok->cen","0",0,"R");$pdf->Cell(10,5,"$riadok->mer","0",0,"L");
$pdf->Cell(15,5,"$spcs","0",0,"R");$pdf->Cell(15,5,"$sprj","0",0,"R");$pdf->Cell(15,5,"$svdj","0",0,"R");
$pdf->Cell(15,5,"$szas","0",0,"R");$pdf->Cell(0,5,"$shod","0",1,"R");

}

if( $riadok->pox == 0 AND $riadok->pox1 == 1 )
{

$pdf->SetFont('arial','',8);

$pdf->Cell(135,5,"CELKOM druh $riadok->cis $riadok->druh","T",0,"L");
$pdf->Cell(0,5,"$riadok->hod","T",1,"R");

}

if( $riadok->pox == 9 )
{

$pdf->SetFont('arial','',8);

$pdf->Cell(135,5,"CELKOM Sklad $riadok->skl $riadok->nas","LTB",0,"L");
$pdf->Cell(0,5,"$riadok->hod","RTB",1,"R");
$j=-1;
}

if( $riadok->pox == 10 )
{

$pdf->SetFont('arial','',8);

$pdf->Cell(155,5,"CELKOM v�etky sklady ","LTB",0,"L");
$pdf->Cell(0,5,"$riadok->hod","RTB",1,"R");
$j=-1;
}

}
$i = $i + 1;
$j = $j + 1;
if( $j > 48 ) $j=0;

  }


$pdf->Output("../tmp/podklad.$kli_uzid.pdf");


$sqlt = 'DROP TABLE F'.$kli_vxcf.'_sklprc'.$kli_uzid;
//$vysledok = mysql_query("$sqlt");
$sqlt = 'DROP TABLE F'.$kli_vxcf.'_sklprcd'.$kli_uzid;
//$vysledok = mysql_query("$sqlt");

?> 

<script type="text/javascript">
  var okno = window.open("../tmp/podklad.<?php echo $kli_uzid; ?>.pdf","_self");
</script>

?>

<?php
// celkovy koniec dokumentu
       } while (false);
?>
</BODY>
</HTML>
