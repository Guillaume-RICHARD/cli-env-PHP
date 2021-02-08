<?php

namespace App\utils;

class path
{
    /**
     * path constructor.
     * @param string $path
     */
    public function __construct(string $path) {
        try {
            $this->path = $path;

        } catch(Exeption $e){
            die('Erreur:'.$e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getpath() {
        return passthru('php '.$this->path);
    }
}