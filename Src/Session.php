<?php

namespace App\Src;

use App\Controllers\AppController;

/**
 * Class Session
 * @package App\Src
 */
class Session {

    /**
     * @var AppController
     */
    private $controller;

    /**
     * Démarre une nouvelle Session
     * Session constructor.
     * @param AppController $controller
     */
    public function __construct(AppController $controller) {
        $this->controller = $controller;
    }

    /**
     * Créé une session avec l'id de lutilisateur
     * @param array $user
     */
    public function create_session(array $user)
    {
        $this->write('User', $user['id']);
        if ($user['rights'] == 1)
        {
            $this->write('Writer', true);
        }
        if ($user['rights'] == 2)
        {
            $this->write('Admin', true);
        }
    }

    /**
     * Vérifie que l'utilisateur a bien son mail dans les cookies
     * @return bool
     */
    public function have_session()
    {
        $url = trim($this->controller->request->url, '/');
        if (($url == 'users/login') || ($url == 'users/create'))
        {
            return true;
        }
        return isset($_SESSION['User']);
    }

    /**
     * Détruit la session en cours de l'utilisateur
     */
    public function remove_session()
    {
        $this->unsetKey('User');
        $this->unsetKey('Admin');
        $this->unsetKey('Writer');
    }

    /**
     * Permet d'écrire une clé en Session
     * @param string $key
     * @param mixed $value
     */
    public function write(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Permet de lire une clé en Session
     * @param string|null $key
     * @return bool
     */
    public function read(string $key = null) {
        if($key == null) {
            return $_SESSION;
        } else {
            if (isset($_SESSION[$key])) {
                return $_SESSION[$key];
            } else {
                return false;
            }
        }
    }

    /**
     * Permet de supprimer une clé en Session
     * @param string $key
     * @return bool
     */
    public function unsetKey(string $key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Envoie le message flash stocké en session et le supprime de la session
     * @return bool|string
     */
    public function getFlash()
    {
        $flash = $this->read('flash');
        if ($flash)
        {
            $this->unsetKey('flash');
            return $flash;
        }
        return false;
    }

    /**
     * Crée un cookie pour stocker l'identifiant pendant 30 jours
     * @param string $email
     */
    public function create_cookie(string $email)
    {
        setcookie('email', $email, strtotime( '+30 days' ));
    }

    /**
     * Vérifie que l'utilisateur a bien son mail dans les cookies
     * @return bool
     */
    public function have_cookie()
    {
        return isset($_COOKIE['email']);
    }

    /**
     * Supprime le cookie de l'utilisateur
     */
    public function remove_cookie()
    {
        setcookie('email', '', -1);
    }

    public function is_admin()
    {
        return $this->read('Admin');
    }

    public function is_writer()
    {
        return $this->read('Writer');
    }

}