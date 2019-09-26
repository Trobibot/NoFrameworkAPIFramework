<?php

    /** TODO
     * IMPROVEMENTS:
     *  [ ] Querys to browers table's rows (pseudo graphQL)
     *  [ ] Use real database
     *  [ ] Constrain tables
     *  [ ] Primary and foreign keys
     * 
     * BUGS:
     *  [ ] 2 tables can have the same name
     * 
     * REWORKS:
     *  [ ] Exception handling
    */

    class ORM {

        static private $instance = null;

        private function __construct() {
            $this -> tables = array();
        }

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new ORM();
            return self::$instance;
        }

        public function newTable($tableName, $columnsName) {
            $this -> tables[$tableName] = new Table($tableName, $columnsName);
        }

        public function getTable($tableName = null) {
            return isset($tableName) ? $this -> tables[$tableName] : $this -> tables;
        }

    }

    class Table {

        public function __construct($tableName, $columnsName) {
            $this -> name = $tableName;
            $this -> rows = array();
            $this -> columnsName = array();
            $this -> lastId = 0;
            $this -> setColumnsName($columnsName);
        }

        private function setColumnsName($columnsName) {
            foreach (array_merge(["id"], $columnsName) as $columnName) {
                if (in_array($columnName, $this -> columnsName)) {
                    try {
                        throw new Exception($columnName . " is already used in table : " . $this -> name , 1);
                    } catch (Exception $exc) {
                        ExceptionHandler::catch($exc);
                    }
                }
                array_push($this -> columnsName, $columnName);
            }
        }

        public function newRow($columnsData) {
            $tempRow = array_fill_keys($this -> columnsName, null);
            foreach ($columnsData as $columnName => $columnData) {
                if (!in_array($columnName, $this -> columnsName)) {
                    try {
                        throw new Exception($this -> name . " do not contain a column named : " . $columnName, 1);
                    } catch (Exception $exc) {
                        ExceptionHandler::catch($exc);
                    }
                }
                $tempRow[$columnName] = $columnData;
            }
            $tempRow["id"] = ++$this -> lastId;
            array_push($this -> rows, $tempRow);
        }

        public function getRowByQuery($queries = null) {
            if (is_null($queries))
                return $this -> rows;
             ### TEMP CODE ###
             return $this -> rows;
        }

    }