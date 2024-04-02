<?php

    function open_db(){
        $db_config = parse_ini_file("config.ini", true)['database'];
        try {
            $link = null;
            if ($db_config['need_auth']){
                $dsn = $db_config['driver'].":host=".$db_config['host'].";dbname=".$db_config['schema'];
                $link = new PDO($dsn, $db_config['username'], $db_config['password']);
            } else {
                $link = new PDO($db_config['driver'].":".realpath(dirname(__FILE__)).'/'.$db_config['path']);
                if ($db_config['driver']==="sqlite") $link->sqliteCreateFunction('regexp', 'preg_match', 2);
            }

            if ($link === null) return_error(DATABASE_CONNECTION);

            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;

        } catch (PDOException $e){
            return_error(DATABASE_CONNECTION);
        }

        return false;
    }