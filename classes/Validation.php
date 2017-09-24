<?php
/**
 * Date: 22/09/2017
 * Time: 19:07
 */

class Validation {

    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) {

        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                # get the value from the post or get method.
                # For example;
                # $source => POST, $item => username
                # value => $_POST['username']
                $value = trim($source[$item]);
                $value = escape($value);

                switch ($rule) {
                    case 'required':
                        if (!$this->requiredControl($value)) {
                            $err_message = $item . " is required.";
                            $this->addError($err_message);
                        }
                        break;
                    case 'min':
                        if (!$this->minControl($rule_value, $value)) {
                            $err_message = $item . " must be a greater than " . $rule_value;
                            $this->addError($err_message);
                        }
                        break;
                    case 'max':
                        if (!$this->maxControl($rule_value, $value)) {
                            $err_message = $item . " must be a equal or less than " . $rule_value;
                            $this->addError($err_message);
                        }
                        break;
                    case 'matches':
                        $otherValue = escape(trim($source[$rule_value]));
                        if (!$this->matchControl($otherValue, $value)) {
                            $err_message = $item . "s must matched eachother.";
                            $this->addError($err_message);
                        }
                        break;
                    case 'unique':
                        if (!$this->uniqueControl($rule_value, $item, $value)) {
                            $err_message = "This " . $item . " already exists";
                            $this->addError($err_message);
                        }
                        break;


                }

            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    private function requiredControl($value) {
        if (empty($value)) {
            return false;
        }
        return true;
    }

    private function minControl($bound, $value) {
        if (strlen($value) >= $bound) {
            return true;
        }
        return false;
    }

    private function maxControl($bound, $value) {
        if (strlen($value) <= $bound) {
            return true;
        }
        return false;
    }

    private function matchControl($otherValue, $value) {
        if ($value === $otherValue) {
            return true;
        }
        return false;
    }

    private function uniqueControl($table, $item, $value) {
        $a = $this->_db->get($table, array($item, '=', $value));
        if ($a->getCount()) {
            return false;
        }
        return true;
    }
    # Adding a error to the error list.
    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function getErrors() {
        return $this->_errors;
    }

    public function isPassed() {
        if (empty($this->_errors)) {
            $this->_passed = true;
        } else {
            $this->_passed = false;
        }

        return $this->_passed;
    }
}