<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'admin';
    $table_name = 'admin_user';
    $display_name = 'Admin';

    $conn = connectDB();

    $record_id = trim($_GET['id']);
    $save_type = trim($_GET['type']);

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
    } else {
        $record_id = '';
        $save_type = 'add';
    }

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
                <label class="form-label">User Name</label>
                <input type="text" class="form-control" name="username" value="<?= $recordObj['name'] ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Login ID</label>
                <input type="text" class="form-control" name="login_id" value="<?= $recordObj['login_id'] ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" value="" <?= ($save_type == "add")?'required':'' ?>>
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

        $("#inputForm").validate({
            rules: {
                login_id: {
                    required: true,
                    minlength: 5,
                    remote: {
                        url: "./api/checkAdminIdUnique.php",
                        type: "GET",
                        data: {
                            // login_id: function() {
                            //     return $('[name="login_id"]').val(),
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
                username: {
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