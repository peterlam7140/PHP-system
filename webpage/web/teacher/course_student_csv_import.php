<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_POST['course_id']);
    $study_year = trim($_POST['study_year']);
    $save_type = "import";

    $csv_file = $_FILES['csv_file'];

    $sql = "SELECT a.*, 'edit' AS type 
            FROM $table_name a 
            INNER JOIN `teacher_course_relation` b 
            WHERE a.id = b.course_id 
                AND b.teacher_id = :teacher_id 
                AND a.is_delete = 0
                AND a.id = :id";
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

    $student_id_list = array_combine(array_column($data, 'student_id'), array_column($data, 'id'));

    switch($save_type){
        case 'import';

            $scoreList = [];

            try {
                if (($handle = fopen($csv_file["tmp_name"], "r")) !== FALSE) {
                    $idx = 1;
                    while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                        if($idx > 1){
                            $col_student_id = trim($data[0]);
                            $col_score = trim($data[1]);


                            if($student_id_list[$col_student_id] == null){
                                throw new Exception("Row ".$idx.": Student ID [".$col_student_id."] is not inside course.");
                            }

                            if($col_score == ""){
                                $col_score = null;
                            } else {
                                if($col_score < 0 || $col_score > 100){
                                    throw new Exception("Row ".$idx.": Score [".$col_score."] not between 0 and 100.");
                                }
                            }

                            $scoreList[] = array(
                                "student_id" => $student_id_list[$col_student_id],
                                "score" => $col_score,
                            );
                        }
                        $idx++;
                    }
                    fclose($handle);
                }

                foreach($scoreList as $v){
                    $grade = ($v['score']!='')?calc_grade($v['score']):null;

                    $sql = "UPDATE `course_score` 
                            SET score = :score,
                                grade = :grade
                            WHERE course_id = :course_id 
                                AND student_id = :student_id
                                AND study_year = :study_year";
                    $query = array(
                        ':course_id' => $course_id,
                        ':student_id' => $v['student_id'],
                        ':study_year' => $study_year,
                        ':score' => $v['score'],
                        ':grade' => $grade
                    );
                
                    $sth = $conn->prepare($sql);
                    $sth->execute($query);
                }

                $_SESSION['score_website']['alert'][] = array(
                    'type' => 'success',
                    'text' => $display_name.' record update successfully'
                );

            } catch (Exception $e) {
                $_SESSION['score_website']['alert'][] = array(
                    'type' => 'danger',
                    'text' => $e->getMessage()
                );
            }

        break;
    }

    header('Location: '.$page_name.'_student_list.php?course_id=' . $course_id . '&study_year=' . $study_year);