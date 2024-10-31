<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'student';
    $table_name = 'student_user';
    $display_name = 'Student';

    $conn = connectDB();

    $record_id = trim($_GET['id']);
    $save_type = trim($_GET['type']);

    $recordObj = [];
    $recordObjScore = [];
    $recordObjProgram = [];
    
    if($save_type == 'edit'){
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

        $sql = "SELECT * FROM `student_course_relation` WHERE student_id = :id";
        $query = array(':id' => $record_id);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $recordObjScore = $sth->fetchAll(PDO::FETCH_ASSOC);
        $recordObjScore = array_column($recordObjScore, 'course_id');

        $sql = "SELECT * FROM `student_program_relation` WHERE student_id = :id";
        $query = array(':id' => $record_id);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $recordObjProgram = $sth->fetchAll(PDO::FETCH_ASSOC);
        $recordObjProgram = array_column($recordObjProgram, 'program_id');
    } else {
        $record_id = '';
        $save_type = 'add';
    }

    $sql = "SELECT * FROM program WHERE is_delete = 0";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $programList = $sth->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM course WHERE is_delete = 0";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $courseList = $sth->fetchAll(PDO::FETCH_ASSOC);

    include_once PATH_TEMPLATE . 'header.php';
?>

<form id="inputForm" action="./<?= $page_name ?>_save.php" method="POST">
    <input type="hidden" class="form-control" name="record_id" value="<?= $record_id ?>">
    <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="mb-0 fs-3"><?= ucfirst($save_type) . " " . $display_name ?></div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Student ID</label>
                    <input type="text" class="form-control" name="student_id" value="<?= $recordObj['student_id'] ?>">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" value="">
                </div>
                <div class="col-12">
                    <label class="form-label">Program</label>
                    <select class="form-select select2" name="programCode[]" style="width: 100%;" multiple>
                    <?php foreach($programList as $i => $v) { ?>
                        <option value="<?= $v['id'] ?>" <?= (in_array($v['id'], $recordObjProgram))?'selected':'' ?>><?= $v['name'] ?> (<?= $v['code'] ?>)</option>
                    <?php } ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Course</label>
                    <select class="form-select select2" name="courseCode[]" style="width: 100%;" multiple>
                    <?php foreach($courseList as $i => $v) { ?>
                        <option value="<?= $v['id'] ?>" <?= (in_array($v['id'], $recordObjScore))?'selected':'' ?>><?= $v['name'] ?> (<?= $v['code'] ?>)</option>
                    <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-5"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="mb-0 fs-3">Information</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Student Name</label>
                    <input type="text" class="form-control" name="username" value="<?= $recordObj['name'] ?>">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Day of Birth</label>
                    <input type="date" class="form-control" name="dob" value="<?= $recordObj['dob'] ?>">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Gender</label>
                    <div class="form-check-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="gender-m" value="male" <?= (($recordObj['gender']=='male')?'checked':'') ?>>
                            <label class="form-check-label" for="gender-m">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="gender-f" value="female" <?= (($recordObj['gender']=='female')?'checked':'') ?>>
                            <label class="form-check-label" for="gender-f">Female</label>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="phone" class="form-control" name="phone" value="<?= $recordObj['phone'] ?>">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $recordObj['email'] ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <button class="btn btn-success" type="submit">Save</button>
        <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
    </div>

</form>

<script type="text/javascript">
    $(document).ready(function () {

        $('[name="courseCode[]"], [name="programCode[]"]').on('change', () => {
            $("#inputForm").valid();
        })

        $("#inputForm").validate({
            rules: {
                student_id: {
                    required: true,
                    studFormat: true,
                    remote: {
                        url: "./api/checkStudIdUnique.php",
                        type: "GET",
                        data: {
                            // student_id: function() {
                            //     return $('[name="student_id"]').val(),
                            // },
                            record_id: function() {
                                return $('[name="record_id"]').val();
                            }
                        }
                    }
                },
                "password": {
                    required: <?= ($save_type == "add")?'true':'false' ?>,
                    minlength: 6
                },
                "programCode[]": {
                    required: true
                },
                "courseCode[]": {
                    required: true
                },
                username: {
                    required: true
                },
                dob: {
                    required: true
                },
                gender: {
                    required: true
                },
                phone: {
                    required: true,
                    mobileFormat: true,
                },
                email: {
                    required: true
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