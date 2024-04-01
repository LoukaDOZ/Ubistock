<?php

    include '../../includes/helper.php';

    auth_user();
    isPostSet(['storage_id']);

    $storage = get_by_id("storage", $_POST['storage_id']);
    $cid = get_company_of($_POST['company_user_id']);

    perms_storage_user($_POST['company_user_id'], $storage['storage_id'], PERMS['see_storage_minimum']);

    $link = open_db();

    //looks for resource_minimums in the given storage
    $query = $link->prepare("select storage_id, storage_name, minimum, resource_name, resource_type from storage_minimum natural join storage where company_id=? and family regexp ?");
    $query->execute(array($cid, '~^'.$storage['family'].'(\\.[0-9]+)*$~'));

    if (!($minis = $query->fetchAll(PDO::FETCH_ASSOC))) $minis = array();

    echo json_encode(array(

        $minis

    ), JSON_FORCE_OBJECT);