<?php
namespace App\Config;

use PDO;
use PDOException;

/**
 * Class Db
 * @package Models
 */
class Database
{

    /**
     * @var string
     */
    private $db;
    /**
     * @var string
     */
    private $host;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Db constructor.
     */
    public function __construct()
    {
        $conf = Configuration::$database;
        $this->db = $conf['db'];
        $this->host = $conf['host'];
        $this->login = $conf['login'];
        $this->password = $conf['password'];
        $this->port = $conf['port'];
    }

    /**
     * Retoune une instance de Pdo après la connexion à la base
     * @return PDO
     */
    public function getPdo()
    {
        if (is_null($this->pdo))
        {
            try
            {
                $this->pdo = new PDO('mysql:dbname='.$this->db.';host=' .$this->host.';port='.$this->port,
                    $this->login, $this->password);
            }
            catch (PDOException $pdoException)
            {
                $message = "Erreur de connection à la base de données\n";
                echo $message;
                die;
            }
        }
        return $this->pdo;
    }

}
