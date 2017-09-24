<?php
/**
 * Date: 22/09/2017
 * Time: 18:54
 */

// Add init.php
require_once 'core/init.php';

if (Session::exist('success')) {
    echo Session::flash('success');
}

$user = new User();

if ($user->isLoggedIn()) {
    echo "Hi, " . $user->getData()->username . " | ";
    echo "<a href=\"logout.php\">Log out</a>";
} else {
    echo "You need to <a href=\"login.php\">Log in</a> or <a href=\"register.php\">Register</a>";
}


