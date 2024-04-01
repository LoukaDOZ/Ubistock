<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['group_id', 'user_id']);
    auth_user();

    perms_user($_POST['company_user_id'], PERMS['toggle_user_group']);

    $link = open_db();

    //checks if the given user exists
    $assigned = get_by_id('company_user', $_POST['user_id']);

    //checks if action performer belongs to the given group or all groups
    $query=$link->prepare("select * from company_group_member natural join company_user where company_user_id=? and (accreditation_level<=? or company_group_id=?)");
    $query->execute(array($_POST['company_user_id'], PERMS['belongs_to_all_groups'], $_POST['group_id']));
    if (!($performer = $query->fetch(PDO::FETCH_ASSOC))) return_error(NOT_ENOUGH_PRIVILEGE);

    //gathers infos
    $group=get_by_id('company_group', $_POST['group_id']);

    //checks if the given user is already part of the given group
    $query = $link->prepare("select * from company_group_member natural join company_user where company_user_id=? and company_group_id=?");
    $query->execute(array($_POST['user_id'], $_POST['group_id']));

    //if yes, removes him
    if ($result = $query->fetch(PDO::FETCH_ASSOC)){

        //checks if removed user has higher privileges and if yes, abort
        if ($result['accreditation_level'] <= $performer['accreditation_level']) return_error(NOT_ENOUGH_PRIVILEGE);

        //else, perform the removal
        $query =$link->prepare("delete from company_group_member where company_user_id=? and company_group_id=?");
        $query->execute(array($_POST['user_id'], $_POST['group_id']));

        if (!($query->rowCount())) return_error(OPERATION_FAILED);

        echo json_encode(array(

            "company_user_id" => $_POST['company_user_id'],
            "company_group_id" => "-1"

        ), JSON_FORCE_OBJECT);

    } else {//else adds him

        $query = $link->prepare("insert into company_group_member('company_group_id', 'company_user_id') values(?,?);");
        $query->execute(array($_POST['group_id'], $_POST['user_id']));

        $query = $link->prepare("select * from company_group natural join company_group_member natural join company_user where company_user_id=? and company_group_id=?");
        $query->execute(array($_POST['user_id'], $_POST['group_id']));

        if (!($row = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

        echo json_encode(array(

            "company_user_id" => $row['company_user_id'],
            "company_group_id" => $row['company_group_id'],
            "company_group_name" => $row['company_group_name'],
            "company_user_name" => $row['company_user_name'],
            "company_user_surname" => $row['company_user_surname']

        ), JSON_FORCE_OBJECT);

    }

    //adds the corresponding log
    logify(TOGGLE_USER_GROUP, 1, $group['company_group_id'].':'.$assigned['company_user_id'], $group['company_group_name'].':'.$assigned['company_user_name'], PRIORITY['TOGGLE_USER_GROUP']);

