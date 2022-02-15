<?php

include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $db = new myDBC();
    $user = new User();

    try {
        $db->autoCommit(FALSE);  
        $us = $user->del($id, $db);
        
        if (!$us['estado']):
            throw new Exception('Error al eliminar el usuario. ' . $us['msg']);
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