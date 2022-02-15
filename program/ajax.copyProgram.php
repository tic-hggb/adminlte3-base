<?php

session_start();
include ("../class/classMyDBC.php");
include ("../class/classDistribucionProg.php");
include ("../class/classDistHorasProg.php");
include ("../src/fn.php");
$_admin = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
    $_admin = true;
    $_SESSION['prm_estid'] = 100;
endif;

if (extract($_POST)):
    $db = new myDBC();
    $di = new DistribucionProg();
    $dh = new DistHorasProg();
    
    $date = setDateBD('01/' . $date);
	$date_t = setDateBD('31/' . $date_t);
    $dist = $di->getByEstabPlanta($pl, $_SESSION['prm_estid'], $db);
    $count_d = 0;
    
    try {
        $db->autoCommit(FALSE);
    
        foreach ($dist as $i => $d):
            $chk = $di->getCountByPerDate($d->disp_pesid, $date, $date_t, $d->disp_espid, $db);

            if ($chk == 0):
                $ins = $di->set($d->disp_pesid, $d->disp_descripcion, $d->disp_observaciones, $date, $date_t, $d->disp_jusid, $d->disp_serid, $d->disp_espid, $d->disp_vacaciones, $d->disp_permisos, $d->disp_congreso, $d->disp_descanso, $d->disp_med_general, $_SESSION['prm_userid'], $db);

                if (!$ins['estado']):
                    throw new Exception('Error al copiar los datos de la distribución. ' . $ins['msg']);
                endif;
                
                $count_d++;
                $horas = $dh->getByDist($d->disp_id, $db);
                
                foreach ($horas as $v => $h):
                    $ins_h = $dh->set($ins['msg'], $h->dhp_acpid, $h->dhp_cantidad, $h->dhp_rendimiento, $h->dhp_observacion, $db);
                
                    if (!$ins_h['estado']):
                        throw new Exception('Error al copiar los datos de las horas. ' . $ins_h['msg']);
                    endif;
                endforeach;
            endif;
        endforeach;

        $db->Commit();
        $db->autoCommit(TRUE);
        $response = array('type' => true, 'msg' => $count_d);
        echo json_encode($response);
        
    } catch (Exception $e) {
        $db->Rollback();
        $db->autoCommit(TRUE);
        $response = array('type' => false, 'msg' => $e->getMessage());
        echo json_encode($response);
    }
endif;