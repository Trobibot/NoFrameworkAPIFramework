<?php

    $behaviorRouter = new Router();

    $behaviorRouter -> get("/", [
        getBehaviors()
    ]);
    $behaviorRouter -> get("/:id", [
        getBehaviorById()
    ]);
