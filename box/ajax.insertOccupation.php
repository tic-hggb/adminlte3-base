<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classBloqueHora.php");
include("../class/classUser.php");
include("../src/fn.php");

if (extract($_POST)):
	$db = new myDBC();
	$bh = new BloqueHora();

	$u = new User();
	$user = $u->get($_SESSION['prm_userid']);

	try {
		$db->autoCommit(FALSE);
		$idate = setDateBD($idateas);
		$uniq_id = substr(base64_encode(mt_rand()), 0, 16);

		$ins = $bh->set($iperid, $_SESSION['prm_userid'], $ievent, $isubesp, $ibox, null, null, $idate, $h_ini, $h_fin, $iobscupos,null, false, $uniq_id, $db);

		if (!$ins['estado']):
			throw new Exception('Error al guardar los datos de la ocupación. ' . $ins['msg']);
		endif;

		foreach ($itipocupos as $kc => $kv):
			$ins_cupos = $bh->setCupos($ins['msg'], $kv, $icupos[$kc], $db);

			if (!$ins_cupos['estado']):
				throw new Exception('Error al guardar los datos de los cupos de ocupación. ' . $ins_cupos['msg']);
			endif;
		endforeach;

		$db->Commit();
		$db->autoCommit(TRUE);

		$response = array('type' => true, 'msg' => true);
		echo json_encode($response);
	} catch (Exception $e) {
		$db->Rollback();
		$db->autoCommit(TRUE);
		$response = array('type' => false, 'msg' => $e->getMessage());
		echo json_encode($response);
	}
endif;
