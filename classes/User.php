<?php
/**
 * Date: 22/09/2017
 * Time: 19:06
 */

class User {
    private $_db;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn = false;

    public function __construct($user=null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');

        if (!$user) {
            if (Session::exist($this->_sessionName)) {
                $user_id = Session::get($this->_sessionName);
                if ($this->find_by($user_id)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
                }
            }
        } else {
            $this->find_by($user);
        }
    }

    # Create a user using validated fields.
    public function create($fields) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception("There was a problem creating a account.");
        }
    }

    # Find user using field value.
    # if field value numeric the field value represent the id of the user.
    # if not numeric, represent the username of user.
    public function find_by($field_value = null) {
        if ($field_value) {
            $field = (is_numeric($field_value)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $field_value));

            if ($data->getCount()) {
                $this->_data = $data->getFirst();
                return true;
            }
        }
        return false;
    }

    public function login($username=null, $password=null) {
        $user = $this->find_by($username);
        if ($user) {
            # Check passwords match or not
            if (password_verify($password, $this->_data->password)) {
                Session::put($this->_sessionName, $this->_data->id);
                return true;
            }
        }
        return false;
    }

    public function logout() {
        Session::delete($this->_sessionName);
    }

    public function getData() {
        return $this->_data;
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }
}