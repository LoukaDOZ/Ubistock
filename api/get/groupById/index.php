<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['group_id']);
    auth_user();

    $group = get_by_id("company_group", $_POST['group_id']);
    if (get_company_of($_POST['company_user_id']) !== $group['company_id']) return_error(WRONG_COMPANY);

    //returns info to the client
    echo json_encode(array(

        "company_group_name" => $group['company_group_name'],
        "company_group_id" => $group['company_group_id']

    ), JSON_FORCE_OBJECT);