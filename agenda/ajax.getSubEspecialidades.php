<?php

include("../class/classMyDBC.php");
include("../class/classSubEspecialidadSin.php");

if (extract($_POST)):
	$e = new SubEspecialidadSin();
	echo json_encode($e->getByEspecialidad($esp));
endif;