<?php

    // Init Framework
    define('ROOT', dirname(__FILE__));
    require_once (ROOT . "/bin/conf/config.php");
    require_once (ROOT . "/libraries/initConf.php");

    // Import Framework's Libraries
    require_once (ROOT . "/libraries/app.php");
    require_once (ROOT . "/libraries/router.php");
    require_once (ROOT . "/libraries/handler.php");
    require_once (ROOT . "/libraries/response.php");
    require_once (ROOT . "/libraries/exceptionHandler.php");
    require_once (ROOT . "/tools/tools.php");

    // Import User's Scripts
    require_once (ROOT . "/database/orm.php");
    require_once (ROOT . "/tools/fileReader.php");
    require_once (ROOT . "/handlers/root.handler.php");
    require_once (ROOT . "/routers/root.router.php");
    require_once (ROOT . "/handlers/object.handler.php");
    require_once (ROOT . "/routers/object.router.php");
    require_once (ROOT . "/handlers/behavior.handler.php");
    require_once (ROOT . "/routers/behavior.router.php");

    $orm = ORM::getInstance();
    $orm -> newTable("USERS",    ["nickname", "password"]);
    $orm -> newTable("CONTESTS", ["first_player", "second_player", "winner"]);

    $orm -> getTable("USERS")    -> newRow(["nickname"      => "Bradley"    , "password"      => "Pirate"   ]);
    $orm -> getTable("USERS")    -> newRow(["nickname"      => "Bertille"   , "password"      => "Crochue"  ]);
    $orm -> getTable("USERS")    -> newRow(["nickname"      => "Jean"       , "password"      => "Guy"      ]);
    $orm -> getTable("CONTESTS") -> newRow(["first_player"  => "1"          , "second_player" => "2"        , "winner" => "1"   ]);
    $orm -> getTable("CONTESTS") -> newRow(["first_player"  => "1"          , "second_player" => "3"        , "winner" => "3"   ]);
    $orm -> getTable("CONTESTS") -> newRow(["first_player"  => "2"          , "second_player" => "3"        , "winner" => "tie" ]);

    $app -> setEndpoint("/users", $usersRouter);
    $app -> setEndpoint("/objects", $objectRouter);
    $app -> setEndpoint("/behaviors", $behaviorRouter);

    header('Content-Type: ' . CONTENT_TYPE);
    $app -> listen($_SERVER["REQUEST_URI"]);