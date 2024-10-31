<?php
    include_once __DIR__ . '/template/preload.php';

    $login_id = trim($_POST['login_id']);
    $password = trim($_POST['password']);

    $valid = action_login($login_id, $password);
    if($valid){
        header('Location: welcome.php');
    } else {
        $_SESSION['score_website']['alert'][] = array(
            'type' => 'danger',
            'text' => 'Login fail'
        );

        header('Location: index.php');
    }
?>