<?PHP
session_start();
$_SESSION['ucto_sys'] = 1;
$_SESSION['pocstav'] = 0; /*dopyt, nebude treba v bud�cnosti, nebudem rozli�ova�*/
?>
<!doctype html>
<html>
<?php
//cislo operacie
$copern = 1*$_REQUEST['copern'];

       do
       {
if ( $copern !== 99 )
{
$sys = 'UCT';
$urov = 2000;
$cslm = 1;
if( $_SESSION['kli_vxcf'] == 9999 )
{ echo "Vypnite v�etky okn� v prehliada�i IE a prihl�ste sa znovu pros�m do IS, ak ste pou��vali Cestovn� pr�kazy"; exit; } //dopyt, nie je mi jasn�
$uziv = include("uziv.php");
if( !$uziv ) exit;

  require_once("pswd/password.php");

$mysqldbfir=$mysqldb;
$mysqldbdata=$mysqldb;
$oddelnew=0;
if (File_Exists ("pswd/newdeleniedtb.ano") OR File_Exists ("../pswd/newdeleniedtb.ano"))
          {
$dtb2 = include("oddel_dtb1new.php");
$oddelnew=1;
          }
else
          {
$dtb2 = include("oddel_dtb1.php");
          }

  @$spojeni = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if (!$spojeni):
    echo "Spojenie so serverom nedostupne.";
    exit;
  endif;
  mysql_select_db($mysqldb);

}

$firs = 1*$_REQUEST['firs'];
$umes = 1*$_REQUEST['umes'];

//echo "copern ".$copern." firs ".$firs;

// zmena firmy
if ( $copern == 25 OR $copern == 23 )
     {

//ak zmena firmy nastav umes podla kli_vrok vo firme
if ( $copern == 23 )
     {
$sqlmax = mysql_query("SELECT * FROM $mysqldbfir.fir WHERE xcf=$firs");
  if (@$zaznam=mysql_data_seek($sqlmax,0))
  {
  $riadmax=mysql_fetch_object($sqlmax);
  $umes="1.".$riadmax->rok;
  }
     }


$zmazane = mysql_query("DELETE FROM $mysqldbfir.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldbfir.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");

if( $oddelnew == 1 )
  {
$zmazane = mysql_query("DELETE FROM $mysqldb2010.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2010.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2011.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2011.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2012.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2012.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2013.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2013.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2014.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2014.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2015.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2015.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2016.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2016.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2017.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2017.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2018.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2018.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
$zmazane = mysql_query("DELETE FROM $mysqldb2019.nas_id WHERE id='$kli_uzid'");
$ulozene = mysql_query("INSERT INTO $mysqldb2019.nas_id ( id,xcf,ume ) VALUES ($kli_uzid, $firs, $umes); ");
  }
     }

$cit_nas = include("cis/citaj_nas.php");

$cook=0;
if( $cook == 1 )
    {
setcookie("kli_vxcf", $vyb_xcf, time() + (7 * 24 * 60 * 60));
setcookie("kli_nxcf", $vyb_naz, time() + (7 * 24 * 60 * 60));
setcookie("kli_vume", $vyb_ume, time() + (7 * 24 * 60 * 60));
setcookie("kli_vrok", $vyb_rok, time() + (7 * 24 * 60 * 60));
    }
session_start();
$_SESSION['kli_vxcf'] = $vyb_xcf;
$_SESSION['kli_nxcf'] = $vyb_naz;
$_SESSION['kli_vume'] = $vyb_ume;
$_SESSION['kli_vrok'] = $vyb_rok;
$_SESSION['kli_vduj'] = $vyb_duj;

  $kli_vduj=$_SESSION['kli_vduj'];

if (File_Exists ("pswd/newdeleniedtb.ano") OR File_Exists ("../pswd/newdeleniedtb.ano"))
          {
$dtb2 = include("oddel_dtb2new.php");
          }
else
          {
$dtb2 = include("oddel_dtb2.php");
          }


//echo " rok ".$vyb_rok;
//echo " dbdata ".$mysqldbdata."<br />";
//exit;

  mysql_select_db($mysqldbdata);
$vrz = include("verzia.php");

$cvybxcf=1*$vyb_xcf;
if( $cvybxcf > 0 )
          {
//len ak je vybrana firma
//echo "<br /><br /><br /><br /><br />idem";

$sql = "SELECT zmen FROM ".$mysqldbdata.".F$vyb_xcf"."_uctvsdh";
if( $copern == 1 OR $copern == 25 )
{
//echo $sql;
$vysledok = mysql_query($sql);
if (!$vysledok):
$kli_vxcf=$vyb_xcf;
  mysql_select_db($mysqldbdata);
$kalend = include("ucto/vtvuct.php");
endif;
}


          }
//len ak je vybrana firma

//cleaning
$datdnessql = Date ("Y-m-d", MkTime (date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));
$jeclean=0;
$poslhh = "SELECT * FROM ".$mysqldbdata.".cleaningtmp WHERE dat='$datdnessql' ";
$posl = mysql_query("$poslhh");
if( $posl ) { $jeclean = 1*mysql_num_rows($posl); }
if( $jeclean == 0 )
{
$copernx="alibaba40";
//echo "idem";
$clean = include("funkcie/subory.php");
}

//month navigation
$zmenume=1*$zmenume;
$pole = explode(".", $vyb_ume);
$kli_vmes=$pole[0];
$kli_vrok=$pole[1];
$kli_pmes=$kli_vmes-1;
if ( $kli_pmes < 1 ) $kli_pmes=1;
$kli_dmes=$kli_vmes+1;
if ( $kli_dmes > 12 ) $kli_dmes=12;
$kli_pume=$kli_pmes.".".$kli_vrok;
$kli_dume=$kli_dmes.".".$kli_vrok;
$odkaz="../ucto_md.php?copern=1";
$odkaz64=urlencode($odkaz);

//ufir data
$jemenpid=0;
$sqlpoktt = "SELECT * FROM F$kli_vxcf"."_ufir ";
$sqlpok = mysql_query("$sqlpoktt");
if (@$zaznam=mysql_data_seek($sqlpok,0))
{
$riadokpok=mysql_fetch_object($sqlpok);
$fir_fnaz=$riadokpok->fnaz;
}

//first login new user
if ( $vyb_xcf == '' ) { $copern=22; } //dopyt, preveri�



?>
<head>
  <meta charset="cp1250">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="css/material.min.css">
  <link rel="stylesheet" href="css/material_edit.css">
  <title>��tovn�ctvo | EuroSecom</title>
<style>
/* change default */
.mdl-layout__tab-bar-container {
  border-bottom: 1px solid #CFD8DC;
}
.mdl-layout__tab-bar-container, .mdl-layout__tab-bar-button, .mdl-layout__tab-bar {
  background-color: #ECEFF1;
}
.mdl-layout.is-upgraded .mdl-layout__tab.is-active {
  color: #039BE5;
}
.mdl-layout__tab {
  color: rgba(0,0,0,0.4);
  height: 64px;
  line-height: 64px;
}
.mdl-layout.is-upgraded .mdl-layout__tab.is-active:after {
  height: 3px;
  background-color: #039BE5;
}
.mdl-layout__tab-bar-button, .mdl-layout__tab-bar, .mdl-layout__tab-bar-container {
  height: 64px;
}
.mdl-layout__tab-bar-button .material-icons {
  line-height: 64px;
}


.ui-header-toolbar .mdl-button--icon {
  height: 40px;
  width: 40px;
  min-width: 40px;
}






.container {
  max-width: 1440px;
}


.card-module {
  margin: 16px 0;
  min-height: 56px;
  padding: 10px 0;
  background-color: #fff;
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
}
.card-module-header {
  height: 36px;
  padding: 6px 0 6px 16px;
  position: relative;
  overflow: hidden;
}
.card-module-header .material-icons {
  color: rgba(0,0,0,.54);
  float: left;
}
.card-module-title {
  font-size: 19px;
  float: left;
  height: 24px;
  padding-top: 3px;
  padding-left: 14px;
  width: calc(100% - 48px);
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.card-module-content {
  position: relative;
}
.card-module-content .card-item {
  font-size: 13px;
  height: 36px;
  padding: 12px 28px 12px 56px;
  letter-spacing: 0.02em;
  position: relative;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;

}
.card-module [onclick]:hover {
  /*background-color: #eee;*/
  /*background-color: #E3F2FD;*/
  background-color: rgba(3,155,229,0.2);
  /*background-color: #E1F5FE;*/
}




.card-module [onclick]:hover:after {
  display: inline-block;
  font-family: 'Material Icons';
  content: '\e89e';
  font-size: 18px;
  position: absolute;
  right: 9px;
  top: 9px;
  color: rgba(0,0,0,.54);
}


.card-module strong {
  font-weight: 500;
}






.card-module[disabled], .card-module-header[disabled], .card-item[disabled], .card-module .mdl-menu__item[disabled] {
  color: rgba(0,0,0,.54);
  background-color: transparent;
  box-shadow: none;
  pointer-events: none;
}




.mdl-layout__header-row .btn-dropdown {
  color: rgba(255,255,255,.6);
  letter-spacing: 0.02em;
}
.mdl-layout__header-row .btn-dropdown:hover {
  background-color: #039BE5;
}




.modal-cover {
  z-index: 100;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(33,33,33); /* Fallback color */
  background-color: rgba(33,33,33,0.45);
}
.modal {
  /*width: 56%;*/
  /*max-width: 768px;*/
width: 720px;
  background-color: #fff;
  overflow: auto;
  /*max-width: 768px;*/

  /*height: 480px;*/
  /*padding: 24px;*/
  /*left: 50%;*/
  /*margin-left: -28%;*/
  /*position: absolute;*/
  /*top: 50%;*/
  /*margin-top: -260px;*/

}
.modal-header {
  /*height: 40px;*/
}
.modal-title {
  font-size: 20px;
  letter-spacing: 0.02em;
}


</style>
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--no-drawer-button">
<header class="mdl-layout__header mdl-layout__header--waterfall ui-header" style="min-height: 112px;">
  <div class="mdl-layout__header-row mdl-color--light-blue-700" style="height: 48px;  ">
    <span class="mdl-layout-title mdl-color-text--yellow-A100" style="font-size: 16px;">EuroSecom</span>&nbsp;&nbsp;

    <button type="button" id="select_firm" onclick="selectFirm();" class="mdl-button mdl-js-button btn-dropdown">
      <strong class="mdl-color-text--white" style=""><?php echo $vyb_xcf; ?></strong>&nbsp;
      <span class="mdl-color-text--white" style="text-transform: none; font-weight: 400;"><?php echo $vyb_naz; ?></span>
      <i class="material-icons vacenter">arrow_drop_down</i>
    </button>

    <button type="button" id="select_month" onclick="selectPeriod();" class="mdl-button mdl-js-button btn-dropdown">
      <span class="mdl-color-text--white" style="font-weight: 400;"><?php echo $vyb_ume; ?></span>
      <i class="material-icons vacenter">arrow_drop_down</i>
    </button>
    <div class="mdl-layout-spacer"></div>

    <button type="button" id="user" class="mdl-button mdl-js-button mdl-button--icon mdl-color--indigo-400 mdl-color-text--white avatar"><?php echo $kli_uzid; ?></button>&nbsp;&nbsp;
    <span class="mdl-color-text--white"><?php echo "$kli_uzmeno $kli_uzprie"; ?></span>
  </div>
<!-- Tabs -->
  <div class="mdl-layout__tab-bar ui-header-nav" style="  overflow: auto;">
    <a href="#" onclick="Ucto();" class="mdl-layout__tab is-active">��tovn�ctvo
<?php
if ( $vyb_duj == 0 ) { echo "podvojn�"; }
if ( $vyb_duj == 9 ) { echo "jednoduch�"; }
?>
    </a>
    <a href="#" onclick="Mzdy();" class="mdl-layout__tab">Mzdy</a>
    <a href="#" onclick="Odbyt();" class="mdl-layout__tab">Odbyt</a>
    <a href="#" onclick="Sklad();" class="mdl-layout__tab">Sklad</a>
    <a href="#" onclick="Majetok();" class="mdl-layout__tab">Majetok</a>
    <a href="#" id="more_subs" class="mdl-layout__tab mdl-layout--small-screen-only" style="padding: 0;"><i class="material-icons vacenter">more_horiz</i></a>
    <a href="#" onclick="Doprava();" class="mdl-layout__tab mdl-layout--large-screen-only">Doprava</a>
    <a href="#" onclick="Vyroba();" class="mdl-layout__tab mdl-layout--large-screen-only">Vyroba</a>
    <a href="#" onclick="Analyzy();" class="mdl-layout__tab mdl-layout--large-screen-only">Anal�zy</a>
    <div class="mdl-layout-spacer"></div>
<!-- tools -->
    <div class="ui-header-toolbar flexbox" style="padding: 12px 0; ">
      <button id="searching" onclick="Searching();" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Preh�ad�vanie">
        <i class="material-icons">search</i>
      </button>
      <button id="transfer" onclick="Transfer();" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Prenosy">
        <i class="material-icons">hourglass_empty</i>
      </button>
      <button id="more_tools" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Viac n�strojov">
        <i class="material-icons">more_vert</i>
      </button>
    </div> <!-- .ui-header-toolbar -->
  </div> <!-- tabs -->
</header>

<!-- more subs nav menu -->
<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="more_subs">
  <li onclick="Doprava();" class="mdl-menu__item">Doprava</li>
  <li onclick="Vyroba();" class="mdl-menu__item">V�roba</li>
  <li onclick="Analyzy();" class="mdl-menu__item">Anal�zy</li>
</ul>

<!-- more header tools -->
<div style="position:fixed; right: 32px; top: 56px; z-index: 10;">
      <ul for="more_tools" class="mdl-menu mdl-menu--bottom-right mdl-js-menu">
        <li id="account_checks" class="mdl-menu__item" onclick="AccountChecks();">Kontrola ��tovania</li>
        <li id="backup" class="mdl-menu__item" onclick="Backup();">Z�lohovanie d�t</li>
        <li id="calculator" class="mdl-menu__item" onclick="Calculator();">Kalkula�ka</li>
      </ul>
</div>










<!-- month nav -->
<button type="button" id="month_prev" onclick="navMonth(1);" class="mdl-button mdl-js-button page-nav-btn mdl-button--colored">
  <i class="material-icons md-40">navigate_before</i>
</button>
  <div class="mdl-tooltip" data-mdl-for="month_prev">Prejs� na <?php echo $kli_pume; ?></div>
<button type="button" id="month_next" onclick="navMonth(2);" class="mdl-button mdl-js-button page-nav-btn mdl-button--colored">
  <i class="material-icons md-40">navigate_next</i>
</button>
  <div class="mdl-tooltip" data-mdl-for="month_next">Prejs� na <?php echo $kli_dume; ?></div>





<main class="mdl-layout__content mdl-color--blue-grey-50">
<div class="mdl-grid container">
<!-- 1.column -->
  <div class="mdl-cell mdl-cell--4-col">
<!-- vstup dat -->
    <div id="vstup_data" class="card-module">
      <div class="card-module-header">
        <i class="material-icons">add_to_photos</i>
        <span class="card-module-title">Vstup d�t</span>
      </div>
      <ul class="card-module-content">
        <li id="odber_faktury" onclick="OdberFa();" class="card-item">Odberate�sk� fakt�ry</li>
        <li id="dodav_faktury" onclick="DodavFa();" class="card-item">Dod�vate�sk� fakt�ry</li>
        <li id="prijem_pokladna" onclick="PrijemPokl();" class="card-item">Pr�jmov� pokladni�n� doklady</li>
        <li id="vydaj_pokladna" onclick="VydavPokl();" class="card-item">V�davkov� pokladni�n� doklady</li>
        <li id="bank_vypisy" onclick="BankVyp();" class="card-item">Bankov� v�pisy</li>
        <li id="vseobec_doklady" onclick="VseoDokl();" class="card-item">V�eobecn� ��tovn� doklady</li>
      </ul>
    </div>
<!-- ekorobot -->
    <div id="ekorobot" class="card-module" style="background-color: transparent; box-shadow: none; padding-left: 56px;">
      <div class="card-module-content">
        <img src="obr/robot/robot3.jpg" onclick="showRobotMenu();" title="Ak m�te �elanie, kliknite na m�a" class="">
      </div>
    </div>
<!-- data podsystemy -->
    <div id="podsystem_data" class="card-module">
      <div onclick="PodsystemData();" class="card-module-header">
        <i class="material-icons">storage</i>
        <span class="card-module-title">D�ta z podsyst�mov</span>
      </div>
      <ul class="card-module-content">
        <li id="mzdy_data" onclick="MzdyData();" class="card-item">Mzdy a personalistika</li>
        <li id="sklad_data" onclick="SkladData();" class="card-item">Sklad</li>
        <li id="majetok_data" onclick="MajetokData();" class="card-item">Majetok</li>
      </ul>
    </div>
  </div> <!-- .mdl-cell -->

<!-- 2.column -->
  <div class="mdl-cell mdl-cell--4-col">
<!-- saldokonto -->
    <div id="saldo" class="card-module">
      <div onclick="Saldo();" class="card-module-header">
        <i class="material-icons">thumbs_up_down</i>
        <span class="card-module-title">Saldokonto</span>
      </div>
      <ul class="card-module-content">
        <li id="upomienky" onclick="Upomienky();" class="card-item">Upomienky</li>
        <li id="zapocty" onclick="Zapocty();" class="card-item">Vz�jomn� z�po�ty</li>
        <li id="faktoring" onclick="Faktoring();" class="card-item">Faktoring</li>
        <li id="prikazy" onclick="Prikazy();" class="card-item">Pr�kazy na �hradu</li>
      </ul>
    </div>
<!-- vystupy -->
    <div id="vystupy" class="card-module">
      <div class="card-module-header">
        <i class="material-icons">call_made</i>
        <span class="card-module-title">V�stupy</span>
      </div>
      <ul class="card-module-content">
        <li id="uct_zostavy" onclick="UctZostavy();" class="card-item">��tovn� zostavy
          <button id="account_report" class="mdl-button mdl-js-button mdl-button--icon" style="position: absolute; top: 2px; right: 24px;">
            <i class="material-icons md-dark">more_vert</i>
          </button>
        </li>
        <li>
          <ul for="account_report" class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
             style="" >
            <li id="obratovka" onclick="Obratovka();" class="mdl-menu__item">Obratov� predvaha</li>
            <li id="vysledovka" onclick="Vysledovka();" class="mdl-menu__item">V�sledovka jednoduch�</li>
          </ul>
        </li>
        <li id="stat_vykazy" onclick="StatVykazy();" class="card-item">�tatistick� v�kazy</li>
        <li id="dan_form" onclick="DanForms();" class="card-item">Da�ov� formul�re</li>
      </ul>
    </div>
<!-- dph -->
    <div id="dph" class="card-module">
      <div onclick="Dph();" class="card-module-header">
        <i class="material-icons">playlist_add</i>
        <span class="card-module-title">Da� z pridanej hodnoty</span>
      </div>
    </div>
<!-- cudzia mena -->
    <div id="mena" class="card-module">
      <div onclick="Mena();" class="card-module-header">
        <i class="material-icons">playlist_add</i>
        <span class="card-module-title">Cudzia mena</span>
      </div>
    </div>
  </div> <!-- mdl-cell -->

<!-- 3.column -->
  <div class="mdl-cell mdl-cell--4-col">
<!-- nastavenia -->
    <div id="nastavenia" class="card-module">
      <div class="card-module-header">
        <i class="material-icons">settings</i>
        <span class="card-module-title">Nastavenia</span>
      </div>
      <ul class="card-module-content">
        <li id="parametre" onclick="PrmUcto();" class="card-item">Parametre ��tovn�ctva</li>
        <li id="osnova" onclick="UctOsn();" class="card-item">��tov� osnova</li>
        <li id="dph_kody" onclick="DphKody();" class="card-item">K�dy DPH</li>
        <li class="card-item">Analytick� ��ty
          <button id="account_analytical" class="mdl-button mdl-js-button mdl-button--icon" style="position: absolute; top: 2px; right: 24px; ">
            <i class="material-icons md-dark">more_vert</i>
          </button>
        </li>
        <li>
          <ul for="account_analytical" class="mdl-menu mdl-menu--top-right mdl-js-menu mdl-js-ripple-effect">
            <li id="odber_ucty" onclick="OdberUcty();" class="mdl-menu__item">Odberate�sk� ��ty</li>
            <li id="dodav_ucty" onclick="DodavUcty();" class="mdl-menu__item">Dod�vate�sk� ��ty</li>
            <li id="pokladna_ucty" onclick="PoklUcty();" class="mdl-menu__item">Pokladnice</li>
            <li id="banka_ucty" onclick="BankaUcty();" class="mdl-menu__item">Bankov� ��ty</li>
          </ul>
        </li>
      </ul>
    </div>
<!-- ciselniky -->
    <div id="firma_udaje" class="card-module">
      <div onclick="FirmaUdaje();" class="card-module-header">
        <i class="material-icons">home</i>
        <span class="card-module-title"><?php echo $fir_fnaz; ?></span>
      </div>
      <ul class="card-module-content">
        <li class="card-item clearfix" style="height: 48px; line-height: 1.4; padding-top: 8px;">
          <div class="toleft" style="min-width: 88px;">Firma ID<br><strong><?php echo $kli_vxcf; ?></strong></div>
          <div>Firma obdobie<br><strong><?php echo $kli_vrok; ?></strong></div>
        </li>
        <li id="cico" onclick="CisIco();" class="card-item">��seln�k I�O</li>
        <li onclick="" class="card-item">��seln�ky
          <button id="codelist" class="mdl-button mdl-js-button mdl-button--icon" style="position: absolute; top: 2px; right: 24px;">
            <i class="material-icons md-dark">more_vert</i>
          </button>
        </li>
        <li>
          <ul for="codelist" class="mdl-menu mdl-menu--top-right mdl-js-menu mdl-js-ripple-effect">
            <li id="strediska" onclick="Strediska();" class="mdl-menu__item">Stredisk�</li>
            <li id="zakazky" onclick="Zakazky();" class="mdl-menu__item">Z�kazky</li>
            <li id="skupiny" onclick="Skupiny();" class="mdl-menu__item">Skupiny</li>
            <li id="stavby" onclick="Stavby();" class="mdl-menu__item">Stavby</li>
          </ul>
        </li>
      </ul>
    </div>
  </div> <!-- .mdl-cell -->
</div> <!-- .mdl-grid -->
</main>












<!--   <div class="tiles-col-content">
   <img src="obr/robot/<?php echo $robot3; ?>.jpg" onclick="UkazLista(ekorobot);" title="Ak m�te �elanie, kliknite na m�a" class="ekorobot-xl">
  </div> -->









<?php
if ( $copern == 22 OR $copern == 23 OR $copern == 24 )
     {
?>
<div class="modal-cover">
<?php
//select period
if ( $copern == 22 )
     {
$pole = explode(",", $kli_txt1);

$pole0 = explode("-", $pole[0]);
$kli_fmin0=$pole0[0];
$kli_fmax0=$pole0[1];
$akefirmy = "( xcf >= $kli_fmin0 AND xcf <= $kli_fmax0 )";

$pole1 = explode("-", $pole[1]);
$kli_fmin1=$pole1[0];
$kli_fmax1=$pole1[1];
$cislo=1*$kli_fmin1;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin1 AND xcf <= $kli_fmax1 )";

$pole2 = explode("-", $pole[2]);
$kli_fmin2=$pole2[0];
$kli_fmax2=$pole2[1];
$cislo=1*$kli_fmin2;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin2 AND xcf <= $kli_fmax2 )";

$pole3 = explode("-", $pole[3]);
$kli_fmin3=$pole3[0];
$kli_fmax3=$pole3[1];
$cislo=1*$kli_fmin3;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin3 AND xcf <= $kli_fmax3 )";

$pole4 = explode("-", $pole[4]);
$kli_fmin4=$pole4[0];
$kli_fmax4=$pole4[1];
$cislo=1*$kli_fmin4;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin4 AND xcf <= $kli_fmax4 )";

$pole5 = explode("-", $pole[5]);
$kli_fmin5=$pole5[0];
$kli_fmax5=$pole5[1];
$cislo=1*$kli_fmin5;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin5 AND xcf <= $kli_fmax5 )";

$pole6 = explode("-", $pole[6]);
$kli_fmin6=$pole6[0];
$kli_fmax6=$pole6[1];
$cislo=1*$kli_fmin6;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin6 AND xcf <= $kli_fmax6 )";

$pole7 = explode("-", $pole[7]);
$kli_fmin7=$pole7[0];
$kli_fmax7=$pole7[1];
$cislo=1*$kli_fmin7;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin7 AND xcf <= $kli_fmax7 )";

$pole8 = explode("-", $pole[8]);
$kli_fmin8=$pole8[0];
$kli_fmax8=$pole8[1];
$cislo=1*$kli_fmin8;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin8 AND xcf <= $kli_fmax8 )";

$pole9 = explode("-", $pole[9]);
$kli_fmin9=$pole9[0];
$kli_fmax9=$pole9[1];
$cislo=1*$kli_fmin9;
if ( $cislo > 0 ) $akefirmy = $akefirmy." OR ( xcf >= $kli_fmin9 AND xcf <= $kli_fmax9 )";

if ( $akefirmy == "( xcf >= 0 AND xcf <= 0 )" ) { $setuzfir = include("cis/vybuzfir.php"); }

$sql = mysql_query("SELECT xcf,naz, rok FROM $mysqldbfir.fir WHERE ( $akefirmy ) AND SUBSTRING(prav,$kli_uzid,1) != 'n' ORDER BY xcf");
$cpol = mysql_num_rows($sql);
?>
<FORM name="fir1" method="post" action="ucto_md.php?copern=23">
  <div class="mdl-dialog modal" style="">
    <div class="mdl-dialog__title modal-title ">V�ber firmy</div>
    <div class="mdl-dialog__content modal-content" style="overflow: auto; height: 300px;">
    <table class="data-table highlight-row" style="width: 500px;">
    <tr id="first_row" style="height: 24px;">
      <th class="right" style="width: 80px; padding-right: 16px;">��slo</th>
      <th class="left" style="width: 320px;">N�zov</th>
      <th class="right" style="width: 100px; padding-right: 16px;">Obdobie</th>
    </tr>
<style>
.preselect {
  font-weight: 500;
  /*background-color: #B3E5FC;*/
}
</style>
<?php
while ( $zaznam=mysql_fetch_array($sql) ):
if ( $zaznam["xcf"] == $vyb_xcf ) { $row_state = 'preselect'; } //dopyt, class priraden� viacer�m riadkom
?>
    <tr class="<?php echo $row_state; ?> row-echo" style="height: 32px;">
      <td class="right">
        <input type="radio" name="firs" id="firs<?php echo $zaznam["xcf"]; ?>" value="<?php echo $zaznam["xcf"]; ?>" class="hidden">
        <label for="firs<?php echo $zaznam["xcf"]; ?>" style="padding-right: 16px;"><?php echo $zaznam["xcf"]; ?></label>
      </td>
      <td style="">
        <label for="firs<?php echo $zaznam["xcf"]; ?>"><?php echo $zaznam["naz"]; ?></label>
      </td>
      <td class="right" style="">
        <label for="firs<?php echo $zaznam["xcf"]; ?>" style="padding-right: 16px;"><?php echo $zaznam["rok"]; ?></label>
      </td>
    </tr>
<?php endwhile; ?>
    </table>
    </div> <!-- .modal-content -->
    <div class="mdl-dialog__actions">
      <button class="mdl-button mdl-js-button mdl-button--primary" style="margin-right: 20px;">Vybra�</button>
    </div>
  </div> <!-- .modal -->
</FORM>
<?php
mysql_close();
mysql_free_result($sql);
//end select firm
     }
?>

<?php
//select period
if ( $copern == 23 OR $copern == 24 )
     {
?>
 <FORM name="fir1" method="post" action="ucto_md.php?copern=25">
  <div class="mdl-dialog modal" style="">
    <div class="mdl-dialog__title modal-title">V�ber obdobia</div>
    <div class="mdl-dialog__content modal-content" style="overflow: auto; height: 300px;">

<!-- dopyt dorobi� nie�o na sp�sob : -->
<?php
//while($zaznam=mysql_fetch_array($sql)):
//if ( $zaznam["xcf"] == $vyb_xcf ) { $class = 'selected'; }
?>
<!-- aby som mohol aktu�lnemu mesiacu ur�i� class -->
<style>
.highlight-list li {
  height: 30px;
  line-height: 30px;
  border-bottom: 1px solid #CFD8DC;
  font-size: 13px;
}
.highlight-list li input[type='radio'] {
  float: left;

}

</style>


<ul class="highlight-list tocenter" style="width: 200px;">
  <li id="first_row" style="height: 0;">&nbsp;</li>
  <li>
    <input type="radio" name="period" id="01.<?php echo $vyb_rok; ?>" value="01.<?php echo $vyb_rok; ?>">
    <label for="01.<?php echo $vyb_rok; ?>">01.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="02.<?php echo $vyb_rok; ?>" value="02.<?php echo $vyb_rok; ?>">
    <label for="02.<?php echo $vyb_rok; ?>">02.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="03.<?php echo $vyb_rok; ?>" value="03.<?php echo $vyb_rok; ?>">
    <label for="03.<?php echo $vyb_rok; ?>">03.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="04.<?php echo $vyb_rok; ?>" value="04.<?php echo $vyb_rok; ?>">
    <label for="04.<?php echo $vyb_rok; ?>">04.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="05.<?php echo $vyb_rok; ?>" value="05.<?php echo $vyb_rok; ?>">
    <label for="05.<?php echo $vyb_rok; ?>">05.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="06.<?php echo $vyb_rok; ?>" value="06.<?php echo $vyb_rok; ?>">
    <label for="06.<?php echo $vyb_rok; ?>">06.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="07.<?php echo $vyb_rok; ?>" value="07.<?php echo $vyb_rok; ?>">
    <label for="07.<?php echo $vyb_rok; ?>">07.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="08.<?php echo $vyb_rok; ?>" value="08.<?php echo $vyb_rok; ?>">
    <label for="08.<?php echo $vyb_rok; ?>">08.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="09.<?php echo $vyb_rok; ?>" value="09.<?php echo $vyb_rok; ?>">
    <label for="09.<?php echo $vyb_rok; ?>">09.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="10.<?php echo $vyb_rok; ?>" value="10.<?php echo $vyb_rok; ?>">
    <label for="10.<?php echo $vyb_rok; ?>">10.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="11.<?php echo $vyb_rok; ?>" value="11.<?php echo $vyb_rok; ?>">
    <label for="11.<?php echo $vyb_rok; ?>">11.<?php echo $vyb_rok; ?></label>
  </li>
  <li>
    <input type="radio" name="period" id="12.<?php echo $vyb_rok; ?>" value="12.<?php echo $vyb_rok; ?>">
    <label for="12.<?php echo $vyb_rok; ?>">12.<?php echo $vyb_rok; ?></label>
  </li>
</ul>

<!-- <select size="12" name="umes" id="umes">
 <option value="01.<?php echo $vyb_rok; ?>" selected="selected">01.<?php echo $vyb_rok; ?></option>
 <option value="02.<?php echo $vyb_rok; ?>">02.<?php echo $vyb_rok; ?></option>
 <option value="03.<?php echo $vyb_rok; ?>">03.<?php echo $vyb_rok; ?></option>
 <option value="04.<?php echo $vyb_rok; ?>">04.<?php echo $vyb_rok; ?></option>
 <option value="05.<?php echo $vyb_rok; ?>">05.<?php echo $vyb_rok; ?></option>
 <option value="06.<?php echo $vyb_rok; ?>">06.<?php echo $vyb_rok; ?></option>
 <option value="07.<?php echo $vyb_rok; ?>">07.<?php echo $vyb_rok; ?></option>
 <option value="08.<?php echo $vyb_rok; ?>">08.<?php echo $vyb_rok; ?></option>
 <option value="09.<?php echo $vyb_rok; ?>">09.<?php echo $vyb_rok; ?></option>
 <option value="10.<?php echo $vyb_rok; ?>">10.<?php echo $vyb_rok; ?></option>
 <option value="11.<?php echo $vyb_rok; ?>">11.<?php echo $vyb_rok; ?></option>
 <option value="12.<?php echo $vyb_rok; ?>">12.<?php echo $vyb_rok; ?></option>
</select> -->

<!-- <INPUT type="hidden" id="firs" name="firs" value="<?php echo $vyb_xcf; ?>"> dopyt, neviem kam -->
<!-- <button type="submit" id="umev" name="umev">Vybra�</button> -->
    </div> <!-- .modal-content -->
    <div class="mdl-dialog__actions">
      <button class="mdl-button mdl-js-button mdl-button--primary" style="margin-right: 20px;">Vybra�</button>
    </div>
  </div> <!-- .modal -->
</FORM>
<?php
    }
//end select period
?>
</div> <!-- .modal-cover -->
<?php
//$copern=22,23,24
}
?>

















<!-- <div id="robotokno" style="cursor: hand; display: none; position: absolute; z-index: 200; top: 200; left: 40; width:60; height:100;">
<img border=0 src='../obr/robot/<?php echo $robot3;?>.jpg' style='' onClick="zobraz_robotmenu();"
 alt='Dobr� de� , ja som V� EkoRobot , ak m�te ot�zku alebo nejak� �elanie kliknite na m�a pros�m 1x my�ou' >
<img border=0 src='../obr/zmazuplne.png' style='width:10; height:10;' onClick="zhasnirobot();"
 alt='Zhasni EkoRobota' >
</div>
<div id="robotmenu" style="cursor: hand; display: none; position: absolute; z-index: 300; top: 150; left: 90; width:200; height:100;">
zobrazene menu
</div> -->




</div> <!-- .mdl-layout -->


<?php
//$robot=1;




//celkovy koniec dokumentu
       } while (false);
?>





<script type="text/javascript">
//blank window
var param = 'scrollbars=yes,resizable=yes,top=0,left=0,width=1080,height=900'; //dopyt, premenn� do eng a nesk�r pou�i�

//month nav
  function navMonth(kam)
  {
    window.open('../cis/zmenume.php?odkaz=<?php echo $odkaz64; ?>&copern=' + kam + '', '_self');
  }
<?php if ( $kli_vmes == 1 ) { ?> document.getElementById('month_prev').disabled = true; <?php } ?>
<?php if ( $kli_vmes == 12 ) { ?> document.getElementById('month_next').disabled = true; <?php } ?>

//select firm/month
  function selectFirm()
  {
    window.open('ucto_md.php?copern=22', '_self');
  }
  function selectPeriod()
  {
    window.open('ucto_md.php?copern=24', '_self');
  }
<?php if ( $vyb_xcf > 0 AND $copern == 22 ) { ?>
  document.fir1.firs<?php echo $vyb_xcf; ?>.checked = 'true';
  document.fir1.firs<?php echo $vyb_xcf; ?>.focus();
<?php } ?>

  function selectRow()
  {
    var radios = document.getElementsByName("firs");
    for( var i = 0; i < radios.length; i++ )
    {
      radios[i].onclick = function()
      {
        //remove class from the other rows
        var el = document.getElementById("first_row");
          //go to the nex sibing
          while(el = el.nextSibling)
          {
            if(el.tagName === "TR")
            {
              //remove the selected class
              el.classList.remove("selected");
            }
          }
        //radio.td.tr
        this.parentElement.parentElement.classList.toggle("selected");
      };
    }
  }
  selectRow();

  // function selectRow(period)
  // {
  //   var radios = document.getElementsByName("period");
  //   for( var i = 0; i < radios.length; i++ )
  //   {
  //     radios[i].onclick = function()
  //     {
  //       //remove class from the other rows
  //       var el = document.getElementById("first_row");
  //         //go to the nex sibing
  //         while(el = el.nextSibling)
  //         {
  //           if(el.tagName === "LI")
  //           {
  //             //remove the selected class
  //             el.classList.remove("selected");
  //           }
  //         }
  //       //radio.li
  //       this.parentElement.classList.toggle("selected");
  //     };
  //   }
  // }
  // selectRow(period);




//subsystems
  function Ucto()
  {
    window.open('ucto_md.php?copern=1', '_self');
  }
  function Mzdy()
  {
    window.open('mzdy_md.php?copern=1', '_self');
  }
  function Odbyt()
  {
    window.open('faktury_md.php?copern=1', '_self');
  }
  function Sklad()
  {
    window.open('sklad_md.php?copern=1', '_self');
  }
  function Majetok()
  {
    window.open('majetok_md.php?copern=1', '_self');
  }
  function Doprava()
  {
    window.open('doprava_md.php?copern=1', '_self');
  }
  function Vyroba()
  {
    window.open('vyroba_md.php?copern=1', '_self');
  }
  function Analyzy()
  {
    window.open('analyzy_md.php?copern=1', '_self');
  }

//tools
  function Searching()
  {
    window.open('../ucto/uctohladaj_md.php?copern=1&sysx=UCT', '_blank');
  }
  function Transfer()
  {
    window.open('../cis/prenos_md.php?copern=10&upozorni2011=1&upozorni2012=1&upozorni2013=1', '_blank');
  }
  function AccountChecks()
  {
    window.open('../ucto/ucto_kontrol_md.php?copern=40&drupoh=1&page=1', '_blank'); //page=1 nebude treba, otestova� bez drupoh
  }
  function Backup()
  {
    window.open('../cis/zaldat_ucto_md.php?copern=101', '_blank');
  }
  function Calculator()
  {
    window.open('../ucto/calculator_md.php?copern=5', '_blank');
  }

//vstup dat
  function OdberFa()
  {
    window.open('../faktury/vstfak_md.php?copern=1&drupoh=1001&page=1&pocstav=0', '_blank');
  }
  function DodavFa()
  {
    window.open('../faktury/vstfak_md.php?copern=1&drupoh=1002&page=1&pocstav=0', '_blank');
  }
  function PrijemPokl()
  {
    window.open('../ucto/vstpok_md.php?copern=1&drupoh=1&page=1&sysx=UCT', '_blank');
  }
  function VydavPokl()
  {
    window.open('../ucto/vstpok_md.php?copern=1&drupoh=2&page=1&sysx=UCT', '_blank');
  }
  function BankVyp()
  {
    window.open('../ucto/vstdok_md.php?copern=1&drupoh=4&page=1&sysx=UCT', '_blank');
  }
  function VseoDokl()
  {
    window.open('../ucto/vstdok_md.php?copern=1&drupoh=5&page=1&sysx=UCT', '_blank');
  }
//data podsystemy
  function PodsystemData()
  {
    window.open('../ucto/podsys_md.php?copern=1&sysx=UCT', '_blank');
  }
  function MzdyData()
  {
    window.open('../ucto/podsys_md.php?copern=308&drupoh=1&page=1', '_blank'); /*dopyt, page=1 nebude ma� v�znam, preto�e tam nie je str�nkovanie*/
  }
  function MajetokData()
  {
    window.open('../ucto/podsys_md.php?copern=308&drupoh=2&page=1', '_blank'); /*dopyt, page=1 nebude ma� v�znam, preto�e tam nie je str�nkovanie*/
  }
  function SkladData()
  {
    window.open('../ucto/podsys_md.php?copern=308&drupoh=3&page=1', '_blank'); /*dopyt, page=1 nebude ma� v�znam, preto�e tam nie je str�nkovanie*/
  }
//saldokonto
  function Saldo()
  {
    window.open('../ucto/saldo_md.php?copern=1&sysx=UCT&typhtml=0', '_blank');
  }
  function Upomienky()
  {
    window.open('../ucto/saldo_md.php?copern=1&sysx=UCT&typhtml=1&cinnost=1', '_blank');
  }
  function Zapocty()
  {
    window.open('../ucto/saldo_md.php?copern=1&sysx=UCT&typhtml=1&cinnost=8', '_blank');
  }
  function Faktoring()
  {
    window.open('../ucto/saldo_md.php?copern=1&sysx=UCT&typhtml=1&cinnost=6', '_blank');
  }
  function Prikazy()
  {
    window.open('../ucto/prikazy_md.php?copern=1&page=1&sysx=UCT', '_blank');
  }
//vystupy
  function UctZostavy()
  {
    window.open('../ucto/uctozos_md.php?copern=1&sysx=UCT', '_blank');
  }
  function Obratovka()
  {
    window.open('../ucto/uobrat_md.php?copern=10&drupoh=1&page=1&typ=HTML', '_blank'); /*dopyt, page=1 zbyto�n�, bu� copern alebo typ pou�i� a drupoh preveri�*/
  }
  function Vysledovka()
  {
    window.open('../ucto/vys_mala_md.php?copern=10&h_obdp=1&h_obdk=1&drupoh=1&page=1&typ=PDF&vyb_ume=1.2016&obdx=1', '_blank');
  }
  function StatVykazy()
  {
    window.open('../ucto/statzos_md.php?copern=1&sysx=UCT', '_blank');
  }
  function DanForms()
  {
    window.open('../ucto/danzos_md.php?copern=1&sysx=UCT', '_blank');
  }
//dph
  function Dph()
  {
    window.open('../ucto/dph_md.php?copern=1', '_blank');
  }
//mena
  function Mena()
  {
    window.open('../ucto/mena_md.php?copern=1&drupoh=1&sysx=UCT', '_blank');
  }
//nastavenia
  function PrmUcto()
  {
    window.open('../cis/prmpodsys_md.php?copern=91', '_blank');
  }
  function UctOsn()
  {
    window.open('../ucto/uctosn_md.php?copern=1&page=1', '_blank');
  }
  function DphKody()
  {
    window.open('../ucto/drudan_md.php?copern=1&page=1', '_blank');
  }
  function OdberUcty()
  {
    window.open('../faktury/dodb_md.php?copern=1&page=1', '_blank');
  }
  function DodavUcty()
  {
    window.open('../faktury/ddod_md.php?copern=1&page=1', '_blank');
  }
  function PoklUcty()
  {
    window.open('../ucto/dpok_md.php?copern=1&page=1', '_blank');
  }
  function BankaUcty()
  {
    window.open('../ucto/dban_md.php?copern=1&page=1', '_blank');
  }
//ciselniky
  function FirmaUdaje()
  {
    window.open('../cis/ufir_md.php?copern=1', '_blank');
  }
  function CisIco()
  {
    window.open('../cis/cico_md.php?copern=1&page=1', '_blank');
  }
  function Strediska()
  {
    window.open('../cis/cstr_md.php?copern=1&page=1', '_blank');
  }
  function Zakazky()
  {
    window.open('../cis/czak_md.php?copern=1&page=1', '_blank');
  }
  function Skupiny()
  {
    window.open('../cis/csku_md.php?copern=1&page=1', '_blank');
  }
  function Stavby()
  {
    window.open('../cis/csta_md.php?copern=1&page=1', '_blank');
  }



























//ekorobot
  function showRobotMenu()
  {
    robotmenu.style.display='block';
  }
  function hideRobotMenu()
  {
    robotmenu.style.display='none';
  }





  function NacitajKurzDnes()
  {
   window.open('../cis/stiahni_ecb.php?copern=1010', '_blank');
  }
  function NacitajKurz90()
  {
   window.open('../cis/stiahni_ecb.php?copern=1010&dni90=1', '_blank');
  }

<?php
$rokdph=2014;
if ( $vyb_rok <= 2013 ) { $rokdph=2013; }
if ( $vyb_rok <= 2012 ) { $rokdph=2012; }
?>
  function KontrolaDph() //dopyt, bude presunute vyssie
  {
   window.open('../ucto/prizdph<?php echo $rokdph; ?>.php?copern=40&drupoh=1&page=1&chyby=1', '_blank'); //page=1 nebude treba, otestova� bez drupoh
  }
  function TlacDph()
  {
   window.open('../ucto/prizdph<?php echo $rokdph; ?>.php?copern=10&drupoh=1&page=1fir_uctx01=0&h_drp=1&h_dap=&h_arch=0', '_blank'); //page=1 nebude treba, otestova� bez drupoh
  }

  function KurzListok() //dopyt, mo�no zru��m
  {
   window.open('../ucto/kurzy.php?copern=1&page=1', '_blank');
  }















</script>
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>