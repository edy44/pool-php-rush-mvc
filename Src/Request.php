<?php
namespace App\Src;

/**
 * Class Request
 * @package App\Src
 */
class Request {

    /**
     * URL appelé par l'utilisateur
     * @var string
     */
	public $url;
    /**
     * URL avec le nom du serveur
     * @var string
     */
	public $urlComplete;
    /**
     * Déclaration du préfixe pour les parties Admin et Writer
     * @var null|string
     */
	public $prefix;
    /**
     * Permet de stocker les données envoyées
     * @var bool|array
     */
	private $data;
    /**
     * Stocke la methode
     * @var string
     */
	private $method;
    /**
     * Permet de vérifier si un fichier existe
     * @var bool
     */
	public $fileExist;
    /**
     * Permet de stocker la partie controller de l'URL
     * @var string
     */
	public $controller;
    /**
     * Permet de stocker la partie action de l'URL
     * @var string
     */
    public $action;
    /**
     * Permet de stocker le reste des paramètres de l'URL
     * @var array
     */
    public $params;

    /**
     * Request constructor.
     */
	public function __construct()
    {
		$this->url = isset($_SERVER['REQUEST_URI']) ? str_replace('/PHP_Rush_MVC','', $_SERVER['REQUEST_URI']) : '/';
		$this->urlComplete = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$this->fileExist = $this->existUrl();
		if (!$this->fromPost() && !$this->fromGet())
        {
            $this->method = false;
        }
		$this->prefix = null;
	}

    /**
     * @return bool|array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return bool|string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Récupération des données dans $_POST et création d'un objet Data
     * @return bool
     */
	private function fromPost()
    {
		if (!empty($_POST)) {
			foreach ($_POST as $k => $v) {
				$this->data[$k] = $v;
			}
			$this->method = "POST";
			return true;
		}
		return false;
	}

    /**
     * Récupération du champ de recherche dans $GET
     * @return bool
     */
	private function fromGet()
    {
        if (!empty($_GET)) {
            foreach ($_GET as $k => $v) {
                $this->data[$k] = $v;
            }
            $this->method = "GET";
            return true;
        }
        return false;
	}

    /**
     * Vérifie si le fichier existe déjà
     * @return bool
     */
	private function existUrl()
    {
		if ($this->url === '/') {
			return false;
		} 
		if (file_exists(WEBROOT.$this->url) || file_exists(ROOT.$this->url)) {
			return true;
		} else {
			return false;
		}
	}

}