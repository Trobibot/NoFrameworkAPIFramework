<?php

    /** TODO
     * IMPROVEMENTS:
     *  [ ] Find a way to query through tables (pseudo graphQL)
     *  [ ] Use real database
     *  [ ] Constrain tables
     *  [ ] Primary and foreign keys
     * 
     * BUGS:
     *  [X] 2 tables can have the same name
     * 
     * REWORKS:
     *  [X] Exception handling
    */

    class ORM {

        static private $instance = null;

        private function __construct() {
            $this -> tablesName = array();
        }

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new ORM();
            return self::$instance;
        }

        public function newTable($tableName, $columnsName) {
            if (in_array($tableName, $this -> tablesName))
                ExceptionHandler::throw($tableName . " already exist" , 1);
            array_push($this -> tablesName, $tableName);
            $this -> tables[$tableName] = new Table($tableName, $columnsName);
        }

        public function getTable($tableName) {
            if (!in_array($tableName, $this -> tablesName))
                ExceptionHandler::throw($tableName . " do not exist" , 1);
            return $this -> tables[$tableName];
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
                if (in_array($columnName, $this -> columnsName))
                    ExceptionHandler::throw($columnName . " is already used in table: " . $this -> name, 1);
                array_push($this -> columnsName, $columnName);
            }
        }

        public function newRow($columnsData) {
            $tempRow = array_fill_keys($this -> columnsName, null);
            foreach ($columnsData as $columnName => $columnData) {
                if (!in_array($columnName, $this -> columnsName))
                    ExceptionHandler::throw($this -> name . " do not contain a column named: " . $columnName, 1);
                $tempRow[$columnName] = $columnData;
            }
            $tempRow["id"] = ++$this -> lastId;
            array_push($this -> rows, $tempRow);
        }

        public function getRowByQuery($queries = null) {
            if (is_null($queries))
                return $this -> rows;
             ### TEMP CODE ###
             return $queries;
        }

    }