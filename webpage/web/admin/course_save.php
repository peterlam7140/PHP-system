<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $name = trim($_POST['name']);
    $code = trim($_POST['code']);

    $description = $_POST['description'];

    $programCode = $_POST['programCode'];

    switch($save_type){
        case 'add';

        $sql = "INSERT INTO $table_name (
                    name, code, description, is_active, is_delete
                ) VALUES (
                    :name, :code, :description, 1, 0
                )";
        $query = array(
            ':name' => $name,
            ':code' => $code,
            ':description' => $description,
        );

        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count                     = $sth->rowCount();
        $last_insert_id            = $conn->lastInsertId();

        update_program_relation($last_insert_id, $programCode);

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record add successfully'
        );
        break;
        case 'edit';

        $sql = "UPDATE $table_name SET 
                name = :name, 
                code = :code, 
                description = :description 
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':name' => $name,
            ':code' => $code,
            ':description' => $description,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $count            = $sth->rowCount();

        update_program_relation($record_id, $programCode);

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record update successfully'
        );
        break;
    }

    header('Location: '.$page_name.'_list.php');

    function update_program_relation($record_id, $programCode){
        global $conn;

        $sql = "DELETE FROM `program_course_relation` WHERE course_id = :course_id";
        $query = array(
            ':course_id' => $record_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        foreach($programCode as $i => $code_id) {
            $sql = "INSERT INTO `program_course_relation` (
                        course_id, program_id
                    ) VALUES (
                        :course_id, :program_id
                    )";
            $query = array(
                ':course_id' => $record_id,
                ':program_id' => $code_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);
        }
    }