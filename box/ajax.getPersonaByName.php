<?php

include("../class/classMyDBC.php");
include("../class/classPersona.php");

if (extract($_POST)):
	$per = new Persona();
	$str = explode(': ', utf8_decode($name));
	echo json_encode($per->getByName($str[0]));
endif;