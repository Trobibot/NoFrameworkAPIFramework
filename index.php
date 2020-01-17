<?php

    // Init Framework
    define('ROOT', dirname(__FILE__));
    require_once (ROOT . "/bin/conf/app.config.php");
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
    require_once (ROOT . "/public/" . USER_ROOT);

    App::getInstance() -> listen($_SERVER["REQUEST_URI"]);