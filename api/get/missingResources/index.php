<?php

    include '../../includes/helper.php';

    isPostSet(['storage_id']);

    auth_user();

    $storage = get_by_id('storage', $_POST['storage_id']);

    perms_storage_user($_POST['company_user_id'], $storage['storage_id'], PERMS['see_res_minimum']);
    perms_storage_user($_POST['company_user_id'], $storage['storage_id'], PERMS['see_storage_minimum']);

    $link = open_db();

    //looks for resource_minimums in the given storage
    $query = $link->prepare("select resource_id, minimum-qty as missing from resource natural join resource_minimum where qty<minimum and storage_id in 
                                        (select storage_id from storage where root_id=? and family like ?)");
    $query->execute(array($storage['root_id'], $storage['family'] . '%'));

    $resource_needs = $query->fetchAll(PDO::FETCH_ASSOC);


    //looks for storage minimums in the given storage
    $query = $link->prepare("select * from storage_minimum natural join storage where root_id=? and family like ?");
    $query->execute(array($storage['root_id'], $storage['family'] . '%'));
    $storage_minimums = $query->fetchAll(PDO::FETCH_ASSOC);

    $storage_needs = array();

    //looks for resource needs in the storages minimums
    foreach ($storage_minimums as $key => $storage_minimum) {

        //searches for resources that matches the current minimum, and return the sum, name, and type of all of them
        $query = $link->prepare("select ?-sum(qty) as missing from resource 
                                             where resource_type like ? and resource_name like ? and storage_id in 
                                                    (select storage_id from storage where family like ? and root_id=?)");

        $query->execute(array($storage_minimum['minimum'],
            //if resource type or name is null, puts % to match any type or name
            ($storage_minimum['resource_type'] === null ? '%' : $storage_minimum['resource_type']),
            ($storage_minimum['resource_name'] === null ? '%' : $storage_minimum['resource_name']),
            //adds % at the end to match any sub-storage
            ($storage_minimum['family'] . '%'),
            $storage['root_id']));

        //if no occurence is found go for the next storage_minimum
        if (!($result = $query->fetch(PDO::FETCH_ASSOC))) continue;
        //if there is no need, go to next storage_minimum
        if ($result['missing'] < 0 || $result['missing'] === null) continue;
        $result['resource_type'] = $storage_minimum['resource_type'] ?? null;
        $result['resource_name'] = $storage_minimum['resource_name'] ?? null;

        //else, adds the current storage_minimum to the needs-list
        array_push($storage_needs, $result);
    }

    echo json_encode(array(

        "resource_needs" => $resource_needs,
        "storage_needs" => $storage_needs

    ), JSON_FORCE_OBJECT);