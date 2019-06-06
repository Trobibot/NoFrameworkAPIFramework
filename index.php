<?php

    define('ROOT', dirname(__FILE__));
        
    require_once (ROOT . "/bin/conf/config.php");
    require_once (ROOT . "/libraries/main.php");
    require_once (ROOT . "/libraries/app.php");
    require_once (ROOT . "/libraries/router.php");
    require_once (ROOT . "/libraries/handler.php");
    require_once (ROOT . "/libraries/validator.php");
    require_once (ROOT . "/libraries/response.php");
    require_once (ROOT . "/libraries/exceptionHandler.php");
    require_once (ROOT . "/tools/tools.php");

    /*** IMPORT USER SCRIPTS ***/
    require_once (ROOT . "/tools/fileReader.php");
    require_once (ROOT . "/handlers/root.handler.php");
    require_once (ROOT . "/routers/root.router.php");
    require_once (ROOT . "/handlers/object.handler.php");
    require_once (ROOT . "/routers/object.router.php");
    require_once (ROOT . "/handlers/behavior.handler.php");
    require_once (ROOT . "/routers/behavior.router.php");

    $app = new App();
    $app -> setEndpoint("/", $rootRouter);
    $app -> setEndpoint("/objects", $objectRouter);
    $app -> setEndpoint("/behaviors", $behaviorRouter);

    // $app -> setEndpoint(
    //     "/tests",
    //     $testRouter,
    //     [
    //         new TestValidator(),
    //     ]);

    header('Content-Type: ' . CONTENT_TYPE);
    $app -> listen($_SERVER["REQUEST_URI"]);