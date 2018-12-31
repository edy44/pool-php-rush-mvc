<?php
namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Class Categories
 * @package App\Models
 */
class Categories extends AppModel
{

    /**
     * Categories constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Permet de renvoyer l'ensemble des catégorie hors 'no_category' selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * Le tableau de tri contient la colonne à trier en clé et en valeur le sens de tri ASC ou DESC
     * @param array|null $conditions
     * @param array|null $order
     * @return array
     */
    public function find_all(array $conditions = NULL, array $order = NULL): array
    {
        $vars = [':no_category' => 'no_category'];
        $sql = 'SELECT * FROM '.$this->table.' WHERE name!=:no_category, ';
        if (!is_null($conditions))
        {
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
        $sql = trim($sql, ', ');
        if (!is_null($order)) {
            $sql .= ' ORDER BY ';
            foreach ($order as $column => $direction)
            {
                $sql .= $column.' '.$direction.', ';
            }
            $sql = trim($sql, ', ');
        }
        $sql .= ';';
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

}