<?php

    function getRoot(){
        return function($resp){
            $resp -> send(json_encode("NoFramework API V0.1"), 200);
        };
    }
