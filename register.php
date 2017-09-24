<?php
require_once 'core/init.php';

if (Input::exists('post')) {
    if (Token::check(Input::get(Config::get('session/token_name')))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 8,
                'max' => 50,
                'unique' => 'users'  #  unique to the users table
            ),
            'email' => array(
                'required' => true,
                'max' => 200,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 8,
                'max' => 64
            ),
            'password2' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'first_name' => array(
                'required' => true,
                'max' => 50
            ),
            'last_name' => array(
                'required' => true,
                'max' => 50
            )
        ));

        if ($validation->isPassed()) {
            $user = new User();
            try {
                $user->create(array(
                   'username' => Input::get('username'),
                   'password' => Hash::make(Input::get('password')),
                   'email' => Input::get('email'),
                   'first_name' => Input::get('first_name'),
                   'last_name' => Input::get('last_name'),
                   'join_date' => date('Y-m-d H:i:s'),
                   'group_num' => 1
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->getErrors() as $error) {
                echo "! $error" . "<br>";
            }
        }
    }
}
?>

<form action="" method="post">
    <div>
        <label for="username">
            Username:
            <input type="text" name="username" id="username" value="" autocomplete="off">
        </label>
    </div>
    <div>
        <label for="email">
            Email:
            <input type="email" name="email" id="email" value="" autocomplete="off">
        </label>
    </div>
    <div>
        <label for="password">
            Password:
            <input type="password" name="password" id="password" value="" autocomplete="off">
        </label>
    </div>

    <div>
        <label for="password2">
            Enter password again:
            <input type="password" name="password2" id="password2" value="" autocomplete="off">
        </label>
    </div>
    <div>
        <label for="first_name">
            First Name:
            <input type="text" name="first_name" id="first_name" value="" autocomplete="off">
        </label>
    </div>
    <div>
        <label for="last_name">
            Last Name:
            <input type="text" name="last_name" id="last_name" value="" autocomplete="off">
        </label>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
    <input type="submit" value="Register">
</form>