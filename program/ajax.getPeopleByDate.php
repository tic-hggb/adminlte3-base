<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classPersona.php");
include ("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
    $_admin = true;
endif;

if (extract($_POST)):
    $p = new Persona();

	$est = (!$_admin) ? $_SESSION['prm_estid'] : $iestab;
    $idate = setDateBD('01/' . $iperiodo . '/' . $iyear);
    $idate_tmp = setDateBD('01/12/' . $iyear);
    $idate_t = date("Y-m-t", strtotime($idate_tmp));
    
    $npr = (isset($inoprog)) ? 'on' : '';
    
    echo json_encode($p->getNPrByDate($idate, $idate_t, $iplanta, $est, $npr));
endif;