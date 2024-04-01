<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['group_id', 'group_name']);
    auth_user();

    $link = open_db();

    //checks if the user is part of the group
    $query = $link->prepare("select * from company_user where (accreditation_level<=?) or 
                                        company_user_id in (select company_user_id from company_group_member where company_group_id=? and company_user_id=?)");

    $query->execute(array(PERMS['belongs_to_all_groups'],  $_POST['group_id'], $_POST['company_user_id']));

    if (!($result = $query->fetch())) return_error(NOT_ENOUGH_PRIVILEGE);

    //checks if the user is allowed to perform the operation
    perms_user($_POST['company_user_id'], PERMS['rename_group']);

    //rename the group
    $query = $link -> prepare("update company_group set company_group_name=? where company_group_id=?;");
    $query -> execute(array($_POST['group_name'], $_POST['group_id']));

    if (!($result = get_by_id("company_group", $_POST['group_id']))) return_error(RESOURCE_LOST);

    //returns info to the client
    echo json_encode(array(

        "company_group_name" => $result['company_group_name'],
        "company_group_id" => $result['company_group_id']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(RENAME_GROUP, 1, $result['company_group_id'], $result['company_group_name'], PRIORITY['RENAME_GROUP']);

