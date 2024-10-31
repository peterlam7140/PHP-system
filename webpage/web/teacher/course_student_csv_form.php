<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_GET['course_id']);
    $study_year = trim($_GET['study_year']);
    $save_type = 'import';

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

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0"><?= $recordObj['code'] ?></h6>
        <h1 class="mb-0 fs-3"><?= $recordObj['name'] ?></h1>
    </div>
    <div class="card-body">
        <a href="./<?= $page_name ?>_student_list.php?course_id=<?= $course_id ?>&study_year=<?= $study_year ?>" class="btn btn-danger">Back</a>
    </div>
</div>

<div class="pb-5"></div>

<div class="card shadow-sm">
    <div class="card-body">
        <h1 class="mb-3"><?= ucfirst($save_type) . " " . $display_name ?></h1>
        <form id="inputForm" class="row g-3" action="./<?= $page_name ?>_student_csv_import.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="course_id" value="<?= $course_id ?>">
            <input type="hidden" class="form-control" name="study_year" value="<?= $study_year ?>">
            <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">
            <div class="col-12">
                <label class="form-label">Study Year</label>
                <div class="fs-3"><?= get_study_year_name($study_year) ?></div>
            </div>
            <div class="col-12">
                <label class="form-label">CSV File</label>
                <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                <br/>
            </div>
            <div class="col-12">
                <label class="form-label">Template</label>
                <div><a href="<?= URL_FILES ?>course-score-template.csv" class="btn btn-primary" download>Download</a></div>
            </div>
            <div class="mt-5 text-center">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#inputForm").validate({
            rules: {
                csv_file: {
                    required: true,
                    accept: "text/csv"
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