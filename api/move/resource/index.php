<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id', 'resource_id']);

    $link = open_db();

    auth_user();
    perms_storage_user($_POST['company_user_id'],$_POST['storage_id'], PERMS['move_resource']);

    //gather infos on the resource
    $resource = get_by_id("resource", $_POST['resource_id']);

    perms_storage_user($_POST['company_user_id'], $resource['storage_id'], PERMS['move_resource']);

    //moves it
    $query = $link->prepare("update resource set storage_id=? where resource_id=?");
    $query->execute(array($_POST['storage_id'], $_POST['resource_id']));
    //checks if it failed
    if (!$query->rowCount()) return_error(OPERATION_FAILED);

    //adds log
    logify(MOVE_RESOURCE, $resource['qty'], $resource['resource_id'], $resource['resource_name'], PRIORITY['MOVE_RESOURCE']);
