<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'profile';
    $table_name = '';
    $display_name = 'Profile';

    $conn = connectDB();

    $record_id = $userInfo['id'];

    $genStudent = new GenStudentInfo();
    $genStudent->setTargetId($record_id);

    include_once PATH_TEMPLATE . 'header.php';
?>

<?= $genStudent->genHtml(); ?>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>