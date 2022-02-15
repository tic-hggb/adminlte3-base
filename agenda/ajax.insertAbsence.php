<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../class/classBloqueHora.php");
include("../src/fn.php");

if (extract($_POST)):
	$db = new myDBC();
	$a = new Agenda();
	$bh = new BloqueHora();
	$idatei = setDateBD($idatei);
	$idatet = setDateBD($idatet);

	try {
		$db->autoCommit(FALSE);
		$boxes = [];

		$tmp = explode('-', $idatei);
		$month = $tmp[1];

		if ($month >= 1 and $month < 4):
			$period = '01';
		elseif ($month >= 4 and $month < 7):
			$period = '04';
		elseif ($month >= 7 and $month < 10):
			$period = '07';
		else:
			$period = '10';
		endif;

		$q_a = $a->getEventsByPerson($iperid, $_SESSION['prm_estid'], $tmp[0], $period, $db);

		$tmp = [];
		foreach ($q_a as $i => $v):
			$tmp[] = $v->box_id;
		endforeach;
		$boxes = array_unique($tmp);

		if (count($boxes) == 0) throw new Exception('El médico seleccionado no tiene agenda asociada.<br>Por favor, ingrese la agenda correspondiente al período o seleccione otro médico.');

		$days = workingDaysBetweenDates($idatei, $idatet);
		$uniq_id = substr(base64_encode(mt_rand()), 0, 16);

		foreach ($days as $d):
			foreach ($boxes as $i):
				$ins_bh = $bh->set($iperid, $_SESSION['prm_userid'], null, null, $i, $imotivo, $idestino, $d, $h_ini, $h_fin, $iobs, null, false, $uniq_id, $db);

				if (!$ins_bh['estado']):
					throw new Exception('Error al guardar los datos de la ausencia. ' . $ins_bh['msg']);
				endif;
			endforeach;
		endforeach;

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