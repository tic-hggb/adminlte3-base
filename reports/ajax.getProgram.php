<?php

include ("../class/classMyDBC.php");
include ("../class/classDistribucionProg.php");
include ("../src/fn.php");

if (extract($_POST)):
    $dh = new DistribucionProg();
    
    echo json_encode($dh->getDetail($id));
endif;
