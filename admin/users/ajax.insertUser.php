<?php

include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $db = new myDBC();
    $user = new User();
    
    try {
        $db->autoCommit(FALSE);
        $ins = $user->set($iname, $ilastnamep, $ilastnamem, $iemail, $iusername, $ipassword, $db);

        if ($ins['estado'] == false):
            throw new Exception('Error al guardar los datos de usuario. ' . $ins['msg']);
        endif;

		$grp = $user->setGroup($ins['msg'], $iusergroups, $db);

		if (!$grp['estado']):
			throw new Exception('Error al crear grupos del usuario. ' . $grp['msg']);
		endif;
        
        if (!empty($_FILES)):
			$targetFolder =  BASEFOLDER . 'dist/img/users/';
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

            foreach ($_FILES as $aux => $file):
                $tempFile = $file['tmp_name'][0];
                $fileName = removeAccents(str_replace(' ', '_', $file['name'][0]));
                $targetFile = rtrim($targetPath,'/') . '/' . $ins['msg'] . '_' . $filename;
                move_uploaded_file($tempFile, $targetFile);
                $pic_route = 'users/' . $ins['msg'] . '_' . $filename;
            endforeach;
        else:
            $pic_route = 'users/no-photo.png';
        endif;

        $ins_p = $user->setPicture($ins['msg'], $pic_route, $db);

        if (!$ins_p['estado']):
            throw new Exception('Error al guardar la imagen. ' . $ins_p['msg']);
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
