<?php

    include '../../includes/helper.php';

    isPostSet(['limit', 'offset']);

    auth_user();

    $id = get_company_of($_POST['company_user_id']);

    $link = open_db();

    //gets company name
    $query = $link->prepare("select * from company_log where company_user_id in (select company_user_id from company_user where company_id=?) order by date desc limit ? offset ?");
    $query->execute(array($id, $_POST['limit'], $_POST['offset']));

    //returns all to the client
    echo json_encode(

        $query->fetchAll(PDO::FETCH_ASSOC)

        , JSON_FORCE_OBJECT);