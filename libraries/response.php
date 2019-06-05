<?php

    class Response{

        public function __construct(){

        }

        public function send($content, $status){
            http_response_code($status);
            echo $content;
        }

    }