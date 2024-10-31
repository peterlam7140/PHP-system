<?php
    include_once __DIR__ . '/../template/preload.php';

    $result = true;

    $conn = connectDB();

    $login_id = trim($_GET['login_id']);
    $record_id = trim($_GET['record_id']);

    if($loginSession->check_session()) {

        if($login_id == ""){
            $result = "Input missing.";
        } else {
            $sql = "SELECT `login_id` FROM `teacher_user` 
                    WHERE is_delete = 0 AND login_id = :login_id";
            $query = array(
                ':login_id' => $login_id,
            );
            
            if($record_id != ''){
                $sql .= " AND id != :id";
                $query[':id'] = $record_id;
            }

            $sth = $conn->prepare($sql);
            $sth->execute($query);

            $count = $sth->rowCount();

            $result = ($count == 0)?"true":"Login ID is exist.";
        }

    } else {
        $result = 'Login required';
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);