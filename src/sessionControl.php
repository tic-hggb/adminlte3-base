<?php

$logout = false;

if (isset($_SESSION['prm_logintime']) and !empty($_SESSION['prm_logintime'])):
	$timeout = ((time() - $_SESSION['prm_logintime']) >= SESSION_TIME) ? true : false;

	if ($timeout):
		$logout = true;
	else:
		$time = time();
		setcookie(session_name(), session_id(), $time + SESSION_TIME);
		$_SESSION['prm_logintime'] = $time;
	endif;
elseif (isset($_COOKIE['logged_in'])):
	$logout = true;
endif;

if ($logout and !isset($_GET['timeout'])):
	header("Location: src/logout.php");
endif;