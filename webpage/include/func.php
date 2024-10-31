<?php
    function calc_gpa(array $scoreList) {
        $total_score = 0;
        $total_count = 0;
        $gpa = 0;
        $grade = '';

        foreach($scoreList as $v){
            if($v != '') {
                $total_score += floatval($v);
                $total_count++;
            }
        }

        if($total_count == 0){
            return null;
        }

        $gpa = floatval(number_format(($total_score / ($total_count * 100)) * 4, 2, '.', ''));

        return $gpa;
    }

    function calc_grade($score) {
        $grade = null;

        if($score === null || $score === '') {

        } else {
            $score = floatval($score);
            if($score === (float)0) {
                $grade = 'Fail';
            } else {
                switch($score) {
                    case $score >= 100: 
                        $grade = 'A';
                    break;
                    case $score >= 92.5: 
                        $grade = 'A-';
                    break;
                    case $score >= 82.5:  
                        $grade = 'B+';
                    break;
                    case $score >= 75: 
                        $grade = 'B';
                    break;
                    case $score >= 67.5: 
                        $grade = 'B-';
                    break;
                    case $score >= 57.5:
                        $grade = 'C+';
                    break;
                    case $score >= 50: 
                        $grade = 'C';
                    break;
                    case $score >= 45: 
                        $grade = 'Fail-Resit';
                    break;
                    case $score >= 0: 
                        $grade = 'Fail';
                    break;
                }
            }
        }

        return $grade;
    }

    function get_year_gpa(array $scoreList, String $targetYear) {
        $targetList = [];
        foreach($scoreList as $i => $v){
            if($v['study_year'] == $targetYear && $v['score'] != ''){
                $targetList[] = $v['score'];
            }
        }
        return calc_gpa($targetList);
    }

    function gen_paginator_ele($paginator) {
        if ($paginator->getNumPages() > 1): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php if ($paginator->getPrevUrl()): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a></li>
                    <?php endif; ?>
        
                    <?php foreach ($paginator->getPages() as $page): ?>
                        <?php if ($page['url']): ?>
                            <li class="page-item <?php echo $page['isCurrent'] ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled"><span><?php echo $page['num']; ?></span></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
        
                    <?php if ($paginator->getNextUrl()): ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif;
    }

    function paginator_param($count) {

        $page = (!isset($_GET["page"]) || $_GET["page"] == "") ? 1 : intval($_GET["page"]);
        $page = ($page < 1) ? 1 : $page;
        
        $rowsPerPage = 20;
        $offset = ($page - 1) * $rowsPerPage;
    
        $totalItems = $count;

        return array(
            'page' => $page,
            'rowsPerPage' => $rowsPerPage,
            'offset' => $offset,
            'totalItems' => $totalItems,
        );
    }

    function get_study_year_name($year) {
        global $_STUDY_YEAR;
        return ($_STUDY_YEAR[$year]!='')?$_STUDY_YEAR[$year]:$year;
    }

    function get_week_name($week) {
        global $_WEEK;
        return ($_WEEK[$week]!='')?$_WEEK[$week]:$week;
    }