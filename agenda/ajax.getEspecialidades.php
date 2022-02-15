<?php

include("../class/classMyDBC.php");
include("../class/classEspecialidadSin.php");

if (extract($_POST)):
	$e = new EspecialidadSin();
	echo json_encode($e->getByAgrupacion($agr));
endif;