#!/usr/bin/php
<?php
include_once('partials/header.php');

$url            = 'https://www.manga-news.com/index.php/planning';
$first_year     = 1970;
$current_year   = date("Y");
$client         = new \Goutte\Client();

// Enregistrement des données en BDD
$pdo = new \App\sql\mysql($_ENV['HOST'], 'scraping', $_ENV['USERNAME'], $_ENV['PASSWORD']);
$dbh = $pdo->getDb();

for ($i = $first_year; $i <= $current_year; $i++) {
    for ($j = 1; $j <= 12; $j++) {
        for ($page = 1; $page <= 20; $page++) {
            $crawler = $client->request('GET', $url."?p_year=".$i."&p_month=".$j."&p_editor=&page=".$page);

            $links = $crawler->filter('#listing-chroniques')->filter('.listing-item')->each(function ($child, $i) {
                return $child->filter('.content > a')->each(function ($el, $i) {
                    return $el->attr('href');
                });
            });

            if (empty($links)) {
                $page = 20;
                continue;
            }

            foreach ($links as $link) {
                $crawler_manga = $client->request('GET', $link[0]);

                $listing = $crawler_manga->filter('#main')->each(function ($infos, $i) {
                    $title = $infos->filter('h1.entryTitle')->each(function ($child, $i) {
                        return $child->text();
                    });
                    if (empty($title)) { $title[] = "N/A"; }

                    $data = $infos->filter('#topinfo')->filter('ul.entryInfos')->each(function ($child, $i) {
                        return $child->filter('li')->each(function ($el, $i) {
                            return $el->text();
                        });
                    });

                    foreach ($data as $d) {
                        $filter = new RegexIterator(new ArrayIterator($d), '/Titre VO/');
                        $title_kana = iterator_to_array($filter);
                        foreach ($title_kana as $item) {
                            unset($title_kana);
                            $title_kana[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($title_kana)) { $title_kana[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Titre traduit/');
                        $title_vo = iterator_to_array($filter);
                        foreach ($title_vo as $item) {
                            unset($title_vo);
                            $title_vo[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($title_vo)) { $title_vo[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Scénario/');
                        $mangaka = iterator_to_array($filter);
                        foreach ($mangaka as $item) {
                            unset($mangaka);
                            $mangaka[] = (trim(explode("::", $item)[1])) ? (trim(explode("::", $item)[1])) : "";
                        }
                        if (empty($mangaka)) { $mangaka[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Editeur VF/');
                        $editeur_vf = iterator_to_array($filter);
                        foreach ($editeur_vf as $item) {
                            unset($editeur_vf);
                            $editeur_vf[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($editeur_vf)) { $editeur_vf[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Type/');
                        $type = iterator_to_array($filter);
                        foreach ($type as $item) {
                            unset($type);
                            $type[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($type)) { $type[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Genre/');
                        $genre = iterator_to_array($filter);
                        foreach ($genre as $item) {
                            unset($genre);
                            $genre[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($genre)) { $genre[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Editeur VO/');
                        $editeur_vo = iterator_to_array($filter);
                        foreach ($editeur_vo as $item) {
                            unset($editeur_vo);
                            $editeur_vo[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                        }
                        if (empty($editeur_vo)) { $editeur_vo[] = "N/A"; }

                        $filter = new RegexIterator(new ArrayIterator($d), '/Date de publication/');
                        $date = iterator_to_array($filter);
                        foreach ($date as $item) {
                            unset($date);

                            $gestion_date = new App\utils\date();
                            $date[] = (trim(explode(":", $item)[1])) ? (trim(explode(":", $item)[1])) : "";
                            // var_dump($item, $date[0]); die;

                            [$day, $month, $year] = explode(" ", $date[0]);
                            $month_dec = $gestion_date->switchTo($month);

                            $datetime[] = $year."-".$month_dec."-".$day;
                        }
                        if (empty($date)) { $date[] = "N/A"; }
                        if (empty($datetime)) { $datetime[] = "N/A"; }
                    }

                    $age = $infos->filter('#agenumber a')->each(function ($child, $i) {
                        return $child->text();
                    });
                    if (empty($age)) { $age[] = "N/A"; }
                    
                    $prix = $infos->filter('#prixnumber')->each(function ($child, $i) {
                        return $child->text();
                    });
                    if (empty($prix)) { $prix[] = "N/A"; }

                    $description = $infos->filter('#summary p')->each(function ($child, $i) {
                        return $child->text();
                    });
                    $description = implode(" ",$description)."...";

                    return array_merge($title, $title_kana, $title_vo, $mangaka, $editeur_vf, $editeur_vo, $type, $genre, $date, $datetime, $age, $prix, [$description]);
                });

                foreach ($listing as $manga) {
                    try{
                        $sql = "INSERT INTO `manga`(`title_vf`, `title_kana`, `title_vo`, `mangaka`, `editeur_vf`, `editeur_vo`, `type`, `genre`, `date`, `datetime`, `age`, `prix`, `description`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $req = $dbh->prepare($sql)->execute([$manga[0], $manga[1], $manga[2], $manga[3], $manga[4], $manga[5], $manga[6], $manga[7], $manga[8], $manga[9], $manga[10], $manga[11], $manga[12]]);

                        echo "Enregistrement `manga` ".$manga[0]." effectué !!!\n";
                    }catch(Exception $e) {
                        // en cas d'erreur :
                        echo " Erreur ! " . $e->getMessage();
                    }
                }
            }
        }
    }
}

include_once('partials/footer.php');