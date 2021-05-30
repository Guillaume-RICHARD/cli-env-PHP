#!/usr/bin/php
<?php
include_once('partials/header.php');

unset($argv[0]);
sort($argv);
$xml = new \App\file\xml("data/essence/PrixCarburants_annuel_".$argv[0].".xml");
$data = $xml->getPath();

// Enregistrement des données en BDD
$pdo = new \App\sql\mysql($_ENV['HOST'], 'essence', $_ENV['USERNAME'], $_ENV['PASSWORD']);
$dbh = $pdo->getDb();

foreach ($data->pdv as $pdv) {
    $lieux = [
        'id'        => $pdv['id'],
        'latitude'  => (!empty($pdv['latitude'])) ? $pdv['latitude'] : 0,
        'longitude' => (!empty($pdv['longitude'])) ? $pdv['longitude'] : 0,
        'cp'        => $pdv['cp'],
        'pop'       => $pdv['pop'],
        'adresse'   => $pdv->adresse,
        'ville'     => $pdv->ville,
    ];
    $lieux['ouverture'] = substr(json_encode((array)$pdv->ouverture),15, -1);
    $lieux['services'] = implode(",", (array)$pdv->services->service);
    $lieux['fermeture'] = substr(json_encode((array)$pdv->fermeture),15, -1);
    $lieux['rupture'] = substr(json_encode((array)$pdv->fermeture),15, -1);

    try{
        $sql = "INSERT INTO `pdv`(`id`, `latitude`, `longitude`, `cp`, `pop`, `adresse`, `ville`, `ouverture`, `services`, `rupture`, `fermeture`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $req = $dbh->prepare($sql)->execute([$lieux['id'], $lieux['latitude'], $lieux['longitude'], $lieux['cp'], $lieux['pop'], $lieux['adresse'], $lieux['ville'], $lieux['ouverture'], $lieux['services'], $lieux['rupture'], $lieux['fermeture']]);

        echo "Enregistrement `pdv` ".$lieux['id']." effectué !!!\n";
    }catch(Exception $e) {
        // en cas d'erreur :
        echo " Erreur ! " . $e->getMessage();
    }

    $i = 0;
    foreach ($pdv->prix as $pr) {
        $i++;
        $prix = [
            'id_pdv' => (int)$pdv['id'],
            'id'     => (int)$pr["id"],
            'nom'    => (string)$pr["nom"],
            'maj'    => (string)$pr["maj"],
            'valeur' => (int)$pr["valeur"],
        ];

        try{
            $sql = "INSERT INTO `prix`(`id_pdv`, `id`, `nom`, `maj`, `valeur`) VALUES (?,?,?,?,?)";
            $req = $dbh->prepare($sql)->execute([$prix['id_pdv'], $prix['id'], $prix['nom'], $prix['maj'], $prix['valeur']]);

            echo "Enregistrement `prix` $i ".$lieux['id']." effectué !!!\n";
        } catch(Exception $e) {
            // en cas d'erreur :
            echo " Erreur ! " . $e->getMessage();
        }
    }
}

include_once('partials/footer.php');