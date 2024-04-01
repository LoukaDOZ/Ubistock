<?php

    include '../../includes/helper.php';

    auth_user();

    $id = get_company_of($_POST['company_user_id']);

    $link = open_db();

    //gets company name
    $query = $link->prepare("select company_name from company where company_id=?");
    $query->execute(array($id));
    $company = $query->fetch(PDO::FETCH_ASSOC);

    //gets users infos
    $query = $link->prepare("select count(*) as total, accreditation_level from company_user where company_id=? group by accreditation_level");
    $query->execute(array($id));
    $company_users = $query->fetchAll(PDO::FETCH_ASSOC);

    //gets groups infos
    $query = $link->prepare("select count(*) as total from company_group where company_id=?");
    $query->execute(array($id));
    $company_groups = $query->fetch(PDO::FETCH_ASSOC);

    //gets storages infos
    $query = $link->prepare("select count(*) as total from storage where company_id=?");
    $query->execute(array($id));
    $storages = $query->fetch(PDO::FETCH_ASSOC);

    //gets resources infos
    $query = $link->prepare("select count(*) as total, resource_type from resource where storage_id in (select storage_id from storage where company_id=?) group by resource_type");
    $query->execute(array($id));
    $resources = $query->fetchAll(PDO::FETCH_ASSOC);

    //returns all to the client
    echo json_encode(array(

        "company" => $company,
        "company_users" => $company_users,
        "company_groups" => $company_groups,
        "storages" => $storages,
        "resources" => $resources

        ), JSON_FORCE_OBJECT);