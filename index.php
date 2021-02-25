#!/usr/bin/php
<?php
// short-circuiting of the memory_limit which can come from the OS
ini_set('memory_limit','-1');

// require vendor
require('vendor/autoload.php');

DEFINE('project_path', dirname(__DIR__).DIRECTORY_SEPARATOR.'cli');
DEFINE('content_path', project_path.DIRECTORY_SEPARATOR.'scripts');

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(content_path ));
$files = new RegexIterator($files, '/^.+\.php/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $path => $value){
    if (strpos($path, $argv[1])) {
        unset($argv[0],$argv[1]);
        sort($argv);

        $script = new \App\utils\path($path);
        $script->getpath($argv);
    }
}
