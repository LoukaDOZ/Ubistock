<?php

    include '../../includes/helper.php';

    isPostSet(['group_id']);

    auth_user();

    $id = get_company_of($_POST['company_user_id']);
    $group = get_by_id("company_group", $_POST['group_id']);
    if ($id !== $group['company_id']) return_error(WRONG_COMPANY);

    $link = open_db();

    $query = $link->prepare("select * from company_group_member natural join company_user where company_group_id=?");
    $query->execute(array($_POST['group_id']));
    $members = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $link->prepare("select * from company_group_storage natural join storage where company_group_id=?");
    $query->execute(array($_POST['group_id']));
    $storages = $query->fetchAll(PDO::FETCH_ASSOC);
    //Returns infos to the client
    echo json_encode(array(

        "storages" => $storages,
        "members" => $members

    ), JSON_FORCE_OBJECT);