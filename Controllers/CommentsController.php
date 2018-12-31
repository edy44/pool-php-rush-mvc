<?php
namespace App\Controllers;

use App\Models\Comments;
use App\Src\Request;
use App\Src\Validation;

/**
 * Class CommentsController
 * @package App\Controllers
 */
class CommentsController extends AppController
{

    /**
     * @var Comments
     */
    private $comments;
    /**
     * Messages flash
     * @var array
     */
    protected $messages = [
        'create' => 'Votre commentaire a bien été créé',
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
    }

    /**
     * Création d'un commentaire dans l'article correspondant
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
        $this->redirect('articles/view/'.$article_id);
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
