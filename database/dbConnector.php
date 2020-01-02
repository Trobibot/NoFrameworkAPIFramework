<?php

    /**
     * IMPROVMENT
     * [ ] Add switchs in query functions to handle differents driver
     */

    class DBConnector {

        static private $instance = null;
        private $db;

        private function __construct() {
            $this -> db = new PDO(DB_DRIVER . ":host=" . DB_SERVER_NAME . ";port=" . DB_SERVER_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        }

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new DBConnector();
            return self::$instance;
        }

        public function doesTableExists($tableName) {
            $query = $this -> db -> prepare("SHOW TABLES LIKE :tableName");
            $query -> bindValue(":tableName", $tableName, PDO::PARAM_STR);
            $query -> execute();
            return !!$query -> fetch();
        }

        public function getTableColumns($tableName) {
            $query = $this -> db -> prepare("SHOW COLUMNS FROM $tableName");
            $query -> execute();
            $tempArray = array();
            foreach ($query -> fetchAll(PDO::FETCH_ASSOC) as $item) {
                $tempArray[$item["Field"]] = [
                    "canBeNull" => $item["Null"] == "NO" ? false : true,
                    "defaultValue" => $item["Default"],
                    "isAutoIncrement" => $item["Extra"] == "auto_increment" ? true : false
                ];
            }
            return $tempArray;
        }

        public function insert($tableName, $data) {
            $strProvidedColumns = implode(", ", array_keys($data));
            $strProvidedValues = implode(", ", array_map(function($item) { return ":$item"; }, array_keys($data)));
            $sqlQuery = "INSERT INTO $tableName ($strProvidedColumns) VALUES ($strProvidedValues)";

            $query = $this -> db -> prepare($sqlQuery);
            foreach ($data as $key => $value)
                $query -> bindValue(":$key", $value, PDO::PARAM_STR);
            $isRowInserted = $query -> execute();

            return !$isRowInserted ? $isRowInserted : $this -> db -> lastInsertId();
        }

        public function select($tableName, $filters = []) {
            $sqlFilters = empty($filters) ? "" : " WHERE " . implode(" AND ", array_map(function($item) { return "$item = :$item"; }, array_keys($filters)));
            $sqlQuery = "SELECT * FROM $tableName $sqlFilters";

            $query = $this -> db -> prepare($sqlQuery);

            foreach ($filters as $key => $value)
                $query -> bindValue(":$key", $value, PDO::PARAM_STR);
            $query -> execute();
            return $query -> fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete($tableName, $rowId = null) {
            $sqlFilters = is_null(rowId) ? "" : " WHERE id = :id";
            $sqlQuery = "DELETE FROM $tableName";
            
            $query = $this -> db -> prepare($sqlQuery);
            $query -> bindValue(":id", $rowId, PDO::PARAM_INT);
            return $query -> execute();
        }

    }