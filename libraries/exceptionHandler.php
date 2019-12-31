<?php

    final class ExceptionHandler{

        private function __construct(){}

        static public function catch(Exception $exc){
            Response::send($exc -> getMessage(), $exc -> getCode());
            exit;
        }

        static public function throw($message, $code) {
            try {
                throw new Exception($message , $code);
            } catch (Exception $exc) {
                self::catch($exc);
            }
        }

    }