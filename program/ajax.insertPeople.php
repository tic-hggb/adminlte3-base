<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersona.php");
include("../class/classPersonaEstablecimiento.php");

if (extract($_POST)):
	$db = new myDBC();
	$per = new Persona();
	$pes = new PersonaEstablecimiento();
	$_new = false;

	if ($iid == ''):
		$_new = true;
	endif;

	try {
		$db->autoCommit(FALSE);

		if ($_new):
			$ins = $per->set(strtoupper($irut), strtoupper($iname), $iprofesion, strtoupper($iespec), $db);

			if (!$ins['estado']):
				throw new Exception('Error al guardar los datos de la persona. ' . $ins['msg']);
			endif;

			$iid = $ins['msg'];
		else:
			$ins = $per->mod($iid, strtoupper($iname), $iprofesion, strtoupper($iespec), $db);

			if (!$ins['estado']):
				throw new Exception('Error al modificar los datos de la persona. ' . $ins['msg']);
			endif;
		endif;

		$ins_es = $pes->set($iid, $_SESSION['prm_estid'], $itcontrato, $icorr, $ihoras, $db);

		if (!$ins_es['estado']):
			throw new Exception('Error al guardar los datos del contrato. ' . $ins_es['msg']);
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