<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['resource_id', 'minimum']);

    auth_user();

    $link = open_db();

    //checks if resource exists
    $query = $link->prepare("select * from resource where resource_id=?");
    $query->execute(array($_POST['resource_id']));
    if(!($resource = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);

    //checks if the user has the right to perform that action
    perms_storage_user($_POST['company_user_id'], $resource['storage_id'], PERMS['create_resource_minimum']);

    //creates the minimum
    $query = $link->prepare("replace into resource_minimum(resource_id, minimum) 
                                        values (?, ?)");

    $query->execute(array($_POST['resource_id'], $_POST['minimum']));

    //checks if it failed
    $query=$link->prepare("select * from resource_minimum natural join resource where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if(!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

    //return information to the client
    echo json_encode(array(

        "resource_id" => $result['resource_id'],
        "minimum" => $result['minimum']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(CREATE_RESOURCE_MINIMUM, $result['minimum'], $result['resource_id'], $result['resource_name'], PRIORITY['CREATE_RESOURCE_MINIMUM']);
