<?php
/**
 * Date: 22/09/2017
 * Time: 19:08
 * @param $string
 * @return string
 */

/*
 * htmlentites() function convert some characters to
 * HTML entites. Using this function, we can prevent
 * our systems from the some script injections, xss.
 *
 * We decided to using ENT_QUATES as a quote style.
 * Because, we want to convert not only double quotes
 * but also single quotes.
 *
 */
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}