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

    echo json_encode($result);