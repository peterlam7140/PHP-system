<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_GET['course_id']);
    $study_year = trim($_GET['study_year']);

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

    $sql = "SELECT a.study_year 
            FROM `course_score` a 
            WHERE a.course_id = :course_id 
            GROUP BY a.study_year
            ORDER BY a.study_year DESC";
    $query = array(':course_id' => $course_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $studyYearList = array_column($data, 'study_year');

    if($study_year == ''){
        $study_year = $studyYearList[0];
    }

    $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
            FROM `student_user` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.student_id 
            INNER JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE a.is_delete = 0
                AND b.course_id = :course_id
                AND c.study_year = :study_year";
    $query = array(':course_id' => $course_id, ':study_year' => $study_year);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $paginator_param = paginator_param($count);
    $sql .= " Limit ".$paginator_param["offset"].", ".$paginator_param["rowsPerPage"];
    $sth = $conn->prepare($sql);
    $sth->execute($query);
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $urlPattern = $_SERVER['PHP_SELF'] . '?page=(:num)' . $urlQuery;
    $paginator  = new Paginator($paginator_param["totalItems"], $paginator_param["rowsPerPage"], $paginator_param["page"], $urlPattern);

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0"><?= $recordObj['code'] ?></h6>
        <h1 class="mb-0 fs-3"><?= $recordObj['name'] ?></h1>
    </div>
    <div class="card-body">
        <form action="">
            <input type="hidden" class="form-control" name="course_id" value="<?= $course_id ?>">
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Study Year <span class="text-danger">*</span></label>
                    <select class="form-select" name="study_year" required>
                        <?php foreach($studyYearList as $i => $v){ ?>
                            <option value="<?= $v ?>" <?= ($v == $study_year)?'selected':'' ?>><?= get_study_year_name($v) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <input class="btn btn-success" type="submit" value="Submit">
                <a href="./<?= $page_name ?>_detail.php?id=<?= $course_id ?>" class="btn btn-danger">Back</a>
            </div>
        </form>
    </div>
</div>

<div class="pb-5"></div>

<?php if(count($studyYearList) == 0) { ?>
    <div class="card shadow-sm">
        <div class="card-body py-5">
            <h2 class="text-center">No any year has student study</h2>
        </div>
    </div>
<?php } else { ?>

<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0">Study Year : </h6>
        <h1 class="mb-0 fs-3"><?= get_study_year_name($study_year) ?></h1>
    </div>
    <div class="card-body">
        <?php if($recordObj['type'] == 'edit') { ?>
        <a href="./<?= $page_name ?>_student_csv_form.php?course_id=<?= $course_id ?>&study_year=<?= $study_year ?>" class="btn btn-primary">Upload CSV</a>
        <?php } ?>
        <a href="./<?= $page_name ?>_student_csv_export.php?course_id=<?= $course_id ?>&study_year=<?= $study_year ?>" class="btn btn-primary" target="_blank">Download CSV</a>
        <a href="./<?= $page_name ?>_student_detail.php?course_id=<?= $course_id ?>&study_year=<?= $study_year ?>" class="btn btn-primary">View Chart</a>
    </div>
</div>

<div class="pb-5"></div>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3">View Student</div>
    </div>
    <div class="card-body">
        <table class="table gridTableRow">
            <thead>
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Study Year</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Score</th>
                    <th scope="col">Grade</th>
                    <?php if($recordObj['type'] == 'edit') { ?>
                    <th scope="col">Action</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $i => $v){
                ?>
                <tr>
                    <td><label>Student ID</label><?= $v['student_id'] ?></td>
                    <td><label>Student Name</label><?= $v['name'] ?></td>
                    <td><label>Study Year</label><?= get_study_year_name($v['study_year']) ?></td>
                    <td><label>Semester</label><?= $v['semester'] ?></td>
                    <td><label>Score</label><?= ($v['score']!='')?$v['score']:'-' ?></td>
                    <td><label>Grade</label><?= ($v['grade']!='')?$v['grade']:'-' ?></td>
                    <?php if($recordObj['type'] == 'edit') { ?>
                    <td>
                    <div class="btn-group">
                        <a href="./student_info.php?id=<?= $v['id'] ?>" target="_blank" class="btn btn-secondary">View</a>
                        <a href="./<?= $page_name ?>_student_form.php?course_id=<?= $course_id ?>&stud_id=<?= $v['id'] ?>" class="btn btn-primary">Edit</a>
                        </div>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<div class="pb-5"></div>

<?php gen_paginator_ele($paginator); ?>

<div class="mt-5 text-center">
</div>
<?php } ?>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>