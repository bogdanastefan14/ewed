<?php
/**
 * Date: 22/09/2017
 * Time: 19:06
 */

class Token {
    public static function generate() {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    # Check the token exist in the session or not.
    public static function check($token) {
        $token_name = Config::get('session/token_name');
        if (Session::exist($token_name) && $token === Session::get($token_name)) {
            Session::delete($token_name);
            return true;
        }
        return false;
    }
}