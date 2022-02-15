<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classEstablecimientoLugar.php");
include("../src/fn.php");

if (extract($_POST)):
	$e = new EstablecimientoLugar();
	echo json_encode($e->getByEstab($est));
endif;