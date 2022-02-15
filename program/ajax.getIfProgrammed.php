<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classDistribucionProg.php");
include ("../src/fn.php");

if (extract($_POST)):
    $date_ini = setDateBD('01/'.$d_ini);
    $date_ter = setDateBD('31/'.$d_ter);
    
    $di = new DistribucionProg();
    $num_d = $di->getCountByPerDate($per, $_SESSION['prm_estid'], $date_ini, $date_ter, $esp);
    
    echo $num_d;
endif;