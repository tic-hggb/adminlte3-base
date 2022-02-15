<?php

include("../class/classMyDBC.php");
include("../class/classBloqueHora.php");
include("../src/fn.php");

if (extract($_POST)):
	$b = new BloqueHora();
	$ini = setDateBD($f_ini);
	$ter = setDateBD($f_ter);

	echo json_encode($b->getAbsenceByDate($ini, $ter, $per));
endif;