<?php

class Model
{
    public $db;
    public $model;
    public $logger;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $mysqli   = DB::getInstance();
        $this->db = $mysqli->getConnection();

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
        $sql          .= " FOR UPDATE;";
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
        $sqlQuery = [];
        foreach ($params as $id => $sum) {
            $sql        = 'UPDATE ' . $table . ' SET `sum` = `sum`-' . $sum;
            $sql        .= ' WHERE id="' . $this->escapeString($id) . '";' . "\n";
            $sqlQuery[] = trim($sql);
        }

        if (count($sqlQuery) > 1) {
            $this->db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
        }

        foreach ($sqlQuery as $sql) {
            $this->db->query($sql);
            if ($this->db->sqlstate == 45000) {
                if (count($sqlQuery) > 1) {
                    $this->db->rollback();
                }
                return false;
            }
        }

        if (count($sqlQuery) > 1 && ! $this->db->commit()) {
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
