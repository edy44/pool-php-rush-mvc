<?php
namespace App\Controllers\Writer;

use App\Models\Comments;
use App\Models\Users;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class CommentsController
 * @package App\Controllers\Admin
 */
class CommentsController extends AppWriterController
{

    /**
     * @var Comments
     */
    private $comments;
    /**
     * @var Users
     */
    private $users;
    /**
     * Messages flash
     * @var array
     */
    protected $messages = [
        'create' => 'Votre commentaire a bien été créé',
        'delete' => 'Votre commentaire a bien été supprimé',
        'error_db' => 'Une erreur est survenue lors de l\'enregistrement dans la base de données'
    ];

    /**
     * CommentsController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->comments = $this->loadModel('comments');
        $this->users = $this->loadModel('users');
    }

    /**
     * Affiche tous les commentaires associés à un article
     * @param int $article_id
     */
    public function index(int $article_id)
    {
        $comments = $this->comments->find_all(['article_id' => $article_id]);
        foreach ($comments as $key => $comment)
        {
            $user_id = $comments[$key]['user_id'];
            $user = $this->users->find_one($user_id);
            $comments[$key]['user_name'] = $user['username'];
            $comments[$key]['date'] = date('d-m-Y', strtotime($comment['creation_date'])).' à '.
                date('H:i:s', strtotime($comment['creation_date']));
        }
        $this->vars = compact('comments', 'article_id');
    }

    /**
     * Créé un nouveau commentaire et renvoie dans la page des commentaires associés à l'article
     * @param int $article_id
     */
    public function create(int $article_id)
    {
        if ($this->request->getMethod() === "POST")
        {
            $validator = $this->getValidation($this->request);
            if ($validator->isValid())
            {
                $date = date('Y-m-d h:i:s');
                $data = $this->request->getData();
                $data = array_merge($data,
                    [
                        'user_id' => $this->session->read('User'),
                        'article_id' => $article_id,
                        'creation_date' => $date
                    ]
                );
                if ($this->comments->create($data))
                {
                    $this->setFlash($this->messages['create']);
                }
                else
                {
                    $this->setFlash($this->messages['error_db']);
                }
            }
            else
            {
                extract($this->request->getData());
                $this->setFlash($validator->getErrors()['content']);
            }
        }
        $this->redirect('admin/comments/index/'.$article_id);
    }

    /**
     * Supprime le commentaire avec l'id passé en paramètre, et redirige vers la page des commentaires associés à l'article
     * @param int $id
     * @param int $article_id
     */
    public function delete(int $id, int $article_id)
    {
        $this->comments->delete($id);
        $this->setFlash($this->messages['delete']);
        $this->redirect('../admin/comments/index/'.$article_id);
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
            ->required('content')
            ->notEmpty('content');
    }

}
