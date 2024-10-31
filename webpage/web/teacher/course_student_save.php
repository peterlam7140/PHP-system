<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_POST['course_id']);
    $stud_id = trim($_POST['stud_id']);
    $save_type = trim($_POST['save_type']);

    $score = trim($_POST['score']);
    $grade = trim($_POST['grade']);

    $sql = "SELECT a.*, 'edit' AS type 
            FROM $table_name a 
            INNER JOIN `teacher_course_relation` b 
            WHERE a.id = b.course_id 
                AND b.teacher_id = :teacher_id 
                AND a.is_delete = 0
                AND a.id = :id";
    $query = array(':teacher_id' => $loginSession->getSessionUserId(), ':id' => $course_id);

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

    $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
            FROM `student_user` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.student_id 
            INNER JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE a.is_delete = 0 
                AND b.course_id = :course_id
                AND a.id = :student_id";
    $query = array(':course_id' => $course_id, ':student_id' => $stud_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    if($count == 0){
        $_SESSION['score_website']['alert'][] = array(
            'type' => 'danger',
            'text' => 'Student not found.'
        );
        header('Location: '.$page_name.'_student_list.php?course_id=' . $course_id);
    }
    $studentObj = $data[0];

    $score = ($score!='')?floatval($score):null;
    $grade = calc_grade($score);

    switch($save_type){
        case 'add';
        break;
        case 'edit';

        $sql = "UPDATE `course_score` 
                SET score = :score,
                    grade = :grade
                WHERE course_id = :course_id 
                    AND student_id = :student_id";
        $query = array(
            ':course_id' => $course_id,
            ':student_id' => $stud_id,
            ':score' => $score,
            ':grade' => $grade
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

    header('Location: '.$page_name.'_student_list.php?course_id=' . $course_id . '&study_year=' . $studentObj['study_year']);