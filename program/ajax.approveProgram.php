<?php

include("../class/classMyDBC.php");
include("../class/classDistribucionProg.php");
include("../src/fn.php");

if (extract($_POST)):
    $db = new myDBC();
    $dp = new DistribucionProg();

    try {
        $db->autoCommit(FALSE);
        
        $ins = $dp->setApproved($id, $db);

        if (!$ins['estado']):
            throw new Exception('Error al aprobar la programación. ' . $ins['msg']);
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
