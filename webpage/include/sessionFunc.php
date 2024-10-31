<?php
    function action_login($login_id, $password){
        global $loginSession;
        return $loginSession->action_login($login_id, $password);
    }

    function action_logout(){
        global $loginSession;
        $loginSession->action_logout();
    }

    function check_session(){
        global $loginSession;
        return $loginSession->check_session();
    }

    function procress_checkLogin(){
        global $loginSession;
        $loginSession->procress_checkLogin();
    }

    function getSessionUserInfo(){
        global $loginSession;
        return $loginSession->getSessionUserInfo();
    }

    function isLogin(){
        global $loginSession;
        return $loginSession->isLogin();
    }