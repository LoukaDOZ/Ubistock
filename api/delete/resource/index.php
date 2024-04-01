<?php

    include '../../includes/helper.php';

    //checks if all parameters have been given
    isPostSet(['resource_id']);

    //authentifies the user
    auth_user();

    //opens a database connection
    $link = open_db();

    //checks if the resource exists, if yes, gathers informations for the rest of the script
    $query = $link->prepare("select storage_id from resource natural join storage where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if (!($result = $query->fetch())) return_error(NO_SUCH_RESOURCE);

    //checks if the given user is allowed to delete a resource in the given storage
    perms_storage_user($_POST['company_user_id'], $result['storage_id'], PERMS['delete_resource']);

    //deletes old minimum for this specific resource and global minimums if theres no possible replacement
    $query = $link -> prepare("delete from resource_minimum where resource_id=?");
    $query->execute(array($_POST["resource_id"]));

    //deletes this resource from dbgs
    $query = $link -> prepare("delete from resource where resource_id=?");
    $query->execute(array($_POST["resource_id"]));

    //returns an errors if it has failed
    if (!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_RESOURCE, 1, $result['resource_id'], $result['resource_name'], PRIORITY['DELETE_RESOURCE']);