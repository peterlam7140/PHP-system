<?php
    session_start();
    include_once __DIR__ . "/../../../include/config.php";

    include_once PATH_LIBS . "Paginator.php";
    include_once PATH_CLASS . 'GenUserInfo.php';
    include_once PATH_CLASS . 'LoginSessionStudent.php';
    include_once PATH_INCLUDE . "var.inc.php";
    include_once PATH_INCLUDE . "sessionFunc.php";
    include_once PATH_INCLUDE . "func.php";
    include_once PATH_INCLUDE . "db.php";

    $loginSession = new LoginSessionStudent();

    DEFINE('PATH_TEMPLATE', __DIR__ . "/");
    DEFINE('CURR_SCOPE', 'admin');
    DEFINE('CURR_PAGE', $_curr_page);

    $userInfo = getSessionUserInfo();