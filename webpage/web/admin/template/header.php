<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= WEBSITE_NAME ?> (Admin)</title>

        <script src="<?= URL_LIBS ?>jquery.min.js"></script>

        <link href="<?= URL_LIBS ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?= URL_LIBS ?>bootstrap/js/bootstrap.bundle.min.js"></script>

        <link href="<?= URL_LIBS ?>select2/select2.min.css" rel="stylesheet">
        <script src="<?= URL_LIBS ?>select2/select2.min.js"></script>

        <script src="<?= URL_LIBS ?>chartjs/chart.umd.min.js"></script>

        <script type="text/javascript" src="<?= URL_LIBS ?>jquery-validation/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?= URL_LIBS ?>jquery-validation/dist/additional-methods.min.js"></script>

        <link href="<?= URL_LIBS ?>fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet">

        <link href="<?= URL_CSS ?>main.css?t=<?= time() ?>" rel="stylesheet">

        <script src="<?= URL_JS ?>deleteRecord.js?t=<?= time() ?>"></script>
        <script src="<?= URL_JS ?>main.js?t=<?= time() ?>"></script>
    </head>
    <body class="bg-danger-subtle">

    <?php if(CURR_PAGE != "login") { ?>
        <header class="bg-dark shadow">
            <nav class="container">
                <div class="navbar">
                    <div class="container-fluid px-0">
                        <span class="navbar-brand mb-0">
                            <div class="header-logo">
                                <span class="badge text-bg-light"><img src="<?= URL_IMG ?>HKMU__LOGO.png" alt=""></span>
                                <div class="fs-4 text-white"><?= WEBSITE_NAME ?> <span class="badge text-bg-danger">(Admin)</span></div>
                            </div>
                        </span>
                        
                        <?php if(isLogin()){ ?>
                        <div class="navbar-mob">
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </nav>
            <div class="bg-body-secondary">
                <div class="container pt-2 pb-2">
                    <div class="text-center">Welcome, <?= $userInfo['name'] ?></div>
                </div>
            </div>
            <div class="offcanvas offcanvas-end text-bg-white" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="card shadow-sm">
                        <ul class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action" href="./welcome.php"><div class="menu-icon"><i class="fa-solid fa-house"></i> Home</div></a>
                            <a class="list-group-item list-group-item-action" href="./profile.php"><div class="menu-icon"><i class="fa-regular fa-id-badge"></i> Profile</div></a>
                            <a class="list-group-item list-group-item-action" href="./change_password_form.php"><div class="menu-icon"><i class="fa-solid fa-key"></i> Change Password</div></a>
                            <a class="list-group-item list-group-item-action" href="./admin_list.php"><div class="menu-icon"><i class="fa-solid fa-user-tie"></i> Admin</div></a>
                            <a class="list-group-item list-group-item-action" href="./student_list.php"><div class="menu-icon"><i class="fa-solid fa-person"></i> Student</div></a>
                            <a class="list-group-item list-group-item-action" href="./teacher_list.php"><div class="menu-icon"><i class="fa-solid fa-person-chalkboard"></i> Teacher</div></a>
                            <a class="list-group-item list-group-item-action" href="./program_list.php"><div class="menu-icon"><i class="fa-solid fa-school"></i> Program</div></a>
                            <a class="list-group-item list-group-item-action" href="./course_list.php"><div class="menu-icon"><i class="fa-solid fa-book"></i> Course</div></a>
                            <a class="list-group-item list-group-item-action" href="./timetable_list.php"><div class="menu-icon"><i class="fa-solid fa-calendar-day"></i> Timetable</div></a>
                            <a class="list-group-item list-group-item-action bg-danger text-white" href="./logout.php"><div class="menu-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</div></a>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <?php if(is_array($_SESSION['score_website']['alert'])){ ?>
        <div class="container mt-5"><div class="card"><div class="card-body alert-container">
        <?php foreach($_SESSION['score_website']['alert'] as $i => $v){ ?>
            <div class="alert alert-<?= $v['type'] ?>" role="alert"><?= $v['text'] ?></div>
        <?php
            }
        ?>
        </div></div></div>
        <?php unset($_SESSION['score_website']['alert']); } ?>

        <div class="container my-5">
            <div class="sidebar-container">
                <div class="sidebar-menu">
                    <div class="card shadow-sm">
                        <ul class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action" href="./welcome.php"><div class="menu-icon"><i class="fa-solid fa-house"></i> Home</div></a>
                            <a class="list-group-item list-group-item-action" href="./profile.php"><div class="menu-icon"><i class="fa-regular fa-id-badge"></i> Profile</div></a>
                            <a class="list-group-item list-group-item-action" href="./change_password_form.php"><div class="menu-icon"><i class="fa-solid fa-key"></i> Change Password</div></a>
                            <a class="list-group-item list-group-item-action" href="./admin_list.php"><div class="menu-icon"><i class="fa-solid fa-user-gear"></i> Admin</div></a>
                            <a class="list-group-item list-group-item-action" href="./student_list.php"><div class="menu-icon"><i class="fa-solid fa-user-graduate"></i> Student</div></a>
                            <a class="list-group-item list-group-item-action" href="./teacher_list.php"><div class="menu-icon"><i class="fa-solid fa-person-chalkboard"></i> Teacher</div></a>
                            <a class="list-group-item list-group-item-action" href="./program_list.php"><div class="menu-icon"><i class="fa-solid fa-school"></i> Program</div></a>
                            <a class="list-group-item list-group-item-action" href="./course_list.php"><div class="menu-icon"><i class="fa-solid fa-book"></i> Course</div></a>
                            <a class="list-group-item list-group-item-action" href="./timetable_list.php"><div class="menu-icon"><i class="fa-solid fa-calendar-day"></i> Timetable</div></a>
                            <a class="list-group-item list-group-item-action bg-danger text-white" href="./logout.php"><div class="menu-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</div></a>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-page">
    <?php } ?>