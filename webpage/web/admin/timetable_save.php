<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'timetable';
    $table_name = 'course_timetable';
    $display_name = 'Course Timetable';

    $conn = connectDB();

    $record_id = trim($_POST['record_id']);
    $save_type = trim($_POST['save_type']);

    $course_id = trim($_POST['course_id']);
    $study_year = trim($_POST['study_year']);
    $semester = trim($_POST['semester']);
    $period_start = trim($_POST['period_start']);
    $period_end = trim($_POST['period_end']);
    $attend_day = trim($_POST['attend_day']);
    $time_start = trim($_POST['time_start']);
    $time_end = trim($_POST['time_end']);

    $t = explode(':', $time_start);
    $time_start = $t[0].':'.$t[1].':00';

    $t = explode(':', $time_end);
    $time_end = $t[0].':'.$t[1].':59';

    switch($save_type){
        case 'add';
        $sql = "INSERT INTO $table_name (
                    `course_id`, `study_year`, `semester`, `period_start`, `period_end`, `attend_day`, `time_start`, `time_end`, is_active, is_delete
                ) VALUES (
                    :course_id, :study_year, :semester, :period_start, :period_end, :attend_day, :time_start, :time_end, 1, 0
                )";
        $query = array(
            ':course_id' => $course_id, 
            ':study_year' => $study_year, 
            ':semester' => $semester, 
            ':period_start' => $period_start, 
            ':period_end' => $period_end, 
            ':attend_day' => $attend_day, 
            ':time_start' => $time_start, 
            ':time_end' => $time_end
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
                course_id = :course_id, 
                study_year = :study_year, 
                semester = :semester, 
                period_start = :period_start, 
                period_end = :period_end, 
                attend_day = :attend_day, 
                time_start = :time_start, 
                time_end = :time_end
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':course_id' => $course_id, 
            ':study_year' => $study_year, 
            ':semester' => $semester, 
            ':period_start' => $period_start, 
            ':period_end' => $period_end, 
            ':attend_day' => $attend_day, 
            ':time_start' => $time_start, 
            ':time_end' => $time_end
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