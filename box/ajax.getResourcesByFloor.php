<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classBox.php");
include("../src/fn.php");

if (extract($_POST)):
	$b = new Box();
	$array = [];

	if (!empty($floor)):
		$box = $b->getByFloor($floor, $type);

		foreach ($box as $k => $v):
			$array[] = array(
				'id' => $v->box_id,
				'title' => $v->box_numero
			);
		endforeach;
	endif;

	echo json_encode($array);
endif;