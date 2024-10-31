<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'student';
    $table_name = 'student_user';
    $display_name = 'Student';

    $conn = connectDB();

    $record_id = trim($_GET['id']);
    $save_type = 'edit';

    $recordObj = [];
    $recordObjScore = [];
    
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

    $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
            FROM `course` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.course_id 
            LEFT JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE b.student_id = :student_id 
                AND a.is_delete = 0";
    $query = array(':student_id' => $record_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $recordObjScore = $data;

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3"><?= ucfirst($save_type) . " Course" ?></div>
    </div>
    <div class="card-body">
        <form id="inputForm" action="./<?= $page_name ?>_score_save.php" method="POST">
            <input type="hidden" class="form-control" name="record_id" value="<?= $record_id ?>">
            <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">

            <?php foreach($recordObjScore as $i => $scoreRow){ ?>
                <div class="card <?= ($i > 0)?'mt-4':'' ?>">
                    <div class="card-header">
                        <h6 class="mb-0"><?= $scoreRow['code'] ?></h6>
                        <h1 class="mb-0 fs-3"><?= $scoreRow['name'] ?></h1>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Study Year</label>
                                <select class="form-select select2" name="study_year[<?= $scoreRow['id'] ?>]" required>
                                    <option value="">-- Select --</option>
                                    <?php foreach($_STUDY_YEAR as $i => $v) { ?>
                                    <option value="<?= $i ?>" <?= $scoreRow['study_year']==$i?'selected':'' ?>><?= $v ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Semester</label>
                                <select class="form-select" name="semester[<?= $scoreRow['id'] ?>]" required>
                                    <option value="">-- Select --</option>
                                    <?php foreach($_SEMESTER as $i => $v) { ?>
                                    <option value="<?= $i ?>" <?= $scoreRow['semester']==$i?'selected':'' ?>><?= $v ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Score</label>
                                <input type="number" class="form-control" name="score" value="<?= $scoreRow['score'] ?>" step="0.1" required readonly disabled>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Grade</label>
                                <input type="text" class="form-control" name="grade" value="<?= $scoreRow['grade'] ?>" required readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-5 text-center">
                <button class="btn btn-success" type="submit">Save</button>
                <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#inputForm").validate({
            rules: {
                <?php foreach($recordObjScore as $i => $scoreRow){ ?>
                "study_year[<?= $scoreRow['id'] ?>]": {
                    required: true
                },
                "semester[<?= $scoreRow['id'] ?>]": {
                    required: true
                },
                <?php } ?>
            },
            messages: {

            },

        });
    })
</script>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>