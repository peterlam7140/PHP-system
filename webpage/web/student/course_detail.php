<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $record_id = trim($_GET['id']);

    $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
            FROM `course` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.course_id 
            LEFT JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE b.student_id = :student_id 
                AND a.is_delete = 0
                AND c.course_id = :course_id";
    $query = array(':student_id' => $loginSession->getSessionUserId(), ':course_id' => $record_id);

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
                AND b.course_id = :course_id";
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

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3">Student Course</div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Study Year</label>
                <div class="fs-3"><?= get_study_year_name($recordObj['study_year']) ?></div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Semester</label>
                <div class="fs-3"><?= $recordObj['semester'] ?></div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Score</label>
                <div class="fs-3"><?= $recordObj['score'] ?></div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Grade</label>
                <div class="fs-3"><?= $recordObj['grade'] ?></div>
            </div>
        </div>
        <div class="pb-5"></div>
        <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
    </div>
</div>


<div class="pb-5"></div>

<?= $genTeacher->genHtml(); ?>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>