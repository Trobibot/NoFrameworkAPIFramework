<?php

    $usersRouter = new Router();

    $usersRouter -> get("/", [
        getUsers()
    ]);

    $usersRouter -> get("/:idUser", [
        getUserById()
    ]);

    $usersRouter -> get("/:idUser/contests", [
        getUserContests()
    ]);

    $usersRouter -> get("/:idUser/contests/:idContest", [
        getUserContestBy()
    ]);