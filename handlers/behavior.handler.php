<?php

    function getBehaviors(){
        return function($resp){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/behaviors")), 200);
        };
    }

    function getBehaviorById(){
        return function($resp, $id){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/behaviors/" . $id . ".json")), 200);
        };
    } 