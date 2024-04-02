<?php

function auth_user()
{
    isPostSet(["company_user_id", "token_id"]);

    $link = open_db();

    $query = $link->prepare("select * from token where company_user_id=? and token_id=? and UNIX_TIMESTAMP(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP(token_creation) < ?;");
    $query->execute(array($_POST['company_user_id'], $_POST['token_id'], TOKEN_VALIDITY));

    if (!($result = $query->fetch())) return_error(SESSION_EXPIRED);

}

function perms_storage_user($user_id, $storage_id, $perm) {

    $link = open_db();

    //checks if the user has sufficient accreditation level
    $query = $link->prepare("select * from company_user where accreditation_level<=? and company_user_id=?");
    $query->execute(array($perm, $user_id));

    if (!($result = $query->fetch())) return_error(NOT_ENOUGH_PRIVILEGE);


    //get info from storage
    $query = $link->prepare("select * from storage where storage_id=?");
    $query->execute(array($storage_id));

    //if storage doesnt exist, throws an error
    if (!($storage = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);



    //checks if the user belongs to all groups
    $query = $link->prepare("select * from company_user where company_user_id=? and accreditation_level<=?");
    $query->execute(array($user_id, PERMS['belongs_to_all_groups']));

    if ($query->fetch()) return true;



    //checks if the given storage belongs to one of the users groups
    $query = $link->prepare("select family from company_group_member natural join company_group_storage natural join storage
                                        where company_user_id=?;");
    $query->execute(array($user_id));
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row){
        if (substr(  $storage['family'], 0, strlen($row['family'])) === $row['family']) return true;
    }

    //if yes, returns true, else, checks for his parent storage
    return_error(NOT_ENOUGH_PRIVILEGE);
    return false;
}

function perms_user($user_id, $perm) {

    global $link;
    if(!isset($link)) $link = open_db();

    //checks if the user has sufficient accreditation level
    $query = $link->prepare("select * from company_user where accreditation_level<=? and company_user_id=?");
    $query->execute(array($perm, $user_id));

    if (!($result = $query->fetch())) return_error(NOT_ENOUGH_PRIVILEGE);
}

function has_perm($user_id, $perm){
    global $link;
    if(!isset($link)) $link = open_db();

    //checks if the user has sufficient accreditation level
    $query = $link->prepare("select * from company_user where accreditation_level<=? and company_user_id=?");
    $query->execute(array($perm, $user_id));

    return $query->fetch();
}

function check_email($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return_error(INVALID_EMAIL);

    $link = open_db();

    $query = $link->prepare("select * from company_user where email=?");
    $query->execute(array($email));

    if ($query->fetch()) return true;
    return false;
}
