<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'teacher';
    $table_name = 'teacher_user';
    $display_name = 'Teacher';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $username = trim($_POST['username']);
    $login_id = trim($_POST['login_id']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $programCode = $_POST['programCode'];
    $courseCode = $_POST['courseCode'];

    switch($save_type){
        case 'add';

        $sql = "INSERT INTO $table_name (
                    name, login_id, password, role, 
                    dob, gender, phone, email, 
                    is_active, is_delete
                ) VALUES (
                    :name, :login_id, :password, :role, 
                    :dob, :gender, :phone, :email, 
                    1, 0
                )";
        $query = array(
            ':name' => $username,
            ':login_id' => $login_id,
            ':password' => md5($password),
            ':role' => $role,
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
                login_id = :login_id, role = :role, 
                dob = :dob, gender = :gender, phone = :phone, email = :email 
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':name' => $username,
            ':login_id' => $login_id,
            ':role' => $role,
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

        $sql = "DELETE FROM `teacher_course_relation` WHERE teacher_id = :teacher_id";
        $query = array(
            ':teacher_id' => $record_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        foreach($courseCode as $i => $code_id) {
            $sql = "INSERT INTO `teacher_course_relation` (
                        teacher_id, course_id
                    ) VALUES (
                        :teacher_id, :course_id
                    )";
            $query = array(
                ':teacher_id' => $record_id,
                ':course_id' => $code_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }

    }

    function update_program_relation($record_id, $programCode){
        global $conn;

        $sql = "DELETE FROM `teacher_program_relation` WHERE teacher_id = :teacher_id";
        $query = array(
            ':teacher_id' => $record_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        foreach($programCode as $i => $code_id) {
            $sql = "INSERT INTO `teacher_program_relation` (
                        teacher_id, program_id
                    ) VALUES (
                        :teacher_id, :program_id
                    )";
            $query = array(
                ':teacher_id' => $record_id,
                ':program_id' => $code_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }
    }