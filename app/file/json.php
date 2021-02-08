<?php


namespace App\file;


class json {

    /**
     * json constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $data = file_get_contents($path, true); // Lire le fichier JSON
        return json_decode($data, true);
    }
}