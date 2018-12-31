<?php
namespace App\Controllers;

use App\Config\Core;
use App\Models\AppModel;
use App\Src\Request;
use App\Src\Router;
use App\Src\Session;
use App\Src\Validation;
use App\Views\HTML\Form;

/**
 * Class AppController
 * @package App\Controllers
 */
class AppController {
    /**
     * @var Request
     */
    public $request;
    /**
     * @var string
     */
    protected $viewPath;
    /**
     * @var array $vars
     */
    protected $vars;
    /**
     * @var Session
     */
    public $session;
    /**
     * @var string
     */
    protected $layout;
    /**
     * @var bool
     */
    private $rendered;
    /**
     * @var array
     */
    protected $messages;
    /**
     * AppController constructor.
     * @param Form
     */
    public $form;

    /**
     * AppController constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->session = new Session($this);
        if (!is_null($request))
        {
            $this->request = $request;
            $this->viewPath = VIEWS.DS.$this->request->controller;
            if(!is_null($this->request->prefix))
            {
                $this->viewPath = VIEWS.DS.$this->request->prefix.DS.$this->request->controller;
            }
            if (!$this->session->have_session())
            {
                $this->redirect('users/login');
            }
        }
        if (is_null($this->layout))
        {
            $this->layout = 'default';
        }
        $this->form = new Form($this);
        $this->vars = [];
        $this->rendered = false;
    }

    /**
     * Permet de rendre la vue
     * @param string $view
     * @return bool
     */
    public function render(string $view)
    {
        if ($this->rendered)
        {
            return false;
        }
        extract($this->vars);
        ob_start();
        require $this->viewPath.DS.$view.'.php';
        $content_for_layout = ob_get_clean(); //On récupère le contenu dans le layout
        require VIEWS.DS.'layouts'.DS.$this->layout.'.php';
        $this->rendered = true;
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * Génère une page erreur de type 404
     * @param string $message
     */
    public function e404(string $message)
    {
        header("HTTP/1.0 404 Not Found");
        $this->viewPath = VIEWS;
        $this->layout = 'modal';
        $this->vars = compact('message');
        $this->render('/errors/404');
        die();
    }

    /**
     * Redirige vers une autre page
     * @param string $url
     * @param int $code
     */
    public function redirect(string $url, int $code = null)
    {
        if ($code == 301) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: " . Router::url($url));
        die();
    }

    /**
     * Retourne un objet de type Model
     * @param string $name
     * @return AppModel
     */
    protected function loadModel(string $name)
    {
        if (!isset($this->$name)) {
            return Core::getInstance()->getModel($name);
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Validation
     */
    protected function getValidation(Request $request, int $id = NULL): Validation
    {
        return new Validation($request);
    }

    /**
     * Enregistre un message flash en session
     * @param string $message
     */
    protected function setFlash(string $message)
    {
        $this->session->write('flash', $message);
    }

    /**
     * Retourne le message flash stocké en session
     */
    protected function getFlash() {

        return $this->session->getFlash();
    }

}
