<HTML>
<?php
$sys = 'UCT';
if( $_SERVER['SERVER_NAME'] == "www.stavoimpexsro.sk" ) { $sys = 'MZD'; }
$urov = 1000;
$uziv = include("../uziv.php");
if( !$uziv ) exit;

if(!isset($kli_vxcf)) $kli_vxcf = 1;

       do
       {

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
$cislo_dok = $_REQUEST['cislo_dok'];
$ajtext = 1*$_REQUEST['ajtext'];
?>
<?php

$citnas = include("../cis/citaj_nas.php");
$citfir = include("../cis/citaj_fir.php");
$mena1 = $fir_mena1;
$mena2 = $fir_mena2;
$kurz12 = $fir_kurz12;

//datumove funkcie
$sDat = include("../funkcie/dat_sk_us.php");

if (File_Exists ("../tmp/priku$cislo_dok.$kli_uzid.pdf")) { $soubor = unlink("../tmp/priku$cislo_dok.$kli_uzid.pdf"); }

   define('FPDF_FONTPATH','../fpdf/font/');
   require('../fpdf/fpdf.php');

$pdf=new FPDF("P","mm","A4");
$pdf->Open();
$pdf->AddFont('arial','','arial.php');

$pdf->AddPage();
$pdf->SetFont('arial','',10);

$pdf->SetLeftMargin(15); 
$pdf->SetTopMargin(15); 

$sety=10;
$pdf->SetY($sety);

if( $drupoh == 1 )
{
$tabl = "uctpriku";
$uctpol = "uctprikp";
}


if ( $copern == 20 AND $drupoh == 1 )
{
$sqltt = "SELECT * FROM F$kli_vxcf"."_$tabl".
" LEFT JOIN F$kli_vxcf"."_ico".
" ON F$kli_vxcf"."_$tabl.ico=F$kli_vxcf"."_ico.ico".
" LEFT JOIN klienti".
" ON F$kli_vxcf"."_$tabl.id=klienti.id_klienta".
" LEFT JOIN F$kli_vxcf"."_dban".
" ON F$kli_vxcf"."_$tabl.uce=F$kli_vxcf"."_dban.dban".
" WHERE F$kli_vxcf"."_$tabl.dok = $cislo_dok ".
"";
//echo $sqltt;
$sql = mysql_query("$sqltt");
}


  if (@$zaznam=mysql_data_seek($sql,0))
  {
  $hlavicka=mysql_fetch_object($sql);

$dat_sk=SkDatum($hlavicka->dat);
if( $dat_sk == "00.00.0000" ) $dat_sk="";


//zaciatok vypisu poloziek

$tovtt = "SELECT * FROM F$kli_vxcf"."_$uctpol".
" WHERE F$kli_vxcf"."_$uctpol.dok = $cislo_dok ".
" ORDER BY cpl";
//echo $tovtt;

$tov = mysql_query("$tovtt");
$tvpol = mysql_num_rows($tov);
$koniec=$tvpol-1;
if( $tvpol > 0 ) $jetovar=1;
$jednapolozka=0;
if( $tvpol == 1 ) $jednapolozka=1;

//Ak su polozky
if( $jetovar == 1 )
           {
$j=0;
$i=0;
  while ($i <= $koniec )
  {

  if (@$zaznam=mysql_data_seek($tov,$i))
{
$rtov=mysql_fetch_object($tov);


if( $j == 0 )
{
$celkomstrana=0;

if( $i > 0 )
{
$pdf->AddPage();
$pdf->SetFont('arial','',10);

$pdf->SetLeftMargin(15); 
$pdf->SetTopMargin(15);

$sety=10;
$pdf->SetY($sety);
}

$pdf->Cell(50,6,"$hlavicka->nban","0",0,"L");
$pdf->SetFont('arial','',14);
if( $jednapolozka != 1 ) $pdf->Cell(130,6,"HROMADN� PR�KAZ NA �HRADU ","0",1,"C");
if( $jednapolozka == 1 ) $pdf->Cell(130,6,"PR�KAZ K �HRADE ","0",1,"C");
$pdf->SetFont('arial','',10);
$pdf->Cell(180,6,"     ","0",1,"L");

$pdf->Cell(40,6," ","0",0,"R");$pdf->Cell(20,6," ","0",0,"R");
$pdf->Cell(30,6,"Mena $hlavicka->mena","1",0,"R");$pdf->Cell(35,6," ","LTR",0,"R");$pdf->Cell(55,6," ","LTR",1,"L");

$eurofp=0;
if( $_SERVER['SERVER_NAME'] == "www.eurofp.sk" ) { $eurofp=1; }

if( $eurofp == 0 ) {
$pdf->Cell(40,6,"��et platite�a","1",0,"R");$pdf->Cell(20,6,"NumK�d","1",0,"R");
$pdf->Cell(30,6,"Celkom suma","1",0,"R");$pdf->Cell(35,6,"D�a","LBR",0,"R");$pdf->Cell(55,6," ","LBR",1,"L");
                   }
if( $eurofp == 1 ) {
$pdf->Cell(40,6,"��et platite�a","1",0,"R");$pdf->Cell(20,6,"NumK�d","1",0,"R");
$pdf->Cell(30,6,"Celkom suma","1",0,"R");$pdf->Cell(35,6,"D�tum splatnosti","LBR",0,"R");$pdf->Cell(55,6," ","LBR",1,"L");
                   }


$pdf->Cell(40,6,"$hlavicka->uceb","1",0,"R");$pdf->Cell(20,6,"$hlavicka->numb","1",0,"R");
$pdf->Cell(30,6,"            ","1",0,"R");$pdf->Cell(35,6,"$dat_sk","1",0,"R");$pdf->Cell(55,6," ","1",1,"L");

$pdf->Cell(180,3,"     ","0",1,"L");

$pdf->Cell(40,6," ","LRT",0,"R");$pdf->Cell(20,6," ","LRT",0,"R");
$pdf->Cell(30,6," ","LRT",0,"R");$pdf->Cell(90,6,"Symboly platby","LRT",1,"C");

$pdf->Cell(40,6,"��et pr�jemcu","LRB",0,"R");$pdf->Cell(20,6,"NumK�d","LRB",0,"R");
$pdf->Cell(30,6,"Sumy","LRB",0,"R");$pdf->Cell(35,6,"Variabiln�","1",0,"R");$pdf->Cell(20,6,"Kon�tantn�","1",0,"R");$pdf->Cell(35,6,"�pecifick�","1",1,"R");
}

$celkomstrana=$celkomstrana+$rtov->hodm;
$Cislo=$celkomstrana+"";
$Hcelkomstrana=sprintf("%0.2f", $Cislo);

$twib=trim($rtov->twib);

if( $twib == '' OR $ajtext == 0 )
  {
$pdf->Cell(40,6,"$rtov->uceb","LR",0,"R");$pdf->Cell(20,6,"$rtov->numb","LR",0,"R");$pdf->Cell(30,6,"$rtov->hodm","LR",0,"R");
$pdf->Cell(35,6,"$rtov->vsy","LR",0,"R");$pdf->Cell(20,6,"$rtov->ksy","LR",0,"R");$pdf->Cell(35,6,"$rtov->ssy","LR",1,"R");
  }

if( $twib != '' AND $ajtext == 1 )
  {
$pdf->Cell(40,6,"$rtov->uceb","LR",0,"R");$pdf->Cell(20,6,"$rtov->numb","LR",0,"R");$pdf->Cell(30,6,"$rtov->hodm","LR",0,"R");
$pdf->Cell(35,6,"$rtov->vsy","LR",0,"R");$pdf->Cell(20,3,"$rtov->ksy","LR",0,"R");$pdf->Cell(35,3,"$rtov->ssy","LR",1,"R");
$pdf->Cell(40,3," ","LR",0,"R");$pdf->Cell(20,3," ","LR",0,"R");$pdf->Cell(30,3," ","LR",0,"R");$pdf->Cell(35,6," ","LR",0,"R");
$pdf->Cell(55,3,"$twib","LR",1,"L");
  }

}
$i = $i + 1;
$j = $j + 1;

$polozieknastranu=8;
$xbxbxb=1*$hlavicka->txp;
if( $xbxbxb > 0 ) { $polozieknastranu=$xbxbxb; }

if( $j == $polozieknastranu ) 
{ 
$sumaryb=$pdf->GetY();
$sumarxb=$pdf->GetX();

$sumary=34;
$sumarx=70;
$pdf->SetY($sumary);
$pdf->SetX($sumarx);
$pdf->SetFont('arial','',10);
$pdf->Cell(35,6,"$Hcelkomstrana","0",0,"R");

$pdf->SetY($sumaryb);
$pdf->SetX($sumarxb);

$j=0; 
}

//popis pod polozkamy
if( $j == 0 AND $i < $tvpol ) {
$pdf->Cell(40,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(30,6," ","LR",0,"R");
$pdf->Cell(35,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(35,6," ","LR",1,"R");

$pdf->Cell(40,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(30,6," ","LR",0,"R");
$pdf->Cell(35,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(35,6," ","LR",1,"R");

$pdf->Cell(40,6," ","LRB",0,"R");$pdf->Cell(20,6," ","LRB",0,"R");$pdf->Cell(30,6," ","LRB",0,"R");
$pdf->Cell(35,6," ","LRB",0,"R");$pdf->Cell(20,6," ","LRB",0,"R");$pdf->Cell(35,6," ","LRB",1,"R");

$pdf->Cell(35,6," ","0",1,"R");
$pdf->Cell(35,6,"Doru�il:","0",1,"L");
if( $sekov == 1 ) {
$pdf->Cell(35,6,"Miesto: $fir_fmes","0",0,"L");$pdf->Cell(75,6," ","0",0,"L");$pdf->Cell(70,6," ","0",1,"R");
                  }
$pdf->Cell(35,6,"D�a:","0",0,"L");$pdf->Cell(75,6," ","0",0,"R");$pdf->Cell(70,6," ","0",1,"R");
$pdf->Cell(35,6," ","0",0,"L");$pdf->Cell(75,6," ","0",0,"R");$pdf->Cell(70,6,"podpis,pe�iatka pr�kazcu","T",1,"C");

$koniec=270;
$pdf->SetY($koniec);
$pdf->Line(15, $koniec, 195, $koniec); 
$pdf->SetY($koniec+2);
$pdf->SetFont('arial','',6);
$pdf->Cell(180,3,"Vystavil(a): $hlavicka->meno $hlavicka->priezvisko / $hlavicka->id ","0",1,"L");

$sumary=34;
$sumarx=70;
$pdf->SetY($sumary);
$pdf->SetX($sumarx);
$pdf->SetFont('arial','',10);
$pdf->Cell(35,6,"$Hcelkomstrana","0",0,"R");
              }
//koniec popis pod polozkami

  }
//koniec while
           }
//koniec ak su polozky

$pdf->Cell(40,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(30,6," ","LR",0,"R");
$pdf->Cell(35,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(35,6," ","LR",1,"R");

$pdf->Cell(40,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(30,6," ","LR",0,"R");
$pdf->Cell(35,6," ","LR",0,"R");$pdf->Cell(20,6," ","LR",0,"R");$pdf->Cell(35,6," ","LR",1,"R");

$pdf->Cell(40,6," ","LRB",0,"R");$pdf->Cell(20,6," ","LRB",0,"R");$pdf->Cell(30,6," ","LRB",0,"R");
$pdf->Cell(35,6," ","LRB",0,"R");$pdf->Cell(20,6," ","LRB",0,"R");$pdf->Cell(35,6," ","LRB",1,"R");

//tlac textu na zostavy
$zostxx = include("../cis/zostxx.php");
//koniec tlac textu na zostavy

$pdf->Cell(35,6," ","0",1,"R");
$pdf->Cell(35,6,"Doru�il:","0",1,"L");
if( $sekov == 1 ) {
$pdf->Cell(35,6,"Miesto: $fir_fmes","0",0,"L");$pdf->Cell(75,6," ","0",0,"L");$pdf->Cell(70,6," ","0",1,"R");
                  }
$pdf->Cell(35,6,"D�a:","0",0,"L");$pdf->Cell(75,6," ","0",0,"R");$pdf->Cell(70,6," ","0",1,"R");
$pdf->Cell(35,6," ","0",0,"L");$pdf->Cell(75,6," ","0",0,"R");$pdf->Cell(70,6,"podpis,pe�iatka pr�kazcu","T",1,"C");

$koniec=270;
$pdf->SetY($koniec);
$pdf->Line(15, $koniec, 195, $koniec); 
$pdf->SetY($koniec+2);
$pdf->SetFont('arial','',6);
$pdf->Cell(180,3,"Vystavil(a): $hlavicka->meno $hlavicka->priezvisko / $hlavicka->id ","0",1,"L");

$sumary=34;
$sumarx=70;
$pdf->SetY($sumary);
$pdf->SetX($sumarx);
$pdf->SetFont('arial','',10);
$pdf->Cell(35,6,"$Hcelkomstrana","0",0,"R");


  }
//koniec hlavicky


$pdf->Output("../tmp/priku$cislo_dok.$kli_uzid.pdf")


?> 

<script type="text/javascript">
  var okno = window.open("../tmp/priku<?php echo $cislo_dok; ?>.<?php echo $kli_uzid; ?>.pdf","_self");
</script>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=cp1250">
  <link type="text/css" rel="stylesheet" href="../css/styl.css">
<title>Pr�kaz na �hradu PDF</title>
  <style type="text/css">

  </style>
<script type="text/javascript">
    
</script>
</HEAD>
<BODY class="white" >

<?php 



?>

<table class="h2" width="100%" >
<tr>
<td>EuroSecom  -  Pr�kaz na �hradu PDF form�t</td>
<td align="right"><span class="login"><?php echo "login: $kli_uzmeno $kli_uzprie / $kli_uzid ";?></span></td>
</tr>
</table>
<br />

<?php 



?>

<a href="../tmp/test<?php echo $kli_uzid; ?>.pdf">../tmp/test<?php echo $kli_uzid; ?>.pdf</a>


<?php
// celkovy koniec dokumentu
       } while (false);
?>
</BODY>
</HTML>
