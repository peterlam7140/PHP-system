<?php
abstract class LoginSession {

    abstract function loginScope(): String;

    abstract function loginPath(): String;

    abstract function getSessionTypeName(): String;

    abstract function loginPrepare($conn, $login_id, $password);

    abstract function getInfoPrepare($conn);

    function getSessionGroupName(): String {
        return 'score_website';
    }

    function getSessionUserId() {
        return $_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['user_id'];
    }

    function action_login($login_id, $password){
        $conn = connectDB();

        $sth = $this->loginPrepare($conn, $login_id, $password);
    
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $count = $sth->rowCount();

        $isValid = $count > 0;

        if($isValid){
            $user_id = $data[0]['id'];
            $session_id = time().rand(1000, 9999);




            // $sql = "DELETE FROM admin_session WHERE user_id = :user_id";
            // $query = array(
            //     ':user_id' => $user_id,
            // );

            // $sth = $conn->prepare($sql);
            // $sth->execute($query);

            // $sql = "INSERT INTO admin_session (
            //             session_id, user_id, datetime
            //         ) VALUES (
            //             :session_id, :user_id, :datetime
            //         )";
            // $query = array(
            //     ':session_id' => $session_id,
            //     ':user_id' => $user_id,
            //     ':datetime' => date('Y-m-d H:i:s')
            // );

            // $sth = $conn->prepare($sql);
            // $sth->execute($query);

            // $count                     = $sth->rowCount();
            // $last_insert_id            = $conn->lastInsertId();




            $_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['user_id'] = $user_id;
            $_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['session_id'] = $session_id;
        }

        return $isValid;
    }

    function action_logout(){
        unset($_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]);
    }

    function check_session(){
        $isValid = false;

        if(isset($_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()])){
            
            $conn = connectDB();




            // $sql = "SELECT * FROM admin_session WHERE user_id = :user_id AND session_id = :session_id";
            // $query = array(
            //     ':user_id' => $_SESSION[$this->getSessionGroupName()]['admin_session']['user_id'], 
            //     ':session_id' => $_SESSION[$this->getSessionGroupName()]['admin_session']['session_id']
            // );
        
            // $sth = $conn->prepare($sql);
            // $sth->execute($query);
        
            // $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            // $count = $sth->rowCount();

            // $isValid = $count > 0;



            $sth = $this->getInfoPrepare($conn);
            $isValid = $sth->rowCount() > 0;
        }

        return $isValid;
    }

    function procress_checkLogin(){
        if(!$this->check_session()){
            $this->action_logout();
            header('Location: '.$this->loginPath());
        }
    }

    function getSessionUserInfo(){
        if(isset($_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]['user_id'])){
            $conn = connectDB();

            $sth = $this->getInfoPrepare($conn);

            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
            return $result[0];
        }
    }

    function isLogin(){
        return isset($_SESSION[$this->getSessionGroupName()][$this->getSessionTypeName()]);
    }
}