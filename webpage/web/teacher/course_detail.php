<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $record_id = trim($_GET['id']);

    $sql = "SELECT a.*, 'edit' AS type 
                FROM $table_name a 
                INNER JOIN `teacher_course_relation` b 
                WHERE a.id = b.course_id 
                    AND b.teacher_id = :teacher_id 
                    AND a.is_delete = 0
                    AND a.id = :id
            UNION
            SELECT a.*, 'view' AS type 
                FROM $table_name a 
                INNER JOIN `program_course_relation` b 
                WHERE a.id = b.course_id 
                    AND a.is_delete = 0
                    AND b.program_id IN ( SELECT program_id FROM `teacher_program_relation` WHERE teacher_id = :teacher_id )
                    AND b.course_id NOT IN ( SELECT course_id FROM `teacher_course_relation` WHERE teacher_id = :teacher_id )
                    AND a.id = :id
                GROUP BY a.id";
    $query = array(':teacher_id' => $loginSession->getSessionUserId(), ':id' => $record_id);

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

    $genTeacher = new GenCourseInfo();
    $genTeacher->setTargetId($record_id);

    $sql = "SELECT a.* 
            FROM `teacher_user` a 
            INNER JOIN `teacher_course_relation` b 
                ON a.id = b.teacher_id 
            WHERE a.is_delete = 0
                AND b.course_id = :course_id GROUP BY a.id";
    $query = array(':course_id' => $record_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $teacher_list = $data;

    $sql = "SELECT a.* 
            FROM `program` a 
            INNER JOIN `program_course_relation` b 
                ON a.id = b.program_id 
            WHERE b.course_id = :course_id 
                AND a.is_delete = 0";
    $query = array(':course_id' => $recordObj['id']);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $program_list = $data;

    // $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
    //         FROM `student_user` a 
    //         INNER JOIN `student_course_relation` b 
    //             ON a.id = b.student_id 
    //         INNER JOIN `course_score` c 
    //             ON c.student_id = b.student_id 
    //                 AND c.course_id = b.course_id
    //         WHERE a.is_delete = 0
    //             AND b.course_id = :course_id";
    // $query = array(':course_id' => $record_id);

    // $sth = $conn->prepare($sql);
    // $sth->execute($query);

    // $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    // $count = $sth->rowCount();

    // $record_list = $data;

    // $barChat_label = array_column($record_list, 'student_id');
    // $barChat_value = array_column($record_list, 'score');
    
    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3">Action</div>
    </div>
    <div class="card-body text-center">
        <a href="./<?= $page_name ?>_student_list.php?course_id=<?= $record_id ?>" class="btn btn-primary">View Score</a>
        <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
    </div>
</div>

<div class="pb-5"></div>

<?= $genTeacher->genHtml(); ?>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>