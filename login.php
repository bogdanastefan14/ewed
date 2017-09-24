<?php
/**
 * Date: 22/09/2017
 * Time: 18:55
 */
require_once 'core/init.php';
$user = new User();
if ($user->isLoggedIn()) {
    Redirect::to('index.php');
}
if (Input::exists('post')) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
           'username' => array('required' => true),
           'password' => array('required' => true)
        ));

        if ($validation->isPassed()) {
            $login = $user->login(Input::get('username'), Input::get('password'));

            if ($login) {
                Redirect::to('index.php');
            } else {
                Redirect::to('404');
            }
        } else {
            foreach ($validation->getErrors() as $error) {
                echo "! " . $error . '<br>';
            }
        }
    }
}

?>

<form action="" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" autocomplete="off">
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate()?>">
    <input type="submit" value="Login">
</form>
