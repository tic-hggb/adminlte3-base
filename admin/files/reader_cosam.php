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

// BORRADO DE HORAS CON FECHA $idate
//$d_hor = $h->delAll($idate, $itplanta, $db);

//if (!$d_hor['estado']):
//    throw new Exception('Error al eliminar los datos antiguos de las horas de contrato. ' . $d_hor['msg']);
//endif;

try {
	$time_start = microtime(true);

	$reader = ReaderFactory::create(Type::XLSX);

	$fileName = '../../upload/data_cosam.xlsx';

	$db = new myDBC();
	$p = new Persona();
	$pe = new PersonaEstablecimiento();
	$pr = new Profesion();
	$ac = new Actividad();
	$ta = new AtencionTipo();
	$a = new Atencion();

	$rData = [];
	$head = 0;
	$nReg = false;
	$newP = $newC = [];
	$txt = "";
	$fecha = '2019-07-01';
	$establecimiento = 100;

	$counter = 0;
	$count_meds = 0;
	$count_nomeds = 0;
	$count_newp = 0;
	$count_newac = 0;
	$pers = [];

	$reader->open($fileName);

	foreach ($reader->getSheetIterator() as $sheet):
		$sheetName = $sheet->getName();

		$sheetType = (strpos($sheetName, 'A07')) ? 'M' : 'NM';

		echo "n: " . $sheetName . " - " . $sheetType . "<br>";
		foreach ($sheet->getRowIterator() as $row):
			$dates = false;

			// SI NO ES FILA DE CABECERA
			/*if ($head > 1):
				$counter++;

				if (isset($row[19]) and !empty(trim($row[19]))):
					$count_meds++;
					$rut = null;
					$rec = trim($row[2]);
					$prof = trim($row[19]);
					$act = trim($row[1]);
					$tipoat = trim($row[17]);

					$full_n = trim($row[3]);
					$tmp = explode(' ', $full_n);
					$ap = $tmp[0];
					$am = $tmp[1];
					$nombre = $tmp[2];

					echo "<br>leido $count_meds ($rec, $act, $tipoat) ";

					try {
						$per = $p->getByRec($rec, $db);
						$profe = $pr->getByName($prof, $db);
						$activ = $ac->getByName($act, $db);
						$taten = new stdClass();
						$taten->tat_id = ($tipoat == 'NUEV') ? 1 : 2;

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

							$full_n = trim($row[3]);
							$tmp = explode(' ', $full_n);
							$ap = $tmp[0];
							$am = $tmp[1];
							$nombre = $tmp[2];
							if (isset($tmp[3])): $nombre .= $tmp[3]; endif;

							$ins_p = $p->set($rut, $nombre . ' ' . $ap . ' ' . $am, $profe->prof_id, '', $db);

							$pers[] = array('rut' => $rut, 'profesion' => $profe->prof_id, 'nombre' => $nombre . ' ' . $ap . ' ' . $am, 'sis' => $rec);

							if (!$ins_p['estado']):
								throw new Exception('Error al insertar la persona. ' . $ins_p['msg']);
							endif;

							$ins_pe = $pe->set($ins_p['msg'], $establecimiento, null, null, null, $db);

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
							$ins = $a->set($taten->tat_id, $activ->act_id, $per->per_id, $fecha, $establecimiento, $db);

							if (!$ins['estado']):
								throw new Exception('Error al insertar la atención. ' . $ins['msg']);
							endif;
						else:
							$ins = $a->update($atencion->at_id, $db);

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
			endif;*/

			$head++;

		endforeach;
	endforeach;

	/*
	echo "<pre>";
	print_r($rData);
	echo "</pre>";
	echo count($rData);
	 *
	 */
	/*echo "TOTAL: " . $counter . "<br>";
	echo "MEDICOS: " . $count_meds . "<br>";
	echo "NO MEDICOS: " . $count_nomeds . "<br>";
	echo "PERSONAS: " . $count_newp . "<br>";
	echo "<pre>";
	print_r($pers);
	echo "</pre>";
	echo "ACTIVIDADES: " . $count_newac . "<br>";*/
	$reader->close();

	$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
	$execution_time = ($time_end - $time_start) / 60;

//execution time of the script
	echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
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