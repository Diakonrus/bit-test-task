<?php

/**
 * Mysql database class - only one connection alowed (singleton)
 *
 * Class DB
 */
class DB
{
    private $_connection;
    private static $_instance;

    /**
     * Get an instance of the Database
     *
     * @return DB
     */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * DB constructor.
     */
    private function __construct()
    {
        $config = require(__DIR__ . '/../../config/config.php');
        $database = $config['database'];

        $this->_connection = new mysqli(
            $database['host'],
            $database['user'],
            $database['password'],
            $database['database']
        );

        // Error handling
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                E_USER_ERROR);
        }
    }

    /**
     * Magic method clone is empty to prevent duplication of connection
     */
    private function __clone()
    {
    }

    /**
     * Get mysqli connection
     *
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}