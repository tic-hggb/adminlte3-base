<?php

include ("../class/classMyDBC.php");
include ("../class/classDistHorasProg.php");
include ("../src/fn.php");

if (extract($_POST)):
    $dh = new DistHorasProg();
    $date_ini = setDateBD('01/01/' . $date);
    $date_ter = setDateBD('31/12/' . $date);
    
    echo $dh->getNumByPerDate($id, $date_ini, $date_ter);
endif;