<?php

include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $db = new myDBC();
    $us = new User();
    
    try {
        $db->autoCommit(FALSE);
        
        $pmod = $us->modPass($uid, $inewpass, $db);
        
        if (!$pmod['estado']):
            throw new Exception('Error al modificar la contraseña. ' . $pmod['msg']);
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