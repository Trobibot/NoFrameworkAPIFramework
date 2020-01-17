<?php

    function getUsers(){
        return function($body = null){
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
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(["first_user" => $idUser]), 200);
        };
    }

    function getUserContestBy(){
        return function($idUser, $idContest){
            Response::send(ORM::getInstance() -> getTable("CONTESTS") -> getRowByQuery(["first_user" => $idUser, "id" => $idContest]), 200);
        };
    }

    function addUser(){
        return function($userData){
            Response::send(ORM::getInstance() -> getTable("USERS") -> addRow($userData), 200);
        };
    }

    function deleteUser(){
        return function($userId){
            Response::send(ORM::getInstance() -> getTable("USERS") -> deleteRow($userId), 200);
        };
    }
