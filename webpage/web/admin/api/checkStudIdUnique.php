<?php
    include_once __DIR__ . '/../template/preload.php';

    $result = true;

    $conn = connectDB();

    $student_id = trim($_GET['student_id']);
    $record_id = trim($_GET['record_id']);

    if($loginSession->check_session()) {

        if($student_id == ""){
            $result = "Input missing.";
        } else {
            $sql = "SELECT `student_id` FROM `student_user` 
                    WHERE is_delete = 0 AND student_id = :student_id";
            $query = array(
                ':student_id' => $student_id,
            );
            
            if($record_id != ''){
                $sql .= " AND id != :id";
                $query[':id'] = $record_id;
            }

            $sth = $conn->prepare($sql);
            $sth->execute($query);

            $count = $sth->rowCount();

            $result = ($count == 0)?"true":"Student ID is exist.";
        }

    } else {
        $result = 'Login required';
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);