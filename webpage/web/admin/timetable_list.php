<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'timetable';
    $table_name = 'course_timetable';
    $display_name = 'Course Timetable';

    $conn = connectDB();

    $sql = "SELECT a.*, b.code 
            FROM $table_name a 
            INNER JOIN course b ON a.course_id = b.id
            WHERE a.is_delete = 0 AND b.is_delete = 0";
    $query = array();

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
        <div class="fs-3">View <?= $display_name ?></div>
    </div>
    <div class="card-body">
        <table class="table gridTableRow">
            <thead>
                <tr>
                    <th scope="col">Course Code</th>
                    <th scope="col">Study Year</th>
                    <th scope="col">semester</th>
                    <th scope="col">Period</th>
                    <th scope="col">Addend Day</th>
                    <th scope="col">Addend Time</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $i => $v){
                ?>
                <tr>
                    <td><label>Course code</label><?= $v['code'] ?></td>
                    <td><label>Study Year</label><?= get_study_year_name($v['study_year']) ?></td>
                    <td><label>Semester</label><?= $v['semester'] ?></td>
                    <td><label>Period</label><?= $v['period_start'] . ' - ' . $v['period_end'] ?></td>
                    <td><label>Addend Day</label><?= get_week_name($v['attend_day']) ?></td>
                    <td><label>Addend DateTime</label><?= $v['time_start'] . ' - ' . $v['time_end'] ?></td>
                    <td>
                    <div class="btn-group">
                        <a href="./<?= $page_name ?>_form.php?type=edit&id=<?= $v['id'] ?>" class="btn btn-primary">Edit</a>
                        <div onClick="deleteRecord('<?= $table_name ?>', <?= $v['id'] ?>)" class="btn btn-danger">Delete</div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<div class="pb-5"></div>

<?php gen_paginator_ele($paginator); ?>

<div class="mt-5 text-center">
    <a href="./<?= $page_name ?>_form.php?type=add" class="btn btn-primary">Add</a>
    <a href="./welcome.php" class="btn btn-danger">Back</a>
</div>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>