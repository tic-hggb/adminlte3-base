<?php

include ("../../class/classMyDBC.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $user = new User();
    echo json_encode($user->get($id));
endif;