<?php

    include '../../includes/helper.php';

    auth_user();
    isPostSet(['resource_id']);

    $resource = get_by_id("resource", $_POST['resource_id']);
    perms_storage_user($_POST['company_user_id'], $resource['storage_id'], PERMS['see_res_minimum']);

    $link = open_db();

    //looks for resource_minimums in the given storage
    $query = $link->prepare("select * from resource_minimum where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if (!($mini = $query->fetch(PDO::FETCH_ASSOC))) $mini = array();

    echo json_encode(array(

        $mini

    ), JSON_FORCE_OBJECT);