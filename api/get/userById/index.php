<?php

    include '../../includes/helper.php';

    isPostSet(['user_id']);

    auth_user();

    $link = open_db();

    //gets company id
    $id = get_company_of($_POST['company_user_id']);

    //gets the user with the corresponding id and company
    $query = $link->prepare("select * from company_user where company_user_id=? and company_id=?");
    $query->execute(array($_POST['user_id'], $id));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_USER);

    //returns all to the client
    echo json_encode(array(

        "company_user_name" => $result['company_user_name'],
        "company_user_surname" => $result['company_user_surname'],
        "email" => $result['email'],
        "accreditation_level" => $result['accreditation_level']

    ), JSON_FORCE_OBJECT);