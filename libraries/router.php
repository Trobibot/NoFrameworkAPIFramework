<?php

    class Router{

        public function __construct(){
            $this -> endpoint = null;
            $this -> middlewares = null;
            $this -> get = [];
            $this -> post = [];
        }

        public function get($url, $middlewares){
            $this -> get[$url] = $middlewares;
        }

        public function findPath($url){
            foreach (array_keys($this -> get) as $path) {
                $pathRegexp = preg_replace("/(|^)\\/(?!])/", "\\/", preg_replace("/:[^\\/]+/", "[^\\/]+", $path));
                if (preg_match("/^" . $pathRegexp . "$/", $url) > 0)
                    return $path;
            }
        }

        public function getParamOfPath($path, $url){
            $params = [];
            $pathChunks = explode("/", $path);
            $urlChunks = explode("/", $url);
            array_shift($pathChunks);
            array_shift($urlChunks);
            foreach($pathChunks as $key => $chunk)
                if (preg_match("/:[^\\/]+/", $chunk, $matches))
                    $params[ltrim($matches[0], ":")] = $urlChunks[$key];
            return $params;
        }

    }