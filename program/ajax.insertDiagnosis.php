<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classDiagnostico.php");
include ("../src/fn.php");

if (extract($_POST)):
    $db = new myDBC();
    $dia = new Diagnostico();
    $date = setDateBD('01/01/'.$idate);
    
    try {
        $db->autoCommit(FALSE);
        $ins = $dia->set($_SESSION['prm_estid'], $iesp, $iserv, $_SESSION['prm_userid'], $date, $itat, $iges, $itiq, $igesiq, $itaa, $itac, $itpro, $db);

        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos del diagnóstico. ' . $ins['msg']);
        endif;

        $db->Commit();
        $db->autoCommit(TRUE);
        $response = array('type' => true, 'msg' => 'OK');
        echo json_encode($response);
        
    } catch (Exception $e) {
        $db->Rollback();
        $db->autoCommit(TRUE);
        $response = array('type' => false, 'msg' => $e->getMessage());
        echo json_encode($response);
    }
endif;