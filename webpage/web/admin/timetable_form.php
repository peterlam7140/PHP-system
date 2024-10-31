<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'timetable';
    $table_name = 'course_timetable';
    $display_name = 'Course Timetable';

    $conn = connectDB();

    $record_id = trim($_GET['id']);
    $save_type = trim($_GET['type']);

    $recordObj = [];

    if($save_type == 'edit'){
        $sql = "SELECT * 
        FROM $table_name a 
        INNER JOIN course b ON a.course_id = b.id 
        WHERE a.is_delete = 0 AND b.is_delete = 0 AND a.id = :id";
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
    } else {
        $record_id = '';
        $save_type = 'add';
    }

    $sql = "SELECT * FROM course WHERE is_delete = 0";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $courseList = $sth->fetchAll(PDO::FETCH_ASSOC);

    $time_start = explode(':', $recordObj['time_start']);
    $time_end = explode(':', $recordObj['time_end']);
    $time_start = (count($time_start) >= 3)?($time_start[0].':'.$time_start[1].':00'):'';
    $time_end = (count($time_end) >= 3)?($time_end[0].':'.$time_end[1].':00'):'';

    include_once PATH_TEMPLATE . 'header.php';
?>

<form id="inputForm" action="./<?= $page_name ?>_save.php" method="POST">
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="mb-0 fs-3"><?= ucfirst($save_type) . " " . $display_name ?></div>
        </div>
        <div class="card-body">
            <input type="hidden" class="form-control" name="record_id" value="<?= $record_id ?>">
            <input type="hidden" class="form-control" name="save_type" value="<?= $save_type ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Course</label>
                    <select class="form-select select2" name="course_id">
                        <option value="">-- Select --</option>
                        <?php foreach($courseList as $i => $v) { ?>
                            <option value="<?= $v['id'] ?>" <?= ($v['id'] == $recordObj['course_id'])?'selected':'' ?>><?= $v['name'] ?> (<?= $v['code'] ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Study Year</label>
                    <select class="form-select select2" name="study_year" required>
                        <option value="">-- Select --</option>
                        <?php foreach($_STUDY_YEAR as $i => $v) { ?>
                        <option value="<?= $i ?>" <?= $recordObj['study_year']==$i?'selected':'' ?>><?= $v ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Semester</label>
                    <select class="form-select" name="semester" required>
                        <option value="">-- Select --</option>
                        <?php foreach($_SEMESTER as $i => $v) { ?>
                        <option value="<?= $i ?>" <?= $recordObj['semester']==$i?'selected':'' ?>><?= $v ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Period Start</label>
                    <input type="date" class="form-control" name="period_start" value="<?= $recordObj['period_start'] ?>" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Period End</label>
                    <input type="date" class="form-control" name="period_end" value="<?= $recordObj['period_end'] ?>" required>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="mb-0 fs-3">Attend Information</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Attend Day</label>
                    <select class="form-select" name="attend_day" required>
                        <option value="">-- Select --</option>
                        <?php foreach($_WEEK as $i => $v) { ?>
                        <option value="<?= $i ?>" <?= $recordObj['attend_day']==$i?'selected':'' ?>><?= $v ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="time" class="form-control" name="time_start" value="<?= $time_start ?>" required>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="time" class="form-control" name="time_end" value="<?= $time_end ?>" required>
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-success" type="submit">Save</button>
                    <a href="./<?= $page_name ?>_list.php" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {

        $.validator.addMethod("orderPeriod", function(value, element) {
            let start = Date.parse($('[name="period_start"]').val() + ' 00:00:00')
            let end = Date.parse($('[name="period_end"]').val() + ' 00:00:00')
            return this.optional(element) || start <= end;
        }, jQuery.validator.format("Period Start must lass than Period End"));

        $.validator.addMethod("orderTime", function(value, element) {
            let start = Date.parse('01 Jan 1970 ' + $('[name="time_start"]').val())
            let end = Date.parse('01 Jan 1970 ' + $('[name="time_end"]').val())
            return this.optional(element) || start < end;
        }, jQuery.validator.format("Start Time must lass than End Time"));
        
        $('[name="period_start"], [name="period_end"], [name="time_start"], [name="time_end"]').on('change', () => {
            $("#inputForm").valid();
        })

        $("#inputForm").validate({
            rules: {
                course_id: {
                    required: true,
                },
                study_year: {
                    required: true,
                },
                semester: {
                    required: true,
                },
                period_start: {
                    required: true,
                    orderPeriod: true,
                },
                period_end: {
                    required: true,
                    orderPeriod: true,
                },
                attend_day: {
                    required: true,
                },
                time_start: {
                    required: true,
                    orderTime: true,
                },
                time_end: {
                    required: true,
                    orderTime: true,
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