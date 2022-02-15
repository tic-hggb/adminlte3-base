<?php

include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $user = new User();
    $usr = $user->get($id);
    
    $r['msg'] = ($usr->us_password == md5($pass)) ? true : false;

    echo json_encode($r);
endif;