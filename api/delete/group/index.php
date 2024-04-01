<?php
    include '../../includes/helper.php';

    //verifications
    isPostSet(['group_id']);

    auth_user();
    perms_user($_POST['company_user_id'], PERMS['delete_group']);

    $link = open_db();
    //gets group infos
    $group = get_by_id("company_group", $_POST['group_id']);

    //deletes everything related to the group
    $query =$link->prepare("delete from company_group_member where company_group_id=?");
    $query->execute(array($_POST['group_id']));

    $query =$link->prepare("delete from company_group_storage where company_group_id=?");
    $query->execute(array($_POST['group_id']));

    //deletes the group
    $query =$link->prepare("delete from company_group where company_group_id=?");
    $query->execute(array($_POST['group_id']));

    if (!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_GROUP, 1, $_POST['group_id'], $group['company_group_name'], PRIORITY['DELETE_GROUP']);
