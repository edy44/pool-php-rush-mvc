<?php
namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Class Articles
 * @package App\Models
 */
class Articles extends AppModel
{

    /**
     * Articles constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Permet de renvoyer l'ensemble des éléments avec les intitulés de la catégorie et de l'utilisateur
     * selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * Le tableau de tri contient la colonne à trier en clé et en valeur le sens de tri ASC ou DESC
     * @param array|null $conditions
     * @param array|null $order
     * @return array
     */
    public function find_all_with_category_and_user(array $conditions = NULL, array $order = NULL): array
    {
        $vars = [];
        $sql = 'SELECT articles.id AS id, articles.title AS title, articles.description AS description, 
              articles.modification_date AS modification_date, articles.creation_date AS creation_date, 
              articles.img_path AS img_path, categories.name AS category_name, users.username AS user_name 
              FROM '.$this->table.
            ' INNER JOIN categories ON articles.category_id=categories.id 
              INNER JOIN users ON articles.user_id=users.id';
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
     * Permet de renvoyer l'article avec les intitulés de la catégorie et de l'utilisateur
     * selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * Le tableau de tri contient la colonne à trier en clé et en valeur le sens de tri ASC ou DESC
     * @param int $id
     * @return bool|array
     */
    public function find_one_with_category_and_user(int $id)
    {
        $sql = 'SELECT articles.id AS id, articles.title AS title, articles.description AS description, 
              articles.modification_date AS modification_date, articles.creation_date AS creation_date, 
              articles.img_path AS img_path, categories.name AS category_name, users.username AS user_name FROM '.$this->table.
            ' INNER JOIN categories ON articles.category_id=categories.id 
              INNER JOIN users ON articles.user_id=users.id WHERE articles.id=:id;';
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute([':id' => $id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de supprimer tous les commentaires liés à un article
     * @param int $category_id
     * @return bool
     */
    public function delete_category_from_article(int $category_id): bool
    {
        $sql = 'UPDATE '.$this->table.' SET category_id=:default WHERE category_id=:category_id;';
        $req = $this->db->getPdo()->prepare($sql);
        return $req->execute([
            ':default' => '1',
            ':category_id' => $category_id]
        );
    }

    /**
     * Recherche le mot passé en paramètre dans les colonnes de la table articles
     * @param string $string
     * @param array|null $order
     * @return array
     */
    public function search(string $string, array $order = NULL)
    {
        $sql = "SELECT DISTINCT(articles.id) AS id, articles.title AS title, articles.description AS description, 
              articles.modification_date AS modification_date, articles.creation_date AS creation_date, 
              articles.img_path AS img_path, categories.name AS category_name, users.username as user_name 
              FROM ".$this->table.
            " INNER JOIN categories ON articles.category_id=categories.id 
              INNER JOIN users ON articles.user_id=users.id 
              INNER JOIN tags ON articles.id=tags.article_id 
              WHERE (articles.title LIKE '%".$string."%' OR categories.name LIKE '%".$string."%' OR 
              users.username LIKE '%".$string."%' OR articles.modification_date LIKE '%".$string."%' 
              OR articles.creation_date LIKE '%".$string."%' OR tags.name LIKE '%".$string."%')";
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
        $req = $this->db->getPdo()->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


}