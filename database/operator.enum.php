<?php

    class OperatorEnumerator {

        public static function get($enumerator) {
            $enumerators = [
                "lt"     => "<",
                "le"     => "<=",
                "gt"     => ">",
                "ge"     => ">=",
                "e"      => "=",
                "ne"     => "<>",
                "has"    => "REGEXP",
                "hasnot" => "NOT REGEXP"
            ];
            if (array_key_exists($enumerator, $enumerators))
                return $enumerators[$enumerator];
            else
                throw new Exception($enumerator . " is not a valid operator's enumerator", 400);
        }

    }