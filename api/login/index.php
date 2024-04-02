<?php
    include '../includes/helper.php';

    isPostSet(['password']);
    $link = open_db();

    if (isset($_POST['email'])){
        $query = $link->prepare("select * from company_user natural join company where email=?");
        $query->execute(array($_POST['email']));
    } else if (isset($_POST['company_user_id'])){
        $query = $link->prepare("select * from company_user natural join company where company_user_id=?");
        $query->execute(array($_POST['company_user_id']));

    } else{
        return_error(MISSING_GET_ARGS);
    }

    if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_USER);
    if (!password_verify($_POST['password'], $result['password_hash'])) return_error(INVALID_PASSWORD);

    $query = $link->prepare("delete from token where company_user_id=? or UNIX_TIMESTAMP(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP(token_creation) > ?");
    $query -> execute(array($result['company_user_id'], TOKEN_VALIDITY));
    $query = $link->prepare("insert into token(company_user_id,token_id,token_creation) values(?,?,DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s'))");
    $query -> execute(array($result['company_user_id'], generate_key().generate_key()));

    $query = $link->prepare("select * from company_user natural join token where company_user_id=?");
    $query->execute(array($result['company_user_id']));

    $answer = $query ->fetch(PDO::FETCH_ASSOC);

    echo json_encode(array(
        
        "company_user_id" => $answer['company_user_id'],
        "company_user_name" => $answer['company_user_name'],
        "company_user_surname" => $answer['company_user_surname'],
        "accreditation_level" => $answer['accreditation_level'],
        "token_id" => $answer['token_id']
        
    ), JSON_FORCE_OBJECT);
