<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id']);

    auth_user();
    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['delete_storage']);

    $link = open_db();

    //checks if the storage exists and gathers infos
    $query = $link -> prepare("select * from storage where storage_id=?");
    $query->execute(array($_POST["storage_id"]));

    $infos = $query->fetch(PDO::FETCH_ASSOC);

    //delete everything related to that storage:
    //resource minimums of inside resources
    $query = $link -> prepare("delete from resource_minimum where resource_id in (select resource_id from storage natural join resource where root_id=? and family like ?)");
    $query -> execute(array($infos['root_id'], $infos['family'].'%'));

    //inside resources
    $query = $link -> prepare("delete from resource where storage_id in (select storage_id from storage where root_id=? and family like ?)");
    $query -> execute(array($infos['root_id'], $infos['family'].'%'));

    //storage groups
    $query = $link -> prepare("delete from company_group_storage where storage_id in (select storage_id from storage where root_id=? and family like ?)");
    $query -> execute(array($infos['root_id'], $infos['family'].'%'));

    //deletes the storage
    $query = $link -> prepare("delete from storage where (root_id=? and family like ?) or storage_id=?");
    $query -> execute(array($infos['root_id'], $infos['family'].'.%', $infos['storage_id']));
    //checks if it failed
    if (!($query->rowCount())) return_error(OPERATION_FAILED);

    //adds log
    logify(DELETE_STORAGE, 1, $infos['storage_id'], $infos['storage_name'], PRIORITY['DELETE_STORAGE']);