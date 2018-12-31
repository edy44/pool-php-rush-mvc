<?php
namespace App\Controllers;

use App\Models\Articles;
use App\Models\Comments;
use App\Models\Tags;
use App\Src\Request;

/**
 * Class ArticlesController
 * @package App\Controllers
 */
class ArticlesController extends AppController
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
     * @var Tags
     */
    private $tags;
    /**
     * ArticlesController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->articles = $this->loadModel('articles');
        $this->comments = $this->loadModel('comments');
        $this->tags = $this->loadModel('tags');
    }

    /**
     * Affiche l'ensemble des articles avec les informations du créateur
     * @param string|null $sort
     */
    public function index(string $sort = NULL)
    {
        if (!is_null($sort))
        {
            $len = strlen($sort);
            $position = strrpos($sort, '_');
            $column = substr($sort, 0, $position);
            $direction = strtoupper(substr($sort, $position+1, $len-1));
        }
        else
        {
            $sort = 'modification_date_desc';
            $column = 'modification_date';
            $direction = 'DESC';
        }
        $articles = $this->articles->find_all_with_category_and_user(NULL, [$column => $direction]);
        foreach ($articles as $key => $article)
        {
            $articles[$key]['creation_date'] = date('d-m-Y', strtotime($article['creation_date']));
            $articles[$key]['modification_date'] = date('d-m-Y', strtotime($article['modification_date']));
            $articles[$key]['description'] = substr($article['description'], 0, 150);
            if ($article['category_name'] == 'no_category')
            {
                $articles[$key]['category_name'] = "";
            }
        }
        if (is_null($sort))
        {
            $this->vars = compact('articles');
        }
        else
        {
            $this->vars = compact('articles', 'sort');
        }
    }

    /**
     * Affiche un Article en fonction de son id avec l'ensemble des commentaires associés
     * @param int $id
     */
    public function view(int $id)
    {
        $tags = $this->tags->find_all(['article_id' => $id], ['name' => 'ASC']);
        $str_tags = "";
        foreach ($tags as $tag)
        {
            $str_tags .= '#'.$tag['name'].' ';
        }

        $article = $this->articles->find_one_with_category_and_user($id);
        $article['creation_date'] = date('d-m-Y', strtotime($article['creation_date']));
        $article['modification_date'] = date('d-m-Y', strtotime($article['modification_date']));
        if ($article['category_name'] == 'no_category')
        {
            $article['category_name'] = "";
        }
        $comments = $this->comments->find_all_with_user(['article_id' => $id], ['creation_date' => 'ASC']);
        foreach ($comments as $key => $comment)
        {
            $comments[$key]['date'] = date('d-m-Y', strtotime($comment['creation_date'])).' à '.
                date('H:i:s', strtotime($comment['creation_date']));
        }
        $this->vars = compact('article', 'comments', 'str_tags');
    }

    /**
     * Affiche le résultat du moteur de recherche pour les articles
     */
    public function search()
    {
        $search = $this->request->getData()['search'];
        $articles = $this->articles->search($search, ['modification_date' => 'DESC']);
        $this->vars = compact('articles', 'search');
    }

}
