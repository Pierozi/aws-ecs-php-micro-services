<?php

require_once dirname(__DIR__, 1)
    .DIRECTORY_SEPARATOR
    .'vendor'
    .DIRECTORY_SEPARATOR
    .'autoload.php';

$protocol = Hoa\Protocol\Protocol::getInstance();
$protocol[] = new Hoa\Protocol\Node('Config', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'config');

function appConfig() {
    static $config;

    if (null !== $config) {
        return $config;
    }

    $config = parse_ini_file(resolve('hoa://Config') . '/app.ini', true);
    foreach ($config as & $v) {
        $v = (object)$v;
    }

    return $config = (object)$config;
}

function ver() {
    static $version;

    if (null !== $version) {
        return $version;
    }

    $composer = json_decode(file_get_contents(dirname(__DIR__) . '/composer.json'));
    return $version = $composer->version;
}

ini_set('error_reporting', E_ALL);

if ('testing' === appConfig()->general->env) {
    define('__DEV_MODE__', true);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

\Hoa\Exception\Error::enableErrorHandler(true);

/*
 * PHP Settings
 */
date_default_timezone_set('Europe/Paris');