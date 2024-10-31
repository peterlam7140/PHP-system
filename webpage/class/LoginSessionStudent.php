<?php
include_once 'LoginSession.php';

class LoginSessionStudent extends LoginSession {

    function loginScope(): String {
        return 'student';
    }

    function loginPath(): String {
        return URL_SCOPE_STUDENT.'index.php';
    }

    function getSessionTypeName(): String {
        return 'student_session';
    }

    function loginPrepare($conn, $login_id, $password) {
        $sql = "SELECT * FROM student_user WHERE is_delete = 0 AND student_id = :login_id AND password = :password";
        $query = array(':login_id' => $login_id, ':password' => md5($password));
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        return $sth;
    }

    function getInfoPrepare($conn) {
        $sql = "SELECT * FROM student_user WHERE is_delete = 0 AND id = :user_id";
        $query = array(
            ':user_id' => $_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['user_id'], 
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        return $sth;
    }

}