<?php

    $rootRouter = new Router();

    $rootRouter -> get("/", [
        getRoot()
    ]);
