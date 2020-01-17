<?php
    require_once (ROOT . "/public/routes/root.handler.php");
    require_once (ROOT . "/public/routes/root.router.php");
    require_once (ROOT . "/public/routes/users/users.handler.php");
    require_once (ROOT . "/public/routes/users/users.router.php");
    require_once (ROOT . "/public/routes/contests/contests.handler.php");
    require_once (ROOT . "/public/routes/contests/contests.router.php");

    $orm = ORM::getInstance();

    if (!$orm -> getTable("USERS") && !$orm -> getTable("CONTESTS")) {
        $orm -> addTable("USERS", [
            $orm -> setColumn("id")          -> setType("INT")       -> setLength(5) -> setPrimaryKey() -> setAutoIncrement(),
            $orm -> setColumn("nickname")    -> setType("VARCHAR")   -> setLength(255),
            $orm -> setColumn("password")    -> setType("VARCHAR")   -> setLength(255)
        ]);
        $orm -> addTable("CONTESTS", [
            $orm -> setColumn("id")          -> setType("INT") -> setLength(5) -> setPrimaryKey() -> setAutoIncrement(),
            $orm -> setColumn("first_user")  -> setType("INT") -> setLength(5) -> setForeignKey($orm -> getTable("USERS") -> getColumn("id")),
            $orm -> setColumn("second_user") -> setType("INT") -> setLength(5) -> setForeignKey($orm -> getTable("USERS") -> getColumn("id")),
            $orm -> setColumn("winner")      -> setType("INT") -> setLength(5) -> setForeignKey($orm -> getTable("USERS") -> getColumn("id")) -> setNullable()
        ]);

        $bradleyId  = $orm -> getTable("USERS") -> addRow(["nickname" => "Bradley",     "password" => "Pirate" ]);
        $bertilleId = $orm -> getTable("USERS") -> addRow(["nickname" => "Bertille",    "password" => "Crochue" ]);
        $jeanId     = $orm -> getTable("USERS") -> addRow(["nickname" => "Jean",        "password" => "Guy" ]);

        $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bradleyId,     "second_user" => $bertilleId,   "winner" => $bradleyId]);
        $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bradleyId,     "second_user" => $jeanId,       "winner" => $bradleyId]);
        $orm -> getTable("CONTESTS") -> addRow(["first_user" => $bertilleId,    "second_user" => $jeanId]);
    }


    $app = App::getInstance();
    $app -> setEndpoint("/", $rootRouter);
    $app -> setEndpoint("/users", $usersRouter);
    $app -> setEndpoint("/contests", $contestsRouter);
