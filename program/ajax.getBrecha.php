<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classParametro.php");
include ("../class/classDiagnostico.php");
include ("../class/classDistribucionProg.php");
include ("../src/fn.php");

if (extract($_POST)):
    $tmp = explode('/', $d);
    $year = $tmp[1];
    $date = setDateBD('01/01/'.$year);

    $param = new Parametro();
    $t_p = $param->get($year);
    $WEEKS = $t_p->par_semanas;

    $dia = new Diagnostico();
    $data = $dia->getByEspDate($_SESSION['prm_estid'], $esp, $serv, $date);
    
    $dist = new DistribucionProg();
    $dataCC = $dist->getProgrammedCC($_SESSION['prm_estid'], $esp, $pes);
    $dataIQ = $dist->getProgrammedIQ($_SESSION['prm_estid'], $esp, $pes);
    $dataDisp = $dist->getProgrammedEsp($_SESSION['prm_estid'], $esp, $pes);
    
    $total_cc = 0;
    foreach ($dataCC as $k => $v):
        $dias = $v->vacas + $v->permisos + $v->congreso;
        $tmp = round($dias/5);
        $total = $WEEKS - $tmp;
        
        $tot_anual = $total * $v->disponibles;
        $total_cc += $tot_anual;
    endforeach;
    
    $total_iq = 0;
    foreach ($dataIQ as $k => $v):
        $dias = $v->vacas + $v->permisos + $v->congreso;
        $tmp = round($dias/5);
        $total = $WEEKS - $tmp;
        
        $tot_anual = $total * $v->disponibles;
        $total_iq += $tot_anual;
    endforeach;
    
    $total_disp = 0;
    foreach ($dataDisp as $k => $v):
        $dias = $v->vacas + $v->permisos + $v->congreso;
        $tmp = round($dias/5);
        $total = $WEEKS - $tmp;
        
        $tot_anual = $total * $v->disponibles;
        $total_disp += $tot_anual;
    endforeach;
    
    $data->total_cc = $total_cc;
    $data->total_iq = $total_iq;
    $data->total_disp = $total_disp;
    echo json_encode($data);
endif;