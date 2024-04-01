<?php

    include '../includes/helper.php';

    //Verifications
    isPostSet(['resource_id', 'qty']);
    auth_user();

    $link = open_db();

    //checks if the resource exists
    $query = $link->prepare("select storage_id from resource where resource_id=?");
    $query->execute(array($_POST['resource_id']));

    if (!($result = $query->fetch())) return_error(NO_SUCH_RESOURCE);

    //check sif the user is allowed to perform the operation
    perms_storage_user($_POST['company_user_id'], $result['storage_id'], PERMS['edit_qty']);

    //adds the given quantity
    $query = $link -> prepare("update resource set qty=? where resource_id=?;");
    $query -> execute(array($_POST['qty'], $_POST['resource_id']));

    //checks if the resource survived
    $query = $link -> prepare("select * from resource where resource_id=?;");
    $query -> execute(array($_POST['resource_id']));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(RESOURCE_LOST);

    //returns infos to the client
    echo json_encode(array(

        "resource_id" => $result['resource_id'],
        "qty" => $result['qty']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(QTY_EDIT, $result['qty'], $result['resource_id'], $result['resource_name'], PRIORITY['QTY_EDIT']);

