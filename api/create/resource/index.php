<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['resource_name', 'storage_id', 'resource_type']);

    auth_user();
    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['create_resource']);

    //generates a random id
    $resource_id = generateRandomKey('resource');

    $link = open_db();

    //creates the resource
    $query = $link->prepare("insert into resource(resource_id, resource_name, resource_type, storage_id) values(?,?,?,?);");
    $query->execute(array($resource_id, $_POST['resource_name'], $_POST['resource_type'], $_POST['storage_id']));

    $query = $link->prepare("select * from resource where resource_id=?;");
    $query->execute(array($resource_id));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

    //return information to the client
    echo json_encode(array(

        "resource_name" => $result['resource_name'],
        "resource_type" => $result['resource_type'],
        "qty" => $result['qty'],
        "resource_id" => $result['resource_id']

    ), JSON_FORCE_OBJECT);

    //ads log
    logify(CREATE_RESOURCE, 1, $result['resource_id'], $result['resource_name'], PRIORITY['CREATE_RESOURCE']);
