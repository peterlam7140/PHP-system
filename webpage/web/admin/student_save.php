<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'student';
    $table_name = 'student_user';
    $display_name = 'Student';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $username = trim($_POST['username']);
    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);

    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $programCode = $_POST['programCode'];
    $courseCode = $_POST['courseCode'];

    switch($save_type){
        case 'add';

        $sql = "INSERT INTO $table_name (
                    name, student_id, password, 
                    dob, gender, phone, email, 
                    is_active, is_delete
                ) VALUES (
                    :name, :student_id, :password, 
                    :dob, :gender, :phone, :email, 
                    1, 0
                )";
        $query = array(
            ':name' => $username,
            ':student_id' => $student_id,
            ':password' => md5($password),
            ':dob' => $dob,
            ':gender' => $gender,
            ':phone' => $phone,
            ':email' => $email,
        );

        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count                     = $sth->rowCount();
        $last_insert_id            = $conn->lastInsertId();

        update_program_relation($last_insert_id, $programCode);
        update_course_relation($last_insert_id, $courseCode);

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record add successfully'
        );
        break;
        case 'edit';

        $sql = "UPDATE $table_name SET 
                name = :name, 
                student_id = :student_id, 
                dob = :dob, gender = :gender, phone = :phone, email = :email 
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':name' => $username,
            ':student_id' => $student_id,
            ':dob' => $dob,
            ':gender' => $gender,
            ':phone' => $phone,
            ':email' => $email,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count            = $sth->rowCount();

        update_program_relation($record_id, $programCode);
        update_course_relation($record_id, $courseCode);

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

    function update_course_relation($record_id, $courseCode){
        global $conn;

        $sql = "DELETE FROM `student_course_relation` WHERE student_id = :student_id";
        $query = array(
            ':student_id' => $record_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        foreach($courseCode as $i => $code_id) {
            $sql = "INSERT INTO `student_course_relation` (
                        student_id, course_id
                    ) VALUES (
                        :student_id, :course_id
                    )";
            $query = array(
                ':student_id' => $record_id,
                ':course_id' => $code_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }

    }

    function update_program_relation($record_id, $programCode){
        global $conn;

        $sql = "DELETE FROM `student_program_relation` WHERE student_id = :student_id";
        $query = array(
            ':student_id' => $record_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        foreach($programCode as $i => $code_id) {
            $sql = "INSERT INTO `student_program_relation` (
                        student_id, program_id
                    ) VALUES (
                        :student_id, :program_id
                    )";
            $query = array(
                ':student_id' => $record_id,
                ':program_id' => $code_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }
    }