<?php
namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Class Comments
 * @package App\Models
 */
class Comments extends AppModel
{

    /**
     * Comments constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Permet de renvoyer l'ensemble des commentaires avec l'intitulé de l'utilisateur
     * selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * Le tableau de tri contient la colonne à trier en clé et en valeur le sens de tri ASC ou DESC
     * @param array|null $conditions
     * @param array|null $order
     * @return array
     */
    public function find_all_with_user(array $conditions = NULL, array $order = NULL): array
    {
        $vars = [];
        $sql = 'SELECT comments.id AS id, comments.content AS content, comments.creation_date AS creation_date,
              users.username as user_name FROM '.$this->table.
            ' INNER JOIN users ON comments.user_id=users.id';
        if (!is_null($conditions))
        {
            $sql .= ' WHERE ';
            foreach ($conditions as $key => $value) {
                if (is_null($value))
                {
                    $sql .= $key.' IS NULL, ';
                }
                else
                {
                    $sql .= $key.'=:'.$key.', ';
                    $vars[':'.$key] = $value;
                }
            }
            $sql = trim($sql, ', ');
        }
        if (!is_null($order)) {
            $sql .= ' ORDER BY ';
            foreach ($order as $column => $direction)
            {
                $sql .= $column.' '.$direction.', ';
            }
            $sql = trim($sql, ', ');
        }
        $sql = trim($sql, ', ');
        $sql .= ';';
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
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
