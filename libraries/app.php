<?php

    final class App{

        private function __construc(){
            $this -> routers = [];
        }
        
        public function setEndpoint($url, $router, $middelwares = []){
            if(!isset($this -> routers[$url])){
                $router -> endpoint = $url;
                $router -> middelwares = $middelwares;
                $this -> routers[$url] = $router;
            } else {
                try {
                    throw new Exception($url . " is already used by :" . get_class($router), 403);
                } catch (Exception $exc) {
                    catchException($exc);
                }
            }
        }

        public function listen($url){
            try{
                $endpoint = $this -> findEndpoint($url);
                $router = $this -> routers[$endpoint];
                $method = $router -> getRequestMethod();
                $route = str_replace($endpoint, "", $url) === "" ? "/" : str_replace($endpoint, "", $url);
                $path = $router -> findPath($method, $route);
                $params = array_merge([new Response()], $router -> getParamOfPath($path, $route));
                foreach ($router -> $method[$path] as $middelware) {
                    call_user_func_array($middelware, $params);
                }
            } catch (Exception $exc){
                catchException($exc);
            }
        }

        private function findEndpoint($url){
            foreach(array_keys($this -> routers) as $endpoint)
                if (preg_match("/^" . str_replace("/", "\\/", $endpoint) ."(\\/|$)/", $url, $match))
                    return "/" . trim($match[0], "/");
            throw new Exception("Not found", 404);
        }

    }