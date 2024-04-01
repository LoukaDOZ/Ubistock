<?php
    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id']);
    auth_user();

    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['delete_storage_minimum']);

    $link = open_db();

    //gathers infos
    $storage = get_by_id("storage", $_POST['storage_id']);

    //deletes the minimum
    $args = array($_POST['storage_id']);
    if (isset($_POST['resource_type'])) array_push($args, $_POST['resource_type']);
    if (isset($_POST['resource_name'])) array_push($args, $_POST['resource_name']);

    //if theres already a minimum, deletes it
    $query=$link->prepare("delete from storage_minimum where storage_id=? and 
                                                         resource_type ".(isset($_POST['resource_type'])?"=?":"is null")." and 
                                                         resource_name ".(isset($_POST['resource_name'])?"=?":"is null"));
    $query->execute($args);
    //checks if it failed
    if(!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_STORAGE_MINIMUM, 1, $storage['storage_id'], $storage['storage_name'], PRIORITY['DELETE_STORAGE_MINIMUM']);