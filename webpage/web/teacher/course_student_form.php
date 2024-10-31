<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_GET['course_id']);
    $stud_id = trim($_GET['stud_id']);
    $save_type = 'edit';

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

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3"><?= ucfirst($save_type) . " " . $display_name ?></div>
    </div>
    <div class="card-body">
        <form id="inputForm" class="row g-3" action="./<?= $page_name ?>_student_save.php" method="POST">
            <input type="hidden" class="form-control" name="course_id" value="<?= $course_id ?>">
            <input type="hidden" class="form-control" name="stud_id" value="<?= $stud_id ?>">
            <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">
            <div>
                <h6 class="mb-0"><?= $recordObj['code'] ?></h6>
                <h1 class="mb-0 fs-3"><?= $recordObj['name'] ?></h1>
                <div class="pb-3"></div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Student ID</label>
                <input type="text" class="form-control" name="student_id" value="<?= $studentObj['student_id'] ?>" required readonly disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" name="name" value="<?= $studentObj['name'] ?>" required readonly disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Study Year</label>
                <input type="text" class="form-control" name="study_year" value="<?= get_study_year_name($studentObj['study_year']) ?>" required readonly disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Semester</label>
                <input type="text" class="form-control" name="semester" value="<?= $studentObj['semester'] ?>" required readonly disabled>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Score</label>
                <input type="number" class="form-control" name="score" value="<?= $studentObj['score'] ?>" step="0.1">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Grade</label>
                <input type="text" class="form-control" name="grade" value="<?= $studentObj['grade'] ?>" required readonly disabled>
            </div>
            <div class="mt-5 text-center">
                <button class="btn btn-success" type="submit">Save</button>
                <a href="./<?= $page_name ?>_student_list.php?course_id=<?= $course_id ?>&study_year=<?= $studentObj['study_year'] ?>" class="btn btn-danger">Back</a>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#inputForm").validate({
            rules: {
                score: {
                    min: 0,
                    max: 100,
                },
            },
            messages: {

            },

        });
    })
</script>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>