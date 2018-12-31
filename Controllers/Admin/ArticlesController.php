<?php
namespace App\Controllers\Admin;

use App\Models\Articles;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Tags;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class ArticlesController
 * @package App\Controllers\Admin
 */
class ArticlesController extends AppAdminController
{

    /**
     * @var Articles
     */
    private $articles;
    /**
     * @var Comments
     */
    private $comments;
    /**
     * @var Categories
     */
    private $categories;
    /**
     * @var Tags
     */
    private $tags;
    /**
     * Messages flash
     * @var array
     */
    protected $messages = [
        'create' => 'L\'article a bien été créé',
        'edit' => 'L\'article a bien été modifié',
        'delete' => 'L\'article a bien été supprimé avec tous ses commentaires associés',
        'error_db' => 'Une erreur est survenue lors de l\'enregistrement dans la base de données'
    ];

    /**
     * ArticlesController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->articles = $this->loadModel('articles');
        $this->comments = $this->loadModel('comments');
        $this->categories = $this->loadModel('categories');
        $this->tags = $this->loadModel('tags');

    }

    /**
     * Affiche l'ensemble des articles
     */
    public function index()
    {
        $articles = $this->articles->find_all_with_category_and_user(NULL, ['modification_date' => 'DESC']);
        foreach ($articles as $key => $article)
        {
            $articles[$key]['modification_date'] = date('d/m/Y', strtotime($article['modification_date']));
            if ($article['category_name'] == 'no_category')
            {
                $articles[$key]['category_name'] = "Catégorie à définir";
            }
        }
        $this->vars = compact('articles');
    }

    /**
     * Création d'un Article
     */
    public function create()
    {
        $categories = $this->categories->find_all(NULL, ['name' => 'ASC']);
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request);
            if ($validator->isValid())
            {
                $date = date('Y-m-d h:i:s');
                $data = $this->request->getData();
                if (strpos($_FILES['file']['type'], 'image') !== false)
                {
                    $dir = WEBROOT.DS.'img'.DS.'articles'.DS.date('Y-m');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777);
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], $dir.DS.$_FILES['file']['name']);
                    $data['img_path'] = date('Y-m').'/'.$_FILES['file']['name'];
                }
                unset($data['tag']);
                $tags = [];
                foreach ($data as $key => $value)
                {
                    if (stripos($key, 'tag_') !== false)
                    {
                        $tags[] = $value;
                        unset($data[$key]);
                    }
                }
                $data = array_merge($data,
                    [
                        'user_id' => $this->session->read('User'),
                        'creation_date' => $date,
                        'modification_date' => $date,
                        'img_path' => date('Y-m').'/'.$_FILES['file']['name']
                    ]
                );
                if ($this->articles->create($data))
                {
                    $article_id = $this->articles->getDb()->getPdo()->lastInsertId();
                    foreach ($tags as $tag) {
                        $this->tags->create([
                            'name' => $tag,
                            'article_id' => $article_id
                        ]);
                    }
                    $this->setFlash($this->messages['create']);
                    $this->redirect('admin/articles/index');
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->vars = compact('title', 'description', 'tag', 'categories');
                $this->form->setErrors($validator->getErrors());
            }
        }
        else
        {
            $this->vars = compact('categories');
        }
    }

    /**
     * Modifier un article
     * @param int $id
     */
    public function edit(int $id)
    {
        $categories = $this->categories->find_all(NULL, ['name' => 'ASC']);
        $tags = $this->tags->find_all(['article_id' => $id], ['name' => 'ASC']);
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request, $id);
            if ($validator->isValid())
            {
                $data = $this->request->getData();
                $date = date('Y-m-d h:i:s');
                if (strpos($_FILES['file']['type'], 'image') !== false)
                {
                    $dir = WEBROOT.DS.'img'.DS.'articles'.DS.date('Y-m');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777);
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], $dir.DS.$_FILES['file']['name']);
                    $data['img_path'] = date('Y-m').'/'.$_FILES['file']['name'];
                }
                unset($data['tag']);
                $tags = [];
                foreach ($data as $key => $value)
                {
                    if (stripos($key, 'tag_') !== false)
                    {
                        $tags[] = $value;
                        unset($data[$key]);
                    }
                }
                $data = array_merge($data,
                    [
                        'user_id' => $this->session->read('User'),
                        'id' => $id,
                        'modification_date' => $date
                    ]
                );
                if ($this->articles->edit($data))
                {
                    $this->tags->delete_all_from_article($id);
                    foreach ($tags as $tag) {
                        $this->tags->create([
                            'name' => $tag,
                            'article_id' => $id
                        ]);
                    }
                    $this->setFlash($this->messages['edit']);
                    $this->redirect('admin/articles/index');
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
            $article = $this->articles->find_one($id);
            extract($article);
        }
        $this->vars = compact('id','title', 'description', 'category_id', 'categories', 'tags', 'img_path');
    }

    /**
     * Supprimer définitivement un article avec son image, ses commentaires et tags associés
     * @param int $id
     */
    public function delete(int $id)
    {
        $article = $this->articles->find_one($id);
        unlink(WEBROOT.DS.'img'.DS.'articles'.DS.$article['img_path']);
        $this->articles->delete($id);
        $this->comments->delete_all_from_article($id);
        $this->tags->delete_all_from_article($id);
        $this->setFlash($this->messages['delete']);
        $this->redirect('admin/articles/index');
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
            ->required('title', 'description', 'category_id')
            ->notEmpty('title', 'description', 'category_id')
            ->length('title', 3, 50)
            ->length('description', 20)
            ->extension('file');
    }
}
