<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classDistribucionProg.php");
include ("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
    $_admin = true;
endif;

if (extract($_POST)):
    $d = new DistribucionProg();

	$est = (!$_admin) ? $_SESSION['prm_estid'] : $iestab;
	$idate = setDateBD('01/' . $iperiodo . '/' . $iyear);
    $idate_t = setDateBD('31/12/' . $iyear);
    
    echo json_encode($d->getByFilters($idate, $idate_t, $est, $iplanta, $icr, $iserv, $iesp));
endif;