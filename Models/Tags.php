<?php
namespace App\Models;

use App\Config\Database;

/**
 * Class Tags
 * @package App\Models
 */
class Tags extends AppModel
{

    /**
     * Tags constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Permet de supprimer tous les commentaires liés à un article
     * @param int $article_id
     * @return bool
     */
    public function delete_all_from_article(int $article_id): bool
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE article_id=:article_id;';
        $req = $this->db->getPdo()->prepare($sql);
        return $req->execute(array(':article_id' => $article_id));
    }

}