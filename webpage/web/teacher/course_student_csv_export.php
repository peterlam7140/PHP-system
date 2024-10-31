<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_GET['course_id']);
    $study_year = trim($_GET['study_year']);
    $save_type = 'export';

    $sql = "SELECT a.*, 'edit' AS type 
                FROM $table_name a 
                INNER JOIN `teacher_course_relation` b 
                WHERE a.id = b.course_id 
                    AND b.teacher_id = :teacher_id 
                    AND a.is_delete = 0
                    AND a.id = :id
            UNION
            SELECT a.*, 'view' AS type 
                FROM $table_name a 
                INNER JOIN `program_course_relation` b 
                WHERE a.id = b.course_id 
                    AND a.is_delete = 0
                    AND b.program_id IN ( SELECT program_id FROM `teacher_program_relation` WHERE teacher_id = :teacher_id )
                    AND b.course_id NOT IN ( SELECT course_id FROM `teacher_course_relation` WHERE teacher_id = :teacher_id )
                    AND a.id = :id
                GROUP BY a.id";
    $query = array(':teacher_id' => $loginSession->getSessionUserId(), ':id' => $course_id);

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    if($count == 0){
        $_SESSION['score_website']['alert'][] = array(
            'type' => 'danger',
            'text' => $display_name . ' not found.'
        );
        header('Location: '.$page_name.'_list.php');
    }
    $recordObj = $data[0];

    switch($save_type){
        case 'export';

            $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
                    FROM `student_user` a 
                    INNER JOIN `student_course_relation` b 
                        ON a.id = b.student_id 
                    INNER JOIN `course_score` c 
                        ON c.student_id = b.student_id 
                            AND c.course_id = b.course_id
                    WHERE a.is_delete = 0
                        AND b.course_id = :course_id
                        AND c.study_year = :study_year";
            $query = array(':course_id' => $course_id, ':study_year' => $study_year);

            $sth = $conn->prepare($sql);
            $sth->execute($query);

            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $count = $sth->rowCount();

            $time = date('Ymd-his');

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=course-score-".$course_id."-".$study_year."-".$time.".csv");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            $fp = fopen('php://output', 'w');

            $headerArr = [
                'Student ID',
                'Student Name',
                'Study Year',
                'Semester',
                'Score',
                'Grade',
            ];
            fputcsv($fp, $headerArr);

            foreach ($data as $key => $value) {
                $row = [
                    htmlspecialchars($value["student_id"]),
                    htmlspecialchars($value["name"]),
                    htmlspecialchars(get_study_year_name($value["study_year"])),
                    htmlspecialchars($value["semester"]),
                    htmlspecialchars((($value["score"]!='')?$value["score"]:'-')),
                    htmlspecialchars((($value["grade"]!='')?$value["grade"]:'-')),
                ];
        
                fputcsv($fp, $row);
            }

        break;
    }