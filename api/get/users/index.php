<?php

    include '../../includes/helper.php';

    isPostSet(['limit', 'offset']);

    auth_user();

    $id = get_company_of($_POST['company_user_id']);

    $link = open_db();

    $query = $link -> prepare("select company_user_id, company_user_surname, company_user_name, email, accreditation_level
                                            from company_user where company_id=? limit ? offset ?");
    $query->execute(array($id, $_POST['limit'], $_POST['offset']));

    if (!($result = $query->fetchAll(PDO::FETCH_ASSOC))) return_error(DATABASE_CONNECTION);

    echo json_encode(

            $result

    , JSON_FORCE_OBJECT);

