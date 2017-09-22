<?php
/**
 * Date: 22/09/2017
 * Time: 19:02
 */

class DB {

    private static $instance = null;

    private $_conn;
    private $_query;
    private $_error = false;
    private $_results;
    private $_count = 0;

    private function __construct() {
        try {
            $username = Config::get('mysql/username');
            $passwaord = Config::get('mysql/password');
            $host = Config::get('mysql/host');
            $db_name = Config::get('mysql/db');
            $port = Config::get('mysql/port');
            $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $db_name;

            $this->_conn = new PDO($dsn, $username, $passwaord);

        } catch (PDOException $e) {
            die("Database Connection Failed. [" . $e->getMessage() . "]");
        }

    }

    /*
     * Get the instance from the DB class.
     * What is the benefit of the using the instance?
     * Well, if we don't use the instance, we have to create a new
     * DB object when everytime we want to conenct to the database.
     * Instead of this, we just create one and then use it everywhere
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function query($sql, $parameters = array()) {
        # Reset the _error
        $this->_error = false;
        if ($this->_query = $this->_conn->prepare($sql)) {
            $parameterIndex = 0;
            if (count($parameters)) { # if parameters contains element
                foreach ($parameters as $parameter) {
                    $parameterIndex++;
                    # We want to bind a value at <$paramaterIndex> to the query.
                    $this->_query->bindValue($parameterIndex, $parameter);
                }
            }

            if ($this->_query->execute()) { # Check query excuted successfully

                # We decided to use the <PDO::FETCH_OBJ> as a <fetch_style>
                # because it is more useful than the array.
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
    # Check the given value in the operator list or not.
    private function _isOperator($operator) {
        $operators = array('=', '>', '<', '>=', '<=');

        if (in_array($operator, $operators)) {
            return true;
        }

        return false;
    }

    private function action($action, $table, $where = array()) {
        # Why count($where === 3) ?
        # Because we need the field, operator and value in the $where
        # E.g: action('get', 'users', array('username', '=', 'ewed'))

        if (count($where) === 3) {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if ($this->_isOperator($operator)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->getError()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT * ', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE ', $table, $where);
    }

    # $table -> which table
    # $fields -> which field we will use for the insert operation.
    #    |
    # fields = array(
    #   'name' = 'ewed'
    #   'password' = '4543dfglkdfgjer4k5345',
    # );
    public function insert($table, $fields = array()) {

        # check fields are empty or not
        if (count($fields)) {
            # As we know, the fields array contains keys and values
            # The first step is get keys from the array.
            # For this function we do not need to values. Instead of values,
            # we need question marks. The values will binded to question marks in
            # the query() function.
            $keys = array_keys($fields); # get keys

            # Generate sql sentence
            $sql = "INSERT INTO " . $table . " (" . implode(',', $keys) . ") 
        VALUES (" . $this->generateQuestionMarks(count($keys)) . ")";

            if (!$this->query($sql, $fields)->getError()) {
                return true;
            }
        }
        return false;
    }

    # Update the field/fields.
    public function update($table, $id, $fields = array()) {
        # The logic is same as the insert function.
        # The only differences is sql sentence.

        # check fields are empty or not
        if (count($fields)) {
            $keys = array_keys($fields); # get keys

            # Generate sql sentence
            $sql = "UPDATE " . $table;
            $sql .= " SET " . $this->generateUpdateSql($keys);
            $sql .= " WHERE id = " . $id;

            if (!$this->query($sql, $fields)->getError()) {
                return true;
            }
        }
        return false;
    }

    private function generateUpdateSql($keys) {
        $result = '';
        $i = 0;
        foreach ($keys as $key) {
            if ($i == count($keys)-1) {
                $result .= $key . ' = ?';
            } else {
                $result .= $key . ' = ?, ';
            }
            $i++;
        }
        return $result;
    }
    # Helper function for the insert function.
    # Generates question marks for binding.
    private function generateQuestionMarks($count) {
        $result = '';
        for ($i = 0; $i < $count; $i++) {
            if ($i == $count - 1) {
                $result .= '?';
            } else {
                $result .= '?,';
            }
        }
        return $result;
    }
    public function getError() {
        return $this->_error;
    }

    public function getCount() {
        return $this->_count;
    }

    public function getFirst() {
        return $this->getResults()[0];
    }

    public function getResults() {
        return $this->_results;
    }
}