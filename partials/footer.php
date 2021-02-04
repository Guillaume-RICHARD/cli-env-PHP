<?php
$timeend=microtime(true);
$time=$timeend-$timestart;

//Afficher le temps d'éxecution
$page_load_time = number_format($time, 3);
echo "\nDebut du script: ".date("H:i:s", $timestart);
echo "\nFin du script: ".date("H:i:s", $timeend);
echo "\nScript execute en " . $page_load_time . " sec\n\n";