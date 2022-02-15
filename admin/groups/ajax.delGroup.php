<?php

include ("../../class/classMyDBC.php");
include ("../../class/classGroup.php");

if (extract($_POST)):
    $db = new myDBC();
    $group = new Group();
    
    try {
        $db->autoCommit(FALSE);
        $gre = $group->getIsEmpty($id, $db);
        
        if (!$gre['estado']):
            throw new Exception('Error al eliminar el grupo. ' . $gre['msg']);
        endif;
        
        if ($gre['msg'] != 'OK'):
            throw new Exception('Error al eliminar el grupo. ' . $gre['msg']);
        endif;
        
        $gr = $group->del($id, $db);

        if (!$gr['estado']):
            throw new Exception('Error al eliminar el grupo. ' . $gr['msg']);
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