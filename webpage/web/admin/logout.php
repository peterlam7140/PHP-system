<?php
    include_once __DIR__ . '/template/preload.php';

    action_logout();

    $_SESSION['score_website']['alert'][] = array(
        'type' => 'success',
        'text' => 'Has been Logout.'
    );

    header('Location: index.php');
?>