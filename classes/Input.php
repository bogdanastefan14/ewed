<?php
/**
 * Date: 22/09/2017
 * Time: 19:03
 */

class Input {
    # Check input exists/submitted or not.
    public static function exists($type) {
        switch ($type) {
            case 'post':
                return (!empty($_POST));
                break;

            case 'get':
                return (!empty($_GET));
                break;

            default:
                return false;
                break;
        }
    }

    public static function get($item) {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        }

        if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }
}