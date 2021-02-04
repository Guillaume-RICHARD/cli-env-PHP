<?php
// short-circuiting of the memory_limit which can come from the OS
ini_set('memory_limit','-1');

// require vendor
require('vendor/autoload.php');

// Init environnement variable
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$timestart=microtime(true);