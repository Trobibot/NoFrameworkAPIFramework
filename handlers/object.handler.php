<?php

    function getObjects(){
        return function(){
            Response::send(resources_extractor_recursive(ROOT . "/resources/objects"), 200);
        };
    }

    function getObjectById(){
        return function($id){
            Response::send(resources_extractor_recursive(ROOT . "/resources/objects/" . $id . ".json"), 200);
        };
    }

    function postObject(){
        return function($body){
            $uuid = getUUID();
            file_put_contents(ROOT . "/resources/objects/" . $uuid . ".json", $body["content"]);
            Response::send($uuid, 200);
        };
    }