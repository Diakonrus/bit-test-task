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
        $config = require(__DIR__ . '/../../config/config.php');
        $database = $config['database'];
        $this->db = new mysqli($database['host'], $database['user'], $database['password'], $database['database']);
        if ($this->db->connect_errno) {
            printf("Соединение не удалось: %s\n", $this->db->connect_error);
            exit();
        }
        $this->logger = new Katzgrau\KLogger\Logger(__DIR__.'/../logs');
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
        $this->db->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
        $result = $this->db->query($sql, MYSQLI_USE_RESULT);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $returnResult[] = $row;
            }
            $result->close();
        }
        $this->db->commit();

        return $returnResult;
    }

    /**
     * @param $table
     * @param array $params
     */
    public function updateAllTransaction($table, array $params)
    {
        $this->db->autocommit(FALSE);
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
