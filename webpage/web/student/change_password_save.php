<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'change_password';
    $table_name = '';
    $display_name = 'Change Password';

    $conn = connectDB();

    $record_id = $userInfo['id'];
    $save_type = 'edit';

    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $conform_password = trim($_POST['conform_password']);

    switch($save_type){
        case 'edit';

        if($new_password == null){
            $_SESSION['score_website']['alert'][] = array(
                'type' => 'danger',
                'text' => 'New Password Required'
            );
            break;
        }

        if($new_password != $conform_password){
            $_SESSION['score_website']['alert'][] = array(
                'type' => 'danger',
                'text' => 'New Password not equal to Conform Password'
            );
            break;
        }

        if(md5($current_password) != $userInfo['password']){
            $_SESSION['score_website']['alert'][] = array(
                'type' => 'danger',
                'text' => 'Current Password not valid'
            );
            break;
        }

        $sql = "UPDATE `student_user` SET 
                password = :password 
                WHERE is_delete = 0 AND id = :id";
        $query = array(
            ':id' => $record_id,
            ':password' => md5($new_password),
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $_SESSION['score_website']['alert'][] = array(
            'type' => 'success',
            'text' => $display_name.' record update successfully'
        );
        break;
    }

    header('Location: '.$page_name.'_form.php');