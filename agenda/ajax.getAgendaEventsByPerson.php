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

		$cc = ($v->act_cxc) ? 1 : 0;
		$render = ($v->esp_id == $esp) ? '' : 'background';
		$bgColor = ($v->esp_id == $esp) ? '#0073b7' : 'grey';
		$actId = ($v->esp_id == $esp) ? $v->act_id : '';

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

		$array[] = array(
			'title' => $v->act_nombre,
			'actId' => $actId,
			'box' => $v->box_id,
			'boxText' => $v->box_numero,
			'cc' => $cc,
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
			'rendering' => $render,
			'backgroundColor' => $bgColor,
			'eventSource' => 'ajax');
	endforeach;

	echo json_encode($array);
endif;