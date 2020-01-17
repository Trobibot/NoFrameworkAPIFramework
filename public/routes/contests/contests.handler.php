<?php

    function getContests(){
        return function($body = null){
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(), 200);
        };
    }

    function getContest(){
        return function($idContest){
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(["id" => $idContest]), 200);
        };
    }