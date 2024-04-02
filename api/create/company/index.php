<?php

    //verifications
    include '../../includes/helper.php';

    isPostSet(['name', 'surname', 'password', 'company_name', 'email']);

    //generates the ids of the elements to be created
    $storage_id = generateRandomKey('storage');
    $company_user_id = generateRandomKey('company_user');
    $company_id = generateRandomKey('company');
    $group_id = generateRandomKey('company_group');

    //checks email validity
    if(check_email($_POST["email"])) return_error(EMAIL_ALREADY_TAKEN);

    $link = open_db();
    $link->beginTransaction();
    
    //creates the company
    $query = $link->prepare("INSERT INTO company(company_id, company_name, root_id, ceo_id) VALUES(?, ?, ?, ?);");
    $result = $query->execute(array($company_id, $_POST['company_name'], $storage_id, $company_user_id));

    //creates the ceo User
    $query = $link->prepare("insert into company_user(company_user_id, company_user_name, company_user_surname, password_hash, company_id, accreditation_level, email) values(?, ?, ?, ?, ?, 0, ?);");
    $query->execute(array($company_user_id, $_POST['name'], $_POST['surname'], password_hash($_POST['password'], PASSWORD_DEFAULT), $company_id, $_POST['email']));

    //creates the root storage
    $query = $link->prepare("INSERT INTO storage (company_id, storage_id, storage_name, root_id, family) VALUES (?, ?, 'principal storage', ?, ?);");
    $result = $query->execute(array($company_id, $storage_id, $storage_id, '0'));

    //creates the admin group
    $query = $link->prepare("INSERT INTO company_group(company_group_id, company_group_name, company_id) values(?,'root',?)");
    $result = $query->execute(array($group_id, $company_id));

    //assigns the ceo to the admin group
    $query = $link->prepare("insert into company_group_member(company_group_id, company_user_id) values(?,?)" );
    $result = $query->execute(array($group_id, $company_user_id));

    //assigns the root storage to the admin group
    $query = $link->prepare("insert into company_group_storage(company_group_id, storage_id) values(?,?)" );
    $result = $query->execute(array($group_id, $storage_id));

    $link->commit();

    //get the information to be returned to the client
    $query = $link->prepare("select * from company_user natural join company where company_user_id=?");
    $result = $query->execute(array($company_user_id));
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) return_error(OPERATION_FAILED);

    echo json_encode(array(

        "company_name" => $result['company_name'],
        "ceo_id" => $result['ceo_id'],
        "root_id" => $result['root_id'],
        "company_id" => $result['company_id']

    ),JSON_FORCE_OBJECT);


