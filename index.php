<?php

    // Init Framework
    define('ROOT', dirname(__FILE__));
    require_once (ROOT . "/bin/conf/config.php");
    require_once (ROOT . "/libraries/initConf.php");
    require_once (ROOT . "/bin/conf/db.config.php");

    // Import Framework's Libraries
    require_once (ROOT . "/database/dbConnector.php");
    require_once (ROOT . "/database/operator.enum.php");
    require_once (ROOT . "/database/orm.php");
    require_once (ROOT . "/libraries/app.php");
    require_once (ROOT . "/libraries/router.php");
    require_once (ROOT . "/libraries/handler.php");
    require_once (ROOT . "/libraries/response.php");
    require_once (ROOT . "/libraries/exceptionHandler.php");
    require_once (ROOT . "/tools/tools.php");

    // Import User's Scripts
    require_once (ROOT . "/path/root.handler.php");
    require_once (ROOT . "/path/root.router.php");
    require_once (ROOT . "/path/users/users.handler.php");
    require_once (ROOT . "/path/users/users.router.php");

    $orm = ORM::getInstance();
    $orm -> getTable("USERS")       -> deleteRow();
    $orm -> getTable("CONTESTS")    -> deleteRow();

    $bradleyId  = $orm -> getTable("USERS") -> addRow(["nickname" => "Bradley"    , "password" => "Pirate" ]);
    $bertilleId = $orm -> getTable("USERS") -> addRow(["nickname" => "Bertille"   , "password" => "Crochue" ]);
    $jeanId     = $orm -> getTable("USERS") -> addRow(["nickname" => "Jean"       , "password" => "Guy" ]);

    $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bradleyId    , "second_user" => $bertilleId  , "winner" => $bradleyId]);
    $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bradleyId    , "second_user" => $jeanId      , "winner" => $bertilleId]);
    $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bertilleId   , "second_user" => $jeanId      , "winner" => "-1"]);

    $app = App::getInstance();
    $app -> setEndpoint("/", $rootRouter);
    $app -> setEndpoint("/users", $usersRouter);

    header('Content-Type: ' . CONTENT_TYPE);
    $app -> listen($_SERVER["REQUEST_URI"]);