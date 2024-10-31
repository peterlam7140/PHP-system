<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $record_id = trim($_GET['id']);
    $save_type = trim($_GET['type']);

    $recordObj = [];
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

        $sql = "SELECT * FROM `program_course_relation` WHERE course_id = :id";
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

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3"><?= ucfirst($save_type) . " " . $display_name ?></div>
    </div>
    <div class="card-body">
        <form id="inputForm" class="row g-3" action="./<?= $page_name ?>_save.php" method="POST">
            <input type="hidden" class="form-control" name="record_id" value="<?= $record_id ?>">
            <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">
            <div class="col-12">
                <label class="form-label">Course Name</label>
                <input type="text" class="form-control" name="name" value="<?= $recordObj['name'] ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Course Code</label>
                <input type="text" class="form-control" name="code" value="<?= $recordObj['code'] ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Program</label>
                <select class="form-select select2" name="programCode[]" style="width: 100%;" multiple required>
                <?php foreach($programList as $i => $v) { ?>
                    <option value="<?= $v['id'] ?>" <?= (in_array($v['id'], $recordObjProgram))?'selected':'' ?>><?= $v['name'] ?> (<?= $v['code'] ?>)</option>
                <?php } ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description"><?= $recordObj['description'] ?></textarea>
            </div>
            <div class="mt-5 text-center">
                <button class="btn btn-success" type="submit">Save</button>
                <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('[name="programCode[]"]').on('change', () => {
            $("#inputForm").valid();
        })

        $("#inputForm").validate({
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
                "programCode[]": {
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