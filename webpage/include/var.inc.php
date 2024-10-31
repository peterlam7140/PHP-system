<?php
$_SEMESTER = [
    'Spring' => 'Spring',
    'Summer' => 'Summer',
    'Autumn' => 'Autumn',
    'Winter' => 'Winter',
];

$_STUDY_YEAR = [];
for($i = 1970; $i < 2970; $i++) {
    $_STUDY_YEAR[$i] = $i . ' - ' . ($i+1);
}

$_WEEK = [
    7 => 'Sunday',
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
];

$_CURR_YEAR = date('Y');