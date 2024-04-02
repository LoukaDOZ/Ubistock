<?php
    define("SUCCESS_MSG", "success");

    define("LOWEST_RANK", 4);

    define("TOKEN_VALIDITY", 60*60*2);

    define("PERMS",array(
        "rename_company" => 0,
        "belongs_to_all_groups" => 1,
        "create_user" => 1,
        "delete_user" => 1,
        "edit_user" => 1,
        "create_group" => 1,
        "delete_group" => 1,
        "toggle_user_group" => 1,
        "toggle_storage_group" => 1,
        "create_storage" => 2,
        "create_resource" => 2,
        "create_resource_minimum" =>2,
        "delete_resource_minimum" =>2,
        "create_storage_minimum" =>2,
        "delete_storage_minimum" =>2,
        "rename_storage" => 2,
        "rename_resource" => 2,
        "rename_group" => 2,
        "delete_storage" => 2,
        "delete_resource" => 2,
        "move_storage" => 2,
        "move_resource" => 2,
        "see_res_minimum" => 3,
        "see_storage_minimum" =>3,
        "edit_qty" => 3,
        "see_res" => 4,
        "see_res_qty" => 4,
        "see_res_name" => 4
    ));

    define("PRIORITY", array(
        "TOGGLE_STORAGE_GROUP" => 7,
        "TOGGLE_USER_GROUP" => 5,
        "CREATE_GROUP" => 4,
        "CREATE_STORAGE" => 5,
        "DELETE_STORAGE" => 4,
        "DELETE_GROUP" => 4,
        "CREATE_RESOURCE" => 6,
        "DELETE_RESOURCE" => 6,
        "CREATE_RESOURCE_MINIMUM" => 5,
        "DELETE_RESOURCE_MINIMUM" => 5,
        "CREATE_STORAGE_MINIMUM" => 4,
        "DELETE_STORAGE_MINIMUM" => 4,
        "CREATE_USER" => 2,
        "DELETE_USER" => 2,
        "MOVE_RESOURCE" => 6,
        "MOVE_STORAGE" => 6,
        "QTY_EDIT" => 10,
        "RENAME_COMPANY" => 1,
        "RENAME_STORAGE" => 5,
        "RENAME_RESOURCE" => 5,
        "RENAME_GROUP" => 4,
        "EDIT_PROFILE" => 3
    ));

    define("CREATE_STORAGE", 1);
    define("CREATE_RESOURCE", 2);
    define("CREATE_GROUP", 3);
    define("CREATE_USER", 4);

    define("DELETE_STORAGE", 5);
    define("DELETE_RESOURCE", 6);
    define("DELETE_GROUP", 7);
    define("DELETE_USER", 8);

    define("RENAME_STORAGE", 9);
    define("RENAME_RESOURCE", 10);

    define("MOVE_STORAGE", 11);
    define("MOVE_RESOURCE", 12);

    define("TOGGLE_STORAGE_GROUP", 13);
    define("TOGGLE_USER_GROUP", 14);

    define("RENAME_COMPANY", 15);
    define("RENAME_GROUP", 16);

    define("CREATE_STORAGE_MINIMUM", 17);
    define("CREATE_RESOURCE_MINIMUM", 18);
    define("DELETE_STORAGE_MINIMUM", 19);
    define("DELETE_RESOURCE_MINIMUM", 20);

    define("EDIT_PROFILE", 21);
    define("QTY_EDIT", 22);



