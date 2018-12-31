<?php
namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Class AppModel
 * Gère la connexion à la base de données
 * Et contient les requêtes génériques à toutes les tables
 * @package App\Model
 */
class AppModel
{

    /**
     * @var Database
     */
    protected $db;
    /**
     * @var string
     */
    protected $table;

    /**
     * AppModel constructor.
     * @param Database $db
     */
    public function __construct(Database $db) {
        $this->db = $db;
        if (is_null($this->table)) {
            $params = explode('\\',get_class($this));
            $name = end($params);
            $this->table = strtolower($name);
        }
    }

    /**
     * @return Database
     */
    public function getDb(): Database
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Permet de créer un enregistrement dans la table
     * Le tableau data contient en clé le nom des paramètres et en valeur leur valeur
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $vars = [];
        $sql = 'INSERT INTO '.$this->table.' SET ';
        foreach ($data as $key => $value)
        {
            $sql .= $key.'=:'.$key.', ';
            $vars[':'.$key] = $value;
        }
        $sql = trim($sql, ', ');
        $sql .= ';';
        $req = $this->db->getPdo()->prepare($sql);;
        return $req->execute($vars);
    }

    /**
     * Permet de modifier un enregistrement dans la table
     * Le tableau data contient en clé le nom des paramètres et en valeur leur valeur
     * @param array $data
     * @return bool
     */
    public function edit(array $data): bool
    {
        $vars = [];
        $sql = 'UPDATE '.$this->table.' SET ';
        foreach ($data as $key => $value)
        {
            if (($key != "id"))
            {
                $sql .= $key.'=:'.$key.', ';
            }
            $vars[':'.$key] = $value;
        }
        $sql = trim($sql, ', ');
        $sql .= ' WHERE id=:id;';
        $req = $this->db->getPdo()->prepare($sql);
        return $req->execute($vars);
    }

    /**
     * Permet de sélectionner un unique enregistrement suivant son identifiant
     * @param $id
     * @return array
     */
    public function find_one($id)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE id=:id;';
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute(array(':id' => $id));
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de renvoyer l'ensemble des éléments selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * Le tableau de tri contient la colonne à trier en clé et en valeur le sens de tri ASC ou DESC
     * @param array|null $conditions
     * @param array|null $order
     * @return array
     */
    public function find_all(array $conditions = NULL, array $order = NULL): array
    {
        $vars = [];
        $sql = 'SELECT * FROM '.$this->table;
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
        $sql .= ';';

        $req = $this->db->getPdo()->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte l'ensemble des éléments suivant des conditions rentrés en paramètre
     * @param array|NULL $conditions
     * @return int
     */
    public function count_all(array $conditions = NULL): int
    {
        $vars = [];
        $sql = 'SELECT count(id) FROM '.$this->table;
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
            $sql = trim($sql, ', ').';';
        }
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute($vars);
        $data = $req->fetch();
        if (!empty($data))
        {
            return $data[0];
        }
        return 0;
    }

    /**
     * Permet de supprimer un enregistrement dans la table suivant son identifiant
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE id=:id;';
        $req = $this->db->getPdo()->prepare($sql);
        return $req->execute(array(':id' => $id));
    }

}
