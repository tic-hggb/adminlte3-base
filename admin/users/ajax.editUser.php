<?php

session_start();
include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");
include ("../../class/classGroup.php");

if (extract($_POST)):
    $db = new myDBC();
    $user = new User();
    $group = new Group();
    $_islog = false;
    
    if (isset($iactive)):
        $iactive = 1;
    else:
        $iactive = 0;
    endif;
    
    if ($_SESSION['prm_userid'] == $id):
        $_islog = true;
    endif;
    
    try {
        $db->autoCommit(FALSE);
        $ins = $user->mod($id, $iname, $ilastnamep, $ilastnamem, $iemail, $ipassword, $iactive, $db);
    
        if (!$ins['estado']):
            throw new Exception('Error al guardar los datos de usuario.');
        endif;
        
        if ($_islog):
            $_SESSION['prm_userfname'] = $iname;
            $_SESSION['prm_userlnamep'] = $ilastnamep;
            $_SESSION['prm_userlnamem'] = $ilastnamem;
            $_SESSION['prm_useremail'] = $iemail;
        endif;

        $d_g = $user->delGroup($id, $db);

        if (!$d_g):
            throw new Exception('Error al eliminar los grupos antiguos.');
        endif;

		$grp = $user->setGroup($id, $iusergroups, $db);

		if (!$grp['estado']):
			throw new Exception('Error al crear grupos del usuario. ' . $grp['msg']);
		endif;

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