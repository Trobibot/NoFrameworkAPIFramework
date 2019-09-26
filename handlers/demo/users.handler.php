<?php

    function getUsers(){
        return function(){
            Response::send(ORM::getInstance() -> getTable("USERS") -> getRowByQuery(), 200);
        };
    }

    function getUserById(){
        return function($idUser){
            Response::send(ORM::getInstance() -> getTable("USERS") -> getRowByQuery(["id" => $idUser]), 200);
        };
    }

    function getUserContests(){
        return function($idUser){
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(["first_player" => $idUser]), 200);
        };
    }

    function getUserContestBy(){
        return function($idUser, $idOpponent){
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(["first_player" => $idUser]), 200);
        };
    }


    // function addUser(){
    //     return function(){
    //         Response::send("NoFramework API V0.1", 200);
    //     };
    // }


    // function deleteUser(){
    //     return function(){
    //         Response::send("NoFramework API V0.1", 200);
    //     };
    // }
