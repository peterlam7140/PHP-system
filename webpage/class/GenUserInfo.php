<?php
abstract class GenUserInfo {

    public $targetId = null;

    function setTargetId($targetId){
        $this->targetId = $targetId;
    }

    abstract function getData(): array;

    abstract function genHtml(): String;

    abstract function progress(): void;
}

class GenStudentInfo extends GenUserInfo {

    function getData(): array {
        $conn = connectDB();

        $sql = "SELECT * FROM student_user WHERE is_delete = 0 AND id = :id";
        $query = array(':id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data[0];
        return $recordObj;
    }

    function getCourse(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `course` a 
                INNER JOIN `student_course_relation` b 
                    ON a.id = b.course_id 
                WHERE b.student_id = :student_id 
                    AND a.is_delete = 0";
        $query = array(':student_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function getProgram(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `program` a 
                INNER JOIN `student_program_relation` b 
                    ON a.id = b.program_id 
                WHERE b.student_id = :student_id 
                    AND a.is_delete = 0";
        $query = array(':student_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function genHtml(): String {
        $userInfo = $this->getData();
        $course_list = $this->getCourse();
        $program_list = $this->getProgram();

        $html = '
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Information</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Student ID</label>
                        <div class="fs-6">'.$userInfo['student_id'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Student Name</label>
                        <div class="fs-6">'.$userInfo['name'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Day of Birth</label>
                        <div class="fs-6">'.$userInfo['dob'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Gender</label>
                        <div class="fs-6">'.ucfirst($userInfo['gender']).'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <div class="fs-6">'.$userInfo['phone'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <div class="fs-6">'.$userInfo['email'].'</div>
                    </div>
        
                </div>
            </div>
        </div>

        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Programs</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($program_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="program_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>

        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Courses</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($course_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="course_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        ';

        return $html;
    }

    function progress(): void {

    }
}

class GenTeacherInfo extends GenUserInfo {

    function getData(): array {
        $conn = connectDB();

        $sql = "SELECT * FROM teacher_user WHERE is_delete = 0 AND id = :id";
        $query = array(':id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data[0];
        return $recordObj;
    }

    function getCourse(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `course` a 
                INNER JOIN `teacher_course_relation` b 
                    ON a.id = b.course_id 
                WHERE b.teacher_id = :teacher_id 
                    AND a.is_delete = 0";
        $query = array(':teacher_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function getProgram(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `program` a 
                INNER JOIN `teacher_program_relation` b 
                    ON a.id = b.program_id 
                WHERE b.teacher_id = :teacher_id 
                    AND a.is_delete = 0";
        $query = array(':teacher_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function genHtml(): String {
        $userInfo = $this->getData();
        $course_list = $this->getCourse();
        $program_list = $this->getProgram();

        $roleStr = '';
        switch($userInfo['role']) {
            case "program_leader": $roleStr = 'Program Leader'; break;
            case "teacher": $roleStr = 'Teacher'; break;
            default: $roleStr = $userInfo['role'];
        }

        $html = '
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Information</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Student Name</label>
                        <div class="fs-6">'.$userInfo['name'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Day of Birth</label>
                        <div class="fs-6">'.$userInfo['dob'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Gender</label>
                        <div class="fs-6">'.ucfirst($userInfo['gender']).'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Phone</label>
                        <div class="fs-6">'.$userInfo['phone'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <div class="fs-6">'.$userInfo['email'].'</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Role</label>
                        <div class="fs-6">'.$roleStr.'</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Programs</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($program_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="program_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>

        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Courses</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($course_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="course_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        ';

        return $html;
    }

    function progress(): void {

    }
}

class GenProgramInfo extends GenUserInfo {

    function getData(): array {
        $conn = connectDB();

        $sql = "SELECT * FROM program WHERE is_delete = 0 AND id = :id";
        $query = array(':id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data[0];
        return $recordObj;
    }

    function getCourse(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `course` a 
                INNER JOIN `program_course_relation` b 
                    ON a.id = b.course_id 
                WHERE b.program_id = :program_id 
                    AND a.is_delete = 0";
        $query = array(':program_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function getTeacher(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `teacher_user` a 
                INNER JOIN `teacher_program_relation` b 
                    ON a.id = b.teacher_id 
                WHERE a.is_delete = 0
                    AND b.program_id = :program_id";
        $query = array(':program_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function genHtml(): String {
        $userInfo = $this->getData();
        $course_list = $this->getCourse();
        $teacher_list = $this->getTeacher();

        $html = '';
        $html .= '
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">'.$userInfo['code'].'</h6>
                <h2 class="mb-0 fs-3">'.$userInfo['name'].'</h2>
            </div>
            <div class="card-body">
                <h5>Description</h5>
                <div>'.(($userInfo['description']!='')?nl2br($userInfo['description']):'-').'</div>
            </div>
        </div>
        
        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Related Courses</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($course_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="course_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        
        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Related Teacher</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($teacher_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="teacher_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        ';

        return $html;
    }

    function progress(): void {

    }
}

class GenCourseInfo extends GenUserInfo {

    function getData(): array {
        $conn = connectDB();

        $sql = "SELECT * FROM course WHERE is_delete = 0 AND id = :id";
        $query = array(':id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data[0];
        return $recordObj;
    }

    function getProgram(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `program` a 
                INNER JOIN `program_course_relation` b 
                    ON a.id = b.program_id 
                WHERE b.course_id = :course_id 
                    AND a.is_delete = 0";
        $query = array(':course_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function getTeacher(): array {
        $conn = connectDB();

        $sql = "SELECT a.* 
                FROM `teacher_user` a 
                INNER JOIN `teacher_course_relation` b 
                    ON a.id = b.teacher_id 
                WHERE a.is_delete = 0
                    AND b.course_id = :course_id";
        $query = array(':course_id' => $this->targetId);
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $recordObj = $data;
        return $recordObj;
    }

    function genHtml(): String {
        $userInfo = $this->getData();
        $program_list = $this->getProgram();
        $teacher_list = $this->getTeacher();

        $html = '';
        $html .= '
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">'.$userInfo['code'].'</h6>
                <h2 class="mb-0 fs-3">'.$userInfo['name'].'</h2>
            </div>
            <div class="card-body">
                <h5>Description</h5>
                <div>'.(($userInfo['description']!='')?nl2br($userInfo['description']):'-').'</div>
            </div>
        </div>
        
        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Related Programs</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($program_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="program_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['code'].'<br/>'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        
        <div class="pb-5"></div>
        
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="mb-0 fs-3">Teacher</div>
            </div>
            <div class="card-body">
        
                <div class="row g-1">
        ';
                    foreach($teacher_list as $i => $v) {
        $html .= '
                    <div class="col-auto"><a class="card link-underline link-underline-opacity-0" href="teacher_info.php?id='.$v['id'].'" target="_blank"><div class="card-header px-2 py-1">'.$v['name'].'</div></a></div>
        ';
                    }
        $html .= '
                </div>
        
            </div>
        </div>
        ';

        return $html;
    }

    function progress(): void {

    }
}