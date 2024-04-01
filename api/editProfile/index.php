<?php

    include '../includes/helper.php';

    //Verifications
    isPostSet(['user_id']);

    auth_user();
    perms_user($_POST['company_user_id'], PERMS['edit_user']);

    //checks if the changer and the changed user exists and gather infos
    if (!($changed = get_by_id("company_user", $_POST['user_id']))) return_error(NO_SUCH_USER);
    $changer = get_by_id("company_user", $_POST['company_user_id']);

    //checks that the changer has higher privilege than the changed and that they are in the same company
    if (($changed['accreditation_level'] <= $changer['accreditation_level']) and $changer['accreditation_level'] > 0) return_error(NOT_ENOUGH_PRIVILEGE);
    if ($changer['company_id'] !== $changed['company_id']) return_error(WRONG_COMPANY);

    //checks, if the changer wants to change the permission level that the new permission level is lower than theirs
    if (isset($_POST['permission']))
        if ($_POST['permission'] <= $changer['accreditation_level'])
            return_error(NOT_ENOUGH_PRIVILEGE);


    $link = open_db();

    //checks, if the changer wants to change the email, that the new email is not already taken
    if (isset($_POST['email'])){
        $query = $link->prepare("select * from company_user where email=?");
        $query->execute(array($_POST['email']));
        if ($result = $query->fetch(PDO::FETCH_ASSOC)) return_error(EMAIL_ALREADY_TAKEN);
    }

    //apply changes
    $query = $link -> prepare("update company_user set 
                        accreditation_level=?,
                        company_user_name=?,
                        company_user_surname=?,
                        password_hash=?,                       
                        email=?
    where company_user_id=?;");

    $query -> execute(array(
        $_POST['permission'] ?? $changed['accreditation_level'],
        $_POST['name'] ?? $changed['company_user_name'],
        $_POST['surname'] ?? $changed['company_user_surname'],
        (isset($_POST['password'])?password_hash($_POST['password'], PASSWORD_DEFAULT):$changed['password_hash']),
        $_POST['email'] ?? $changed['email'],
        $_POST['user_id']));

    //returns info to the client
    $query = $link -> prepare("select * from  company_user where company_user_id=?;");
    $query -> execute(array($_POST['user_id']));

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(RESOURCE_LOST);

    echo json_encode(array(

        "company_user_id" => $result['company_user_id'],
        "company_user_name" => $result['company_user_name'],
        "company_user_surname" => $result['company_user_surname'],
        "email" => $result['email'],
        "accreditation_level" => $result['accreditation_level']

    ), JSON_FORCE_OBJECT);

    //adds log
    logify(EDIT_PROFILE, 1, $changed['company_id'], $changed['company_user_surname'].' '.$changed['company_user_name'], PRIORITY['EDIT_PROFILE']);
