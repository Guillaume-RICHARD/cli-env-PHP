#!/usr/bin/php
<?php
include_once('partials/header.php');

$file = 'data/state/state_of_css_2019-2020.json';

// Enregistrement des données en BDD
$pdo = new \App\sql\mysql($_ENV['HOST'], 'stateofcss', $_ENV['USERNAME'], $_ENV['PASSWORD']);
$dbh = $pdo->getDb();

// Lire le fichier JSON
$string = file_get_contents("data/state/state_of_css_2019-2020.json");
$data = json_decode($string, true);

foreach ($data as $d) {

}

include_once('partials/footer.php');