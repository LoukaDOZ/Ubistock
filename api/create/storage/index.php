<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['parent_storage_id', 'storage_name']);

    auth_user();
    perms_storage_user($_POST['company_user_id'], $_POST['parent_storage_id'], PERMS['create_storage']);

    //get parent storage infos
    $parent = get_by_id("storage", $_POST['parent_storage_id']);

    //defines the storage index for example if parent is 0 and there's already 0.1 and 0.3, it will be 0.4
    $max = 0;
    foreach (get_sons_of($parent['storage_id']) as $row) {
        $list = explode('.', $row['family']);
        $max = max($max, intval(end($list)));
    }
    $max += 1;

    //generates a random id
    $storage_id = generateRandomKey('storage');

    $link = open_db();

    //creates the storage
    $query = $link->prepare("insert into storage(storage_name, root_id, storage_id, company_id, family) values(?,?,?,?,?);");
    $query->execute(array($_POST['storage_name'], $parent['root_id'], $storage_id, $parent['company_id'], $parent['family'].'.'.strval($max)));

    //checks if it failed
    if (!($result = get_by_id("storage", $storage_id))) return_error(INSERTION_FAILED);

    //return infos to the client
    echo json_encode(array(

        "storage_id" => $result['storage_id'],
        "storage_name" => $result['storage_name']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(CREATE_STORAGE, 1, $storage_id, $result['storage_name'], PRIORITY['CREATE_STORAGE']);

