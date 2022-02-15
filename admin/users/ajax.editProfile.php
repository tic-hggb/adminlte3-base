<?php

session_start();
include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $db = new myDBC();
    $user = new User();
    $_default = false;
    
    try {
        $db->autoCommit(FALSE);
        $ins = $user->modProfile($id, $iname, $ilastnamep, $ilastnamem, $iemail, $db);
    
        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos de usuario.');
        endif;
        
        $_SESSION['prm_userfname'] = $iname;
        $_SESSION['prm_userlnamep'] = $ilastnamep;
        $_SESSION['prm_userlnamem'] = $ilastnamem;
        $_SESSION['prm_useremail'] = $iemail;

        if (!empty($_FILES)):
			$targetFolder =  BASEFOLDER . 'dist/img/users/';
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

            $u = $user->get($id);
            
            if ($u->us_pic == 'users/no-photo.png'):
                $_default = true;
            endif;

			$img_old = $_SERVER['DOCUMENT_ROOT'] . BASEFOLDER . 'dist/img/' . $u->us_pic;

            if (!is_readable($img_old)):
                throw new Exception('El archivo solicitado no existe.');
            endif;
            
            if (!$_default):
                if (!unlink($img_old)):
                    throw new Exception('Error al eliminar la imagen antigua.');
                endif;
            endif;

            foreach ($_FILES as $aux => $file):
                $tempFile = $file['tmp_name'][0];
                $targetFile = rtrim($targetPath,'/') . '/' . $id . '_' . $file['name'][0];
                move_uploaded_file($tempFile, $targetFile);
            endforeach;

            $pic_route = 'users/' . $id . '_' . $file['name'][0];

            $ins = $user->setPicture($id, $pic_route, $db);

            if (!$ins):
                throw new Exception('Error al guardar la imagen.');
            endif;

            $_SESSION['prm_userpic'] = 'users/' . $id . '_' . $file['name'][0];
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