<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $sql = "SELECT a.* FROM $table_name a INNER JOIN `student_course_relation` b WHERE a.id = b.course_id AND b.student_id = :student_id AND a.is_delete = 0";
    $query = array(':student_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="fs-3">View <?= $display_name ?></div>
    </div>
    <div class="card-body">
        <?php if($count > 0){ ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach($data as $i => $v){ ?>
            <div class="col">
                <a href="./<?= $page_name ?>_detail.php?id=<?= $v['id'] ?>" class="card link-underline link-underline-opacity-0" style="height: 100%;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $v['code'] ?></h5>
                        <p class="card-text"><?= $v['name'] ?></p>
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


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>