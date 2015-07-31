<?php

/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Auth extends Model {

    public static function handleLogin() {
        @session_start();
        $logged = isset($_SESSION[SESSIONNAME]['loggedIn']) ? $_SESSION[SESSIONNAME]['loggedIn'] : "";
        if ($logged == false) {
            unset($_SESSION[SESSIONNAME]);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function escudoLogin($tipo = 0) {
        @session_start();
        $logged = $_SESSION[SESSIONNAME]['loggedIn'];
        
        // Valida status do usuario
        if ($logged == false) {
            unset($_SESSION[SESSIONNAME]);
            header('location: ../');
            exit;
        } 
        
        // Valida tipo de usuario
        if ($_SESSION[SESSIONNAME]['tipo'] < $tipo) {
            unset($_SESSION[SESSIONNAME]);
            header('location: ../');
            exit;
        }
    }

    public static function validaRecaptcha($captcha = null) {
        $resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response=" . $captcha . "&remoteip=" . getenv("REMOTE_ADDR"));
        $resultado = json_decode($resposta, TRUE);
        return $resultado['success'];
    }

    public static function gen() {
        Session::init();
        Session::set('csrf_token', md5(uniqid()));
    }

    public static function get() {
        Session::init();
        return Session::get('csrf_token');
    }

    public static function check($token) {
        Session::init();
        if ($token == Session::get('csrf_token')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
