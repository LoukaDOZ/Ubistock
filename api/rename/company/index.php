<?php

    include '../../includes/helper.php';

    //verifications
    isPostSet(['company_name']);

    auth_user();
    perms_user($_POST['company_user_id'], PERMS['rename_company']);

    //gathers infos
    $company = get_by_id("company", get_company_of($_POST['company_user_id']));

    $link = open_db();

    //renames the company
    $query = $link -> prepare("update company set company_name=? where company_id in 
                                        (select company_id from company_user where company_user_id=?);");

    $query -> execute(array($_POST['company_name'], $_POST['company_user_id']));

    //checks if it survived
    $query = $link -> prepare("select * from company where company_id in 
                                        (select company_id from company_user where company_user_id=?);");
    $query -> execute(array($_POST['company_user_id']));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(RESOURCE_LOST);

    //returns infos to the client
    echo json_encode(array(

        "company_name" => $result['company_name']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(RENAME_COMPANY, 1, $company['company_id'], $company['company_name'], PRIORITY['RENAME_COMPANY']);

