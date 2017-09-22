<?php
/**
 * Date: 22/09/2017
 * Time: 18:59
 */

/*
 * We have a global array which name is 'config'
 * This array includes a some general settings
 * about the database, session etc. To reach this
 * settings, there are several ways.
 * For example, we want to get the mysql host information.
 * 1- use $GLOBALS['config']['mysql']['host']
 *    this returns 127.0.0.1.
 * But it looks more hardcoded and useless. To prevent this
 * We create a Config class. Using the static get method,
 * we can reach the information using more genaric and logical way.
 */
class Config {

    /*
     * Get the config setting from the init.php
     *
     * get method takes the path as an argument.
     * Then, check the path is null or not.
     * İf not null
     *      get the config array.
     *      parse the path
     *      in the loop, get the element from the array.
     *
     * For example; path = 'mysql/host'
     * after parsing the path, we have an array lik path = ('mysql', 'host')
     * in the foreach loop, first get the <config['mysql']>
     * and in the other step get <config['mysql']['host']>
     * then, return the result.
     */

    public static function get($path = null) {
        $isFound = false;
        $config = $GLOBALS['config'];

        if ($path) {
            $path = explode('/', $path);

            foreach ($path as $bit) {
                if(isset($config[$bit])) {
                    $isFound = true;
                    $config = $config[$bit];
                } else {
                    $isFound = false;
                    break;
                }
            }
        }
        if ($isFound) {
            return $config;
        }
        return $isFound;
    }
}