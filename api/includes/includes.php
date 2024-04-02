<?php
    include realpath(dirname(__FILE__))."/../db/open_db.php";
    include "errors.php";

    function isGetSet($list){
        foreach ($list as $get)
            if (!isset($_GET[$get])) return_error(MISSING_GET_ARGS);
        return true;
    }

    function isPostSet($list){
        foreach ($list as $post)
            if (!isset($_POST[$post])) return_error(MISSING_POST_ARGS);

        foreach ($_POST as $key => $value)
            $_POST[$key] = urldecode($value);

    }

    function logify($action, $qty, $target_id, $target_name, $priority){
        global $link;
        if (!isset($link)) $link = open_db();

        $id = generateRandomKey("company_log");

        $query = $link->prepare("insert into company_log(action, company_user_id, target_id, target_name, quantity, date, reason, priority, company_log_id)
            values(?, ?, ?, ?, ?, ?, ?, ?, ?);");

        $query->execute(array($action, $_POST['company_user_id'], $target_id, $target_name, $qty, date("Y-m-d H:i:s"), $_POST['reason'] ?? '', $priority, $id));
    }

    function return_error($error_const){
        header("HTTP/1.1 ".$error_const[0]." ".$error_const[1]);
        exit();
    }

    function generate_key(){
        $string = '';
        $length = 16;
        $chars = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';

        for($i = 0; $i < $length; $i++){
            $string .= $chars[rand(0, strlen($chars)-1)];
        }

        return $string;
    }

    function generateRandomKey($table){
        global $link;
        if (!isset($link)) $link = open_db();

        do {
            $table_id = generate_key();
            $query = $link->prepare("select * from ".$table." where ".$table."_id=?");
            $query->execute(array($table_id));

            $ids = $query->fetchAll()[0][$table.'_id'] ?? '';
        } while ($table_id === $ids);

        return $table_id;
    }

    function get_company_of($user_id){
        global $link;
        if (!isset($link)) $link = open_db();

        $query = $link->prepare("select company_id from company_user where company_user_id=?");
        $query->execute(array($user_id));

        if (!($result = $query->fetch())) return_error(NO_SUCH_USER);

        return $result['company_id'];
    }

    function has_storage_access($user_id, $storage_fam){
        global $link;
        if (!isset($link)) $link = open_db();

        $query = $link->prepare("select family from company_group_storage 
                                            natural join company_group_member natural join storage 
                                            where company_user_id=? and ? regexp '^' || family || '(\..+)*$'");
        $query->execute(array($user_id, $storage_fam));

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    function get_by_id($table, $id){
        global $link;
        if (!isset($link)) $link = open_db();

        $query = $link->prepare("select * from ".$table." where ".$table."_id=?");
        $query->execute(array($id));

        if(!($res = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);
        return $res;
    }

    function get_sons_of($storage_id){
         global $link;
        if (!isset($link)) $link = open_db();

         $query = $link->prepare("select * from storage where storage_id=?");
         $query->execute(array($storage_id));

         if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);

         $query = $link->prepare("select storage_id, storage_name, family from storage where root_id=? and family REGEXP ?");
         $query->execute(array($result['root_id'], '^'.$result['family'].'\.[0-9]+$'));

         return $query->fetchAll(PDO::FETCH_ASSOC);
     }

     function are_in_same_group($user_id1, $user_id2){
        $link = open_db();

        $query = $link->prepare("select * from company_user where company_user_id=? or company_user_id=?");
        $query->execute(array($user_id1, $user_id2));

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) < 2) return_error(NO_SUCH_USER);
        if ($result[0]['company_id'] !== $result[1]['company_id']) return_error(WRONG_COMPANY);

        //searches for a group that contains user 1 and user 2
        $query = $link->prepare("select * from company_group_member natural join company_user where company_user_id=? and company_group_id in 
                                                               (select company_group_id from company_group_member where company_user_id=? or accreditation_level<=?) or accreditation_level<=?");
        $query->execute(array($user_id1, $user_id2, PERMS['belongs_to_all_groups'], PERMS['belongs_to_all_groups']));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return (count($result) > 0);

     }

     function get_bros_of($storage_id){
         $link = open_db();

         $query = $link->prepare("select * from storage where storage_id=?");
         $query->execute(array($storage_id));

         if (!($result = $query->fetch(PDO::FETCH_ASSOC))) return_error(NO_SUCH_RESOURCE);

         $bros_fam = explode('.', $result['family']);
         array_pop($bros_fam);
         $bros_fam = implode('.', $bros_fam);

         $query = $link->prepare("select * from storage where root_id=? and family REGEXP ?");
         $query->execute(array($result['root_id'], '/^'.$bros_fam.'\.[0-9]$/ui'));

         return $query->fetchAll(PDO::FETCH_ASSOC);
     }

     function str_replace_first($from, $to, $content){
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }
