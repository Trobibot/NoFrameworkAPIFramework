<?php

    function getBehaviors(){
        return function(){
            Response::send(resources_extractor_recursive(ROOT . "/resources/behaviors"), 200);
        };
    }

    function getBehaviorById(){
        return function($id){
            Response::send(resources_extractor_recursive(ROOT . "/resources/behaviors/" . $id . ".json"), 200);
        };
    } 