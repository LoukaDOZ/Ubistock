<?php

    include '../../includes/helper.php';

    auth_user();

    $id = get_company_of($_POST['company_user_id']);

    $link = open_db();

    if (has_perm($_POST['company_user_id'], PERMS['belongs_to_all_groups'])) {
        $query = $link->prepare("select storage_id, storage_name, family from storage where company_id=?");
        $query->execute(array($id));

    } else {
        $query = $link->prepare("select storage_id, storage_name, family from (storage natural join company_group_storage) natural join company_group_member where company_user_id=?");
        $query->execute(array($_POST['company_user_id']));

    }
    
    echo json_encode(

        $query->fetchAll(PDO::FETCH_ASSOC)

            , JSON_FORCE_OBJECT);

