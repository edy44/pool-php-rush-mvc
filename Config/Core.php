<?php
namespace App\Config;

use App\Dispatcher;
use App\Models\AppModel;
use App\Src\Router;

/**
 * Class Core
 * @package App\Config
 */
class Core
{

     /**
     * @var Database
     */
     private $db_instance;
    /**
     * @var Core
     */
     public static $_instance;

    /**
     * Permet de générer une instance, et la retourne
     * @return Core
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new Core();
        }
        return self::$_instance;
    }

    /**
     * Permet d'appeler le Dispatcher
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return new Dispatcher();
    }

    /**
     * Choix des prefixes pour les parties Admin et Writer
     */
    public function setRules()
    {
        Router::prefix('admin', 'admin');
        Router::prefix('writer', 'writer');
    }

    /**
     * @param string $name
     * @param string|null $prefix
     * @return string
     */
    public function getController(string $name, string $prefix = null)
    {
        if (is_null($prefix)) {
            $class_name = 'App\Controllers\\';
        } else {
            $class_name = 'App\Controllers\\'.ucfirst($prefix).'\\';
        }
        $class_name .= ucfirst($name).'Controller';
        return $class_name;
    }

    /**
     * Retourne un objet de type Model
     * @param string $name
     * @return AppModel
     */
    public function getModel(string $name)
    {
        $class_name = 'App\Models\\'.ucfirst($name);
        return new $class_name($this->getDb());
    }

    /**
     * Retourne une instance de la base de données
     * @return Database
     */
    private function getDb()
    {
        if (is_null($this->db_instance))
        {
            $this->db_instance = new Database();
        }
        return $this->db_instance;
    }

}
