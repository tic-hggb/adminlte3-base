<?php

include_once("../src/settings.php");
$logout = false;

try {
	if (isset($_SESSION['prm_logintime'])):
		$timeout = ((time() - $_SESSION['prm_logintime']) >= SESSION_TIME) ? false : true;

		if (!$timeout):
			$logout = true;
			throw new Exception('Su sesión ha cerrado por inactividad, debe iniciar sesión nuevamente. Redirigiendo a página de inicio...', 1);
		else:
			$_SESSION['prm_logintime'] = time();
		endif;
	else:
		throw new Exception('Su sesión ha cerrado por inactividad, debe iniciar sesión nuevamente. Redirigiendo a página de inicio...', 1);
	endif;
} catch (Exception $e) {
	$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
	echo json_encode($response);
}