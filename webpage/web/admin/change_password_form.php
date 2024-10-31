<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'change_password';
    $table_name = '';
    $display_name = 'Change Password';

    $conn = connectDB();

    include_once PATH_TEMPLATE . 'header.php';
?>


<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3"><?= ucfirst($save_type) . " " . $display_name ?></div>
    </div>
    <div class="card-body">
        <form id="inputForm" class="row g-3" action="./<?= $page_name ?>_save.php" method="POST">
            <div class="col-12">
                <label class="form-label">Current Password</label>
                <input type="password" class="form-control" name="current_password" value="" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" name="new_password" value="" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Conform Password</label>
                <input type="password" class="form-control" name="conform_password" value="" required>
            </div>
            <div class="mt-5 text-center">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('[name="password"], [name="conform_password"]').on('change', () => {
            $("#inputForm").valid();
        })

        $("#inputForm").validate({
            rules: {
                current_password: {
                    required: true,
                    minlength: 6
                },
                password: {
                    required: true,
                    minlength: 6
                    equalNewPwd: true,
                },
                conform_password: {
                    required: true,
                    minlength: 6
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