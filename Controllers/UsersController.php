<?php
namespace App\Controllers;

use App\Models\Users;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class UsersController
 * @package App\Controllers
 */
class UsersController extends AppController
{

    /**
     * @var Users
     */
    private $users;
    /**
     * Messages flash
     * @var array
     */
    protected $messages = [
        'create' => 'Votre profil a bien été créé',
        'edit' => 'Votre profil a bien été modifié',
        'delete' => 'Votre compte a bien été supprimé',
        'error_db' => 'Une erreur est survenue lors de l\'enregistrement dans la base de données',
        'banned' => 'Vous ne pouvez pas accéder au site car vous avez été banni par un administrateur'
    ];

    /**
     * UsersController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->users = $this->loadModel('users');
    }

    /**
     * Créer un nouveau profil par l'utilisateur
     */
    public function create()
    {
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request);
            if ($validator->isValid())
            {
                $date = date('Y-m-d h:i:s');
                $data = $this->request->getData();
                $hash = password_hash($data['password'], PASSWORD_DEFAULT);
                unset($data['password_confirm']);
                unset($data['password']);
                $data = array_merge($data,
                    [
                        'password' => $hash,
                        'rights' => 0,
                        'status' => 0,
                        'delete_account' => 0,
                        'creation_date' => $date,
                        'modification_date' => $date
                    ]
                );
                if ($this->users->create($data))
                {
                    $this->setFlash($this->messages['create']);
                    $this->redirect('users/login');
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->vars = compact('username', 'email', 'password', 'password_confirm');
                $this->form->setErrors($validator->getErrors());
            }
        }
        $this->layout = 'modal';
    }

    /**
     * Modifier le profil de l'utilisateur
     */
    public function edit()
    {
        if ($this->request->getMethod() === "POST")
        {
            $id = $this->session->read('User');
            if (!empty($this->request->getData()['password_confirm']) ||
                !empty($this->request->getData()['password']))
            {
                $validator = $this->getValidation($this->request, $id);
            }
            else
            {
                $validator = $this->getValidationEdit($this->request, $id);
            }
            if ($validator->isValid())
            {
                $data = $this->request->getData();
                $data['id'] = $this->session->read('User');
                $date = date('Y-m-d h:i:s');
                $data['modification_date'] = $date;
                if (!empty($data['password']))
                {
                    $hash = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                unset($data['password_confirm']);
                unset($data['password']);
                if (isset($hash))
                {
                    $data['password'] = $hash;
                }
                if ($this->users->edit($data))
                {
                    $this->setFlash($this->messages['edit']);
                    $this->redirect('users/edit');
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->form->setErrors($validator->getErrors());
                $this->vars = compact('username', 'email', 'password', 'password_confirm');
            }
        }
        else
        {
            $id = $this->session->read('User');
            $user = $this->users->find_one($id);
            extract($user);
            $password = "";
            $this->vars = compact('username', 'email', 'password');
        }
    }

    /**
     * Connexion de l'utilisateur sur le site
     */
    public function login()
    {
        if ($this->session->have_cookie())
        {
            $this->redirect('articles/index');
        }
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidationLogin($this->request);
            if ($validator->isValid())
            {
                $data = $this->request->getData();
                $user = $this->users->find_with_mail($data['email']);
                if (($user['status'] == 1) || ($user['delete_account'] == 1))
                {
                    $this->setFlash($this->messages['banned']);
                    $this->redirect('users/login');
                }
                if ($user && password_verify($data['password'], $user['password']))
                {
                    $this->session->create_session($user);
                    if ($data['remember_me'])
                    {
                        $this->session->create_cookie($data['email']);
                    }
                    $this->redirect('articles/index');
                }
                else
                {
                    $error = ['connexion' => 'Mot de passe ou Login incorrect'];
                    $this->form->setErrors($error);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->vars = compact('email', 'password', 'remember_me');
                $this->form->setErrors($validator->getErrors());
            }
        }
        $this->layout = 'modal';
    }

    /**
     * Supprime la session et les cookie de l'utilisateur en cas de connexion
     */
    public function logout()
    {
        $this->session->remove_session();
        $this->session->remove_cookie();
        $this->redirect('users/login');
    }

    /**
     * Permet à l'utilisateur de supprimer son compte
     */
    public function delete()
    {
        if ($this->session->have_session())
        {
            $id = $this->session->read('User');
            if ($this->users->delete($id))
            {
                $this->setFlash($this->messages['delete']);
                $this->logout();
            }
            else
            {
                $this->setFlash($this->messages['error_db']);
                $this->redirect('articles/index');
            }
        }
    }

    /**
     * Validation des données de la table utilisateur
     * @param Request $request
     * @param int|null $id
     * @return Validation
     */
    protected function getValidation(Request $request, int $id = NULL): Validation
    {
        return parent::getValidation($request, $id)
            ->required('username', 'email', 'password', 'password_confirm')
            ->notEmpty('username', 'email', 'password', 'password_confirm')
            ->length('username', 3, 10)
            ->length('password', 8, 20)
            ->length('password_confirm', 8, 20)
            ->confirm('password')
            ->unique('email', $this->users, NULL, $id);
    }

    /**
     * Validation des données de la table utilisateur
     * @param Request $request
     * @return Validation
     */
    protected function getValidationLogin(Request $request): Validation
    {
        return (new Validation($request))
            ->required('email', 'password')
            ->notEmpty('email', 'password')
            ->length('password', 8, 20);
    }

    /**
     * Validation des données de la table utilisateur
     * @param Request $request
     * @param int|null $id
     * @return Validation
     */
    protected function getValidationEdit(Request $request, int $id = NULL): Validation
    {
        return (new Validation($request))
            ->required('username', 'email')
            ->notEmpty('username', 'email')
            ->length('username', 3, 10)
            ->unique('email', $this->users, NULL, $id);
    }

}
