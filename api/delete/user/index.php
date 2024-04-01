<?php

    include '../../includes/helper.php';

    isPostSet(['user_id']);

    $link = open_db();

    //logs the user in and checks if he has the correct rights
    auth_user();
    perms_user($_POST['company_user_id'], PERMS['delete_user']);

    //checks if the user is about to delete himself, would be very sad
    if ($_POST['company_user_id'] === $_POST['user_id']) return_error(DONT_DELETE_YOURSELF);

    //get info from the user about to be deleted
    $query = $link->prepare("select * from company_user where company_user_id=?");
    $query->execute(array($_POST['user_id']));

    if (!($dead = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_USER);

    //get infos from the user that is deleting the other
    $query = $link->prepare("select * from company_user where company_user_id=?");
    $query->execute(array($_POST['company_user_id']));

    $boss = $query->fetch(PDO::FETCH_ASSOC);

    //checks if the deleting user has higher accreditation level than the deleted one
    if ($dead['accreditation_level']<=$boss['accreditation_level']) return_error(NOT_ENOUGH_PRIVILEGE);

    //proceed to deletion
    $query = $link->prepare("delete from company_group_member where company_user_id=?");
    $query->execute(array($_POST['user_id']));

    $query = $link->prepare("delete from company_user where company_user_id=?");
    $query->execute(array($_POST['user_id']));
    //checks if it failed
    if (!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_USER, 1, $dead['company_user_id'], $dead['company_user_surname'].' '.$dead['company_user_name'], PRIORITY['DELETE_USER']);