<?php
    include_once __DIR__ . '/template/preload.php';

    procress_checkLogin();

    $sql = "SELECT a.*, b.code, b.name 
            FROM course_timetable a 
            INNER JOIN course b ON a.course_id = b.id 
            INNER JOIN student_course_relation c ON c.course_id = a.course_id AND c.student_id = :student_id 
            INNER JOIN course_score d ON d.course_id = a.course_id AND d.student_id = c.student_id AND d.study_year = a.study_year AND d.semester = a.semester 
            WHERE a.is_delete = 0 
            AND b.is_delete = 0
            GROUP BY a.id";
    $query = array(':student_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $timetable_list = $sth->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($timetable_list);
    include_once PATH_TEMPLATE . 'header.php';
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            var eventObj = info.event;
            if (eventObj.url) {
                window.open(eventObj.url);
                info.jsEvent.preventDefault();
            }
        },
        events: [
            <?php 
                foreach($timetable_list as $period_i => $period_v) {
                    $period_start = new DateTime($period_v["period_start"]);
                    $period_end = new DateTime($period_v["period_end"]);
                    $cursor_date = new DateTime($period_v["period_start"]);
                    $period_start_week = $period_start->format("N");

                    if($period_v["attend_day"] < $period_start_week) {
                        $cursor_date->modify(get_week_name($period_v['attend_day']) . ' next week');
                    } else {
                        $cursor_date->modify(get_week_name($period_v['attend_day']) . ' this week');
                    }
                    while($cursor_date <= $period_end) {
            ?>
            {
                "title": '<?= $period_v["code"] ?>',
                "url": './course_info.php?id=<?= $period_v["course_id"] ?>',
                "start": "<?= $cursor_date->format('Y-m-d') ?>T<?= $period_v["time_start"] ?>+08:00",
                "end": "<?= $cursor_date->format('Y-m-d') ?>T<?= $period_v["time_end"] ?>+08:00"
            },
            <?php 
                        $cursor_date->modify('+7 days');
                    } 
                } 
            ?>
        ],
    });
    calendar.render();
});

</script>

<div class="card shadow-sm">
    <div class="card-body">
        <h1>Welcome</h1>
        <h3 class="text-center my-5">Welcome, <?= $userInfo['name'] ?></h3>
    </div>
</div>

<div class="mt-5"></div>

<div class="card shadow-sm">
    <div class="card-header"><div class="fs-3 text-center">Course Timetable</div></div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>


<?php
    include_once PATH_TEMPLATE . 'footer.php';
?>