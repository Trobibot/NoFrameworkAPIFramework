<?php

    /**
     * IMPROVMENT
     * [ ] Add switchs in query functions to handle differents driver
     */

    class DBConnector {

        static private $instance = null;

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
            // return array_map(
            //     function($item) {
            //         return [
            //             "name" => $item["Field"],
            //             "canBeNull" => $item["Null"] == "NO" ? false : true,
            //             "defaultValue" => $item["Default"]
            //         ];
            //     },
            //     $query -> fetchAll(PDO::FETCH_ASSOC)
            // );
        }

        public function insert($tableName, $data) {
            $strProvidedColumns = implode(", ", array_keys($data));
            $strProvidedValues = array_map(function($item) { return $item + 1; }, array_keys($data));
            $sqlQuery = "INSERT INTO $tableName ($strProvidedColumns) VALUES ('$strProvidedValues')";

            var_dump($sqlQuery);

            $query = $this -> db -> prepare($sqlQuery);
            $query -> execute(array_values($data));
            return $query -> fetch();
        }

    }