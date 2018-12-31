<?php
namespace App\Controllers\Admin;

use App\Models\Articles;
use App\Models\Categories;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class CategoriesController
 * @package App\Controllers\Admin
 */
class CategoriesController extends AppAdminController
{

    /**
     * @var Categories
     */
    private $categories;
    /**
     * @var Articles
     */
    private $articles;
    /**
     * Messages flash
     * @var array
     */
    protected $messages = [
        'create' => 'La catégorie a bien été créée',
        'edit' => 'La catégorie a bien été modifiée',
        'delete' => 'La catégorie a bien été supprimée',
        'error_db' => 'Une erreur est survenue lors de l\'enregistrement dans la base de données'
    ];

    /**
     * CategoriesController constructor.
     * @param Request|null $request
     *
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->categories = $this->loadModel('categories');
        $this->articles = $this->loadModel('articles');
    }

    /**
     * Affiche l'ensemble des catégories
     */
    public function index()
    {
        $categories = $this->categories->find_all();
        $this->vars = compact('categories');
    }

    /**
     * Ajoute une nouvelle catégorie
     */
    public function create()
    {
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request);
            if ($validator->isValid())
            {
                $data = $this->request->getData();
                if ($this->categories->create($data))
                {
                    $this->setFlash($this->messages['create']);
                    $this->redirect('admin/categories/index');
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->vars = compact('name');
                $this->form->setErrors($validator->getErrors());
            }
        }
    }

    /**
     * Modifie la catégorie
     * @param int $id
     */
    public function edit(int $id)
    {
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request, $id);
            if ($validator->isValid())
            {
                $data = $this->request->getData();
                $data = array_merge($data,
                    [
                        'id' => $id
                    ]
                );
                if ($this->categories->edit($data))
                {
                    $this->setFlash($this->messages['edit']);
                    $this->redirect('admin/categories/index');
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
            }
        }
        else
        {
            $article = $this->categories->find_one($id);
            extract($article);
        }
        $this->vars = compact('id','name');
    }

    /**
     * Supprime définitivement une catégorie avec remise à NULL des articles concernés
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->categories->delete($id);
        $this->articles->delete_category_from_article($id);
        $this->setFlash($this->messages['delete']);
        $this->redirect('admin/categories/index');
    }

    /**
     * Validation des données de la table categorie
     * @param Request $request
     * @param int|null $id
     * @return Validation
     */
    protected function getValidation(Request $request, int $id = NULL): Validation
    {
        return parent::getValidation($request, $id)
            ->required('name')
            ->notEmpty('name')
            ->unique('name', $this->categories, NULL, $id);
    }

}
