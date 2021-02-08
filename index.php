#!/usr/bin/php
<?php
include_once ('partials/header.php');

DEFINE('project_path', dirname(__DIR__).DIRECTORY_SEPARATOR.'cli');
DEFINE('content_path', project_path.DIRECTORY_SEPARATOR.'scripts');

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(content_path ));
$files = new RegexIterator($files, '/^.+\.php/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $path => $value){
    if (strpos($path, $argv[1])) {
        $script = new \App\utils\path($value);
        $script->getpath();
    }
}

include_once ('partials/footer.php');