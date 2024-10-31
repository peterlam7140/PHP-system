<?php
die();
    include_once __DIR__ . '/../template/preload.php';

    $result = true;

    $conn = connectDB();

    if(!$loginSession->check_session()) {
        echo 'Login required';
        die();
    }

    $sql = "SELECT course_id, study_year, semester FROM `course_score` GROUP BY course_id, study_year, semester";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    $course_scoreList = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo $sql.'<br/>';

    $sql = "DELETE FROM `course_timetable`";
    $query = array();

    $sth = $conn->prepare($sql);
    $sth->execute($query);
    echo $sql.'<br/>';

    $poolDay = [
                'Spring' => ['-01-01', '-03-31'],
                'Summer' => ['-04-01', '-06-30'],
                'Autumn' => ['-07-01', '-09-30'],
                'Winter' => ['-10-01', '-12-31'],
            ];

    $poolYear = [
                2020 => [
                    'Spring' => 2021,
                    'Summer' => 2021,
                    'Autumn' => 2020,
                    'Winter' => 2020,
                ],
                2021 => [
                    'Spring' => 2022,
                    'Summer' => 2022,
                    'Autumn' => 2021,
                    'Winter' => 2021,
                ],
                2022 => [
                    'Spring' => 2023,
                    'Summer' => 2023,
                    'Autumn' => 2022,
                    'Winter' => 2022,
                ],
                2023 => [
                    'Spring' => 2024,
                    'Summer' => 2024,
                    'Autumn' => 2023,
                    'Winter' => 2023,
                ],
            ];

    $poolTime = [
                ['09:00:00', '10:59:59'],
                ['11:00:00', '12:59:59'],
                ['14:00:00', '15:59:59'],
                ['16:00:00', '17:59:59'],
                ['18:00:00', '19:59:59'],
            ];

    foreach($course_scoreList as $student_i => $student_v){
        $idxTime = rand(1, (count($poolTime) - 1));

        echo '----------- '.$student_id.' -----------<br/>';

        $course_id = $student_v['course_id'];
        $study_year = $student_v['study_year'];
        $semester = $student_v['semester'];
        $period_start = $poolYear[$student_v['study_year']][$student_v['semester']] . $poolDay[$student_v['semester']][0];
        $period_end = $poolYear[$student_v['study_year']][$student_v['semester']] . $poolDay[$student_v['semester']][1];
        $attend_day = rand(1, 5);
        $time_start = $poolTime[$idxTime][0];
        $time_end = $poolTime[$idxTime][1];

        insertTimetable($course_id, $study_year, $semester, $period_start, $period_end, $attend_day, $time_start, $time_end);
    }



    function insertTimetable($course_id, $study_year, $semester, $period_start, $period_end, $attend_day, $time_start, $time_end){
        global $conn;

        $sql = "INSERT INTO `course_timetable` 
                (`course_id`, `study_year`, `semester`, `period_start`, `period_end`, `attend_day`, `time_start`, `time_end`, `is_active`, `is_delete`) 
                VALUES 
                (:course_id, :study_year, :semester, :period_start, :period_end, :attend_day, :time_start, :time_end, 1, 0)";
        $query = array(
            ':course_id' => $course_id,
            ':study_year' => $study_year,
            ':semester' => $semester,
            ':period_start' => $period_start,
            ':period_end' => $period_end,
            ':attend_day' => $attend_day,
            ':time_start' => $time_start,
            ':time_end' => $time_end
        );

        var_dump($query); echo '<br/>';
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    }