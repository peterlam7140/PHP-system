<?php
    include_once __DIR__ . '/../template/preload.php';

    $result = [];
    $result['ststus'] = true;
    $result['msg'] = '';
    $result['data'] = [];

    $conn = connectDB();

    if($loginSession->check_session()) {

        $year = trim($_GET['year']);

        $sql = "SELECT a.id, a.code, a.name, c.study_year, c.semester, c.score, c.grade 
                FROM `course` a 
                INNER JOIN `student_course_relation` b 
                    ON a.id = b.course_id 
                LEFT JOIN `course_score` c 
                    ON c.student_id = b.student_id 
                        AND c.course_id = b.course_id
                WHERE b.student_id = :student_id 
                    AND a.is_delete = 0";
        $query = array(':student_id' => $loginSession->getSessionUserId());

        if($year != ''){
            $sql .= " AND c.study_year = :study_year ";
            $query[':study_year'] = $year;
        }

        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $course_list = $data;

        $result['data'] = $course_list;

        // $barChat_label = array_column($course_list, 'name');
        // $barChat_value = array_column($course_list, 'score');

        // $result['data']['label'] = $barChat_label;
        // $result['data']['value'] = $barChat_value;

    } else {
        $result = [];
        $result['ststus'] = false;
        $result['msg'] = 'Login required';
    }


    echo json_encode($result);