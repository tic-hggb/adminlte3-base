<?php

include ("../../class/classMyDBC.php");
include ("../../class/classGroup.php");

if (extract($_POST)):
    $db = new myDBC();
    $group = new Group();
    
    try {
        $db->autoCommit(FALSE);
        $ins = $group->set($iname, $iprofile, $db);

        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos de grupo. ' . $ins['msg']);
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