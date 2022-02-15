<?php

include("../class/classMyDBC.php");
include("../class/classActividad.php");

if (extract($_POST)):
	$act = new Actividad();
	echo json_encode($act->get($id));
endif;