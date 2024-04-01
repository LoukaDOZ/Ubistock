<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id', 'dest_storage_id']);
    auth_user();

    perms_storage_user($_POST['company_user_id'],$_POST['storage_id'], PERMS['move_storage']);
    perms_storage_user($_POST['company_user_id'],$_POST['dest_storage_id'], PERMS['move_storage']);

    //gathers infos on the dest storage and the given one
    $destination = get_by_id("storage", $_POST['dest_storage_id']);
    $self = get_by_id("storage", $_POST['storage_id']);

    //determines the new index of the storage, see api/create/storage/index.php for more infos
    $max = 0;
    foreach (get_sons_of($destination['storage_id']) as $row) {
        $list = explode('.', $row['family']);
        $max = max($max, intval(end($list)));
    }
    $max ++;

    //creates the new family number
    $new_family = $destination['family'].'.'.strval($max);

    $link = open_db();

    //moves the storage to its destination
    $query = $link->prepare("update storage set family=? where storage_id=?");
    $query->execute(array($new_family, $self['storage_id']));

    //get all child storages
    $query = $link->prepare("select * from storage where root_id=? and family like ?");
    $query->execute(array($self['root_id'], $self['family'].'.%'));

    $kids = $query->fetchAll(PDO::FETCH_ASSOC);

    //changes all storage family individually
    foreach($kids as $row){
        $query = $link->prepare("update storage set family=? where storage_id=?");
        $query->execute(array(str_replace_first($self['family'], $new_family, $row['family']), $row['storage_id']));
    }

    //checks if the resource survived
    $query = $link->prepare("select * from storage where storage_id=?;");
    $query->execute(array($_POST['storage_id']));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(RESOURCE_LOST);

    //adds log
    logify(MOVE_STORAGE, 1, $self['storage_id'], $self['storage_name'], PRIORITY['MOVE_STORAGE']);