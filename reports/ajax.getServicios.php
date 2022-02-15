<?php

include ("../class/classMyDBC.php");
include ("../class/classServicio.php");

if (extract($_POST)):
    $ser = new Servicio();
    $list_s = $ser->getByCR($cr);
    
    echo json_encode($list_s);
endif;