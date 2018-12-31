<?php
namespace App\Controllers\Admin;

use App\Models\Users;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class UsersController
 * @package App\Controllers\Admin
 */
class UsersController extends AppAdminController
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
        'create' => 'Le profil a bien été créé',
        'edit' => 'Le profil a bien été modifié',
        'delete' => 'Le compte a bien été supprimé',
        'error_db' => 'Une erreur est survenue lors de l\'enregistrement dans la base de données'
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
     * Afficher l'ensemble des utilisateurs
     */
    public function index()
    {
        $users= $this->users->find_all(['delete_account' => 0]);
        foreach ($users as $key => $user)
        {
            if ($user['rights'] == 0)
            {
                $users[$key]['rights'] = 'Utilisateur';
            }
            elseif ($user['rights'] == 1)
            {
                $users[$key]['rights'] = 'Ecriture';
            }
            else
            {
                $users[$key]['rights'] = 'Administrateur';
            }
            if ($user['status'] == 0)
            {
                $users[$key]['status_name'] = 'Actif';
            }
            else
            {
                $users[$key]['status_name'] = 'Bannis';
            }
        }
        $this->vars = compact('users');
    }

    /**
     * Créer un nouvel utilisateur
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
                        'status' => 0,
                        'delete_account' => 0,
                        'creation_date' => $date,
                        'modification_date' => $date
                    ]
                );
                if ($this->users->create($data))
                {
                    $this->setFlash($this->messages['create']);
                    $this->redirect('admin/users/index');
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->vars = compact('username', 'email', 'password', 'password_confirm', 'rights');
                $this->form->setErrors($validator->getErrors());
            }
        }
    }

    /**
     * Modifier un compte utilisateur
     * @param int $id
     */
    public function edit(int $id)
    {
        if ($this->request->getMethod() === "POST")
        {
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
                $data['id'] = $id;
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
                    $this->redirect('admin/users/index');
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
                $this->vars = compact('id', 'username', 'email', 'password', 'password_confirm', 'rights');
            }
        }
        else
        {
            $user = $this->users->find_one($id);
            extract($user);
            $password = "";
            $this->vars = compact('id', 'username', 'email', 'password', 'rights');
        }
    }

    /**
     * Supprimer définitivement un compte utilisateur
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->users->delete($id);
        $this->setFlash($this->messages['delete']);
        $this->redirect('admin/users/index');
    }

    /**
     * Permet de changer le statut de l'utilisateur
     * @param int $id
     */
    public function status(int $id)
    {
        $user = $this->users->find_one($id);
        $data['id'] = $id;
        if ($user['status'] == 0)
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 0;
        }
        $this->users->edit($data);
        $this->redirect('admin/users/index');
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
            ->required('username', 'email', 'password', 'password_confirm', 'rights')
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
     * @param int|null $id
     * @return Validation
     */
    protected function getValidationEdit(Request $request, int $id = NULL): Validation
    {
        return (new Validation($request))
            ->required('username', 'email', 'rights')
            ->notEmpty('username', 'email')
            ->length('username', 3, 10)
            ->unique('email', $this->users, NULL, $id);
    }

}
