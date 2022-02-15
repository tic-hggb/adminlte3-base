<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classPersona.php");

if (extract($_POST)):
    $per = new Persona();
    echo json_encode($per->getByRutEstLey($rut, $_SESSION['prm_estid'], $con, $corr));
endif;