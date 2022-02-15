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
	$idate = setDateBD('01/' . $iperiodo . '/' . $iyear);
	$idate_t = setDateBD('31/12/' . $iyear);
    $est = (!$_admin) ? $_SESSION['prm_estid'] : $iestab;
    
    echo json_encode($p->getJustifyMeds($est, $idate, $idate_t, $iplanta));
endif;