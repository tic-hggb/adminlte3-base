<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../class/classBloqueHora.php");
include("../src/fn.php");

if (extract($_POST)):
	$db = new myDBC();
	$age = new Agenda();
	$bh = new BloqueHora();

	try {
		$db->autoCommit(FALSE);
		$boxes = [];

		foreach ($events as $k => $v):
			$tmp = explode(' ', $v['ini']);
			$h_ini = $tmp[1];
			$tmp_date = explode('-', $tmp[0]);
			$periodo = $tmp_date[0] . '-' . $tmp_date[1] . '-00';
			$tmp = explode(' ', $v['fin']);
			$h_fin = $tmp[1];

			$ins = $age->set($pers, $esp, $v['actId'], $v['subespSin'], $v['box'], $_SESSION['prm_userid'], $periodo, $v['dia'], $h_ini, $h_fin, $v['cuposObs'], $db);

			if (!$ins['estado']):
				throw new Exception('Error al guardar los datos del bloque de agenda. ' . $ins['msg']);
			endif;

			$arrCupos = explode(',', $v['cupos']);
			$arrTipoCupos = explode(',', $v['tipoCupos']);

			foreach ($arrCupos as $kc => $kv):
				$ins_cupos = $age->setCupos($ins['msg'], $arrTipoCupos[$kc], $kv, $db);

				if (!$ins_cupos['estado']):
					throw new Exception('Error al guardar los datos de los cupos de agenda. ' . $ins_cupos['msg']);
				endif;
			endforeach;

			$boxes[] = $v['box'];
		endforeach;

		$boxes = array_unique($boxes);

		if (isset($blocks)):
			foreach ($blocks as $kb => $vb):
				$days = workingDaysBetweenDates($vb['f_ini'], $vb['f_ter']);
				$uniq_id = substr(base64_encode(mt_rand()), 0, 16);

				foreach ($days as $d):
					foreach ($boxes as $i):
						$ins_bh = $bh->set($pers, $_SESSION['prm_userid'], null, null, $i, $vb['motivo'], $vb['destino'], $d, '00:00:00', '23:59:59', null, $vb['obs'], true, $uniq_id, $db);

						if (!$ins_bh['estado']):
							throw new Exception('Error al guardar los datos del bloqueo de horario. ' . $ins_bh['msg']);
						endif;
					endforeach;
				endforeach;
			endforeach;
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