<?php
    session_start();
    include_once __DIR__ . "/../../../include/config.php";

    include_once PATH_LIBS . "Paginator.php";
    include_once PATH_CLASS . 'gridTable.php';
    include_once PATH_CLASS . 'LoginSessionAdmin.php';
    include_once PATH_INCLUDE . "var.inc.php";
    include_once PATH_INCLUDE . "sessionFunc.php";
    include_once PATH_INCLUDE . "func.php";
    include_once PATH_INCLUDE . "db.php";

    $loginSession = new LoginSessionAdmin();

    DEFINE('PATH_TEMPLATE', __DIR__ . "/");
    DEFINE('CURR_SCOPE', 'admin');
    DEFINE('CURR_PAGE', $_curr_page);

    $userInfo = getSessionUserInfo();