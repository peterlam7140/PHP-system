<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'profile';
    $table_name = '';
    $display_name = 'Profile';

    $conn = connectDB();

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3"><?= $display_name ?></div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold">User Name</label>
                <div class="fs-6"><?= $userInfo['name'] ?></div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Login ID</label>
                <div class="fs-6"><?= $userInfo['login_id'] ?></div>
            </div>
        </div>
    </div>
</div>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>