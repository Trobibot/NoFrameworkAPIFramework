<?php

    final class App{

        static private $instance = null;

        private function __construct() {
            $this -> routers = array();
        }

        static public function getInstance() {
            if(is_null(self::$instance))
                self::$instance = new App();
            return self::$instance;
        }
        
        public function setEndpoint($url, $router, $middlewares = []){
            if(!isset($this -> routers[$url])){
                $router -> endpoint = $url;
                $router -> middlewares = $middlewares;
                $this -> routers[$url] = $router;
            } else {
                try {
                    throw new Exception($url . " is already used by :" . get_class($router), 403);
                } catch (Exception $exc) {
                    ExceptionHandler::catch($exc);
                }
            }
        }

        public function listen($url){
            try{
                if (BASE_URL)
                    $url = str_replace(BASE_URL, "", $url);
                $endpoint = $this -> findEndpoint($url);
                $router = $this -> routers[$endpoint];
                $method = $router -> getRequestMethod();
                $route = str_replace($endpoint, "", $url) === "" ? "/" : str_replace($endpoint, "", $url);
                $path = $router -> findPath($method, $route);
                $params = $router -> getParamOfPath($method, $route);
                $body = $router -> getRequestBody();
                if ($body)
                    $params = array_merge($params, $body);
                foreach ($router -> middlewares as $middleware) {
                    $middleware();
                }
                foreach ($router -> $method[$path] as $middleware) {
                    call_user_func_array($middleware, $params);
                }
            } catch (Exception $exc){
                ExceptionHandler::catch($exc);
            }
        }

        private function findEndpoint($url){
            foreach(array_keys($this -> routers) as $endpoint)
                if (preg_match("/^" . str_replace("/", "\\/", $endpoint) ."(\\/|$)/", $url, $match))
                    return "/" . trim($match[0], "/");
            throw new Exception("Not found", 404);
        }

    }