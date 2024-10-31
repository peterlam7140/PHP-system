<?php
    $_curr_page = "login";

    include_once __DIR__ . '/template/preload.php';

    if(check_session()){
        header('Location: welcome.php');
    }

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="login-container">
    <div class="login-bg"><img src="<?= URL_IMG ?>hkmu_ioh.jpg" alt=""></div>
    <div class="login-panel bg-info-subtle">

        <?php if(is_array($_SESSION['score_website']['alert'])){ ?>
        <div class="container mb-5"><div class="card"><div class="card-body alert-container">
        <?php foreach($_SESSION['score_website']['alert'] as $i => $v){ ?>
            <div class="alert alert-<?= $v['type'] ?>" role="alert"><?= $v['text'] ?></div>
        <?php
            }
        ?>
        </div></div></div>
        <?php unset($_SESSION['score_website']['alert']); } ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="login-logo mb-3 text-center"><img src="<?= URL_IMG ?>HKMU__LOGO.png" alt=""></div>
                <h1 class="fs-4 mb-3 text-center"><span class="badge bg-info">Teacher</span></h1>
                <form id="inputForm" class="row g-3" action="./login_save.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Login ID</label>
                        <input type="text" class="form-control" name="login_id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mt-5">
                        <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="mt-2 text-center">
                        <a class="my-2 link-underline link-underline-opacity-0" style="color: #000; display: inline-block;">Forget Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#inputForm").validate({
            rules: {
                login_id: {
                    required: true,
                    minlength: 5
                },
                password: {
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