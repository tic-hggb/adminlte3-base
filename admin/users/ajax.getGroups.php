<?php

include ("../../class/classMyDBC.php");
include ("../../class/classGroup.php");
include ("../../class/classUser.php");

if (extract($_POST)):
    $user = new User();
    $group = new Group();
    $a_tmp = $user->getGroups($id);
    $list_g = [];
    
    foreach ($a_tmp as $g => $id):
        $list_g[] = $group->get($id);
    endforeach;
    
    echo json_encode($list_g);
endif;