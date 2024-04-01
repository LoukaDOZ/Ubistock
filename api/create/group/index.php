<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['group_name']);

    auth_user();
    perms_user($_POST['company_user_id'], PERMS['create_group']);

    $link = open_db();

    //generates random ids
    $group_id = generateRandomKey('company_group');
    $company_id = get_company_of($_POST['company_user_id']);

    //creates the group
    $query = $link -> prepare("insert into company_group('company_group_id', 'company_group_name', 'company_id') values(?,?,?);");
    $query -> execute(array($group_id, $_POST['group_name'], $company_id));

    //checks if insertion was successful and the real name of the group
    $query = $link -> prepare("select * from company_group where company_group_id=?;");
    $query -> execute(array($group_id));

    if(!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

    //returns information
    echo json_encode(array(

        "company_group_name" => $result['company_group_name'],
        "company_group_id" => $result['company_group_id']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(CREATE_GROUP, 1, $result['company_group_id'], $result['company_group_name'], PRIORITY['CREATE_GROUP']);

