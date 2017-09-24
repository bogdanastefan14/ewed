<?php
/**
 * Date: 23/09/2017
 * Time: 16:47
 */

class Hash {
    public static function make($string) {
        return password_hash($string, PASSWORD_DEFAULT);
    }
}