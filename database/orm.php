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
        protected $db;

        protected function __construct() {
            $this -> db = DBConnector::getInstance();
        }

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new ORM();
            return self::$instance;
        }

        public function getTable($tableName) {
            if (!$this -> db -> doesTableExists($tableName))
                ExceptionHandler::throw($tableName . " do not exist" , 1);
            $tableColumns = $this -> db -> getTableColumns($tableName);
            $ormTable = ORMTable::getInstance();
            $ormTable -> setName($tableName);
            $ormTable -> setColumns($tableColumns);
            return $ormTable;
        }

    }

    class ORMTable extends ORM {

        static private $instance = null;
        private $name;
        private $columns;

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new ORMTable();
            return self::$instance;
        }

        protected function setName($name) {
            $this -> name = $name;
        }

        public function getName($name) {
            return $this -> name;
        }

        protected function setColumns($columns) {
            $this -> columns = $columns;
        }

        public function getColumns() {
            return $this -> columns;
        }

        public function addRow($data) {
            $obligatoryColumns = array_filter(
                $this -> getColumns(),
                function($item) {
                    return !$item["canBeNull"] && !$item["isAutoIncrement"];
                }
            );

            // Test if all obligatory columns are provided
            $nonProvidedObligatoryColumns = array();
            foreach ($obligatoryColumns as $columnName => $columnValue)
                if (!array_key_exists($columnName, $data))
                    array_push($nonProvidedObligatoryColumns, $columnName);
            if (count($nonProvidedObligatoryColumns) > 0)
                ExceptionHandler::throw("Column(s) " . implode(", ", $nonProvidedObligatoryColumns) . " can't be Null.", 1);

            // Test if columns from given data exists
            $notExistingColumns = array();
            foreach ($data as $columnName => $columnValue)
                if (!array_key_exists($columnName, $this -> columns))
                    array_push($notExistingColumns, $columnName);
            if (count($notExistingColumns) > 0)
                ExceptionHandler::throw("This column(s) " . implode(", ", $notExistingColumns) . " doesn't exists.", 1);

            $rowId = $this -> db -> insert($this -> name, $data);
            if(!$rowId)
                ExceptionHandler::throw("Could not insert row: [" . implode(", ", $data) . "]", 1);

            return $rowId;
        }

        public function getRowByQuery($filters = []) {
            return $this -> db -> select($this -> name, $filters);
        }

        public function deleteRow($rowId = null) {
            return $this -> db -> delete($this -> name, $rowId);
        }

    }

    /*
    class ORM {

        static private $instance = null;

        private function __construct() {
            $this -> tablesName = array();
            $this -> tables = array();
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
    */