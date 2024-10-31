<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-body">
        <h1>Welcome</h1>
        <h3 class="text-center my-5">Welcome, <?= $userInfo['name'] ?></h3>
    </div>
</div>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>