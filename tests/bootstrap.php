<?php
/**
 * One of the curl - based HTTP libraries implemented in PHP
 * bootstrap
 * 
 * @category Crul_Http
 * @package  Qinqw\Curl\Http
 * @author   Kevin <qinqiwei@hotmail.com>
 * @license  Apache License V2
 * @link     https://github.com/qinqw/curl-http
 */

spl_autoload_register( 
    function ($class) {
        // project-specific namespace prefix
        $prefix = 'Qinqw\\Curl\\Http';

        // base directory for the namespace prefix
        $base_dir = dirname(__DIR__) . '/src/';

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            //require $file;
            include $file;
        }
    }
);
