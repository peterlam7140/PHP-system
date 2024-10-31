<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);

    // ini_set('session.gc_maxlifetime', 1800);
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    // ini_set("session.use_trans_sid", false);

    date_default_timezone_set('Asia/Hong_Kong');
    mb_internal_encoding('UTF-8');
    mb_regex_encoding(mb_internal_encoding());

    DEFINE('URL_IMG', '/img/');
    DEFINE('URL_CSS', '/css/');
    DEFINE('URL_JS', '/js/');
    DEFINE('URL_LIBS', '/libs/');
    DEFINE('URL_FILES', '/files/');

    DEFINE('URL_SCOPE_ADMIN', '/web/admin/');
    DEFINE('URL_SCOPE_TEACHER', '/web/teacher/');
    DEFINE('URL_SCOPE_STUDENT', '/web/student/');

    DEFINE('PATH_INCLUDE', __DIR__ . '/');
    DEFINE('PATH_CLASS', __DIR__ . '/../class/');
    DEFINE('PATH_LIBS', __DIR__ . '/../libs/');

    DEFINE('WEBSITE_NAME', 'Academic Record Management System');

    DEFINE('DB_HOST', ($_ENV["DB_HOST"] != "")?$_ENV["DB_HOST"]:"localhost");
    DEFINE('DB_USER', 'root');
    DEFINE('DB_PASSWORD', 'root');
    DEFINE('DB_NAME', 'seproject');