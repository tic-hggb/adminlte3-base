<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

if (extract($_POST)):
	$a = new Agenda();

	$est = (!$_admin) ? $_SESSION['prm_estid'] : $iestab;

	echo json_encode($a->getAgendasByPeriod($iperiodo, $iyear, $est, $iplanta, $iserv, $iesp));
endif;