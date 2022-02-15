<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classBloqueHora.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

if (extract($_POST)):
	$bh = new BloqueHora();
	$est = (!$_admin) ? $_SESSION['prm_estid'] : $iestab;

	echo json_encode($bh->getAbsencesByFilters($iyear, $iperiodo, $est, $iplanta, $icr, $iserv));
endif;