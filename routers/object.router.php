<?php

    $objectRouter = new Router();

    $objectRouter -> get("/", [
        getObjects()
    ]);
    $objectRouter -> get("/:id", [
        getObjectById()
    ]);
