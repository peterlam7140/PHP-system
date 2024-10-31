<?php
    include_once __DIR__ . '/../template/preload.php';

    $result = [];
    $result['ststus'] = true;
    $result['msg'] = '';

    $conn = connectDB();

    if($loginSession->check_session()) {

        $record_id = trim($_POST['recordId']);
        $tableName = trim($_POST['tableName']);

        switch($tableName){
            case 'admin_user':
                $page_name = 'admin';
                $table_name = 'admin_user';
                $display_name = 'Admin';
                break;
            case 'student_user':
                $page_name = 'student';
                $table_name = 'student_user';
                $display_name = 'Student';
                break;
            case 'teacher_user':
                $page_name = 'teacher';
                $table_name = 'teacher_user';
                $display_name = 'Teacher';
                break;
            case 'course':
                $page_name = 'course';
                $table_name = 'course';
                $display_name = 'Course';
                break;
            case 'program':
                $page_name = 'program';
                $table_name = 'program';
                $display_name = 'Program';
                break;
            case 'course_timetable':
                $page_name = 'timetable';
                $table_name = 'course_timetable';
                $display_name = 'Timetable';
                break;
            default:
                $page_name = '';
                $table_name = '';
                $display_name = '';
                break;
        }

        if($table_name != ''){

            $sql = "UPDATE ".$table_name." SET 
                is_delete = 1 
                WHERE is_delete = 0 AND id = :id";
            $query = array(
                ':id' => $record_id,
            );

            $sth = $conn->prepare($sql);
            $sth->execute($query);

            $count            = $sth->rowCount();

            if($count > 0){
                $result = [];
                $result['ststus'] = true;
                $result['msg'] = $display_name.' record delete successfully';
            } else {
                $result = [];
                $result['ststus'] = false;
                $result['msg'] = $display_name.' record delete fail';
            }

        } else {
            $result = [];
            $result['ststus'] = false;
            $result['msg'] = 'Action not required';
        }
    } else {
        $result = [];
        $result['ststus'] = false;
        $result['msg'] = 'Login required';
    }

    $_SESSION['score_website']['alert'][] = array(
        'type' => ($result['ststus'])?'success':'danger',
        'text' => $result['msg']
    );

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);