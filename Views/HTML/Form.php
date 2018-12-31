<?php
namespace App\Views\HTML;

use App\Controllers\AppController;
use App\Src\Router;

/**
 * Class Form
 * @package App\Views\HTML
 */
class Form
{

    /**
     * @var AppController
     */
    private $controller;
    /**
     * @var array
     */
    private $errors;

    /**
     * Form constructor.
     * @param AppController $controller
     */
    public function __construct(AppController $controller)
    {
        $this->controller = $controller;
        $this->errors = [];
    }

    /**
     * Afficher le titre
     * @param string $title
     * @return string
     */
    public function title(string $title)
    {
        return '<h3>'.$title.'</h3>';
    }

    /**
     * Afficher le sous titre
     * @param string $title
     * @return string
     */
    public function subtitle(string $subtitle)
    {
        return '<h5>'.$subtitle.'</h5>';
    }

    /**
     * Permet de créer un nouvel input avec son label associé
     * @param string $name
     * @param string $label
     * @param string $options
     * @return string
     */
    public function input(string $name, string $label, string $options = NULL)
    {
        $html = '<div>';
        if (isset($this->errors[$name]))
        {
            $html .= '<div>'.$this->errors[$name].'</div>';
        }
        $html .= '<label>'.$label.'</label>';
        if (is_null($options))
        {
            $html .= '<input type="text" name="'.$name.'" value="'.$this->getValue($name).'">';
        }
        elseif ($options == 'email')
        {
            $html .= '<input type="email" name="'.$name.'" value="'.$this->getValue($name).'">';
        }
        elseif ($options == 'password')
        {
            $html .= '<input type="password" name="'.$name.'" value="'.$this->getValue($name).'">';
        }
        elseif ($options == 'file')
        {
            $html .= '<input type="file" name="'.$name.'">';
        }
        elseif ($options == 'textarea')
        {
            $html .= '<textarea name="'.$name.'" rows="6">'.$this->getValue($name).'</textarea>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Permet de créer un bouton de type Submit
     * @param string $name
     * @return string
     */
    public function submit(string $name)
    {
        return '<button class="waves-effect waves-light btn" type="submit">'.$name.'<i class="material-icons right">check</i></button>';
    }

    /**
     * Permet de créer un bouton de type redirection
     * @param string $name
     * @param string $url
     * @param string $class
     * @param string $icon
     * @return string
     */
    public function redirect(string $name, string $url, string $class = "", string $icon = "")
    {
        $html = '<a class="btn waves-effect waves-light '.$class.'" type="button" href="'.$url.'"' ;
        if ($class == 'btn-delete')
        {
            $html .= ' onclick="return confirm(\'Voulez-vous vraiment confirmer la suppression ?\')"';
        }
        $html .= '><i class="material-icons left">'.$icon.'</i>'.$name.'</a>';
        return $html;
    }


    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Permet d'appeler une URL
     * @param string $url
     * @return string
     */
    public function url(string $url)
    {
        return Router::url($url);
    }

    /**
     * Permet d'appeler un fichier
     * @param string $url
     * @return string
     */
    public function webroot(string $url)
    {
        return Router::webroot($url);
    }

    /**
     * @param string $index
     * @return null
     */
    private function getValue(string $index)
    {
        if (isset($this->controller->request->getData()[$index]))
        {
            return $this->controller->request->getData()[$index];
        }
        elseif (isset($this->controller->getVars()[$index]))
        {
            return $this->controller->getVars()[$index];
        }
        return null;
    }

}

