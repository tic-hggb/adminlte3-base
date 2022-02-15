<?php

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require_once '../../class/classMyDBC.php';
require_once '../../class/classPersona.php';
require_once '../../class/classPersonaEstablecimiento.php';
require_once '../../class/classProfesion.php';
require_once '../../class/classActividad.php';
require_once '../../class/classAtencionTipo.php';
require_once '../../class/classAtencion.php';
require_once '../../vendor/autoload.php';
require_once '../../src/fn.php';

try {
	$time_start = microtime(true);

	if (!empty($_FILES)):
		$targetFolder = BASEFOLDER . 'upload/';
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

		foreach ($_FILES as $aux => $file):
			$tempFile = $file['tmp_name'][0];
			$targetFile = rtrim($targetPath, '/') . '/data.xlsx';

			if (!move_uploaded_file($tempFile, $targetFile))
				throw new Exception('Error al importar archivo', 0);
		endforeach;
	endif;

	$reader = ReaderFactory::create(Type::XLSX);
	$fileName = '../../upload/data.xlsx';

	$db = new myDBC();
	$p = new Persona();
	$pe = new PersonaEstablecimiento();
	$pr = new Profesion();
	$ac = new Actividad();
	$ta = new AtencionTipo();
	$a = new Atencion();

	if (!extract($_POST)) throw new Exception('Error al extraer datos POST', 0);
	$rData = [];
	$head = true;
	$nReg = false;
	$newP = $newC = [];
	$fecha = setDateBD('01/' . $idate);
	$establecimiento = 100;

	$counter = 0;
	$count_meds = 0;
	$count_nomeds = 0;
	$count_newp = 0;
	$count_newac = 0;
	$pers = [];

	$reader->open($fileName);

	foreach ($reader->getSheetIterator() as $sheet):
		foreach ($sheet->getRowIterator() as $row):
			$dates = false;

			// SI NO ES FILA DE CABECERA
			if (!$head):
				$counter++;

				if (isset($row[69]) and !empty(trim($row[69])) and substr(trim($row[69]), -3) == 'A07'):
					$count_meds++;
					$rut = number_format(intval(trim($row[49])), 0, '', '.') . '-' . trim($row[50]);
					$prof = trim(substr(trim($row[69]), 0, -4));
					$act = trim($row[10]);
					$tipoat = trim($row[59]);

					//echo "<br>leido $count_meds ($rut, $act, $tipoat) ";

					try {
						$per = $p->getByRut($rut, $db);
						$profe = $pr->getByName($prof, $db);
						$activ = $ac->getByName($act, $db);
						$taten = $ta->getByName($tipoat, $db);

						if (is_null($per->per_id)):
							$count_newp++;

							if (is_null($profe->prof_id)):
								$ins_pr = $pr->set($prof, $db);

								if (!$ins_pr['estado']):
									throw new Exception('Error al insertar la profesion. ' . $ins_pr['msg']);
								endif;

								$profe = new stdClass();
								$profe->prof_id = $ins_pr['msg'];
							endif;

							$ins_p = $p->set($rut, trim($row[52]) . ' ' . trim($row[53]) . ' ' . trim($row[51]), $profe->prof_id, '', $db);

							$pers[] = array('rut' => $rut, 'profesion' => $profe->prof_id, 'nombre' => trim($row[52]) . ' ' . trim($row[53]) . ' ' . trim($row[51]), 'sis' => '');

							if (!$ins_p['estado']):
								throw new Exception('Error al insertar la persona. ' . $ins_p['msg']);
							endif;

							$ins_pe = $pe->set($ins_p['msg'], $establecimiento, '', '', '', $db);

							if (!$ins_pe['estado']):
								throw new Exception('Error al insertar la persona en su establecimiento. ' . $ins_pe['msg']);
							endif;

							$per = new stdClass();
							$per->per_id = $ins_p['msg'];
						endif;

						if (is_null($activ->act_id)):
							$count_newac++;
							$comite = (strpos($act, 'COMITE') !== false) ? 'TRUE' : 'FALSE';

							$ins_a = $ac->set($act, $comite, $db);

							if (!$ins_a['estado']):
								throw new Exception('Error al insertar la actividad. ' . $ins_a['msg']);
							endif;

							$activ = new stdClass();
							$activ->act_id = $ins_a['msg'];
						endif;

						$atencion = $a->getByParams($taten->tat_id, $activ->act_id, $per->per_id, $fecha, $establecimiento, $db);

						if (is_null($atencion->at_id)):
							//inserta atencion
							$ins = $a->set($taten->tat_id, $activ->act_id, $per->per_id, $fecha, $establecimiento, $db);

							if (!$ins['estado']):
								throw new Exception('Error al insertar la atención. ' . $ins['msg']);
							endif;
						else:
							$ins = $a->update($atencion->at_id, $db);
							//echo "<br>update $atencion->at_id";

							if (!$ins['estado']):
								throw new Exception('Error al updatear la atención. ' . $ins['msg']);
							endif;
						endif;
					} catch (Exception $e) {
						//printf("Error: %s.\n", $stmt->error);
						echo $e->getMessage();
						break;
					}
				else:
					$count_nomeds++;
				endif;
			endif;

			$head = false;

		endforeach;
	endforeach;

	$result = '';
	$result .= "TOTAL: " . $counter . "<br>";
	$result .= "ATENCIONES CARGADAS: " . $count_meds . "<br>";
	$result .= "ATENCIONES NO CARGADAS: " . $count_nomeds . "<br>";
	$result .= "PERSONAS NUEVAS: " . $count_newp . "<br>";
	$result .= "ACTIVIDADES NUEVAS: " . $count_newac . "<br>";
	$reader->close();

	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start) / 60;

	$result .= '<br><b>Total Tiempo Ejecucion:</b> ' . $execution_time . ' Mins';

	$response = array('type' => true, 'msg' => $result);
	echo json_encode($response);
} catch (UnsupportedTypeException $e) {
	$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => 0);
	echo json_encode($response);
} catch (IOException $e) {
	$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => 0);
	echo json_encode($response);
} catch (ReaderNotOpenedException $e) {
	$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => 0);
	echo json_encode($response);
} catch (Exception $e) {
	$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
	echo json_encode($response);
}