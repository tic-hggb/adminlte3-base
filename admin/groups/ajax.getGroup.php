<?php

include ("../../class/classMyDBC.php");
include ("../../class/classGroup.php");

if (extract($_POST)):
    $group = new Group();
    echo json_encode($group->get($id));
endif;