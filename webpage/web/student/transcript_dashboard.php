<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $sql = "SELECT c.study_year
            FROM `course` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.course_id 
            LEFT JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE b.student_id = :student_id 
                AND a.is_delete = 0
            GROUP BY c.study_year ORDER BY c.study_year ASC";
    $query = array(':student_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $study_year_list = $data;
    $study_year_list = array_column($study_year_list, 'study_year');

    $sql = "SELECT a.*, c.study_year, c.semester, c.score, c.grade 
            FROM `course` a 
            INNER JOIN `student_course_relation` b 
                ON a.id = b.course_id 
            LEFT JOIN `course_score` c 
                ON c.student_id = b.student_id 
                    AND c.course_id = b.course_id
            WHERE b.student_id = :student_id 
                AND a.is_delete = 0";
    $query = array(':student_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $course_list = $data;

    $barChat_label = array_column($course_list, 'name');
    $barChat_value = array_column($course_list, 'score');

    $t_cgpa = calc_gpa($barChat_value);

    include_once PATH_TEMPLATE . 'header.php';
?>

<div id="app-dashboard">

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="mb-0 fs-3">Statistic</div>
        </div>
        <div class="card-body">

            <div class="row g-3">
                <div class="col-12">
                    <h6 class="mb-0">CGPA</h6>
                    <h1 class="mb-0 fs-3"><?= (($t_cgpa!='')?$t_cgpa:'-') ?></h1>
                </div>
                <?php 
                    foreach($study_year_list as $yeari => $yearv){ 
                        $t_gpa = get_year_gpa($course_list, $yearv);
                ?>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-0">Year<?= ($yeari + 1) ?> GPA (<?= get_study_year_name($yearv) ?>)</h6>
                            <h1 class="mb-0 fs-3"><?= (($t_gpa!='')?$t_gpa:'-') ?></h1>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: selectedCourseCode == null }" @click="loadCourse(null)">All</a>
                    </li>
                    <?php foreach($study_year_list as $yeari => $yearv){ ?>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ active: selectedCourseCode == '<?= $yearv ?>' }" @click="loadCourse('<?= $yearv ?>')"><?= get_study_year_name($yearv) ?></a>
                    </li>
                    <?php } ?>
                </ul>

                <div class="loader-wrapper" v-if="itemlist_loading == true"><div class="spinner-border text-primary" style="width: 6rem; height: 6rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>

                <div class="accordion" id="accordionExample" v-show="itemlist_loading == false">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#coursename" aria-expanded="false" aria-controls="coursename">
                                Course Name
                            </button>
                        </h2>
                        <div id="coursename" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6" v-for="course in courseRecord">
                                            <a class="card link-underline link-underline-opacity-0" :href="'course_info.php?id='+course.id" target="_blank" style="height: 100%">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ course.code }}</h6>
                                                    <h4 class="mb-0 fs-5">{{ course.name }}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mt-1 row"><div class="col-12">Study Year : {{ course.study_year }} - {{ (parseInt(course.study_year) + 1) }}</div></div>
                                                    <div class="mt-1 row"><div class="col-12">Semester : {{ course.semester }}</div></div>
                                                    <div class="mt-1 row"><div class="col-6">Score : {{ (course.score!=null)?course.score:'-' }}</div><div class="col-6">Grade : {{ (course.grade)?course.grade:'-' }}</div></div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div id="barCanvasContainer" class="ratio ratio-16x9" v-show="itemlist_loading == false"></div>
                </div>
                <div class="col-12">
                    <div id="scatterCanvasContainer" class="ratio ratio-16x9" v-show="itemlist_loading == false"></div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="<?= URL_JS ?>app-dashboard.js" type="text/javascript"></script>
<script>
    const { createApp } = Vue
    createApp(app_dashboard).mount('#app-dashboard')
</script>

<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>