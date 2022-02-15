<?php

session_start();
include '../class/classMyDBC.php';
include '../class/classAtencion.php';
include '../src/fn.php';

if (extract($_POST)):
	$_admin = false;
	if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']) $_admin = true;

	$est = ($_admin) ? null : $_SESSION['prm_estid'];
	$year = date('Y');

	$at = new Atencion();
	$arr = $at->getTotalByEstYear($est, $planta, $year);

	echo json_encode($arr);
endif;
