<?php

include ("../class/classMyDBC.php");
include ("../class/classEspecialidad.php");

if (extract($_POST)):
    $esp = new Especialidad();
    $list_e = $esp->getByServicio($serv);
    
    echo json_encode($list_e);
endif;