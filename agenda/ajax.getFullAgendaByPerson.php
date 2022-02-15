<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../src/fn.php");

if (extract($_POST)):
	$ag = new Agenda();

	$a = $ag->getEventsByPerson($per, $est, $year, $period);
	$array = [];

	foreach ($a as $k => $v):
		$tmp = explode('-', $v->age_periodo);
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
		$bgColor = ($v->act_multi == true) ? '#001f3f' : '#008d4c';

		$array[] = array(
			'title' => $v->act_nombre,
			'boxText' => $v->box_numero,
			'cupos' => $eCupos,
			'tipoCupos' => $eTipoCupos,
			'tipoCuposText' => $eTextCupos,
			'especialidad' => $v->esp_nombre,
			'espSin' => $v->sesp_id,
			'espSinText' => $v->sesp_nombre,
			'subespSin' => $v->ssub_id,
			'subespSinText' => $v->ssub_nombre,
			'pisoText' => $v->lugar_nombre,
			'start' => $fecha . ' ' . $v->age_hora_ini,
			'end' => $fecha . ' ' . $v->age_hora_ter,
			'editable' => false,
			'backgroundColor' => $bgColor);
	endforeach;

	echo json_encode($array);
endif;