<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'student';
    $table_name = 'student_user';
    $display_name = 'Student';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = 'edit';

    $study_year = $_POST['study_year'];
    $semester = $_POST['semester'];

    $sql = "SELECT * FROM $table_name WHERE is_delete = 0 AND id = :id";
    $query = array(':id' => $record_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    if($count == 0){
        $_SESSION['score_website']['alert'][] = array(
            'type' => 'danger',
            'text' => $display_name . ' not found.'
        );
        header('Location: '.$page_name.'_list.php');
    }
    $recordObj = $data[0];

    switch($save_type){
        case 'add';
        break;
        case 'edit';

        foreach($study_year as $idx => $val) {
            $sql = "INSERT INTO `course_score` 
                    (course_id, student_id, study_year, semester) 
                    VALUES (:course_id, :stud_id, :study_year, :semester) 
                    ON DUPLICATE KEY 
                    UPDATE study_year = :study_year, semester = :semester";
            $query = array(
                ':course_id' => trim($idx),
                ':stud_id' => $record_id,
                ':study_year' => trim($val),
                ':semester' => $semester[$idx]
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