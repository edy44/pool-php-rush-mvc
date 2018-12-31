<?php

namespace App\Config;

/**
 * Class Configuration
 * @package App\Config
 */
class Configuration
{

    /**
     * Stocker les données de connexion à la base de données
     * @var array
     */
    public static $database = array(
        'host'		=> 'localhost',
        'db'	=> 'rush_mvc_php',
        'login'		=> 'root',
        'password'	=> 'root',
        'port'      => 80
    );

}
