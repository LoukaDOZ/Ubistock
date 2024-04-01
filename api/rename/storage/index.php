<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id', 'storage_name']);
    auth_user();

    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['rename_storage']);

    $link = open_db();
    //rename storage
    $query = $link -> prepare("update storage set storage_name=? where storage_id=?;");
    $query -> execute(array($_POST['storage_name'], $_POST['storage_id']));

    //checks if it survived
    if (!($result = get_by_id("storage", $_POST['storage_id']))) return_error(RESOURCE_LOST);

    //returns info to the client
    echo json_encode(array(

        "storage_id" => $result['storage_id'],
        "storage_name" => $result['storage_name']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(RENAME_STORAGE, 1, $result['storage_id'], $result['storage_name'], PRIORITY['RENAME_STORAGE']);

