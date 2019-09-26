<?php

    // function sortBySmallerString($a, $b){
    //     return strlen($a) - strlen($b);
    // }

    // function getUUID() {
    //     return sprintf(
    //         '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    //         mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    //         mt_rand( 0, 0xffff ),
    //         mt_rand( 0, 0x0fff ) | 0x4000,
    //         mt_rand( 0, 0x3fff ) | 0x8000,
    //         mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    //     );
    // }

    /**
     * Flatten an array recursively
     * @param Array $a The array to flatten
     * @param Boolean $kk Does the function keep keys
     * @param Array $af The flatten array
     */
    function array_flatten($a, $kk = false, $af = array()) {
        foreach ($a as $k => $v) {
            if (!is_array($v)) {
                if ($kk)
                    $af[$k] = $v;
                else
                    array_push($af, $v);
            } else {
                $af = array_flatten($v, $kk, $af);
            }
        }
        return $af;
    }