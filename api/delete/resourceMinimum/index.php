<?php
    include '../../includes/helper.php';

    //verifications
    isPostSet(['resource_id']);
    auth_user();

    $link = open_db();

    //checks if the resource exists and gets its id
    $query = $link->prepare("select * from resource natural join resource_minimum where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if(!($resource = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);

    //checks if the user is allowed to perform the operation
    perms_storage_user($_POST['company_user_id'], $resource['storage_id'], PERMS['delete_resource_minimum']);

    //deletes the minimum
    $query = $link->prepare("delete from resource_minimum where resource_id=?");
    $query->execute(array($_POST['resource_id']));
    //checks if it failed
    if(!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_RESOURCE_MINIMUM, $resource['minimum'], $resource['resource_id'], $resource['resource_name'], PRIORITY['DELETE_RESOURCE_MINIMUM']);
