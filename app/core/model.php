<?php

class Model
{
    public $model;
    public $db;
    public $logger;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->setConnectDb();
        $this->logger = new Katzgrau\KLogger\Logger(__DIR__.'/../logs');
    }

    /**
     * Set connect to DataBase
     */
    private function setConnectDb()
    {
        global $connectDB;

        if (empty($connectDB)) {
            $config    = require(__DIR__ . '/../../config/config.php');
            $database  = $config['database'];
            $connectDB = new mysqli(
                $database['host'],
                $database['user'],
                $database['password'],
                $database['database']
            );
        }
        $this->db = $connectDB;
        if ($this->db->connect_errno) {
            printf("Соединение не удалось: %s\n", $this->db->connect_error);
            exit();
        }
    }

    /**
     * @param $table
     * @param array $param
     * @return array
     */
    public function find($table, array $param = [])
    {
        $sql = 'SELECT * FROM ' . $table;
        if (!empty($param)) {
            $sql .= ' WHERE ';
            $sqlParamArray = $this->getParamSqlQuryArray($param);
            $sql .= implode(" AND ", $sqlParamArray);
        }
        $result = $this->exeSql($sql);

        return $result;
    }

    /**
     * @param $sql
     * @return array
     */
    public function exeSql($sql)
    {
        $returnResult = [];
        $result       = $this->db->query($sql, MYSQLI_USE_RESULT);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $returnResult[] = $row;
            }
            $result->close();
        }

        return $returnResult;
    }

    /**
     * Withdraws funds from the account
     *
     * @param $table
     * @param array $params
     * @return bool
     */
    public function updateFinances($table, array $params)
    {
        $this->db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
        foreach ($params as $id => $sum) {
            $sql = 'UPDATE ' . $table . ' SET `sum` = IF (`sum`-' . $sum . ' < 0, `sum`, `sum`-' . $sum . ')';
            $sql .= ' WHERE id="' . $this->escapeString($id) . '";' . "\n";
            $this->db->query($sql);
        }

        if (! $this->db->commit()) {
            return false;
        }

        return true;
    }


    /**
     * @param $table
     * @param array $params
     */
    public function updateAllTransaction($table, array $params)
    {
        $this->db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
        foreach ($params as $id => $param) {
            $sql           = 'UPDATE ' . $table . ' SET ';
            $sqlParamArray = $this->getParamSqlQuryArray($param);
            $sql           .= implode(", ", $sqlParamArray);
            $sql           .= ' WHERE id="' . $this->escapeString($id) . '";' . "\n";
            $this->db->query($sql);
        }

        if (! $this->db->commit()) {
            return false;
        }

        return true;
    }

    /**
     * @param $param
     * @return array
     */
    public function getParamSqlQuryArray($param)
    {
        $sqlParamArray = [];
        foreach ($param as $keyParam => $valueParam) {
            $keyParam = $this->escapeString($keyParam);
            $valueParam = $this->escapeString($valueParam);
            $sqlParamArray[] = '`' . $keyParam . '` = "' . $valueParam . '"';
        }
        return $sqlParamArray;
    }

    /**
     * @param $param
     * @return string
     */
    public function escapeString($param)
    {
        return $this->db->real_escape_string($param);
    }


}
