<?php

    include '../../includes/helper.php';

    isPostSet(['user_id']);

    auth_user();

    $id = get_company_of($_POST['company_user_id']);
    if ($id !== get_company_of($_POST['user_id'])) return_error(WRONG_COMPANY);

    $link = open_db();

    //get groups the user belongs to
    if (has_perm($_POST['user_id'], PERMS['belongs_to_all_groups'])){//if hes part of every group
        $query = $link->prepare("select * from company_group where company_id=?");
        $query->execute(array($id));
    } else {//if hes not
        $query = $link->prepare("select * from company_group where company_id=? and company_group_id in (select company_group_id from company_group_member where company_user_id=?)");
        $query->execute(array($id, $_POST['user_id']));
    }

    //Returns infos to the client
    echo json_encode(

        $query->fetchAll(PDO::FETCH_ASSOC)

    , JSON_FORCE_OBJECT);