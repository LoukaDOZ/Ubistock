<?php

    include '../../includes/helper.php';

    isPostSet(['resource_id']);

    auth_user();

    $link = open_db();

    //gets company id
    $id = get_company_of($_POST['company_user_id']);

    $resource = get_by_id("resource", $_POST['resource_id']);
    $storage = get_by_id("storage", $resource['storage_id']);

    //checks if the resource is in the correct company
    if ($storage['company_id'] !== $id) return_error(WRONG_COMPANY);


    $query = $link->prepare("select * from resource_minimum where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if (!(($mini = $query->fetch(PDO::FETCH_ASSOC))
        && has_perm($_POST['company_user_id'], PERMS['see_res_minimum']) ))
        $mini = array();

    //returns all to the client
    echo json_encode(array(

        "resource_name" => $resource['resource_name'],
        "resource_type" => $resource['resource_type'],
        "qty" => $resource['qty'],
        "family" => $storage['family'],
        "minimums" => $mini

    ), JSON_FORCE_OBJECT);