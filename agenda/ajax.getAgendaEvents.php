<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classAgenda.php");
include("../src/fn.php");

if (extract($_POST)):
	$ag = new Agenda();

	$a = $ag->getOccupationByPeriodo($box, $year, $period);
	$array = [];

	foreach ($a as $k => $v):
		$tmp = explode('-', $v->age_periodo);
		$fecha = getFirstDay($v->age_dia, $tmp[1], $tmp[0]);

		$array[] = array(
			'title' => $v->act_nombre,
			'personaText' => $v->per_nombres,
			'start' => $fecha . ' ' . $v->age_hora_ini,
			'end' => $fecha . ' ' . $v->age_hora_ter,
			'actId' => $v->act_id,
			'multi' => $v->act_multi,
			'cxc' => $v->act_cxc,
			'rendering' => 'background',
			'backgroundColor' => 'red');
	endforeach;

	echo json_encode($array);
endif;