<?php
/**
 * Date: 22/09/2017
 * Time: 19:06
 */

class User {
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function create($fields) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception("There was a problem creating a account.");
        }
    }
}