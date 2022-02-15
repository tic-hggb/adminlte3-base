<?php

include("../../class/classMyDBC.php");
include("../../class/classUser.php");

if (extract($_POST)):
	$user = new User();
	$key = $user->getPrivateKey($id);

	$r['msg'] = ($key == md5($pass)) ? true : false;

	echo json_encode($r);
endif;