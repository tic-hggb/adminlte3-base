<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
	$_SESSION['prm_estid'] = 100;
endif;

if ($_POST):
	foreach ($_POST as $key => $value):
		$init = substr($key, 0, 1);

		if ($value != '' and $value != '0.00' and $init != 't'):
			$data[$key] = $value;
		endif;
	endforeach;

	$data['idesc'] = $_POST['idesc'];
	$data['iobserv'] = $_POST['iobserv'];

	$db = new myDBC();
	$di = new DistribucionProg();
	$dh = new DistHorasProg();
	$date = $_POST['idate'];
	$date_t = $_POST['idate_t'];
	$justif = $_POST['ijustif'];
	$serv = $_POST['iserv'];
	$esp = $_POST['iesp'];
	$date_ini = setDateBD('01/' . $date);

	$date_temp = setDateBD('01/' . $date_t);
	$date_ter = date("Y-m-t", strtotime($date_temp));

	try {
		$db->autoCommit(FALSE);

		if (isset($_POST['igeneral'])):
			$igeneral = 1;
		else:
			$igeneral = 0;
		endif;

		$act = $di->setLast($data['id'], $esp, $serv, $db);

		if (!$act['estado']):
			throw new Exception('Error al actualizar los datos de la programación. ' . $act['msg']);
		endif;

		$ins_d = $di->set($data['id'], $data['idesc'], $data['iobserv'], $date_ini, $date_ter, $justif, $serv, $esp, $_POST['ivacaciones'], $_POST['ipermiso'], $_POST['icongreso'], $_POST['idescanso'], $igeneral, $_SESSION['prm_userid'], $db);

		if (!$ins_d['estado']):
			throw new Exception('Error al guardar los datos de la programación. ' . $ins_d['msg']);
		endif;

		foreach ($data as $key => $value):
			$insert = false;

			$ind = explode('act', $key);

			if ($ind[0] != 'i'):
				switch ($key):
					case 'disp':
						$thor = 1;
						$rend = 0;
						$obs = '';
						$insert = true;
						break;
					case 'universidad':
						$thor = 2;
						$rend = 0;
						$obs = '';
						$insert = true;
						break;
					case 'becados':
						$thor = 3;
						$rend = 0;
						$obs = '';
						$insert = true;
						break;
					default:
						break;
				endswitch;
			else:
				$thor = $ind[1];
				$rend_i = 'ract' . $ind[1];
				$obs_i = 'oact' . $ind[1];
				$rend = (isset($data[$rend_i])) ? $data[$rend_i] : '';
				$obs = (isset($data[$obs_i])) ? $data[$obs_i] : '';
				$insert = true;
			endif;

			if ($insert):
				$ins = $dh->set($ins_d['msg'], $thor, $value, $rend, $obs, $db);

				if (!$ins['estado']):
					throw new Exception('Error al guardar los datos de la programación. ' . $ins['msg']);
				endif;
			endif;
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

