<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classPersona.php");
include ("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
    $_admin = true;
endif;

if (extract($_POST)):
    $p = new Persona();
    $temp = explode('/', $idate);
    $date = $temp[1] . '-' . $temp[0] . '-01';
    $est = (!$_admin) ? $_SESSION['prm_estid'] : '';
    
    echo json_encode($p->getNPMedics($est, $date, $iplanta));
endif;
