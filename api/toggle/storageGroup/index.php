<?php

    include '../../includes/helper.php';

    //checks if theres all the required arguments for the script
    isPostSet(['group_id', 'storage_id']);

    //authentifies the user
    auth_user();

    //checks if they're allowed to perform the given operation on the given storage
    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['toggle_storage_group']);

    //checks if the given group exists
    $group = get_by_id("company_group", $_POST['group_id']);

    //checks if the given storage exists
    $storage = get_by_id("storage", $_POST['storage_id']);

    $link = open_db();
    $query =$link->prepare("select * from company_group_storage where storage_id=? and company_group_id=?");
    $query->execute(array($_POST['storage_id'], $_POST['group_id']));

    //if storage is already part of group, lets remove it
    if ($result = $query->fetch()){

        $query =$link->prepare("delete from company_group_storage where storage_id=? and company_group_id=?");
        $query->execute(array($_POST['storage_id'], $_POST['group_id']));

        if (!($query->rowCount())) return_error(OPERATION_FAILED);

        echo json_encode(array(

            "storage_id" => $result['storage_id'],
            "company_group_id" => "-1"

            ), JSON_FORCE_OBJECT);

    } else {//else, lets add it

        //perform insertion
        $query = $link -> prepare("insert into company_group_storage('company_group_id', 'storage_id') values(?,?);");
        $query -> execute(array($_POST['group_id'], $_POST['storage_id']));

        //checks if it worked
        $query =$link->prepare("select * from storage natural join company_group_storage natural join company_group where storage_id=? and company_group_id=?");
        $query->execute(array($_POST['storage_id'], $_POST['group_id']));

        if (!($row = $query->fetch())) return_error(INSERTION_FAILED);

        //returns information
        echo json_encode(array(

            "storage_id" => $row['storage_id'],
            "company_group_id" => $row['company_group_id']

            ), JSON_FORCE_OBJECT);

    };

    //adds logs
    logify(TOGGLE_STORAGE_GROUP, 1, $storage['storage_id'].':'.$group['company_group_id'], $storage['storage_name'].':'.$group['company_group_name'], PRIORITY['TOGGLE_STORAGE_GROUP']);

