<?php
/**
 *  @author Renant Bernabé <contato@renant.com.br>
 */
class Session {

    public static function init() {
        @session_start();
    }

    public static function set($key, $value) {
        $_SESSION[SESSIONNAME][$key] = $value;
    }

    public static function get($key) {
        if (isset($_SESSION[SESSIONNAME][$key])){
            return $_SESSION[SESSIONNAME][$key];
        }
    }
    
    public static function setLoc($key, $value) {
        $_SESSION['loc'][$key] = $value;
    }
    
    public static function getLoc($key) {
        if (isset($_SESSION['loc'][$key])){
            return $_SESSION['loc'][$key];
        }
    }

    public static function destroy() {
        unset($_SESSION[SESSIONNAME]);
//        session_destroy();
    }

}
