<?php

    // Set Reporting
    if (DEV_ENV) {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . "/bin/logs/error.log");
    }

    // Rremove Magic Quotes
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }

    function stripSlashesDeep($value) {
        return is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    }