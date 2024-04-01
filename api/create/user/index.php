<?php
    include '../../includes/helper.php';

    //verifications
    isPostSet(['name', 'surname', 'password', 'email']);

    auth_user();
    perms_user($_POST['company_user_id'], PERMS['create_user']);
    if (check_email($_POST['email'])) return_error(EMAIL_ALREADY_TAKEN);

    //generates a random id
    $company_user_id = generateRandomKey('company_user');

    $link = open_db();

    //checks if the user has enough privilege to create a new user and the new user privilege is lower than creator's
    $query=$link->prepare("select * from company_user where company_user_id=?");
    $query->execute(array($_POST['company_user_id']));

    $boss = $query -> fetch(PDO::FETCH_ASSOC);

    if (($_POST['accreditation'] ?? LOWEST_RANK) <= $boss['accreditation_level']) return_error(NOT_ENOUGH_PRIVILEGE);

    //creates the user
    $query = $link->prepare("insert into company_user('company_user_id', 'company_user_name', 'company_user_surname',
                         'password_hash', 'company_id', 'accreditation_level', 'email') values(?, ?, ?, ?, ?, ?, ?);");

    $query->execute(array($company_user_id, $_POST['name'], $_POST['surname'], password_hash($_POST['password'], PASSWORD_DEFAULT),
        $boss['company_id'], $_POST['accreditation'] ?? LOWEST_RANK, $_POST['email']));

    //checks if it worked
    $query = $link->prepare("select * from company_user where company_user_id=?");
    $query->execute(array($company_user_id));

    if(!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(INSERTION_FAILED);

    //return infos to the client
    echo json_encode(array(

        "company_user_id" => $result['company_user_id'],
        "company_user_name" => $result['company_user_name'],
        "company_user_surname" => $result['company_user_surname'],
        "accreditation_level" => $result['accreditation_level'],
        "email" => $result['email']

    ),JSON_FORCE_OBJECT);

    //adds log
    logify(CREATE_USER, 1, $result['company_user_id'], $result['company_user_surname'].' '.$result['company_user_name'], PRIORITY['CREATE_USER']);


