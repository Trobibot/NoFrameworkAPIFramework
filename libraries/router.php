<?php

    class Router{

        public function __construct(){
            $this -> endpoint = null;
            $this -> middlewares = null;
            $this -> get = [];
            $this -> head = [];
            $this -> post = [];
            $this -> put = [];
            $this -> delete = [];
            $this -> connect = [];
            $this -> options = [];
            $this -> trace = [];
            $this -> patch = [];
        }

        public function get($url, $middlewares){
            $this -> get[$url] = $middlewares;
        }

        public function head($url, $middlewares){
            $this -> head[$url] = $middlewares;
        }

        public function post($url, $middlewares){
            $this -> post[$url] = $middlewares;
        }

        public function put($url, $middlewares){
            $this -> put[$url] = $middlewares;
        }

        public function delete($url, $middlewares){
            $this -> delete[$url] = $middlewares;
        }

        public function connect($url, $middlewares){
            $this -> connect[$url] = $middlewares;
        }

        public function options($url, $middlewares){
            $this -> options[$url] = $middlewares;
        }

        public function trace($url, $middlewares){
            $this -> trace[$url] = $middlewares;
        }

        public function patch($url, $middlewares){
            $this -> patch[$url] = $middlewares;
        }

        public function findPath($method, $url) {
            foreach (array_keys($this -> $method) as $path) {
                $pathRegexp = "/^" . preg_replace("/\//", "\\/", preg_replace("/(?!\\/):[^\\/]+/", "([^/]+)", $path)) . "$/";
                if (preg_match_all($pathRegexp, $url) > 0)
                    return $path;
            }
            return null;
        }

        public function getParamOfPath($method, $url){
            $pathParamsName = array();
            $tempPathParams = array();
            $pathParams = array();
            foreach (array_keys($this -> $method) as $path) {
                preg_match_all("/(?!\\/)(?::)([^\\/]+)/", $path, $pathParamsName);
                $pathRegexp = "/^" . preg_replace("/\//", "\\/", preg_replace("/(?!\\/):[^\\/]+/", "([^/]+)", $path)) . "$/";
                if (preg_match_all($pathRegexp, $url, $tempPathParams) > 0) {
                    $pathParamsName = array_flatten(array_slice($pathParamsName, 1));
                    $tempPathParams = array_flatten(array_slice($tempPathParams, 1));
                    foreach ($pathParamsName as $key => $paramName)
                        $pathParams[$paramName] = $tempPathParams[$key];
                    return $pathParams;
                }
            }
            return null;
        }

        public function getRequestMethod(){
            $method = strtolower($_SERVER["REQUEST_METHOD"]);
            if(isset($this -> $method) && count($this -> $method) > 0)
                return $method;
            else
                throw new Exception("Method not allowed", 405);
        }

        public function getRequestBody(){
            $body = file_get_contents('php://input');
            return $body != "" ? ["body" => json_decode($body, true)] : null;
        }

    }