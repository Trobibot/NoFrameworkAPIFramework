<?php

    function getRoot(){
        return function(){
            Response::send("NoFramework API V0.1", 200);
        };
    }
