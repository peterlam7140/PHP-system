<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'program';
    $table_name = 'program';
    $display_name = 'Program';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $name = trim($_POST['name']);
    $code = trim($_POST['code']);

    $description = $_POST['description'];

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


        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record update successfully'
        );
        break;
    }

    header('Location: '.$page_name.'_list.php');