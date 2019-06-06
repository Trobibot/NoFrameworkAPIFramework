<?php

    function getObjects(){
        return function($resp){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/objects")), 200);
        };
    }

    function getObjectById(){
        return function($resp, $id){
            $resp -> send(json_encode(resources_extractor_recursive(ROOT . "/resources/objects/" . $id . ".json")), 200);
        };
    }

    function postObject(){
        return function($resp, $body){
            $uuid = getUUID();
            file_put_contents(ROOT . "/resources/objects/" . $uuid . ".json", $body["content"]);
            $resp -> send(json_encode($uuid), 200);
        };
    }