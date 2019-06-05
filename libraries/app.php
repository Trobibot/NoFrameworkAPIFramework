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
                throw new Exception($url . " is already used by :" . get_class($router));
            }
        }

        public function listen($url){
            $endpoint = $this -> findEndpoint($url);
            if (!$endpoint)
                throw new Exception("not found", 404);
            $router = $this -> routers[$endpoint];
            $route = str_replace($endpoint, "", $url) === "" ? "/" : str_replace($endpoint, "", $url);
            $path = $router -> findPath($route);
            $params = array_merge([new Response()], $router -> getParamOfPath($path, $route));
            foreach ($router -> get[$path] as $middelware) {
                call_user_func_array($middelware, $params);
            }
        }

        private function findEndpoint($url){
            $urlChunks = explode("/", ltrim($url, "/"));
            $goodURL = null;
            foreach ($urlChunks as $key => $value) {
                $testedURL = $value;
                if ($key > 0)
                    $testedURL = $urlChunks[$key - 1] . "\\/" . $value;
                $routerURLMatched = preg_grep("/^\\/" . $testedURL . "/", array_keys($this -> routers));
                if (count($routerURLMatched) > 0){
                    usort($routerURLMatched,'sortBySmallerString');
                    $goodURL = $routerURLMatched[0];
                } else {
                    return $goodURL;
                }
            }
            return $goodURL;
        }

    }