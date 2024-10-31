<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $sql = "SELECT a.*, 'edit' AS type 
                FROM $table_name a 
                INNER JOIN `teacher_course_relation` b 
                WHERE a.id = b.course_id 
                    AND b.teacher_id = :teacher_id 
                    AND a.is_delete = 0
            UNION
            SELECT a.*, 'view' AS type 
                FROM $table_name a 
                INNER JOIN `program_course_relation` b 
                WHERE a.id = b.course_id 
                    AND a.is_delete = 0
                    AND b.program_id IN ( SELECT program_id FROM `teacher_program_relation` WHERE teacher_id = :teacher_id )
                    AND b.course_id NOT IN ( SELECT course_id FROM `teacher_course_relation` WHERE teacher_id = :teacher_id )
                GROUP BY a.id";
    $query = array(':teacher_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $record_list = $data;

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="fs-3">View <?= $display_name ?></div>
    </div>
    <div class="card-body">
        <?php if($count > 0){ ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach($record_list as $i => $v){ ?>
            <div class="col">
                <a href="./<?= $page_name ?>_detail.php?id=<?= $v['id'] ?>" class="card link-underline link-underline-opacity-0" style="height: 100%;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $v['code'] ?></h5>
                        <div class="card-text"><?= $v['name'] ?></div>
                    </div>
                    <div class="card-footer">
                    <small><?= ($v['type']=='edit')?'<i class="fa-solid fa-pen"></i> Edit<br/>':'' ?> <i class="fa-solid fa-eye"></i> Read</small>
                    </div>
                </a>
            </div>
        <?php } ?>
        </div>
        <?php } else { ?>
            No Course
        <?php } ?>
    </div>
</div>

<div class="pb-5"></div>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>