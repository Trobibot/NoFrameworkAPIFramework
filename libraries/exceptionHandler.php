<?php

    final class ExceptionHandler{

        private function __construct(){}

        static public function catchException(Exception $exc){
            Response::send($exc -> getMessage(), $exc -> getCode());
            exit;
        }

    }