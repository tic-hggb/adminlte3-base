<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../class/classBloqueHora.php");
include("../src/fn.php");

if (extract($_POST)):
	$db = new myDBC();
	$ag = new Agenda();
	$bh = new BloqueHora();
	$cur_period = $year . '-' . $period . '-00';

	switch ($period):
		case '01':
			$prev_per = '10';
			$year--;
			break;
		case '04':
			$prev_per = '01';
			break;
		case '07':
			$prev_per = '04';
			break;
		case '10':
			$prev_per = '07';
			break;
		default:
			break;
	endswitch;

	$a = $ag->getEventsByPersonEsp($per, $esp, $est, $year, $prev_per, $db);
	$array = [];
	$arrayPend = [];

	foreach ($a as $k => $v):
		$check = $bh->getIsFilled($cur_period, $v->age_dia, $v->box_id, $v->age_hora_ini, $v->age_hora_ter, $db);

		$tmp = explode('-', $cur_period);
		$fecha = getFirstDay($v->age_dia, $tmp[1], $tmp[0]);

		$eCupos = $eTipoCupos = $eTextCupos = '';
		$ag_c = $ag->getCupos($v->age_id);
		foreach ($ag_c as $kc => $kv):
			$eCupos .= $kv->acu_numero . ',';
			$eTipoCupos .= $kv->tcu_id . ',';
			$eTextCupos .= $kv->tcu_descripcion . ',';
		endforeach;

		$eCupos = substr($eCupos, 0, -1);
		$eTextCupos = substr($eTextCupos, 0, -1);
		$eTipoCupos = substr($eTipoCupos, 0, -1);

		if (!$check):
			$array[] = array(
				'actId' => $v->act_id,
				'title' => $v->act_nombre,
				'box' => $v->box_id,
				'boxText' => $v->box_numero,
				'cupos' => $eCupos,
				'tipoCupos' => $eTipoCupos,
				'tipoCuposText' => $eTextCupos,
				'cuposObs' => $v->age_cuposObs,
				'espec' => $v->esp_id,
				'especialidad' => $v->esp_nombre,
				'espSin' => $v->sesp_id,
				'espSinText' => $v->sesp_nombre,
				'subespSin' => $v->ssub_id,
				'subespSinText' => $v->ssub_nombre,
				'pisoText' => $v->lugar_nombre,
				'start' => $fecha . ' ' . $v->age_hora_ini,
				'end' => $fecha . ' ' . $v->age_hora_ter,
				'day' => $v->age_dia,
				'multi' => $v->act_multi,
				'cxc' => $v->act_cxc,
				'editable' => false,
				'backgroundColor' => '#008d4c');
		else:
			$arrayPend[] = array(
				'title' => $v->act_nombre,
				'boxText' => $v->box_numero,
				'cupos' => $eCupos,
				'especialidad' => $v->esp_nombre,
				'espSin' => $v->sesp_id,
				'espSinText' => $v->sesp_nombre,
				'subespSin' => $v->ssub_id,
				'subespSinText' => $v->ssub_nombre,
				'pisoText' => $v->lugar_nombre,
				'start' => $fecha . ' ' . $v->age_hora_ini,
				'end' => $fecha . ' ' . $v->age_hora_ter,
				'pendiente' => true,
				'editable' => false,
				'backgroundColor' => '#eee',
				'borderColor' => '#999',
				'textColor' => 'red');
		endif;
	endforeach;

	$data['done'] = $array;
	$data['pend'] = $arrayPend;

	echo json_encode($data);
endif;