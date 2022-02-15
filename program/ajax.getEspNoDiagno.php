<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classEspecialidad.php");
include ("../src/fn.php");

if (extract($_POST)):
    $date = setDateBD('01/01/'.$idate);
    
    $esp = new Especialidad();
    $list_e = $esp->getNoDiagnose($date, $_SESSION['prm_estid'], $iserv);
    
    echo json_encode($list_e);
endif;