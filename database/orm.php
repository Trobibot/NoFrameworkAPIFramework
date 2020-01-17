<?php

    /** TODO
     * IMPROVEMENTS:
     *  [ ] Find a way to query through tables (pseudo graphQL)
     *  [X] Use real database
     *  [X] Constrain tables
     *  [X] Primary and foreign keys
     *  [ ] Delete action on constrained
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
                return false;
            $tableColumns = $this -> db -> getTableColumns($tableName);
            $ormTable = ORMTable::getInstance();
            $ormTable -> setName($tableName);
            $ormTable -> setColumns($tableColumns);
            return $ormTable;
        }

        public function addTable($tableName, $tableColumns) {
            if ($this -> db -> doesTableExists($tableName))
                ExceptionHandler::throw("Table " . $tableName . " already exist" , 1);
            return $this -> db -> createTable($tableName, $tableColumns);
        }

        public function setColumn($rowName) {
            return new ORMColumn($rowName);
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

        public function getColumn($columnName) {
            foreach ($this -> getColumns() as $column)
                if ($column -> getName() == $columnName) {
                    $column -> setTableName($this -> name);
                    return $column;
                }
        }

        public function addRow($data) {

            $obligatoryColumns = array_filter(
                $this -> getColumns(),
                function($item) {
                    return !$item -> getNullable() && !$item -> getPrimaryKey();
                }
            );

            // Test if all obligatory columns are provided
            $nonProvidedObligatoryColumns = array();
            foreach ($obligatoryColumns as $column)
                if (!array_key_exists($column -> getName(), $data))
                    array_push($nonProvidedObligatoryColumns, $column -> getName());

            if (count($nonProvidedObligatoryColumns) > 0)
                ExceptionHandler::throw("Column(s) " . implode(", ", $nonProvidedObligatoryColumns) . " can't be Null.", 1);

            // Test if columns from given data exists
            $providedColumns = array();
            foreach ($this -> columns as $column)
                if (array_key_exists($column -> getName(), $data))
                    array_push($providedColumns, $column -> getName());
            $notExistingColumns = array_diff(array_keys($data), $providedColumns);

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

    class ORMColumn extends ORM{

        private $table;
        private $name;
        private $type;
        private $length;
        private $defaultValue;
        private $nullable = false;
        private $foreignKey = false;
        private $primaryKey = false;
        private $autoIncrement = false;
        private $VALIDDATATYPES = ["TINYINT", "SMALLINT", "MEDIUMINT", "INT", "BIGINT", "FLOAT", "DOUBLE", "DATETIME", "DATE", "TIMESTAMP", "CHAR", "VARCHAR", "BINARY", "VARBINARY", "BLOB", "TEXT"];

        public function __construct($columnName) {
            if (!preg_match("/^[\w_-]+$/", $columnName))
                ExceptionHandler::throw("Data name in the column '" . $columnName . "' do not respect naming conventions.", 1);
            $this -> name = $columnName;
            parent::__construct();
        }

        public function toSQL() {
            $name           = $this -> getName();
            $type           = $this -> getType();
            $length         = $this -> getLength();
            $autoIncrement  = ($this -> getAutoIncrement() ? "AUTO_INCREMENT" : "");
            $nullable       = (!$this -> getNullable() ? "NOT NULL" : "");
            return "$name $type($length) $autoIncrement $nullable";
        }

        public function getName() {
            return $this -> name;
        }

        public function getTableName() {
            return $this -> table;
        }

        public function setTableName($table) {
            if (!$this -> db -> doesTableExists($table))
                ExceptionHandler::throw("Data table in the column '" . $this -> getName() . "' does not refer to an existing table.", 1);
            if (!is_null($this -> getTableName()))
                ExceptionHandler::throw("Data table in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> table = $table;
            return $this;
        }

        public function getType() {
            return $this -> type;
        }

        public function setType($type) {
            if (!in_array("INT", $this -> VALIDDATATYPES))
                ExceptionHandler::throw("Data type in the column '" . $this -> getName() . "' is not a valid data type.", 1);
            if (!is_null($this -> getType()))
                ExceptionHandler::throw("Data type in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> type = strtoupper($type);
            return $this;
        }

        public function getLength() {
            return $this -> length;
        }

        public function setLength($length) {
            if (gettype($length) != "integer")
                ExceptionHandler::throw("Data length in the column '" . $this -> getName() . "' is not an integer.", 1);
            if (!is_null($this -> getLength()))
                ExceptionHandler::throw("Data length in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> length = $length;
            return $this;
        }

        public function getNullable() {
            return $this -> nullable;
        }

        public function setNullable() {
            if ($this -> getNullable())
                ExceptionHandler::throw("Data nullable of column '" . $this -> getName() . "' is already defined.", 1);
            if (!is_null($this -> getDefaultValue()))
                ExceptionHandler::throw("Column '" . $this -> getName() . "' can't be nullable. Default value has already been defined.", 1);
            $this -> nullable = true;
            return $this;
        }

        public function getDefaultValue() {
            return $this -> defaultValue;
        }

        public function setDefaultValue($defaultValue) {
            if (!preg_match("/^[^;]+$/", $columnName))
                ExceptionHandler::throw("Data default value in the column '" . $this -> getName() . "' do not respect naming conventions.", 1);
            if (!is_null($this -> getDefaultValue()))
                ExceptionHandler::throw("Data default value in the column '" . $this -> getName() . "' is already defined.", 1);
            if ($this -> getNullable())
                ExceptionHandler::throw("Column '" . $this -> getName() . "' can't have a default value. Nullable has already been defined.", 1);
            $this -> defaultValue = $defaultValue;
            return $this;
        }

        public function getPrimaryKey() {
            return $this -> primaryKey;
        }

        public function setPrimaryKey() {
            if ($this -> getPrimaryKey())
                ExceptionHandler::throw("Data primary key in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> primaryKey = true;
            return $this;
        }

        public function getAutoIncrement() {
            return $this -> autoIncrement;
        }

        public function setAutoIncrement() {
            if ($this -> getAutoIncrement())
                ExceptionHandler::throw("Data auto increment in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> autoIncrement = true;
            return $this;
        }

        public function getForeignKey() {
            return $this -> foreignKey;
        }

        public function setForeignKey($targetColumn) {
            if ($this -> getForeignKey())
                ExceptionHandler::throw("Data foreign key in the column '" . $this -> getName() . "' is already defined.", 1);
            $this -> foreignKey = $targetColumn;
            return $this;
        }

    }