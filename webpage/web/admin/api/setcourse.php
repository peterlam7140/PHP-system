<?php
die();
    include_once __DIR__ . '/../template/preload.php';

    $result = true;

    $conn = connectDB();

    if(!$loginSession->check_session()) {
        echo 'Login required';
        die();
    }

    $sql = "SELECT * FROM `course`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    $courseList = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo $sql.'<br/>';

    $sql = "SELECT * FROM `student_user`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    $student_userList = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo $sql.'<br/>';

    $sql = "SELECT * FROM `course_score`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    $course_scoreList = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo $sql.'<br/>';

    $sql = "DELETE FROM `course_score`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    echo $sql.'<br/>';

    $sql = "DELETE FROM `student_course_relation`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    echo $sql.'<br/>';

    $idCourse = array_column($courseList, 'id');
    $idYear = [
                ['year'=>2020, 'sem'=>'Spring'],
                ['year'=>2020, 'sem'=>'Summer'],
                ['year'=>2020, 'sem'=>'Autumn'],
                ['year'=>2020, 'sem'=>'Winter'],
                
                ['year'=>2021, 'sem'=>'Spring'],
                ['year'=>2021, 'sem'=>'Summer'],
                ['year'=>2021, 'sem'=>'Autumn'],
                ['year'=>2021, 'sem'=>'Winter'],
                
                ['year'=>2022, 'sem'=>'Spring'],
                ['year'=>2022, 'sem'=>'Summer'],
                ['year'=>2022, 'sem'=>'Autumn'],
                ['year'=>2022, 'sem'=>'Winter'],
                
                ['year'=>2023, 'sem'=>'Spring'],
                ['year'=>2023, 'sem'=>'Summer'],
                ['year'=>2023, 'sem'=>'Autumn'],
                ['year'=>2023, 'sem'=>'Winter'],
            ];

    foreach($student_userList as $student_i => $student_v){
        $student_id = $student_v['id'];

        $poolCourse = $idCourse;
        $poolYear = $idYear;

        // shuffle($poolCourse);
        shuffle($poolYear);

        echo '----------- '.$student_id.' -----------<br/>';

        for($idx = 0; $idx < 16; $idx++){
            $tYear = array_shift($poolYear);

            $course_id = array_shift($poolCourse);
            $study_year = $tYear['year'];
            $semester = $tYear['sem'];
            $score = rand(0,100);
            $grade = calc_grade(floatval($score));

            if($study_year == 2023 && in_array($semester, ['Spring', 'Summer'])){
                $score = null;
                $grade = null;
            }

            insertCourse($course_id, $student_id, $study_year, $semester, $score, $grade);
        }
    }



    function insertCourse($course_id, $student_id, $study_year, $semester, $score, $grade){
        global $conn;

        $sql = "INSERT INTO `course_score` 
                (course_id, student_id, study_year, semester, score, grade) 
                VALUES (:course_id, :student_id, :study_year, :semester, :score, :grade)";
        $query = array(
            ':course_id' => $course_id,
            ':student_id' => $student_id,
            ':study_year' => $study_year,
            ':semester' => $semester,
            ':score' => $score, 
            ':grade' => $grade
        );

        var_dump($query); echo '<br/>';
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        $sql = "INSERT INTO `student_course_relation` 
                (`student_id`, `course_id`) 
                VALUES (:student_id, :course_id)";
        $query = array(
            ':student_id' => $student_id,
            ':course_id' => $course_id,
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    }