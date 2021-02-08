<?php


namespace App\sql;


class mysql
{
    protected $db;

    /**
     * sql constructor.
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, string $dbname, string $username, string $password) {
        try {
            $this->db = new \PDO('mysql:host='.$host.';dbname='.$dbname,$username,$password, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING))
                or die(print_r($this->db->errorInfo()));
            $this->db->exec('SET NAMES utf8');
        } catch(Exeption $e){
            die('Erreur:'.$e->getMessage());
        }
    }

    /**
     * @return \PDO
     */
    public function getDb() {
        if ($this->db instanceof \PDO) {
            return $this->db;
        }
    }
}