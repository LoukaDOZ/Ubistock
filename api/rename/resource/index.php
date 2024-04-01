<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['resource_id', 'resource_name']);
    auth_user();

    $link = open_db();
    $query = $link->prepare("select storage_id from resource where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if (!($result = $query->fetch())) return_error(NO_SUCH_RESOURCE);

    //checks if the user is allowed to perform the operation
    perms_storage_user($_POST['company_user_id'], $result['storage_id'], PERMS['rename_resource']);

    //renames the resource
    $query = $link -> prepare("update resource set resource_name=? where resource_id=?;");
    $query -> execute(array($_POST['resource_name'], $_POST['resource_id']));

    //checks if it survived

    if (!($result = get_by_id("resource", $_POST['resource_id']))) return_error(RESOURCE_LOST);

    //returns infos to the client
    echo json_encode(array(

        "resource_name" => $result['resource_name'],
        "resource_id" => $result['resource_id']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(RENAME_RESOURCE, $result['qty'], $result['resource_id'], $result['resource_name'], PRIORITY['RENAME_RESOURCE']);

