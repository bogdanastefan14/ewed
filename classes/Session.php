<?php
/**

 * Date: 22/09/2017
 * Time: 19:05
 */

class Session {
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function exist($name) {
        return isset($_SESSION[$name]);
    }

    public static function delete($name) {
        if (self::exist($name)) {
            unset($_SESSION[$name]);
        }
    }
}