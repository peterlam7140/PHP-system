<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'teacher';
    $table_name = 'teacher_user';
    $display_name = 'Teacher';

    $conn = connectDB();

    $sql = "SELECT * FROM $table_name WHERE is_delete = 0";
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
                    <th scope="col">Login ID</th>
                    <th scope="col">Teacher Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $i => $v){
                        $roleStr = '';
                        switch($v['role']) {
                            case "program_leader": $roleStr = 'Program Leader'; break;
                            case "teacher": $roleStr = 'Teacher'; break;
                            default: $roleStr = $v['role'];
                        }
                ?>
                <tr>
                    <td><label>Login ID</label><?= $v['login_id'] ?></td>
                    <td><label>Teacher Name</label><?= $v['name'] ?></td>
                    <td><label>Role</label><?= $roleStr ?></td>
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