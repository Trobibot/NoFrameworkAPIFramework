<?php

    function getObjects(){
        return function($resp){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/objects")), 404);
        };
    }

    function getObjectById(){
        return function($resp, $id){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/objects/" . $id . ".json")), 200);
        };
    } 