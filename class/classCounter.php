<?php

class Counter {

	public function __construct()
	{
	}

	/**
	 * @return stdClass
	 */
	public function get(): stdClass
	{
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT cou_num FROM prm_counter");

		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->cou_num = $row['cou_num'];

		unset($db);
		return $obj;
	}

	/**
	 * @return array
	 */
	public function set(): array
	{
		$db = new myDBC();

		try {
			$stmt = $db->Prepare("UPDATE prm_counter SET cou_num = cou_num + 1");

			if (!$stmt):
				throw new Exception("La inserción de la visita falló en su preparación.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la visita falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => '');
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}