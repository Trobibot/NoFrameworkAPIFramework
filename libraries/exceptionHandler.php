<?php

    function catchException(Exception $exc){
        $resp = new Response();
        $resp -> send(json_encode($exc -> getMessage()), $exc -> getCode());
    }

    // final class ExceptionHandler{

    //     public function __construct(){
    //         $this -> resp = new Response();
    //     }

    //     public function catchException(Exception $exc){
    //         $this -> resp -> send($exc -> getMessage(), $exc -> getCode());
    //     }

    // }