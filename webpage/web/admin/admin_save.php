<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'admin';
    $table_name = 'admin_user';
    $display_name = 'Admin';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $username = trim($_POST['username']);
    $login_id = trim($_POST['login_id']);
    $password = trim($_POST['password']);

    switch($save_type){
        case 'add';

        $sql = "INSERT INTO $table_name (
                    name, login_id, password, is_active, is_delete
                ) VALUES (
                    :name, :login_id, :password, 1, 0
                )";
        $query = array(
            ':name' => $username,
            ':login_id' => $login_id,
            ':password' => md5($password),
        );

        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count                     = $sth->rowCount();
        $last_insert_id            = $conn->lastInsertId();

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record add successfully'
        );
        break;
        case 'edit';

        $sql = "UPDATE $table_name SET 
                name = :name, 
                login_id = :login_id 
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':name' => $username,
            ':login_id' => $login_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count            = $sth->rowCount();

        if($password != ""){
            $sql = "UPDATE $table_name SET 
                    password = :password 
                    WHERE is_delete = 0 AND id = :id";
            $query = array(
                ':id' => $record_id,
                ':password' => md5($password),
            );
        
            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record update successfully'
        );
        break;
    }

    header('Location: '.$page_name.'_list.php');