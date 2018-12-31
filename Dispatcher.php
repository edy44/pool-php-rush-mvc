<?php

namespace App;

use App\Src\Request;
use App\Src\Router;
use App\Controllers\AppController;
use App\Config\Core;

class Dispatcher {

    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->request = new Request();
        if (!$this->request->fileExist) {
            Core::getInstance()->setRules();
            Router::parse($this->request->url,$this->request);
            session_start();
            $controller = $this->loadController();
            $action = $this->request->action;
            if (!in_array($action,array_diff(get_class_methods($controller),get_class_methods(new AppController())))) {
                $this->error('Une erreur s\'est produite lors de l\'ouverture de la page');
            }
            call_user_func_array(array($controller,$action),$this->request->params); //Permet d'appeler la fonction correspondant au nom de l'action dans le controller et de mettre les paramètres de l'URL dans les paramètres de la fonction
            $controller->render($controller->request->action);
        }
    }

    private function error($message)
    {
        $controller = new AppController($this->request);
        $controller->e404($message);
    }

    private function loadController()
    {
        $class_name = Core::getInstance()->getController($this->request->controller, $this->request->prefix);
        $class_name_dir = str_replace('App\\', '', $class_name);
        $class_name_dir = str_replace('\\', DS, $class_name_dir);
        $file = ROOT.DS.$class_name_dir.'.php';
        if (!file_exists($file)) {
            $this->error('Une erreur s\'est produite lors de l\'ouverture de la page');
        }
        return new $class_name($this->request);
    }

}
