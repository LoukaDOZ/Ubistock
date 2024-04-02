<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id', 'minimum']);
    auth_user();

    $link = open_db();

    //checks if the storage exists
    $query = $link->prepare("select * from storage where storage_id=?");
    $query->execute(array($_POST['storage_id']));
    if(!($storage = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);

    //checks if the user is allowed to perform the operation
    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['create_storage_minimum']);

    //setups args
    $args = array($_POST['storage_id']);
    if (isset($_POST['resource_type'])) array_push($args, $_POST['resource_type']);
    if (isset($_POST['resource_name'])) array_push($args, $_POST['resource_name']);

    //if theres already a minimum, deletes it
    $query=$link->prepare("delete from storage_minimum where storage_id=? and 
                                                         resource_type ".(isset($_POST['resource_type'])?"=?":"is null")." and 
                                                         resource_name ".(isset($_POST['resource_name'])?"=?":"is null"));
    $query->execute($args);

    //creates the minimum
    $query = $link->prepare("insert into storage_minimum(storage_id, resource_type, resource_name, minimum) 
                                        values (?, ?, ?, ?) ");

    $query->execute(array($_POST['storage_id'], $_POST['resource_type'] ?? null, $_POST['resource_name'] ?? null, $_POST['minimum']));

    //checks if it worked
    $query=$link->prepare("select * from storage_minimum natural join storage where storage_id=? and 
                                                         resource_type ".(isset($_POST['resource_type'])?"=?":"is null")." and 
                                                         resource_name ".(isset($_POST['resource_name'])?"=?":"is null"));
    $query->execute($args);
    if(!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

    //return infos to the client
    echo json_encode(array(

        "storage_id" => $result['storage_id'],
        "resource_type" => $result['resource_type'],
        "resource_name" => $result['resource_name'],
        "minimum" => $result['minimum']

    ), JSON_FORCE_OBJECT);

    //ads log
    logify(CREATE_STORAGE_MINIMUM, $result['minimum'], $result['storage_id'], $result['storage_name'], PRIORITY['CREATE_STORAGE_MINIMUM']);
