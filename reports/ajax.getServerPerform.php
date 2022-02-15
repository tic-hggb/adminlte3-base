<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classParametro.php");
include("../class/classPersona.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../class/classAtencion.php");
include("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

$db = new myDBC();
$par = new Parametro();
$pe = new Persona();
$d = new DistribucionProg();
$dh = new DistHorasProg();
$at = new Atencion();

$data = [];
$est = (!$_admin) ? $_SESSION['prm_estid'] : $_POST['iestab'];

$planta = $_POST['iplanta'];
$fecha = $_POST['idate'];
$cr = (isset($_POST['icr'])) ? $_POST['icr'] : '';
$serv = (isset($_POST['iserv'])) ? $_POST['iserv'] : '';
$comite = false;

$t_par = $par->get($fecha);
$WEEKS = $t_par->par_semanas;

$personal = $d->getReprogsByFilters($fecha, $est, $planta, $cr, $serv, '');

foreach ($personal as $persona => $det):
	$pObj = $det;
	$prTotal = 0;
	$progTotal = 0;

	for ($i = 0; $i < 12; $i++):
		$m = $i + 1;
		$m = ($i < 9) ? '0' . $m : $m;
		$date = $fecha . "-" . $m . "-01";
		$tmp = date('Y-m-d', strtotime('+1 month', strtotime($date)));

		$prod = $at->getByPerComite($det->per_id, $date, $comite, $est, $db);

		$pro_t = 0;
		foreach ($prod as $kp => $vp):
			$pro_t += $vp->at_cantidad;
		endforeach;

		$month = "m_" . $m;
		$pObj->$month = $pro_t;
		$prTotal += $pro_t;
	endfor;

	$vacaciones = $permisos = $congreso = $c_c = 0;
	$primer_per = $segundo_per = $tercer_per = $cuarto_per = false;

	// Progr Ene-Dic
	$dist_e_d = $d->getByPerDateEsp($det->per_id, $est, $det->per_espid, $fecha . '/01/01', $fecha . '/12/31');
	if ($dist_e_d->disp_id != null):
		$primer_per = true;
		$vacaciones = $dist_e_d->disp_vacaciones;
		$permisos = $dist_e_d->disp_permisos;
		$congreso = $dist_e_d->disp_congreso;

		$dias = $vacaciones + $permisos + $congreso;
		$tmp = round($dias / 5);
		$total = ($WEEKS - $tmp);
		$total_pp = $total * 0.24;

		$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
		$c_c += $c_c_pp;
	endif;

	// Progr Abr-Dic
	$dist_m_d = $d->getByPerDateEsp($det->per_id, $est, $det->per_espid, $fecha . '/04/01', $fecha . '/12/31');
	if ($dist_m_d->disp_id != null):
		$segundo_per = true;
		$vacaciones = $dist_m_d->disp_vacaciones;
		$permisos = $dist_m_d->disp_permisos;
		$congreso = $dist_m_d->disp_congreso;

		$dias = $vacaciones + $permisos + $congreso;
		$tmp = round($dias / 5);
		$total = ($WEEKS - $tmp);
		$total_sp = $total * 0.26;

		$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
		$c_c += $c_c_sp;
	// no tiene Abr-Dic se rellena con Ene-Mar * 0.26 si lo tiene
	elseif ($primer_per):
		$total_pp = $total * 0.26;

		$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
		$c_c += $c_c_pp;
	endif;

	// Progr Jun-Dic
	$dist_j_d = $d->getByPerDateEsp($det->per_id, $est, $det->per_espid, $fecha . '/07/01', $fecha . '/12/31');
	if ($dist_j_d->disp_id != null):
		$tercer_per = true;
		$vacaciones = $dist_j_d->disp_vacaciones;
		$permisos = $dist_j_d->disp_permisos;
		$congreso = $dist_j_d->disp_congreso;

		$dias = $vacaciones + $permisos + $congreso;
		$tmp = round($dias / 5);
		$total = ($WEEKS - $tmp);
		$total_tp = $total * 0.26;

		$c_c_tp = $dh->getByConsCont($dist_j_d->disp_id) * $total_tp;
		$c_c += $c_c_tp;
	// no tiene Jul-Dic
	else:
		// si tiene Abr-Dic se rellena
		if ($segundo_per):
			$total_sp = $total * 0.26;

			$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
			$c_c += $c_c_sp;
		// no tiene Abr-Dic se rellena con Ene-Mar * 0.26 si lo tiene
		elseif ($primer_per):
			$total_pp = $total * 0.26;

			$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
			$c_c += $c_c_pp;
		endif;
	endif;

	// Progr Sep-Dic
	$dist_s_d = $d->getByPerDateEsp($det->per_id, $est, $det->per_espid, $fecha . '/10/01', $fecha . '/12/31');
	if ($dist_s_d->disp_id != ''):
		$cuarto_per = true;
		$vacaciones = $dist_s_d->disp_vacaciones;
		$permisos = $dist_s_d->disp_permisos;
		$congreso = $dist_s_d->disp_congreso;

		$dias = $vacaciones + $permisos + $congreso;
		$tmp = round($dias / 5);
		$total = ($WEEKS - $tmp);
		$total_cp = $total * 0.24;

		$c_c_cp = $dh->getByConsCont($dist_s_d->disp_id) * $total_cp;
		$c_c += $c_c_cp;
	// no tiene Sep-Dic
	else:
		// si tiene Jul-Dic se rellena con Jul-Dic * 0.24
		if ($tercer_per):
			$total_tp = $total * 0.24;

			$c_c_tp = $dh->getByConsCont($dist_j_d->disp_id) * $total_tp;
			$c_c += $c_c_tp;
		// no tiene Jul-Dic se rellena con Abr-Dic * 0.24 si lo tiene
		elseif ($segundo_per):
			$total_sp = $total * 0.24;

			$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
			$c_c += $c_c_sp;
		// no tiene Abr-Dic se rellena con Ene-Mar si lo tiene
		elseif ($primer_per):
			$total_pp = $total * 0.24;

			$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
			$c_c += $c_c_pp;
		endif;
	endif;
	$progTotal = round($c_c);

	$pObj->per_prodtotal = $prTotal;
	$pObj->per_progtotal = $progTotal;

	$pObj->per_cumpl = ($progTotal > 0) ? round($prTotal / $progTotal * 100) : 100;

	if ($progTotal > 0 or $prTotal > 0):
		$data[] = $pObj;
	endif;
endforeach;

echo json_encode($data);
