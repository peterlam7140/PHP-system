<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $course_id = trim($_GET['course_id']);
    $study_year = trim($_GET['study_year']);

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

    $record_list = $data;

    $barChat_label = array_column($record_list, 'student_id');
    $barChat_value = array_column($record_list, 'score');

    include_once PATH_TEMPLATE . 'header.php';
?>

<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0"><?= $recordObj['code'] ?></h6>
        <h1 class="mb-0 fs-3"><?= $recordObj['name'] ?></h1>
    </div>
    <div class="card-body">
        <div>
            <h6 class="mb-0">Study Year : </h6>
            <h1 class="mb-0 fs-3"><?= get_study_year_name($study_year) ?></h1>
        </div>
        <div class="mt-3">
            <a href="./<?= $page_name ?>_student_list.php?course_id=<?= $course_id ?>&study_year=<?= $study_year ?>" class="btn btn-danger">Back</a>
        </div>
    </div>
</div>

<div class="pb-5"></div>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="mb-0 fs-3">Statistic</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="ratio ratio-16x9"><canvas id="barChart"></canvas></div>
            </div>
            <div class="col-12">
                <div class="ratio ratio-16x9"><canvas id="scatterChart"></canvas></div>
            </div>
        </div>

        <script>
        const barChart = document.getElementById('barChart');
        const scatterChart = document.getElementById('scatterChart');

        new Chart(barChart, {
            type: 'bar',
            data: {
            labels: ["Score"],
            datasets: [
                <?php foreach($barChat_label as $i => $v){ if($barChat_value[$i]!=''){ ?>
                    {
                        label: '<?= $barChat_label[$i] ?>',
                        data: [<?= $barChat_value[$i] ?>],
                        borderWidth: 1
                    },
                <?php } } ?>
                ]
            },
                options: {
                scales: {
                    y: {
                        max: 100,
                        min: 0
                    }
                }
            }
        });

        new Chart(scatterChart, {
            type: 'scatter',
                data: {
                    datasets: [
                        <?php foreach($barChat_label as $i => $v){ if($barChat_value[$i]!=''){ ?>
                        {
                            label: "<?= $barChat_label[$i] ?>",
                            data: [{
                                x: <?= $barChat_value[$i] ?>,
                                y: <?= $barChat_value[$i] ?>,
                            }],
                        },
                        <?php } } ?>
                    ],
                },
                options: {
                    animations: {
                        tension: {
                            duration: 0.1,
                            easing: 'linear',
                            from: 1,
                            to: 0,
                            loop: true
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            max: 100,
                            min: 0
                        },
                        y: {
                            max: 100,
                            min: 0
                        }
                    }
                }
        });

        </script>
    </div>
</div>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>