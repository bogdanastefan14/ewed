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

    # Show flash message to the user
    public static function flash($name, $content = null) {
        if (self::exist($name)) {
            # if this message exist, remove it
            # because we want to see the message just one.
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $content);
        }
    }
}