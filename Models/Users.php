<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Users extends AppModel
{

    /**
     * Users constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Retourne un utilisateur grâce à son email
     * @param string $email
     * @return mixed
     */
    public function find_with_mail(string $email)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE email=:email;';
        $req = $this->db->getPdo()->prepare($sql);
        $req->execute(array(':email' => $email));
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de supprimer un compte en passant le paramètre delete_account à 1
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'UPDATE '.$this->table.' SET delete_account=1 WHERE id=:id;';
        $req = $this->db->getPdo()->prepare($sql);
        return $req->execute([':id' => $id]);
    }

}
