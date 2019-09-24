<?php

    final class Response{

        private function __construct(){}

        static public function send($content, $status){
            http_response_code($status);
            echo json_encode($content);
        }

    }