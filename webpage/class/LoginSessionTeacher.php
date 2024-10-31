<?php
include_once 'LoginSession.php';

class LoginSessionTeacher extends LoginSession {

    function loginScope(): String {
        return 'teacher';
    }

    function loginPath(): String {
        return URL_SCOPE_TEACHER.'index.php';
    }

    function getSessionTypeName(): String {
        return 'teacher_session';
    }

    function loginPrepare($conn, $login_id, $password) {
        $sql = "SELECT * FROM teacher_user WHERE is_delete = 0 AND login_id = :login_id AND password = :password";
        $query = array(':login_id' => $login_id, ':password' => md5($password));
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        return $sth;
    }

    function getInfoPrepare($conn) {
        $sql = "SELECT * FROM teacher_user WHERE is_delete = 0 AND id = :user_id";
        $query = array(
            ':user_id' => $_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['user_id'], 
        );
    
        $sth = $conn->prepare($sql);
        $sth->execute($query);

        return $sth;
    }

}