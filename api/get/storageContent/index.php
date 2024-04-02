<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['storage_id']);

    auth_user();
    perms_storage_user($_POST['company_user_id'], $_POST['storage_id'], PERMS['see_res']);

    //gets accreditation level
    $user = get_by_id("company_user", $_POST['company_user_id']);
    $storage = get_by_id("storage", $_POST['storage_id']);

    if ($storage['company_id'] !== $user['company_id']) return_error(WRONG_COMPANY);
    if (!has_storage_access($user['company_user_id'], $storage['family'])) return_error(NOT_ENOUGH_PRIVILEGE);

    $sub_storages = get_sons_of($_POST['storage_id']);

    $link = open_db();

    //select infos according to the permission level
    $query = $link->prepare("select ".

        (($user['accreditation_level']<=PERMS['see_res_qty'])?"qty, ":"").
        (($user['accreditation_level']<=PERMS['see_res_name'])?"resource_name, ":"").

        "resource_id from resource where storage_id=?");
    $query->execute(array($_POST['storage_id']));

    $sub_resources = $query->fetchAll(PDO::FETCH_ASSOC);

    //gets parent storages, visible from the user

    //creates the good regexp (format is like "0(.1(.2(.3)?)?)?")
    $regexp = explode ('.', $storage['family']);
    $count = count($regexp);
    $regexp = join('(\\.', $regexp);

    for ($i = 1; $i < $count; $i++)
        $regexp = $regexp.')?';
    $regexp = '^'.$regexp.'$';

    //gets the storages
    $query = $link->prepare("select storage_id, storage_name, family from storage where root_id=? and family regexp ? order by family");
    $query->execute(array($storage['root_id'], $regexp));
    $breadCrumb = $query->fetchAll(PDO::FETCH_ASSOC);

    //keeps only storages the client is allowed to see
    for ($i = count($breadCrumb); $i > 0; $i--){
        if (!has_storage_access($_POST['company_user_id'], $breadCrumb[$i-1]['family'])) {
            $breadCrumb = array_slice($breadCrumb, $i);
            break;
        }
    }

    $minimums = array();
    if (has_perm($_POST['company_user_id'], PERMS['see_storage_minimum'])){
        //get the minimums
        $query = $link->prepare("select storage_id, storage_name, minimum, resource_name, resource_type from storage_minimum natural join storage where company_id=? and storage_id=?");
        $query->execute(array($user['company_id'], $storage['storage_id']));

        if (!($minis = $query->fetchAll(PDO::FETCH_ASSOC))) $minis = array();

        foreach ($minis as $key => $storage_minimum) {

            //searches for resources that matches the current minimum, and return the sum, name, and type of all of them
            $query = $link->prepare("select sum(qty) as 'count' from resource 
                                                 where resource_type like ? and resource_name like ? and storage_id in 
                                                        (select storage_id from storage where family like ? and root_id=?)");

            $query->execute(array(
                //if resource type or name is null, puts % to match any type or name
                ($storage_minimum['resource_type'] === null ? '%' : $storage_minimum['resource_type']),
                ($storage_minimum['resource_name'] === null ? '%' : $storage_minimum['resource_name']),
                //adds % at the end to match any sub-storage
                ($storage['family'] . '%'),
                $storage['root_id']));

            //if no occurence is found go for the next storage_minimum
            if (!($result = $query->fetch(PDO::FETCH_ASSOC))) continue;

            $result['resource_type'] = $storage_minimum['resource_type'] ?? null;
            $result['resource_name'] = $storage_minimum['resource_name'] ?? null;

            $storage_minimum['count'] = $result['count'];

            //adds the current storage_minimum to the mini-list
            array_push($minimums, $storage_minimum);
        }
    }

    //returns infos to the client
    echo json_encode(array(

        "storage_name" => $storage['storage_name'],
        "storage_id" => $storage['storage_id'],
        "family" => $storage['family'],
        "breadcrumb" => $breadCrumb,
        "storages" => $sub_storages,
        "resources" => $sub_resources,
        "minimums" => $minimums

    ), JSON_FORCE_OBJECT);

