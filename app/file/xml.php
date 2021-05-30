<?php


namespace App\file;


class xml {
    protected $path;

    /**
     * xml constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        if (file_exists($this->path)) {
            $this->path = simplexml_load_file($this->path,"SimpleXMLElement",LIBXML_NOCDATA);
        } else {
            exit('Echec lors de l\'ouverture du fichier XML.');
        }
    }

    public function getPath(): \SimpleXMLElement
    {
        return $this->path;
    }
}