<?php

include ("../../class/classMyDBC.php");
include ("../../class/classGroup.php");

if (extract($_POST)):
    $db = new myDBC();
    $group = new Group();
    
    try {
        $db->autoCommit(FALSE);
        
        $gmod = $group->mod($id, $iname, $iprofile, $db);
        
        if (!$gmod['estado']):
            throw new Exception('Error al modificar el grupo. ' . $gmod['msg']);
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