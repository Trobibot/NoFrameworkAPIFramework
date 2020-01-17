<?php

    $contestsRouter = new Router();

    $contestsRouter -> get("/", [
        getContests()
    ]);

    $contestsRouter -> get("/:idContest", [
        getContest()
    ]);
