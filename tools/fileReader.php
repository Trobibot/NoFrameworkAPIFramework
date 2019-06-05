<?php

    function resources_extractor($path){
        if(is_file($path))
            return json_decode(file_get_contents($path));
        else
            return array_values(array_diff(scandir($path), [".", ".."]));
    }

    function resources_extractor_recursive($path){
        $ressources;
        $targetContent = resources_extractor($path);
        if (is_array($targetContent)){
            $ressources = [];
            foreach($targetContent as $content){
                $contentPath = $path . "/" . $content;
                if(is_file($contentPath) || is_dir($contentPath))
                    $ressources[get_name($contentPath)] = resources_extractor_recursive($contentPath);
            }
        } else {
            $ressources = $targetContent;
        }
        return $ressources;
    }

    function get_name($path){
        preg_match("/([-\w\d]+)(?=\.json$)/", $path, $matches);
        return $matches[0];
    }

?>