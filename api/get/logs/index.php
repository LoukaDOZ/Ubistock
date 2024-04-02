<?php

    include '../../includes/helper.php';

    isPostSet(['limit', 'offset']);

    auth_user();

    $id = get_company_of($_POST['company_user_id']);

    $link = open_db();

    //gets company name
    $query = $link->prepare("select * from company_log where company_user_id in (select company_user_id from company_user where company_id=:id) order by date desc limit :limit offset :offset");
    $query->bindValue(':limit', intval($_POST['limit']), PDO::PARAM_INT);
    $query->bindValue(':offset', intval($_POST['offset']), PDO::PARAM_INT); 
    $query->bindValue(':id', $id, PDO::PARAM_STR); 
    $query->execute();

    //returns all to the client
    echo json_encode(

        $query->fetchAll(PDO::FETCH_ASSOC)

        , JSON_FORCE_OBJECT);