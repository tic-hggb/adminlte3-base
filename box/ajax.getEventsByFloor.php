<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classOcupacionBox.php");
include("../class/classAgenda.php");
include("../class/classBox.php");
include("../src/fn.php");
$_admin = false;

if (extract($_POST)):
	$db = new myDBC();
	$o = new OcupacionBox();
	$b = new Box();
	$a = new Agenda();
	$date_i = setDateBD($date);
	$date_t = new DateTime($date_i);
	$date_t = $date_t->modify('Next Friday')->format('Y-m-d');

	$box = $b->getByFloor($floor, $type);

	$mes_eval = getDateOnlyMonthToForm($date_i);
	$anio_eval = getDateYearToForm($date_i);

	$mes = '';
	if ($mes_eval == '01' || $mes_eval == '02' || $mes_eval == '03'):
		$mes = '01';
	elseif ($mes_eval == '04' || $mes_eval == '05' || $mes_eval == '06'):
		$mes = '04';
	elseif ($mes_eval == '07' || $mes_eval == '08' || $mes_eval == '09'):
		$mes = '07';
	elseif ($mes_eval == '10' || $mes_eval == '11' || $mes_eval == '12'):
		$mes = '10';
	endif;

	$response = [];

	foreach ($box as $bop => $z):
		$ob = $o->getOccupationByFecha($z->box_id, $date_i, $date_t, $db);
		$ag = $a->getOccupationByLastPeriodo($z->box_id, $anio_eval, $mes, $db);

		/* Agrega ausencias no programadas con marca */
		if (isset($ob['ausencia'])):
			foreach ($ob['ausencia'] as $ia => $va):
				if ($va->programado == 0):
					$start = $va->fecha . ' ' . $va->hora_ini;
					$end = $va->fecha . ' ' . $va->hora_ter;

					$response[] = array(
						'resourceId' => $va->box_id,
						'tipo' => 1,
						'title' => $va->per_nombres,
						'boxText' => $va->box_numero,
						'especialista' => $va->per_nombres,
						'motivo_ausencia' => $va->mau_descripcion,
						'start' => $start,
						'end' => $end,
						'editable' => false,
						'backgroundColor' => '#ffe3dc',
						'borderColor' => '#ff8484',
						'textColor' => '#ff8484');
				endif;
			endforeach;
		endif;

		/* Agrega actividades no programadas */
		if (isset($ob['presencia'])):
			foreach ($ob['presencia'] as $ia => $va):
				$start = $va->fecha . ' ' . $va->hora_ini;
				$end = $va->fecha . ' ' . $va->hora_ter;

				$eCupos = $eTipoCupos = $eTextCupos = '';
				$ag_c = $o->getCupos($va->bh_id);
				foreach ($ag_c as $kc => $kv):
					$eCupos .= $kv->bhcu_numero . ',';
					$eTipoCupos .= $kv->tcu_id . ',';
					$eTextCupos .= $kv->tcu_descripcion . ',';
				endforeach;

				$eCupos = substr($eCupos, 0, -1);
				$eTextCupos = substr($eTextCupos, 0, -1);
				$eTipoCupos = substr($eTipoCupos, 0, -1);

				$response[] = array(
					'resourceId' => $va->box_id,
					'tipo' => 2,
					'title' => $va->act_nombre,
					'boxText' => $va->box_numero,
					'especialista' => $va->per_nombres,
					'cupos' => $eCupos,
					'tipoCupos' => $eTipoCupos,
					'tipoCuposText' => $eTextCupos,
					'espSin' => $va->sesp_id,
					'espSinText' => $va->sesp_nombre,
					'subespSin' => $va->ssub_id,
					'subespSinText' => $va->ssub_nombre,
					'pisoText' => $va->lugar_nombre,
					'start' => $start,
					'end' => $end,
					'editable' => false,
					'backgroundColor' => '#6ba5c1');
			endforeach;
		endif;

		/* Agrega actividades por agenda */
		foreach ($ag as $k => $v):
			$days = workingDaysBetweenDates($date_i, $date_t);

			foreach ($days as $d):
				$check = false;

				/* Excluye ausencias por bloqueos programados */
				if (isset($ob['ausencia'])):
					foreach ($ob['ausencia'] as $ia => $va):
						if ($va->fecha == $d and $va->programado == 1 and $v->per_id == $va->per_id) $check = true;
					endforeach;
				endif;

				$day = date('w', strtotime($d)) - 1;
				$start = $d . ' ' . $v->age_hora_ini;
				$end = $d . ' ' . $v->age_hora_ter;

				$eCupos = $eTipoCupos = $eTextCupos = '';
				$ag_c = $a->getCupos($v->age_id);
				foreach ($ag_c as $kc => $kv):
					$eCupos .= $kv->acu_numero . ',';
					$eTipoCupos .= $kv->tcu_id . ',';
					$eTextCupos .= $kv->tcu_descripcion . ',';
				endforeach;

				$eCupos = substr($eCupos, 0, -1);
				$eTextCupos = substr($eTextCupos, 0, -1);
				$eTipoCupos = substr($eTipoCupos, 0, -1);

				/* Si no es bloqueo y coincide el dia de la semana */
				if (!$check and ($v->age_dia == $day))
					$response[] = array(
						'resourceId' => $z->box_id,
						'tipo' => 0,
						'title' => $v->act_nombre,
						'boxText' => $v->box_numero,
						'especialista' => $v->per_nombres,
						'cupos' => $eCupos,
						'tipoCupos' => $eTipoCupos,
						'tipoCuposText' => $eTextCupos,
						'especialidad' => $v->esp_nombre,
						'espSin' => $v->sesp_id,
						'espSinText' => $v->sesp_nombre,
						'subespSin' => $v->ssub_id,
						'subespSinText' => $v->ssub_nombre,
						'pisoText' => $v->lugar_nombre,
						'start' => $start,
						'end' => $end,
						'editable' => false,
						'backgroundColor' => '#008d4c');
			endforeach;
		endforeach;
	endforeach;

	echo json_encode($response);
endif;