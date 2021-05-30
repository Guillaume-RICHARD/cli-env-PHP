#!/usr/bin/php
<?php
include_once('partials/header.php');

unset($argv[0]);
sort($argv);
$xml = new \App\file\xml("data/essence/PrixCarburants_annuel_".$argv[0].".xml");
$data = $xml->getPath();

var_dump(count($data->pdv));

include_once('partials/footer.php');