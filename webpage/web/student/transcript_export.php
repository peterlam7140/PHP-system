<?php
    include_once __DIR__ . '/template/preload.php';

    use Dompdf\Dompdf;
    require_once PATH_LIBS . 'dompdf/autoload.inc.php';

    procress_checkLogin();

    $page_name = 'course';
    $table_name = 'course';
    $display_name = 'Course';

    $conn = connectDB();

    $date = date('Y-m-d');

    $sql = "SELECT a.* 
            FROM `program` a 
            INNER JOIN `student_program_relation` b 
                ON a.id = b.program_id 
            WHERE b.student_id = :student_id 
                AND a.is_delete = 0";
    $query = array(':student_id' => $loginSession->getSessionUserId());

    $sth = $conn->prepare($sql);
    $sth->execute($query);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count = $sth->rowCount();

    $program_list = $data;

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

    $t_cgpa = calc_gpa(array_column($course_list, 'score'));

 
    // $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $path = 'img.jpg';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $img = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $html = '
    <html>

    <head>
      <title>Transcript - '.$userInfo['student_id'].'</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <style>
        .inside_break { page-break-inside: auto; }

        @page { margin: 50px 25px; }
        body { width: 745px; margin: auto; font-weight: light; font-family: taipeisanstcbeta; }
        main {  }

        table, table tr, table tr td {
          page-break-inside: avoid;
        }

        header { position: fixed; top: -30px; left: 0px; right: 0px; background-color: #FFF; height: auto; }
        // footer { position: fixed; bottom: -70px; left: 0px; right: 0px; background-color: #FFF; height: 50px; }

        img { max-width: 250px; }

        .text-center {
          text-align: center;
        }
    
        .after-header {
          padding: 0 0 2px;
          font-size: 12px;
        }
        .after-header * { margin: 0; }

        .after-header::after {content: "";clear: both;display: table;}

        .student-id {
          float: left;
        }
    
        .date-day {
          float: right;
        }
    
        .page-header {
          margin-top: 5px;
          padding: 5px;
          background-color: #d1e7dd;
        }
    
        .page-header h2 {
          font-size: 20px;
          text-align: center;
        }
    
        .details { }
    
        table {
          margin: 5px 0;
          width: 100%;
          border-collapse: 0;
          border-spacing: 0;
          border: 1px solid #1f1f1f;
        }
    
        th {
          padding: 0;
          border: 0;
        }

        td {
          text-align: justify;
          padding: 5px;
          border-bottom: 1px solid #1f1f1f;
        }

        table tr>td:nth-child(1n+2) {
          border-left: 1px solid #1f1f1f;
        }

        table tr:last-child>td {
          border-bottom: 0;
        }
    
        label {
          font-weight: bold;
          font-size: 15px;
        }

      </style>
    </head>
    
    <body>
    <header>
      <div class="after-header">
        <div class="student-id">
          <h3>Student ID: '.$userInfo['student_id'].'</h3>
        </div>
        <div class="date-day">
          <h3>Generate Date: '.$date.'</h3>
        </div>
      </div>
    </header>
    <footer></footer>
      <main>
        <div class="page-header">
          <h2>Student Transcript</h2>
        </div>
        <div class="details" style="padding: 20px 0 0;">
          <!-- div style="padding: 20px 0;">
            <div style="background-color: #f2f2f2; padding: 5px;">
              <h3>Student Information</h3>
            </div>
          </div -->
          <table>
            <tr>
              <th width="10%"></th>
              <th width="20%"></th>
              <th width="10%"></th>
              <th width="20%"></th>
            </tr>
            <tr>
              <td><label>Name:</label></td>
              <td colspan="3"><label>'.$userInfo['name'].'</label></td>
            </tr>
            <tr>
              <td><label>Date of Birth: </label></td>
              <td><label>'.$userInfo['dob'].'</label></td>
              <td><label>Gender: </label></td>
              <td><label>'.ucfirst($userInfo['gender']).'</label></td>
            </tr>
            <tr>
              <td><label>Phone: </label></td>
              <td colspan="3"><label>'.$userInfo['phone'].'</label></td>
            </tr>
            <tr>
              <td><label>Email: </label></td>
              <td colspan="3"><label>'.$userInfo['email'].'</label></td>
            </tr>
            <tr>
              <td><label>CGPA: </label></td>
              <td colspan="3"><label>'.(($t_cgpa!='')?$t_cgpa:'-').'</label></td>
            </tr>
    
          </table>';

foreach($program_list as $v) {
  $html .= '
    <div style="padding: 20px 0;">
      <div style="background-color: #f2f2f2; padding: 20px 10px;">
        <h3 style="margin:0">Programs</h3>
      </div>
    </div>

    <table>
      <tr>
        <th width="50%"></th>
        <th width="50%"></th>
      </tr>  
      <tr>
        <td colspan="2"><label>'.$v['name'].' ('.$v['code'].')</label></td>
      </tr>

    </table>
  ';
}

foreach($study_year_list as $yeari => $yearv){
  $t_gpa = get_year_gpa($course_list, $yearv);
  $html .= '
  <div class="inside_break">
    <table style="padding: 20px 0; border: 0;">
      <tr><td style="padding: 0;margin: 0;">
        <div style="background-color: #f2f2f2; padding: 20px 10px;">
          <h2 style="margin:0">'.get_study_year_name($yearv).'</h2>
          <h4 style="margin:0">GPA : '.(($t_gpa!='')?$t_gpa:'-').'</h4>
        </div>
      </td></tr>
    </table>
  </div>
  ';

    foreach($course_list as $v){
      if($yearv == $v['study_year']){
        $html .= '
        <div class="inside_break">
          <h5 style="margin:0">'.$v['code'].'</h5>
          <h3 style="margin:0">'.$v['name'].'</h3>

          <table>
            <tr>
              <th width="50%"></th>
              <th width="50%"></th>
            </tr>  
            <tr>
              <td><label>Study Year: '.get_study_year_name($v['study_year']).'</label></td>
              <td><label>Semester: '.(($v['semester']!='')?$v['semester']:'-').'</label></td>
            </tr>
            <tr>
              <td><label>Score: '.(($v['score']!='')?$v['score']:'-').'</label></td>
              <td><label>Grade: '.(($v['grade']!='')?$v['grade']:'-').'</label></td>
            </tr>
      
          </table>
        </div>
        ';
      }
    }
  $html .= '</div>';
}

$html .= '
        </div>
      </main>
    </body>
    
    </html>
';



    if(true){
        // https://github.com/dompdf/dompdf/blob/2eaf8fe0f1c95ab76e7a428a39a54dd240e2b2ec/src/Options.php

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // $dompdf->set_option('isRemoteEnabled', true);
        // $dompdf->set_option('debugLayout', true);
        // $dompdf->set_option('debugPng', true);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
         
        // Attachment: 0 直接顯示, 1 強制下載
        $dompdf->stream('transcript', ['Attachment' => 0]);
    } else {
        echo $html;
    }
